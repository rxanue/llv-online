
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$orders = $orders ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Orders | LLV Online</title>
    <link rel="stylesheet" href="/assets/css/llv-pages.css">
</head>
<body>

<?php include __DIR__ . '/partials/admin_sidebar.php'; ?>

<main class="main">
    <div class="topbar">
        <h1>Food Orders</h1>
        <span class="role-label">ADMIN</span>
    </div>

    <section class="panel">
        <h2>Order Records</h2>
        <p>Monitor customer food orders and their current status.</p>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($order['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($order['customer_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($order['food_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars((string)($order['quantity'] ?? '')) ?></td>
                            <td>₱<?= number_format((float)($order['total_amount'] ?? 0), 2) ?></td>
                            <td><?= htmlspecialchars($order['order_date'] ?? '') ?></td>
                            <td><span class="status"><?= htmlspecialchars($order['status'] ?? 'Pending') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="empty">No food orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>