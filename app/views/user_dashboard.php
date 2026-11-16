<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'dashboard';
$title = 'My Dashboard';

include __DIR__ . '/layouts/header.php'; 
?>

<style>
    .dashboard-container { padding: 40px 10%; min-height: 80vh; background: var(--bg-color); }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
    .stat-card { background: var(--card-bg); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; border-top: 5px solid transparent; }
    .stat-card h3 { margin: 0; color: var(--text-muted); font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
    .stat-card p { margin: 10px 0 0 0; color: #ff7900 !important; font-size: 32px; font-weight: bold; }

    .chart-section { background: var(--card-bg); padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 40px; display: flex; align-items: center; gap: 40px; flex-wrap: wrap; border-left: 5px solid #07559b; }
    .chart-text { flex: 1; min-width: 300px; }
    .chart-text h2 { color: var(--text-color); margin-top: 0; font-size: 28px; }
    .chart-text p { color: var(--text-muted); line-height: 1.6; }
    .chart-container { width: 250px; height: 250px; margin: 0 auto; position: relative; }

    .activity-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px; }
    .activity-card { background: var(--card-bg); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .activity-card h3 { margin-top: 0; color: var(--text-color); border-bottom: 2px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px; font-size: 20px;}
    
    .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
    .detail-row span:first-child { color: var(--text-muted); }
    .detail-row span:last-child { font-weight: bold; color: var(--text-color); text-align: right; }
    
    /* BADGES */
    .status-badge { padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold; text-transform: uppercase; color: white !important; letter-spacing: 0.5px;}
    .badge-pending { background-color: #ff7900 !important; color: white !important; }
    .badge-paid { background-color: #07559b !important; color: white !important; }
    .badge-confirmed { background-color: #07559b !important; color: white !important; }

    .btn-action { display: block; text-align: center; margin-top: 20px; color: white; padding: 12px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px; transition: 0.3s;}
    .empty-state { color: var(--text-muted); font-style: italic; text-align: center; padding: 20px 0; }
</style>

<div class="dashboard-container">
    <div style="background: linear-gradient(135deg, #14243d 0%, #07559b 100%); padding: 40px; border-radius: 12px; color: white; margin-bottom: 40px;">
        <h1 style="margin: 0 0 10px 0; font-size: 36px; color: white !important;">Welcome back, <?= htmlspecialchars($userName) ?></h1>
        <p style="margin: 0; font-size: 16px; opacity: 0.9; color: #e0e0e0 !important;">Here is a snapshot of your journey with LLV Online.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="border-top-color: #ff7900;">
            <h3>Active Bookings</h3>
            <p><?= $activeBookings ?></p>
        </div>
        <div class="stat-card" style="border-top-color: #07559b;">
            <h3>Total Spent</h3>
            <p>PHP <?= number_format($totalSpent, 2) ?></p>
        </div>
        <div class="stat-card" style="border-top-color: #ff7900;">
            <h3>Latest Food Order</h3>
            <p style="font-size: 24px;"><?= $latestOrder ? 'PHP '.number_format((float)$latestOrder['total_amount'], 2) : 'None yet' ?></p>
        </div>
    </div>

    <div class="chart-section">
        <div class="chart-text">
            <h2>Your Travel Footprint</h2>
            <p>An interactive breakdown of your activity with us. The more you explore, the more colorful your footprint becomes.</p>
        </div>
        <div class="chart-container">
            <!-- ITO YUNG KULANG KANINA -->
            <canvas id="footprintChart"></canvas>
        </div>
    </div>

    <div class="activity-grid">
        <div class="activity-card" style="border-top: 5px solid #ff7900;">
            <h3>Latest Room Stay</h3>
            <?php if ($latestReservation): ?>
                <div class="detail-row">
                    <span>Room:</span>
                    <span><?= htmlspecialchars($latestReservation['room_name'] ?? 'Room') ?></span>
                </div>
                <div class="detail-row">
                    <span>Check-in:</span>
                    <span><?= date('M d, Y', strtotime($latestReservation['check_in'])) ?></span>
                </div>
                <div class="detail-row">
                    <span>Status:</span>
                    <span class="status-badge <?= strtolower($latestReservation['booking_status']) === 'pending' ? 'badge-pending' : 'badge-confirmed' ?>">
                        <?= htmlspecialchars($latestReservation['booking_status']) ?>
                    </span>
                </div>
                <a href="<?= base_url('reservations') ?>" class="btn-action" style="background: #ff7900;">View All Reservations</a>
            <?php else: ?>
                <div class="empty-state">You haven't booked a room yet.</div>
                <a href="<?= base_url('rooms') ?>" class="btn-action" style="background: #ff7900;">Book a Room</a>
            <?php endif; ?>
        </div>

        <div class="activity-card" style="border-top: 5px solid #07559b;">
            <h3>Latest Food Order</h3>
            <?php if ($latestOrder): ?>
                <div class="detail-row">
                    <span>Items:</span>
                    <span><?= htmlspecialchars($latestOrder['notes'] ?? 'Food Order') ?></span>
                </div>
                <div class="detail-row">
                    <span>Status:</span>
                    <span class="status-badge <?= strtolower($latestOrder['status']) === 'pending' ? 'badge-pending' : 'badge-paid' ?>">
                        <?= htmlspecialchars($latestOrder['status']) ?>
                    </span>
                </div>
                <a href="<?= base_url('orders') ?>" class="btn-action" style="background: #07559b;">View All Orders</a>
            <?php else: ?>
                <div class="empty-state">You haven't ordered food yet.</div>
                <a href="<?= base_url('restaurant') ?>" class="btn-action" style="background: #07559b;">View Menu</a>
            <?php endif; ?>
        </div>

        <div class="activity-card" style="border-top: 5px solid #ff7900;">
            <h3>Latest Dive Booking</h3>
            <?php if ($latestDive): ?>
                <div class="detail-row">
                    <span>Service:</span>
                    <span><?= htmlspecialchars($latestDive['service_name'] ?? 'Dive Service') ?></span>
                </div>
                <div class="detail-row">
                    <span>Scheduled:</span>
                    <span><?= date('M d, Y', strtotime($latestDive['scheduled_at'])) ?></span>
                </div>
                <div class="detail-row">
                    <span>Status:</span>
                    <span class="status-badge <?= strtolower($latestDive['booking_status']) === 'pending' ? 'badge-pending' : 'badge-paid' ?>">
                        <?= htmlspecialchars($latestDive['booking_status']) ?>
                    </span>
                </div>
                <a href="<?= base_url('transactions') ?>" class="btn-action" style="background: #ff7900;">View Transactions</a>
            <?php else: ?>
                <div class="empty-state">You haven't booked a dive yet.</div>
                <a href="<?= base_url('diveservices') ?>" class="btn-action" style="background: #ff7900;">Explore Dive Shop</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check kung existing yung canvas bago i-initialize
        var canvas = document.getElementById('footprintChart');
        if (canvas) {
            var ctx = canvas.getContext('2d');
            var resCount = <?= $chartData['reservations'] ?? 0 ?>;
            var foodCount = <?= $chartData['food_orders'] ?? 0 ?>;
            var diveCount = <?= $chartData['dive_bookings'] ?? 0 ?>;

            if (resCount === 0 && foodCount === 0 && diveCount === 0) {
                resCount = 1; foodCount = 1; diveCount = 1; 
            }

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Room Stays', 'Food Orders', 'Dive Trips'],
                    datasets: [{
                        data: [resCount, foodCount, diveCount],
                        backgroundColor: ['#ff7900', '#07559b', '#ffb366'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { 
                                padding: 20, 
                                font: { size: 13 }, 
                                color: localStorage.getItem('theme') === 'dark' ? '#f1f1f1' : '#14243d' 
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        }
    });
</script>

<?php 
include __DIR__ . '/layouts/footer.php'; 
?>