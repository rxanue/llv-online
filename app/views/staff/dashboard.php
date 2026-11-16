<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
include __DIR__ . '/../layouts/header.php'; 
?>

<div style="padding: 40px 10%;">
    <h1 style="color: var(--text-color);">Staff Dashboard</h1>
    <p style="color: var(--text-muted);">Manage pending orders and bookings.</p>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
        
        <!-- Pending Orders -->
        <div class="activity-card" style="border-top: 5px solid #ff7900;">
            <h3>Pending Food Orders</h3>
            <?php if(!empty($pendingOrders)): ?>
                <?php foreach($pendingOrders as $order): ?>
                    <div style="border-bottom: 1px solid #eee; padding: 15px 0;">
                        <strong>Order #<?= $order['id'] ?></strong> - <?= $order['notes'] ?><br>
                        <small style="color: #666;">Total: PHP <?= number_format($order['total_amount'], 2) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #888;">No pending orders.</p>
            <?php endif; ?>
        </div>

        <!-- Pending Bookings -->
        <div class="activity-card" style="border-top: 5px solid #07559b;">
            <h3>Pending Room Bookings</h3>
            <?php if(!empty($pendingBookings)): ?>
                <?php foreach($pendingBookings as $booking): ?>
                    <div style="border-bottom: 1px solid #eee; padding: 15px 0;">
                        <strong>Ref: <?= $booking['booking_reference'] ?></strong><br>
                        <small style="color: #666;">Check-in: <?= date('M d, Y', strtotime($booking['check_in'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #888;">No pending bookings.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>