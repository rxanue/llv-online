<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$vendorPath = dirname(__DIR__, 3) . '/vendor/autoload.php';
if (file_exists($vendorPath)) {
    require_once $vendorPath;
} else {
    die("Stripe autoload not found at: " . $vendorPath);
}

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Dompdf\Dompdf;

class PaymentController extends Controller
{
    private function getStripeKey() {
        $key = getenv('STRIPE_SECRET_KEY');
        if ($key) {
            return trim($key);
        }
        return ''; // Walang fallback key
    }

    public function checkout($transactionId)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        try {
            \Stripe\Stripe::setApiKey($this->getStripeKey());

            $db = load_class('database');
            $transaction = $db->table('transactions')->where('id', $transactionId)->get();
            
            if (is_object($transaction)) {
                $t = (array) $transaction;
            } elseif (is_array($transaction) && isset($transaction[0])) {
                $t = (array) $transaction[0];
            } else {
                $t = $transaction;
            }

            if (empty($t) || !isset($t['amount']) || !isset($t['description'])) {
                $_SESSION['error'] = 'Transaction not found or invalid data.';
                redirect('transactions');
                exit;
            }

            $domain = getenv('BASE_URL') ?: 'http://127.0.0.1:3000';

            $session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $t['description'],
                        ],
                        'unit_amount' => (int)($t['amount'] * 100), 
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $domain . '/payment/success?session_id={CHECKOUT_SESSION_ID}&txn_id=' . $transactionId,
                'cancel_url' => $domain . '/transactions',
            ]);

            header("Location: " . $session->url);
            exit;

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Payment setup failed: ' . $e->getMessage();
            redirect('transactions');
        }
    }

    public function success()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $transactionId = $_GET['txn_id'] ?? 0;
        $sessionId = $_GET['session_id'] ?? '';
        $redirectTo = 'transactions';

        if (empty($transactionId) || empty($sessionId)) {
            redirect('transactions');
            exit;
        }

        try {
            \Stripe\Stripe::setApiKey($this->getStripeKey());
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $db = load_class('database');
                
                $db->table('transactions')->where('id', $transactionId)->update([
                    'status' => 'paid'
                ]);

                $transaction = $db->table('transactions')->where('id', $transactionId)->get();
                $t = (array) $transaction;
                $desc = $t['description'] ?? '';
                $ref = $t['reference_no'] ?? '';

                // --- QR CODE GENERATION (NAKA-POINT SA PDF LINK) ---
                $domain = getenv('BASE_URL') ?: 'http://127.0.0.1:3000';
                $qrDir = ROOT_DIR . 'public/qrcodes/';
                if (!is_dir($qrDir)) {
                    mkdir($qrDir, 0777, true);
                }
                
                $qrFileName = 'receipt_' . $ref . '.png';
                $qrFilePath = $qrDir . $qrFileName;
                
                // Ito na yung link na ilalagay sa QR. Pag na-scan, dadalhin sa PDF.
                $qrContent = $domain . '/payment/receipt/' . $transactionId;
                
                $options = new QROptions([
                    'version'      => 10,
                    'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel'     => QRCode::ECC_L,
                    'scale'        => 5,
                ]);
                
                (new QRCode($options))->render($qrContent, $qrFilePath);
                
                $_SESSION['receipt_qr'] = 'qrcodes/' . $qrFileName;
                $_SESSION['receipt_txn_id'] = $transactionId; // <--- IDINAGDAG NATIN
                // ---------------------------------------------------

                if (strpos($desc, 'Room Reservation') !== false) {
                    if (!empty($ref)) {
                        $db->table('reservations')->where('booking_reference', $ref)->update([
                            'booking_status' => 'confirmed'
                        ]);
                    }
                    $redirectTo = 'reservations';
                } 
                elseif (strpos($desc, 'Food Order') !== false) {
                    $userId = $_SESSION['user_id'];
                    $user = $db->table('users')->where('id', $userId)->get();
                    $u = (array) $user;
                    $customerName = $u['username'] ?? '';

                    if (!empty($customerName)) {
                        $db->table('food_orders')
                           ->where('customer_name', $customerName)
                           ->where('status', 'pending')
                           ->update(['status' => 'paid']);
                    }
                    $redirectTo = 'orders';
                }
                else {
                    $redirectTo = 'transactions';
                }

                $_SESSION['success'] = 'Payment successful!';
            } else {
                $_SESSION['error'] = 'Payment not completed.';
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error verifying payment: ' . $e->getMessage();
        }

        redirect($redirectTo);
    }

    // BAGONG FUNCTION PARA SA PDF RECEIPT
    public function download_receipt($transactionId)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $db = load_class('database');
        $transaction = $db->table('transactions')->where('id', $transactionId)->get();
        $t = (array) $transaction;

        if (empty($t)) {
            die("Receipt not found.");
        }

        $ref = $t['reference_no'] ?? 'N/A';
        $desc = $t['description'] ?? '';
        $amount = $t['amount'] ?? 0;
        $status = strtoupper($t['status'] ?? 'PENDING');
        $date = date('F j, Y g:i A', strtotime($t['created_at'] ?? 'now'));

        // Disenyo ng resibo (HTML to PDF)
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; color: #14243d; padding: 40px; }
                .header { text-align: center; border-bottom: 3px solid #ff7900; padding-bottom: 20px; margin-bottom: 30px; }
                .header h1 { margin: 0; font-size: 32px; color: #14243d; }
                .header p { margin: 5px 0 0 0; color: #666; }
                .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                .details-table th, .details-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eaeaea; }
                .details-table th { background: #f9f9f9; width: 30%; color: #666; }
                .details-table td { font-weight: bold; }
                .total-box { margin-top: 30px; text-align: right; font-size: 24px; font-weight: bold; color: #07559b; }
                .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 20px; }
                .status { padding: 5px 10px; border-radius: 15px; font-size: 14px; color: white; background: #07559b; display: inline-block;}
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LLV ONLINE RECEIPT</h1>
                <p>Official Payment Receipt</p>
            </div>

            <table class="details-table">
                <tr>
                    <th>Reference Number</th>
                    <td>' . htmlspecialchars($ref) . '</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>' . htmlspecialchars($desc) . '</td>
                </tr>
                <tr>
                    <th>Date Issued</th>
                    <td>' . $date . '</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="status">' . $status . '</span></td>
                </tr>
            </table>

            <div class="total-box">
                TOTAL AMOUNT: PHP ' . number_format((float)$amount, 2) . '
            </div>

            <div class="footer">
                Thank you for choosing LLV Online!<br>
                For inquiries, please contact us at support@llvonline.com
            </div>
        </body>
        </html>';

        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // I-display sa browser (pwedeng i-download o i-print)
        $dompdf->stream("LLV-Receipt-" . $ref . ".pdf", ["Attachment" => false]);
        exit;
    }
}