<?php
$active_page = 'admin_rooms'; $title = 'Edit Room';
include __DIR__ . '/../layouts/header.php'; 
?>
<div style="padding: 40px 10%; background: var(--bg-color);">
    <h1 style="color: var(--text-color);">Edit Room</h1>
    <div class="stat-card" style="margin-top: 20px; max-width: 600px;">
        <form action="<?= base_url('admin/rooms/update') ?>" method="POST">
            <input type="hidden" name="id" value="<?= $room['id'] ?>">
            <label style="color: var(--text-color); font-weight:bold;">Room Number</label>
            <input type="text" name="room_number" value="<?= $room['room_number'] ?>" required style="width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">
            
            <label style="color: var(--text-color); font-weight:bold;">Room Name</label>
            <input type="text" name="room_name" value="<?= $room['room_name'] ?>" required style="width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">
            
            <label style="color: var(--text-color); font-weight:bold;">Room Type</label>
            <select name="room_type" required style="width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">
                <option value="Standard" <?= $room['room_type'] == 'Standard' ? 'selected' : '' ?>>Standard</option>
                <option value="Deluxe" <?= $room['room_type'] == 'Deluxe' ? 'selected' : '' ?>>Deluxe</option>
                <option value="Suite" <?= $room['room_type'] == 'Suite' ? 'selected' : '' ?>>Suite</option>
            </select>

            <label style="color: var(--text-color); font-weight:bold;">Capacity</label>
            <input type="number" name="capacity" value="<?= $room['capacity'] ?>" required style="width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">

            <label style="color: var(--text-color); font-weight:bold;">Price Per Night</label>
            <input type="number" step="0.01" name="price_per_night" value="<?= $room['price_per_night'] ?>" required style="width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">

            <label style="color: var(--text-color); font-weight:bold;">Description</label>
            <textarea name="description" required style="width:100%; padding:10px; margin-bottom:20px; border-radius:5px; border:1px solid #ccc;"><?= $room['description'] ?></textarea>

            <button type="submit" style="background: #ff7900; color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Update Room</button>
            <a href="<?= base_url('admin/rooms') ?>" style="margin-left: 10px; color: #666;">Cancel</a>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>