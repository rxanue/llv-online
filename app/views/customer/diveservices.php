<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'diveservices';
$title = 'Dive Services';

include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .services-container { padding: 40px 10%; background: #f9f9f9; min-height: 70vh; }
    .services-header { text-align: center; margin-bottom: 40px; }
    .services-header h1 { color: #14243d; margin: 0 0 10px 0; font-size: 36px; }
    .services-header p { color: #666; font-size: 16px; }

    .services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
    
    .service-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s; display: flex; flex-direction: column; padding: 25px; }
    .service-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    
    .service-card h3 { margin: 0 0 10px 0; color: #14243d; font-size: 22px; }
    .service-desc { color: #666; font-size: 14px; margin-bottom: 20px; flex-grow: 1; }
    
    .service-meta { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px; margin-bottom: 20px; }
    .service-price { font-size: 24px; font-weight: bold; color: #07559b; }
    .service-price span { font-size: 14px; color: #888; font-weight: normal; }
    
    .btn-book { display: block; text-align: center; background: #ff7900; color: white; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: 0.3s; border:none; cursor:pointer; width:100%; font-family:inherit; font-size:16px;}
    .btn-book:hover { background: #e66d00; }
    
    .empty { text-align: center; color: #888; padding: 40px; font-size: 18px; background: white; border-radius: 12px; grid-column: 1 / -1;}

    .booking-form { display: flex; flex-direction: column; gap: 10px; }
    .booking-form input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-family: inherit; box-sizing: border-box; }
</style>

<div class="services-container">
    <div class="services-header">
        <h1>Dive Services</h1>
        <p>Explore the diving services offered by LLV.</p>
    </div>

    <!-- Success/Error Message -->
    <?php if(isset($_SESSION['error'])): ?>
        <div style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="services-grid">
        <?php if (!empty($dive_services)): ?>
            <?php foreach ($dive_services as $service): ?>
                
                <?php 
                    $s = (array) $service;
                    $serviceId = $s['id'] ?? 0;
                    $serviceName = $s['service_name'] ?? 'Dive Service';
                    $serviceDesc = $s['description'] ?? 'No description available.';
                    $servicePrice = $s['price'] ?? 0;
                ?>

                <div class="service-card">
                    <h3><?= htmlspecialchars($serviceName) ?></h3>
                    <p class="service-desc"><?= htmlspecialchars($serviceDesc) ?></p>
                    
                    <div class="service-meta">
                        <div class="service-price">₱<?= number_format((float)$servicePrice, 2) ?> <span>/ person</span></div>
                    </div>
                    
                    <!-- Form para makapag-book na may date -->
                    <form action="<?= base_url('diveservices/store') ?>" method="POST" class="booking-form">
                        <input type="hidden" name="service_id" value="<?= $serviceId ?>">
                        <input type="date" name="booking_date" required>
                        <button type="submit" class="btn-book">Book Service</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty">
                Dive service information will be available soon.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>