<?php
$active_page = 'reservations'; $title = 'Manage Bookings';
include __DIR__ . '/../layouts/header.php'; 
?>

<div style="padding: 40px 10%; background: var(--bg-color);">
    <h1 style="color: var(--text-color); margin-bottom: 20px;">Manage Room Bookings</h1>

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
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Room</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Check-in</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Status</th>
                    <th style="padding: 10px; text-align: center; color: var(--text-muted);">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($res['booking_reference'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($res['room_name'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= date('M d, Y', strtotime($res['check_in'])) ?></td>
                    <td style="padding: 15px;">
                        <form action="<?= base_url('staff/reservations/update') ?>" method="POST">
                            <input type="hidden" name="id" value="<?= $res['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="padding:8px; border-radius:5px; border:1px solid #ccc; font-weight:bold; color:var(--text-color); background:var(--bg-color);">
                                <option value="pending" <?= strtolower($res['booking_status']) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= strtolower($res['booking_status']) == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="cancelled" <?= strtolower($res['booking_status']) == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <strong>₱<?= number_format((float)($res['total_amount'] ?? 0), 2) ?></strong>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>