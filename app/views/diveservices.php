
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$dive_services = $dive_services ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dive Services | LLV Online</title>
    <link rel="stylesheet" href="/assets/css/llv-pages.css">
</head>
<body>

<?php include __DIR__ . '/partials/admin_sidebar.php'; ?>

<main class="main">
    <div class="topbar">
        <h1>Dive Services</h1>
        <span class="role-label">ADMIN</span>
    </div>

    <section class="panel">
        <h2>Add Dive Service</h2>

        <form action="/diveservices/store" method="POST" class="form-grid">
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="service_name" placeholder="Enter service name" required>
            </div>

            <div class="form-group">
                <label>Service Type</label>
                <select name="service_type" required>
                    <option value="">Select type</option>
                    <option value="Scuba Diving">Scuba Diving</option>
                    <option value="Discover Scuba Diving">Discover Scuba Diving</option>
                    <option value="Fun Dive">Fun Dive</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" placeholder="Example: 1 hour">
            </div>

            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" placeholder="Describe the diving service"></textarea>
            </div>

            <div class="full-width">
                <button type="submit">Add Dive Service</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Available Dive Services</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dive_services)): ?>
                    <?php foreach ($dive_services as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($service['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($service['service_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($service['service_type'] ?? '') ?></td>
                            <td>₱<?= number_format((float)($service['price'] ?? 0), 2) ?></td>
                            <td><?= htmlspecialchars($service['duration'] ?? '') ?></td>
                            <td><span class="status"><?= htmlspecialchars($service['status'] ?? 'Available') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="empty">No dive services added yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
