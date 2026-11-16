<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LLV Online | Home</title>
    <!-- Ginamit natin ang base_url() imbes na BASE_URL para sigurado -->
    <link rel="stylesheet" href="<?= base_url('assets/css/llv-home.css') ?>">
</head>
<body>

<div class="site-wrapper">

    <header class="top-header">
        <button class="menu-toggle" type="button" onclick="toggleMenu()" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <a href="<?= base_url('/') ?>" class="logo">LLV ONLINE</a>

        <div class="menu-panel" id="menuPanel">
            <a href="<?= base_url('/') ?>">Home</a>
            <!-- Pinalitan natin ang #resort ng tamang link papuntang rooms -->
            <a href="<?= base_url('rooms') ?>">Resort</a>
            <a href="<?= base_url('diveservices') ?>">Dive Shop</a>
            <a href="<?= base_url('restaurant') ?>">Restaurant</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                <a href="<?= base_url('logout') ?>">Logout</a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>">Login</a>
                <a href="<?= base_url('signup') ?>">Create Account</a>
            <?php endif; ?>
        </div>
    </header>

    <nav class="main-navigation">
        <a href="<?= base_url('/') ?>" class="active">Home</a>
        <a href="<?= base_url('rooms') ?>">Resort</a>
        <a href="<?= base_url('diveservices') ?>">Dive Shop</a>
        <a href="<?= base_url('restaurant') ?>">Restaurant</a>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>LLV ONLINE</h1>
            <p>
                Experience a relaxing stay, discover amazing
                underwater adventures, and enjoy delicious
                food with LLV.
            </p>
            <a href="#recommendations" class="primary-button">Learn more &gt;</a>
        </div>
    </section>

    <section class="recommendations" id="recommendations">
        <h2>Recommended for you</h2>

        <div class="recommendation-grid">

            <!-- Inalis natin yung id="resort" kasi URL link na ang gagamitin natin -->
            <div class="recommendation-card">
                <h3>Resort</h3>
                <p>Find a comfortable place to stay and enjoy your vacation.</p>
                <!-- Dito papasok sa /rooms -->
                <a href="<?= base_url('rooms') ?>" class="card-link">Explore rooms &gt;</a>
            </div>

            <div class="recommendation-card">
                <h3>Dive Shop</h3>
                <p>Discover diving services and underwater experiences.</p>
                <a href="<?= base_url('diveservices') ?>" class="card-link">Explore services &gt;</a>
            </div>

            <div class="recommendation-card">
                <h3>Restaurant</h3>
                <p>Enjoy delicious meals and explore the LLV Bistro menu.</p>
                <a href="<?= base_url('restaurant') ?>" class="card-link">View restaurant &gt;</a>
            </div>

        </div>
    </section>

    <footer class="site-footer">
        © <?= date('Y') ?> LLV Online. All Rights Reserved.
    </footer>

</div>

<script>
function toggleMenu() {
    document.getElementById('menuPanel').classList.toggle('open');
}
</script>

</body>
</html>