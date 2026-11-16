
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$menu_items = $menu_items ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu | LLV Online</title>
    <link rel="stylesheet" href="/assets/css/llv-pages.css">
</head>
<body>

<?php include __DIR__ . '/partials/admin_sidebar.php'; ?>

<main class="main">
    <div class="topbar">
        <h1>Food Menu Management</h1>
        <span class="role-label">ADMIN</span>
    </div>

    <section class="panel">
        <h2>Add Food Item</h2>

        <form action="/restaurant/store" method="POST" class="form-grid">
            <div class="form-group">
                <label>Food Name</label>
                <input type="text" name="name" placeholder="Enter food name" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">Select category</option>
                    <option value="Breakfast">Breakfast</option>
                    <option value="Filipino Dishes">Filipino Dishes</option>
                    <option value="Steaks">Steaks</option>
                    <option value="Pizza">Pizza</option>
                    <option value="Pasta">Pasta</option>
                    <option value="Desserts">Desserts</option>
                    <option value="Drinks">Drinks</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Availability</label>
                <select name="availability">
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" placeholder="Describe the food item"></textarea>
            </div>

            <div class="full-width">
                <button type="submit">Add Food Item</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Food Menu List</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Food Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($menu_items)): ?>
                    <?php foreach ($menu_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($item['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['category'] ?? '') ?></td>
                            <td>₱<?= number_format((float)($item['price'] ?? 0), 2) ?></td>
                            <td><span class="status"><?= htmlspecialchars($item['availability'] ?? 'Available') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="empty">No food items added yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
