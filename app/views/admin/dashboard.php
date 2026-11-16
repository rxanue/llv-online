<?php
$active_page = 'admin';
$title = 'Admin Dashboard';
include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .admin-container { padding: 40px 10%; min-height: 80vh; background: var(--bg-color); }
    
    /* Stats Cards */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px; }
    .stat-card { background: var(--card-bg); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; }
    .stat-card h3 { margin: 0; color: var(--text-muted); font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
    .stat-card p { margin: 10px 0 0 0; color: #ff7900 !important; font-size: 32px; font-weight: bold; }

    /* Chart Section */
    .chart-section { background: var(--card-bg); padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #07559b; }
    .chart-section h2 { color: var(--text-color); margin-top: 0; font-size: 24px; margin-bottom: 20px;}
    .chart-container { width: 100%; height: 300px; position: relative; }
</style>

<div class="admin-container">
    <div style="margin-bottom: 30px;">
        <h1 style="color: var(--text-color); margin: 0;">Admin Dashboard</h1>
        <p style="color: var(--text-muted); margin-top: 5px;">Overview of the entire LLV Online system.</p>
    </div>

    <!-- STATS CARDS -->
    <div class="stats-grid">
        <div class="stat-card" style="border-top: 5px solid #07559b;">
            <h3>Total Users</h3>
            <p style="color: #07559b !important;"><?= $totalUsers ?></p>
        </div>
        <div class="stat-card" style="border-top: 5px solid #ff7900;">
            <h3>Total Revenue</h3>
            <p style="color: #ff7900 !important;">PHP <?= number_format($totalRevenue, 2) ?></p>
        </div>
        <div class="stat-card" style="border-top: 5px solid #07559b;">
            <h3>Room Reservations</h3>
            <p style="color: #07559b !important;"><?= $totalReservations ?></p>
        </div>
        <div class="stat-card" style="border-top: 5px solid #ff7900;">
            <h3>Food Orders</h3>
            <p style="color: #ff7900 !important;"><?= $totalOrders ?></p>
        </div>
    </div>

    <!-- BAR CHART -->
    <div class="chart-section">
        <h2>System Activity Breakdown</h2>
        <div class="chart-container">
            <canvas id="adminChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('adminChart').getContext('2d');
        
        var resCount = <?= $chartData['reservations'] ?? 0 ?>;
        var foodCount = <?= $chartData['food_orders'] ?? 0 ?>;
        var diveCount = <?= $chartData['dive_bookings'] ?? 0 ?>;
        
        // Fallback kung walang data
        if (resCount === 0 && foodCount === 0 && diveCount === 0) {
            resCount = 1; foodCount = 1; diveCount = 1;
        }

        new Chart(ctx, {
            type: 'bar', // Ginawa nating Bar chart para pang-admin
            data: {
                labels: ['Room Reservations', 'Food Orders', 'Dive Bookings'],
                datasets: [{
                    label: 'Total Transactions',
                    data: [resCount, foodCount, diveCount],
                    backgroundColor: ['#07559b', '#ff7900', '#6c757d'],
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: localStorage.getItem('theme') === 'dark' ? '#f1f1f1' : '#14243d'
                        },
                        grid: {
                            color: localStorage.getItem('theme') === 'dark' ? '#333' : '#eaeaea'
                        }
                    },
                    x: {
                        ticks: {
                            color: localStorage.getItem('theme') === 'dark' ? '#f1f1f1' : '#14243d'
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>