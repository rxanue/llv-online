<?php
$active_page = 'scan'; 
$title = 'Scan Booking';
include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .scan-container { padding: 40px 10%; background: var(--bg-color); min-height: 80vh; display: flex; justify-content: center; align-items: flex-start; }
    .scan-card { background: var(--card-bg); padding: 40px; border-radius: 12px; max-width: 500px; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid #ff7900; }
    .scan-title { color: var(--text-color); margin-top: 0; font-size: 28px; margin-bottom: 10px; }
    .scan-subtitle { color: var(--text-muted); margin-bottom: 30px; font-size: 16px; }
    .scan-input { width: 100%; padding: 15px; font-size: 20px; border: 2px solid #ff7900; border-radius: 8px; text-align: center; text-transform: uppercase; margin-bottom: 20px; box-sizing: border-box; background: var(--bg-color); color: var(--text-color); font-weight: bold; letter-spacing: 2px; }
    .scan-input:focus { outline: none; box-shadow: 0 0 10px rgba(255,121,0,0.3); }
    .btn-scan { width: 100%; padding: 15px; background: #ff7900; color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
    .btn-scan:hover { background: #e66d00; }
    .qr-icon { font-size: 60px; margin-bottom: 20px; color: var(--text-muted); }
</style>

<div class="scan-container">
    <div class="scan-card">
        <div class="qr-icon">📷</div>
        <h1 class="scan-title">Confirm Booking</h1>
        <p class="scan-subtitle">Enter the Reference Number from the customer's receipt or QR code.</p>

        <?php if(isset($_SESSION['success'])): ?>
            <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px; font-weight: bold;">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px; font-weight: bold;">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= base_url('staff/confirm_booking') ?>" method="POST">
            <input type="text" name="reference" class="scan-input" placeholder="e.g. LLV-12345" required autofocus autocomplete="off">
            <button type="submit" class="btn-scan">Confirm Booking</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>