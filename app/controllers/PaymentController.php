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

            $domain = 'http://127.0.0.1:3000';

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
                $amount = $t['amount'] ?? 0;

                // --- QR CODE GENERATION (NAKA-SETUP NA PARA SA MAHABANG TEXT) ---
                $qrDir = ROOT_DIR . 'public/qrcodes/';
                if (!is_dir($qrDir)) {
                    mkdir($qrDir, 0777, true);
                }
                
                $qrFileName = 'receipt_' . $ref . '.png';
                $qrFilePath = $qrDir . $qrFileName;
                
                // Pinaikli natin ang laman para hindi mag-overflow
                $qrContent = "Ref: " . $ref . "\n";
                $qrContent .= "Amount: PHP " . number_format($amount, 2) . "\n";
                $qrContent .= "Status: PAID";
                
                // I-setup ang options na kayang humawak ng mas mahabang text (Version 10)
                $options = new QROptions([
                    'version'      => 10, // Mas mataas na version = mas maraming data
                    'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel'     => QRCode::ECC_L, // Pinakamababang error correction para mas maraming data
                    'scale'        => 5,
                ]);
                
                // Generate at save
                (new QRCode($options))->render($qrContent, $qrFilePath);
                
                $_SESSION['receipt_qr'] = 'qrcodes/' . $qrFileName;
                // ----------------------------------------------

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
}