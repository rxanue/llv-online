<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'reservations';
$title = 'My Reservations';

include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .res-container { padding: 40px 10%; background: #f9f9f9; min-height: 70vh; }
    .res-header { text-align: center; margin-bottom: 40px; }
    .res-header h1 { color: #14243d; margin: 0 0 10px 0; font-size: 36px; }
    .res-header p { color: #666; font-size: 16px; }

    .res-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .res-card h3 { color: #14243d; margin-top: 0; border-bottom: 2px solid #ff7900; padding-bottom: 10px; display: inline-block; margin-bottom: 20px;}
    
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eaeaea; vertical-align: middle; }
    th { background: #f4f4f4; color: #14243d; font-weight: 600; font-size: 14px; text-transform: uppercase;}
    tr:hover { background: #fafafa; }
    
    .status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: capitalize; display: inline-block; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-confirmed { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    .btn-pay { background:#14243d; color:white; padding:8px 15px; border-radius:5px; text-decoration:none; font-size:12px; font-weight:bold; display:inline-block; margin-top: 5px; transition: 0.3s; }
    .btn-pay:hover { background: #2a436b; }

    .empty { text-align: center; color: #888; padding: 40px; font-size: 18px; }
    .btn-explore { display: inline-block; margin-top: 15px; background: #ff7900; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
</style>

<div class="res-container">
    <div class="res-header">
        <h1>My Reservations</h1>
        <p>View and keep track of your room reservations.</p>
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

    <div class="res-card">
        <h3>Reservation History</h3>

        <?php if (!empty($reservations)): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total</th>
                            <th>Status / Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $res): ?>
                            <?php 
                                $r = (array) $res;
                                $statusClass = 'status-pending';
                                $stat = strtolower($r['payment_status'] ?? 'pending');
                                
                                if ($stat === 'paid' || strtolower($r['booking_status'] ?? '') === 'confirmed') {
                                    $statusClass = 'status-confirmed';
                                    $stat = 'Paid';
                                } elseif ($stat === 'cancelled') {
                                    $statusClass = 'status-cancelled';
                                }
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($r['booking_reference'] ?? 'N/A') ?></strong></td>
                                <td><?= htmlspecialchars($r['room_name'] ?? 'Unknown Room') ?></td>
                                <td><?= date('M d, Y', strtotime($r['check_in'] ?? 'now')) ?></td>
                                <td><?= date('M d, Y', strtotime($r['check_out'] ?? 'now')) ?></td>
                                <td>₱<?= number_format((float)($r['total_amount'] ?? 0), 2) ?></td>
                                <td>
                                    <span class="status <?= $statusClass ?>"><?= htmlspecialchars($stat) ?></span>
                                    
                                    <?php if(strtolower($r['payment_status'] ?? '') === 'pending' && ($r['transaction_id'] ?? 0) > 0): ?>
                                        <br>
                                        <a href="<?= base_url('payment/checkout/' . $r['transaction_id']) ?>" class="btn-pay">Pay Now</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty">
                <p>You don't have any reservations yet.</p>
                <a href="<?= base_url('rooms') ?>" class="btn-explore">Explore Rooms</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>