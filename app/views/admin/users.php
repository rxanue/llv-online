<?php
$active_page = 'admin_users'; $title = 'Manage Users';
include __DIR__ . '/../layouts/header.php'; 
?>

<div style="padding: 40px 10%; background: var(--bg-color);">
    <h1 style="color: var(--text-color); margin-bottom: 20px;">Manage Users</h1>

    <div class="stat-card" style="margin-bottom: 30px;">
        <h3 style="color: var(--text-color); margin-bottom: 15px;">Add New User</h3>
        <form action="<?= base_url('admin/users/store') ?>" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
            <input type="text" name="username" placeholder="Username" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
            <input type="email" name="email" placeholder="Email" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
            <input type="password" name="password" placeholder="Password" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
            <select name="role" required style="padding:10px; border:1px solid #ccc; border-radius:5px;">
                <option value="user">Customer</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" style="grid-column: span 2; padding: 12px; background: #ff7900; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Add User</button>
        </form>
    </div>

    <div style="background: var(--card-bg); border-radius: 12px; padding: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Username</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Email</th>
                    <th style="padding: 10px; text-align: left; color: var(--text-muted);">Role</th>
                    <th style="padding: 10px; text-align: center; color: var(--text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): $user = (array)$u; ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($user['username'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color);"><?= htmlspecialchars($user['email'] ?? '') ?></td>
                    <td style="padding: 15px; color: var(--text-color); text-transform: capitalize;"><?= htmlspecialchars($user['role'] ?? '') ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <button onclick="editUser(<?= $user['id'] ?? 0 ?>, '<?= htmlspecialchars($user['username'] ?? '') ?>', '<?= htmlspecialchars($user['email'] ?? '') ?>', '<?= htmlspecialchars($user['role'] ?? '') ?>')" style="background: #07559b; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; margin-right: 5px;">Edit</button>
                        <form action="<?= base_url('admin/users/delete/' . ($user['id'] ?? 0)) ?>" method="POST" onsubmit="return confirm('Delete this user?');" style="display:inline;">
                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="editUserModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--card-bg); padding:30px; border-radius:12px; width:90%; max-width:400px;">
        <h3 style="color:var(--text-color); margin-top:0;">Edit User</h3>
        <form action="<?= base_url('admin/users/update') ?>" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="username" id="edit_username" required style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;">
            <input type="email" name="email" id="edit_email" required style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;">
            <input type="password" name="password" placeholder="New Password (leave blank to keep current)" style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;">
            <select name="role" id="edit_role" required style="width:100%; padding:10px; margin-bottom:20px; border-radius:5px; border:1px solid #ccc;">
                <option value="user">Customer</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="closeEditModal()" style="flex:1; padding:12px; background:#ccc; border:none; border-radius:5px; cursor:pointer;">Cancel</button>
                <button type="submit" style="flex:1; padding:12px; background:#ff7900; color:white; border:none; border-radius:5px; cursor:pointer;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editUser(id, username, email, role) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        document.getElementById('editUserModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>