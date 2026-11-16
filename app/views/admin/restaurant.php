<?php
$active_page = 'admin_restaurant'; $title = 'Manage Menu';
include __DIR__ . '/../layouts/header.php'; 
?>

<div style="padding: 40px 10%; background: var(--bg-color);">
    <h1 style="color: var(--text-color); margin-bottom: 20px;">Manage Restaurant Menu</h1>

    <div class="stat-card" style="margin-bottom: 30px;">
        <h3 style="color: var(--text-color); margin-bottom: 15px;">Add New Menu Item</h3>
        <form action="<?= base_url('admin/restaurant/store') ?>" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <input type="text" name="item_name" placeholder="Food Name" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
            <input type="number" name="category_id" placeholder="Category ID (1=Main, 2=Appetizer, etc)" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
            <input type="number" step="0.01" name="price" placeholder="Price" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
            <input type="text" name="description" placeholder="Short description" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
            <button type="submit" style="grid-column: span 2; padding: 12px; background: #ff7900; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Add Menu Item</button>
        </form>
    </div>

    <div style="background: var(--card-bg); border-radius: 12px; padding: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Item Name</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Price</th>
                    <th style="padding: 10px; text-align: center; color: var(--text-muted);">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): $i = (array)$item; ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($i['item_name'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color);">₱<?= number_format((float)($i['price'] ?? 0), 2) ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="<?= base_url('admin/restaurant/edit/' . ($i['id'] ?? 0)) ?>" style="background: #07559b; color: white; border: none; padding: 8px 12px; border-radius: 5px; text-decoration: none; font-size: 14px; margin-right: 5px;">Edit</a>
                        <form action="<?= base_url('admin/restaurant/delete/' . ($i['id'] ?? 0)) ?>" method="POST" onsubmit="return confirm('Delete this item?');" style="display:inline;">
                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>