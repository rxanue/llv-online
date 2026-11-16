<?php
$active_page = 'staff_divebookings'; $title = 'Manage Dive Bookings';
include __DIR__ . '/../layouts/header.php'; 
?>

<div style="padding: 40px 10%; background: var(--bg-color);">
    <h1 style="color: var(--text-color); margin-bottom: 20px;">Manage Dive Bookings</h1>

    <?php if(isset($_SESSION['success'])): ?>
        <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div style="background: var(--card-bg); border-radius: 12px; padding: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Ref #</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Service</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Schedule</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Pax</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Status</th>
                    <th style="padding: 10px; text-align: center; color: var(--text-muted);">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($divebookings as $dive): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($dive['booking_reference'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($dive['service_name'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= date('M d, Y h:i A', strtotime($dive['scheduled_at'])) ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($dive['participants'] ?? '1') ?></td>
                    <td style="padding: 15px;">
                        <form action="<?= base_url('staff/divebookings/update') ?>" method="POST">
                            <input type="hidden" name="id" value="<?= $dive['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="padding:8px; border-radius:5px; border:1px solid #ccc; font-weight:bold; color:var(--text-color); background:var(--bg-color);">
                                <option value="pending" <?= strtolower($dive['booking_status']) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= strtolower($dive['booking_status']) == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="completed" <?= strtolower($dive['booking_status']) == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= strtolower($dive['booking_status']) == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 15px; text-align: center; color: var(--text-color); font-weight:bold;">₱<?= number_format((float)($dive['total_amount'] ?? 0), 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>