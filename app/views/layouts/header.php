<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LLV Online | <?= isset($title) ? $title : 'Dashboard' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/llv-home.css') ?>">
    
    <!-- CHART.JS LIBRARY -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* ==========================================
           GLOBAL VARIABLES (LIGHT & DARK)
           ========================================== */
        :root {
            --bg-color: #f9f9f9;
            --text-color: #14243d;
            --text-muted: #666666;
            --card-bg: #ffffff;
            --border-color: #eaeaea;
            --nav-bg: #ffffff;
        }

        body.dark-mode {
            --bg-color: #121212;
            --text-color: #f1f1f1;
            --text-muted: #aaaaaa;
            --card-bg: #1e1e1e;
            --border-color: #333333;
            --nav-bg: #1e1e1e;
        }

        /* ==========================================
           BASE STYLES
           ========================================== */
        body { 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            transition: background-color 0.3s, color 0.3s;
        }
        
        body.dark-mode .site-wrapper {
            background-color: var(--bg-color) !important;
        }

        /* ==========================================
           NAVIGATION BAR
           ========================================== */
        body.dark-mode .main-navigation { 
            background-color: var(--nav-bg) !important; 
            border-bottom: 1px solid var(--border-color); 
        }
        body.dark-mode .main-navigation a { 
            color: var(--text-muted) !important; 
        }
        body.dark-mode .main-navigation a.active { 
            color: #ff7900 !important; 
            border-bottom-color: #ff7900 !important; 
        }
        
        body.dark-mode .menu-panel { 
            background-color: var(--nav-bg) !important; 
            border: 1px solid var(--border-color); 
        }
        body.dark-mode .menu-panel a { 
            color: var(--text-color) !important; 
            border-bottom: 1px solid var(--border-color); 
        }
        body.dark-mode .menu-panel a:hover { 
            background-color: #2a2a2a !important; 
        }

        /* ==========================================
           CARDS & CONTAINERS
           ========================================== */
        body.dark-mode .stat-card,
        body.dark-mode .activity-card,
        body.dark-mode .chart-section,
        body.dark-mode .settings-card,
        body.dark-mode .receipt-box,
        body.dark-mode .service-card,
        body.dark-mode .menu-card,
        body.dark-mode .dashboard-container,
        body.dark-mode section,
        body.dark-mode .scan-card { 
            background-color: var(--card-bg) !important; 
            color: var(--text-color) !important;
        }

        /* Text Colors */
        body.dark-mode h1, 
        body.dark-mode h2, 
        body.dark-mode h3, 
        body.dark-mode h4,
        body.dark-mode .detail-row span:last-child { 
            color: var(--text-color) !important; 
        }
        
        body.dark-mode p, 
        body.dark-mode span,
        body.dark-mode .detail-row span:first-child { 
            color: var(--text-muted) !important; 
        }

        /* Tables & Inputs */
        body.dark-mode table th { background: #2a2a2a !important; color: var(--text-color) !important; }
        body.dark-mode table td { border-bottom: 1px solid var(--border-color) !important; color: var(--text-color) !important; }
        body.dark-mode input, body.dark-mode select, body.dark-mode textarea {
            background-color: #2a2a2a !important;
            color: #fff !important;
            border-color: #444 !important;
        }
        
        /* Badges */
        body.dark-mode .status-badge { color: white !important; }
        body.dark-mode .badge-pending { background-color: #ff7900 !important; }
        body.dark-mode .badge-paid, body.dark-mode .badge-confirmed { background-color: #07559b !important; }
        
        /* Role Badge sa Menu */
        .role-badge { padding: 5px 10px; background: #14243d; color: white !important; border-radius: 5px; font-size: 12px; font-weight: bold; text-align: center; margin: 10px; display: block; text-transform: uppercase;}
    </style>
    
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-mode');
            document.addEventListener('DOMContentLoaded', () => document.body.classList.add('dark-mode'));
        }
    </script>
</head>
<body>

<?php 
    // Siguraduhing may active_page variable para hindi mag-error
    $active_page = $active_page ?? '';
    $currentRole = strtolower($_SESSION['role'] ?? 'guest');
    $currentName = $_SESSION['user_name'] ?? 'Guest';
?>

<div class="site-wrapper">
    <header class="top-header" style="background-color: #ff7900; color: white;">
        <button class="menu-toggle" type="button" onclick="toggleMenu()" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <a href="<?= base_url('dashboard') ?>" class="logo" style="color: white !important;">LLV ONLINE</a>

        <div class="menu-panel" id="menuPanel">
            <!-- DYNAMIC MENU BASE SA ROLE -->
            <?php if ($currentRole === 'admin'): ?>
                <div class="role-badge">ADMIN: <?= htmlspecialchars($currentName) ?></div>
                <a href="<?= base_url('admin/dashboard') ?>" style="background: #07559b; color: white !important;">Admin Dashboard</a>
                <a href="<?= base_url('admin/users') ?>">Manage Users</a>
                <a href="<?= base_url('admin/rooms') ?>">Manage Rooms</a>
                <a href="<?= base_url('admin/diveservices') ?>">Manage Dive Services</a>
                <a href="<?= base_url('admin/restaurant') ?>">Manage Restaurant</a>
                <a href="<?= base_url('logout') ?>">Logout</a>
                
            <?php elseif ($currentRole === 'staff'): ?>
                <div class="role-badge" style="background: #ff7900;">STAFF: <?= htmlspecialchars($currentName) ?></div>
                <a href="<?= base_url('staff/dashboard') ?>" style="background: #ff7900; color: white !important;">Staff Dashboard</a>
                <a href="<?= base_url('staff/scan') ?>" style="background: #07559b; color: white !important;">Scan / Confirm Booking</a> <!-- IDINAGDAG ANG SCAN -->
                <a href="<?= base_url('staff/orders') ?>">Manage Orders</a>
                <a href="<?= base_url('staff/reservations') ?>">Manage Bookings</a>
                <a href="<?= base_url('staff/divebookings') ?>">Manage Dive Bookings</a>
                <a href="<?= base_url('logout') ?>">Logout</a>
                
            <?php else: ?>
                <div class="role-badge" style="background: #ff7900;">CUSTOMER</div>
                <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                <a href="<?= base_url('rooms') ?>">Browse Rooms</a>
                <a href="<?= base_url('reservations') ?>">My Reservations</a>
                <a href="<?= base_url('restaurant') ?>">Restaurant</a>
                <a href="<?= base_url('orders') ?>">My Orders</a>
                <a href="<?= base_url('diveservices') ?>">Dive Services</a>
                <a href="<?= base_url('transactions') ?>">My Transactions</a>
                <a href="<?= base_url('settings') ?>">Settings</a>
                <a href="<?= base_url('logout') ?>">Logout</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- MAIN NAVIGATION -->
    <nav class="main-navigation">
        <?php if ($currentRole === 'admin'): ?>
            <a href="<?= base_url('admin/dashboard') ?>" class="<?= ($active_page == 'admin') ? 'active' : '' ?>">Admin Home</a>
            <a href="<?= base_url('admin/users') ?>" class="<?= ($active_page == 'admin_users') ? 'active' : '' ?>">Users</a>
            <a href="<?= base_url('admin/rooms') ?>" class="<?= ($active_page == 'admin_rooms') ? 'active' : '' ?>">Rooms</a>
            <a href="<?= base_url('admin/diveservices') ?>" class="<?= ($active_page == 'admin_diveservices') ? 'active' : '' ?>">Dive</a>
            <a href="<?= base_url('admin/restaurant') ?>" class="<?= ($active_page == 'admin_restaurant') ? 'active' : '' ?>">Resto</a>
            
        <?php elseif ($currentRole === 'staff'): ?>
            <a href="<?= base_url('staff/dashboard') ?>" class="<?= ($active_page == 'staff') ? 'active' : '' ?>">Staff Home</a>
            <a href="<?= base_url('staff/scan') ?>" class="<?= ($active_page == 'scan') ? 'active' : '' ?>">Scan</a> <!-- IDINAGDAG ANG SCAN -->
            <a href="<?= base_url('staff/orders') ?>" class="<?= ($active_page == 'orders') ? 'active' : '' ?>">Orders</a>
            <a href="<?= base_url('staff/reservations') ?>" class="<?= ($active_page == 'reservations') ? 'active' : '' ?>">Bookings</a>
            <a href="<?= base_url('staff/divebookings') ?>" class="<?= ($active_page == 'staff_divebookings') ? 'active' : '' ?>">Dive Bookings</a>
            
        <?php else: ?>
            <a href="<?= base_url('dashboard') ?>" class="<?= ($active_page == 'dashboard') ? 'active' : '' ?>">Home</a>
            <a href="<?= base_url('rooms') ?>" class="<?= ($active_page == 'rooms') ? 'active' : '' ?>">Resort</a>
            <a href="<?= base_url('diveservices') ?>" class="<?= ($active_page == 'diveservices') ? 'active' : '' ?>">Dive Shop</a>
            <a href="<?= base_url('map') ?>" class="<?= ($active_page == 'map') ? 'active' : '' ?>">Dive Map</a>
            <a href="<?= base_url('restaurant') ?>" class="<?= ($active_page == 'restaurant') ? 'active' : '' ?>">Restaurant</a>
        <?php endif; ?>
    </nav>