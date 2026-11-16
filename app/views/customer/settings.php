<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'settings';
$title = 'Settings';

include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .settings-container { padding: 40px 10%; background: var(--bg-color, #f9f9f9); min-height: 80vh; }
    .settings-header { margin-bottom: 40px; }
    .settings-header h1 { color: var(--text-color, #14243d); font-size: 36px; margin: 0 0 10px 0; }
    
    .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
    
    .settings-card { background: var(--card-bg, white); border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .settings-card h3 { color: var(--text-color, #14243d); margin-top: 0; border-bottom: 2px solid #ff7900; padding-bottom: 10px; display: inline-block; margin-bottom: 20px; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: var(--text-color, #333); font-size: 14px; }
    .form-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
    
    .btn-save { background: #ff7900; color: white; border: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.3s; font-family: inherit; }
    .btn-save:hover { background: #e66d00; }

    /* THEME TOGGLE SWITCH */
    .theme-toggle-container { display: flex; justify-content: space-between; align-items: center; }
    .switch { position: relative; display: inline-block; width: 60px; height: 34px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #14243d; }
    input:checked + .slider:before { transform: translateX(26px); }

    @media (max-width: 768px) {
        .settings-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="settings-container">
    <div class="settings-header">
        <h1>Account Settings</h1>
        <p style="color: var(--text-muted, #666);">Manage your profile, security, and app preferences.</p>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="settings-grid">
        
        <!-- PROFILE INFO -->
        <div class="settings-card">
            <h3>Profile Information</h3>
            <form action="<?= base_url('settings/update_profile') ?>" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="<?= htmlspecialchars(ucfirst($user['role'] ?? 'User')) ?>" disabled style="background: #eee;">
                </div>
                <button type="submit" class="btn-save">Update Profile</button>
            </form>
        </div>

        <!-- SECURITY & THEME -->
        <div>
            <div class="settings-card" style="margin-bottom: 30px;">
                <h3>Change Password</h3>
                <form action="<?= base_url('settings/update_password') ?>" method="POST">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn-save" style="background: #07559b;">Update Password</button>
                </form>
            </div>

            <div class="settings-card">
                <h3>Appearance</h3>
                <div class="theme-toggle-container">
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: var(--text-color, #14243d);">Dark Mode</h4>
                        <p style="margin: 0; font-size: 13px; color: var(--text-muted, #666);">Switch between light and dark theme.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="themeToggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- SCRIPT PARA SA THEME TOGGLE -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('themeToggle');
        const body = document.body;

        // Check kung may naka-save na theme sa browser
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            toggle.checked = true;
        }

        toggle.addEventListener('change', function() {
            if (this.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    });
</script>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>