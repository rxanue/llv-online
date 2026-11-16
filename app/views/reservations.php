
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$reservations = $reservations ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations | LLV Online</title>
    <link rel="stylesheet" href="/assets/css/llv-pages.css">
</head>
<body>

<?php include __DIR__ . '/partials/admin_sidebar.php'; ?>

<main class="main">
    <div class="topbar">
        <h1>Reservations</h1>
        <span class="role-label">ADMIN</span>
    </div>

    <section class="panel">
        <h2>Reservation Records</h2>
        <p>View and monitor customer room reservations.</p>

        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Customer</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Guests</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservations)): ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($reservation['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($reservation['customer_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($reservation['room_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($reservation['check_in'] ?? '') ?></td>
                            <td><?= htmlspecialchars($reservation['check_out'] ?? '') ?></td>
                            <td><?= htmlspecialchars((string)($reservation['guests'] ?? '')) ?></td>
                            <td><span class="status"><?= htmlspecialchars($reservation['status'] ?? 'Pending') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="empty">No reservations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
