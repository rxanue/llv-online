
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$transactions = $transactions ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions | LLV Online</title>
    <link rel="stylesheet" href="/assets/css/llv-pages.css">
</head>
<body>

<?php include __DIR__ . '/partials/admin_sidebar.php'; ?>

<main class="main">
    <div class="topbar">
        <h1>Transactions</h1>
        <span class="role-label">ADMIN</span>
    </div>

    <section class="panel">
        <h2>Payment Records</h2>
        <p>View and monitor payments made for reservations, food orders, and dive services.</p>

        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Customer</th>
                    <th>Reference</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($transaction['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($transaction['customer_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($transaction['reference'] ?? '') ?></td>
                            <td><?= htmlspecialchars($transaction['payment_method'] ?? '') ?></td>
                            <td>₱<?= number_format((float)($transaction['amount'] ?? 0), 2) ?></td>
                            <td><?= htmlspecialchars($transaction['payment_date'] ?? '') ?></td>
                            <td><span class="status"><?= htmlspecialchars($transaction['status'] ?? 'Pending') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="empty">No transactions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>