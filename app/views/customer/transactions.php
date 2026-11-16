<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'transactions';
$title = 'My Transactions';

include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .trans-container { padding: 40px 10%; background: #f9f9f9; min-height: 70vh; }
    .trans-header { text-align: center; margin-bottom: 40px; }
    .trans-header h1 { color: #14243d; margin: 0 0 10px 0; font-size: 36px; }
    .trans-header p { color: #666; font-size: 16px; }

    .trans-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .trans-card h3 { color: #14243d; margin-top: 0; border-bottom: 2px solid #ff7900; padding-bottom: 10px; display: inline-block; margin-bottom: 20px;}
    
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eaeaea; vertical-align: middle; }
    th { background: #f4f4f4; color: #14243d; font-weight: 600; font-size: 14px; text-transform: uppercase;}
    tr:hover { background: #fafafa; }
    
    .status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: capitalize; display: inline-block; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-paid { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    .btn-pay { background:#14243d; color:white; padding:8px 15px; border-radius:5px; text-decoration:none; font-size:12px; font-weight:bold; display:inline-block; margin-top: 5px; transition: 0.3s; }
    .btn-pay:hover { background: #2a436b; }

    .empty { text-align: center; color: #888; padding: 40px; font-size: 18px; }
</style>

<div class="trans-container">
    <div class="trans-header">
        <h1>My Transactions</h1>
        <p>Review your payment and transaction records for services.</p>
    </div>

    <!-- QR CODE RECEIPT DISPLAY -->
    <?php if(isset($_SESSION['receipt_qr'])): ?>
        <div style="text-align: center; padding: 20px; background: #fff; margin-bottom: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 2px dashed #28a745;">
            <h3 style="color: #28a745; margin-top: 0;">Payment Successful! Here is your Receipt</h3>
            <p style="color: #666; margin-bottom: 20px;">Scan this QR code to verify your transaction.</p>
            <img src="<?= base_url($_SESSION['receipt_qr']) ?>" alt="Receipt QR Code" style="width: 200px; height: 200px; border-radius: 8px;">
            <br>
            <a href="<?= base_url($_SESSION['receipt_qr']) ?>" download style="display: inline-block; margin-top: 15px; background: #14243d; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">Download QR Receipt</a>
        </div>
        <?php unset($_SESSION['receipt_qr']); ?>
    <?php endif; ?>
    <!-- END QR CODE RECEIPT -->

    <?php if(isset($_SESSION['success'])): ?>
        <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="trans-card">
        <h3>Transaction History</h3>

        <?php 
            $filtered_transactions = [];
            if (!empty($transactions)) {
                foreach ($transactions as $trans) {
                    $t = (array) $trans;
                    $desc = $t['description'] ?? '';
                    if (strpos($desc, 'Room Reservation') === false && strpos($desc, 'Food Order') === false) {
                        $filtered_transactions[] = $t;
                    }
                }
            }
        ?>

        <?php if (!empty($filtered_transactions)): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Reference No.</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status / Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filtered_transactions as $t): ?>
                            <?php 
                                $statusClass = 'status-pending';
                                $stat = strtolower($t['status'] ?? 'pending');
                                if ($stat === 'paid') $statusClass = 'status-paid';
                                if ($stat === 'cancelled') $statusClass = 'status-cancelled';
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($t['reference_no'] ?? 'N/A') ?></strong></td>
                                <td><?= htmlspecialchars($t['description'] ?? '') ?></td>
                                <td>₱<?= number_format((float)($t['amount'] ?? 0), 2) ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($t['created_at'] ?? 'now')) ?></td>
                                <td>
                                    <span class="status <?= $statusClass ?>"><?= htmlspecialchars($t['status'] ?? 'pending') ?></span>
                                    <?php if($stat === 'pending'): ?>
                                        <br>
                                        <a href="<?= base_url('payment/checkout/' . $t['id']) ?>" class="btn-pay">Pay Now</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty">
                <p>You don't have any transactions yet.</p>
                <p>Book a dive service to get started!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>