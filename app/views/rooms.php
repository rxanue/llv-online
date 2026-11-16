<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'rooms';
$title = 'Rooms';

// Umakyat ng isang folder para hanapin ang layouts/header.php
include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .rooms-container { padding: 40px 10%; background: #f9f9f9; min-height: 70vh; }
    .rooms-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .rooms-header h1 { color: #14243d; margin: 0; font-size: 32px; }
    
    .panel { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
    .panel h2 { color: #07559b; margin-top: 0; font-size: 22px; margin-bottom: 20px; border-bottom: 2px solid #ff7900; padding-bottom: 10px; display: inline-block; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; }
    .form-group.full-width { grid-column: span 2; }
    .form-group label { font-weight: 600; margin-bottom: 8px; color: #333; font-size: 14px; }
    .form-group input, .form-group select, .form-group textarea { padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-family: inherit; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #ff7900; outline: none; }
    
    .btn-submit { background: #ff7900; color: white; padding: 12px 25px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
    .btn-submit:hover { background: #e66d00; }

    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eaeaea; }
    th { background: #f4f4f4; color: #14243d; font-weight: 600; }
    tr:hover { background: #fafafa; }
    .status { background: #e6f4ea; color: #1e8e3e; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .empty { text-align: center; color: #888; padding: 20px; }
</style>

<div class="rooms-container">
    <div class="rooms-header">
        <h1>Our Rooms</h1>
        <span style="background:#14243d; color:white; padding: 5px 15px; border-radius: 5px; font-size: 12px; font-weight: bold;">ADMIN PANEL</span>
    </div>

    <section class="panel">
        <h2>Add New Room</h2>
        <form action="<?= base_url('rooms/store') ?>" method="POST" class="form-grid">
            <div class="form-group">
                <label>Room Name</label>
                <input type="text" name="room_name" placeholder="Enter room name" required>
            </div>
            <div class="form-group">
                <label>Room Type</label>
                <select name="room_type" required>
                    <option value="">Select room type</option>
                    <option value="Standard">Standard</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Suite">Suite</option>
                    <option value="Family">Family</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price per Night</label>
                <input type="number" name="price" min="0" step="0.01" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" min="1" placeholder="e.g. 2" required>
            </div>
            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" placeholder="Room description..." rows="3"></textarea>
            </div>
            <div class="full-width">
                <button type="submit" class="btn-submit">Add Room</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Room List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Capacity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($room['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($room['room_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($room['room_type'] ?? '') ?></td>
                            <td>₱<?= number_format((float)($room['price'] ?? 0), 2) ?></td>
                            <td><?= htmlspecialchars((string)($room['capacity'] ?? '')) ?></td>
                            <td><span class="status"><?= htmlspecialchars($room['status'] ?? 'Available') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="empty">No rooms available yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>