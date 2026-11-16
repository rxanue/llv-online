<?php
$active_page = 'orders'; $title = 'Manage Orders';
include __DIR__ . '/../layouts/header.php'; 
?>

<div style="padding: 40px 10%; background: var(--bg-color);">
    <h1 style="color: var(--text-color); margin-bottom: 20px;">Manage Food Orders</h1>

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
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Order ID</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Customer</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Items</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Status</th>
                    <th style="padding: 10px; text-align: center; color: var(--text-muted);">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; color: var(--text-color);">#<?= $o['id'] ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($o['customer_name'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($o['notes'] ?? '') ?></td>
                    <td style="padding: 15px;">
                        <form action="<?= base_url('staff/orders/update') ?>" method="POST">
                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="padding:8px; border-radius:5px; border:1px solid #ccc; font-weight:bold; color:var(--text-color); background:var(--bg-color);">
                                <option value="pending" <?= strtolower($o['status']) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="preparing" <?= strtolower($o['status']) == 'preparing' ? 'selected' : '' ?>>Preparing</option>
                                <option value="served" <?= strtolower($o['status']) == 'served' ? 'selected' : '' ?>>Served</option>
                                <option value="paid" <?= strtolower($o['status']) == 'paid' ? 'selected' : '' ?>>Paid</option>
                                <option value="cancelled" <?= strtolower($o['status']) == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <strong>₱<?= number_format((float)($o['total_amount'] ?? 0), 2) ?></strong>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>