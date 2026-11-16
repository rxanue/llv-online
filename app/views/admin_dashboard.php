
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminName = $_SESSION['user_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | LLV Online</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f5f6f8;
    color: #14243a;
}

a {
    text-decoration: none;
}

.sidebar {
    position: fixed;
    width: 255px;
    height: 100vh;
    background: #14243a;
    color: white;
    padding: 28px 18px;
    overflow-y: auto;
}

.brand {
    font-size: 24px;
    font-weight: bold;
    margin: 5px 10px 38px;
}

.brand span {
    color: #f58a1f;
}

.menu-title {
    color: #8492a6;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 1px;
    padding: 0 12px;
    margin-bottom: 12px;
}

.menu a {
    display: block;
    color: #d5dce6;
    padding: 14px 13px;
    margin-bottom: 7px;
    border-radius: 8px;
    font-size: 14px;
    transition: .2s;
}

.menu a:hover,
.menu a.active {
    background: #f58a1f;
    color: white;
}

.menu .logout {
    margin-top: 35px;
    background: #8f2929;
    color: white;
}

.main {
    margin-left: 255px;
    padding: 32px;
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    gap: 15px;
}

.topbar h1 {
    font-size: 28px;
}

.admin-label {
    background: #fff0df;
    color: #d96e0c;
    padding: 10px 17px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: bold;
}

.welcome {
    background: linear-gradient(110deg, #e87917, #fa9a36);
    color: white;
    padding: 32px;
    border-radius: 13px;
    margin-bottom: 28px;
}

.welcome h2 {
    font-size: 25px;
    margin-bottom: 10px;
}

.welcome p {
    line-height: 1.7;
}

.section-heading {
    font-size: 19px;
    margin-bottom: 18px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 18px;
}

.card {
    background: white;
    padding: 23px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,.045);
    border: 1px solid #edf0f3;
}

.card .icon {
    font-size: 27px;
    margin-bottom: 17px;
}

.card p {
    color: #778397;
    font-size: 14px;
    margin-bottom: 12px;
}

.card h2 {
    font-size: 30px;
    color: #14243a;
}

.card a {
    display: inline-block;
    margin-top: 18px;
    background: #fff0df;
    color: #d96e0c;
    padding: 10px 14px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: bold;
}

.card a:hover {
    background: #f58a1f;
    color: white;
}

.overview {
    margin-top: 28px;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,.045);
}

.overview h3 {
    margin-bottom: 12px;
}

.overview p {
    color: #778397;
    line-height: 1.8;
}

@media(max-width: 750px) {
    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
    }

    .menu {
        display: flex;
        flex-wrap: wrap;
    }

    .menu a {
        padding: 10px;
    }

    .main {
        margin-left: 0;
        padding: 20px;
    }
}
</style>
</head>

<body>

<aside class="sidebar">

    <div class="brand">LLV <span>ONLINE</span></div>

    <div class="menu-title">ADMIN MENU</div>

    <nav class="menu">
        <a href="/dashboard" class="active">Dashboard</a>
        <a href="/rooms">Rooms</a>
        <a href="/reservations">Reservations</a>
        <a href="/restaurant">Restaurant</a>
        <a href="/orders">Food Orders</a>
        <a href="/inventory">Inventory</a>
        <a href="/dive-services">Dive Services</a>
        <a href="/transactions">Transactions</a>
        <a href="/admin-settings">Settings</a>
        <a href="/logout" class="logout">Logout</a>
    </nav>

</aside>

<main class="main">

    <div class="topbar">
        <h1>Admin Dashboard</h1>
        <span class="admin-label">ADMIN ACCOUNT</span>
    </div>

    <section class="welcome">
        <h2>Welcome back, <?= htmlspecialchars($adminName); ?>!</h2>
        <p>
            Manage your LLV Online operations and monitor your
            business activities in one place.
        </p>
    </section>

    <h3 class="section-heading">Business Overview</h3>

    <section class="cards">

        <div class="card">
            <div class="icon">🏨</div>
            <p>Total Rooms</p>
            <h2>0</h2>
            <a href="/rooms">Manage Rooms</a>
        </div>

        <div class="card">
            <div class="icon">📅</div>
            <p>Reservations</p>
            <h2>0</h2>
            <a href="/reservations">View Reservations</a>
        </div>

        <div class="card">
            <div class="icon">🍽️</div>
            <p>Menu Items</p>
            <h2>0</h2>
            <a href="/restaurant">Manage Menu</a>
        </div>

        <div class="card">
            <div class="icon">🛒</div>
            <p>Food Orders</p>
            <h2>0</h2>
            <a href="/orders">View Orders</a>
        </div>

        <div class="card">
            <div class="icon">📦</div>
            <p>Inventory Items</p>
            <h2>0</h2>
            <a href="/inventory">Manage Inventory</a>
        </div>

        <div class="card">
            <div class="icon">🌊</div>
            <p>Dive Bookings</p>
            <h2>0</h2>
            <a href="/dive-services">View Services</a>
        </div>

        <div class="card">
            <div class="icon">💳</div>
            <p>Transactions</p>
            <h2>0</h2>
            <a href="/transactions">View Payments</a>
        </div>

    </section>

    <section class="overview">
        <h3>System Overview</h3>
        <p>
            This dashboard provides access to the main management
            sections of LLV Online. Use the sidebar to organize your
            accommodation, restaurant, inventory, diving services,
            and payment records.
        </p>
    </section>

</main>

</body>
</html>
