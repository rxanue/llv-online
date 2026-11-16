<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'rooms';
$title = 'Browse Rooms';

include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .rooms-container { padding: 40px 10%; background: #f9f9f9; min-height: 70vh; }
    .rooms-header { text-align: center; margin-bottom: 40px; }
    .rooms-header h1 { color: #14243d; margin: 0 0 10px 0; font-size: 36px; }
    .rooms-header p { color: #666; font-size: 16px; }

    .room-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
    
    .room-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s; display: flex; flex-direction: column; }
    .room-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    
    .room-img-placeholder { height: 200px; background: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #888; font-size: 14px; }
    
    .room-details { padding: 25px; display: flex; flex-direction: column; flex-grow: 1; }
    .room-details h3 { margin: 0 0 10px 0; color: #14243d; font-size: 22px; }
    .room-type { color: #ff7900; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: block; }
    .room-desc { color: #666; font-size: 14px; margin-bottom: 20px; flex-grow: 1; }
    
    .room-meta { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px; margin-bottom: 20px; }
    .room-price { font-size: 24px; font-weight: bold; color: #07559b; }
    .room-price span { font-size: 14px; color: #888; font-weight: normal; }
    .room-capacity { color: #555; font-size: 14px; }
    
    .btn-book { display: block; text-align: center; background: #ff7900; color: white; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: 0.3s; border:none; cursor:pointer; width:100%; font-family:inherit; font-size:16px;}
    .btn-book:hover { background: #e66d00; }
    
    .empty { text-align: center; color: #888; padding: 40px; font-size: 18px; background: white; border-radius: 12px; grid-column: 1 / -1;}

    /* Styles para sa Modal */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; }
    .modal-content { background: white; padding: 30px; border-radius: 12px; width: 90%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    .modal-content h3 { margin-top: 0; color: #14243d; margin-bottom: 20px; }
    .modal-content label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
    .modal-content input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; margin-bottom: 15px; font-family: inherit; }
    .modal-actions { display: flex; gap: 10px; margin-top: 10px; }
    .modal-actions button { flex: 1; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-family: inherit; }
    .btn-cancel { background: #e0e0e0; color: #333; }
    .btn-confirm { background: #ff7900; color: white; }
</style>

<div class="rooms-container">
    <div class="rooms-header">
        <h1>Our Rooms</h1>
        <p>Find the perfect room for your stay at LLV Online.</p>
    </div>

    <div class="room-grid">
        <?php if (!empty($rooms)): ?>
            <?php foreach ($rooms as $room): ?>
                <?php $r = (array) $room; ?>
                <div class="room-card">
                    <div class="room-img-placeholder">Image Coming Soon</div>
                    
                    <div class="room-details">
                        <span class="room-type"><?= htmlspecialchars($r['room_type'] ?? 'Standard') ?></span>
                        <h3><?= htmlspecialchars($r['room_name'] ?? 'Room') ?></h3>
                        <p class="room-desc"><?= htmlspecialchars($r['description'] ?? 'Enjoy a comfortable stay in our well-furnished room.') ?></p>
                        
                        <div class="room-meta">
                            <div class="room-price">₱<?= number_format((float)($r['price_per_night'] ?? 0), 2) ?> <span>/ night</span></div>
                            <div class="room-capacity">Max <?= htmlspecialchars((string)($r['capacity'] ?? '2')) ?> pax</div>
                        </div>
                        
                        <!-- ITO YUNG BUTTON NA MAGBUBUKAS NG MODAL -->
                        <button type="button" onclick="openBookingModal(<?= $r['id'] ?? 0 ?>, '<?= htmlspecialchars($r['room_name'] ?? 'Room') ?>')" class="btn-book">Book Now</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty">No rooms available at the moment. Please check back later!</div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL PARA SA BOOKING -->
<div id="bookingModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="modalRoomName">Book Room</h3>
        <form action="<?= base_url('rooms/store') ?>" method="POST">
            <input type="hidden" name="room_id" id="modalRoomId">
            
            <div>
                <label>Check-in Date</label>
                <input type="date" name="check_in" required>
            </div>
            
            <div>
                <label>Check-out Date</label>
                <input type="date" name="check_out" required>
            </div>
            
            <div class="modal-actions">
                <button type="button" onclick="closeBookingModal()" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-confirm">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBookingModal(id, name) {
        // I-set yung value sa hidden input at title
        document.getElementById('modalRoomId').value = id;
        document.getElementById('modalRoomName').innerText = 'Book ' + name;
        // Ipakita yung modal
        document.getElementById('bookingModal').style.display = 'flex';
    }

    function closeBookingModal() {
        // Itago yung modal
        document.getElementById('bookingModal').style.display = 'none';
    }
</script>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>