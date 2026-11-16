<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - LLV Online</title>
    <style>
        body { margin: 0; display: flex; height: 100vh; font-family: Arial, sans-serif; }
        .left-panel { width: 50%; background: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d') center/cover; }
        .right-panel { width: 50%; display: flex; flex-direction: column; justify-content: center; padding: 0 10%; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-orange { width: 100%; padding: 12px; background: #F28C28; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .error-box { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="left-panel"></div>
    <div class="right-panel">
        <h1>Create an Account</h1>
        <p>Join LLV Online to manage your stay.</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-box"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="signup/process" method="POST">
    <div class="form-group">
        <label>Full Name</label>
        <!-- Dapat may name="name" ito -->
        <input type="text" name="name" placeholder="Enter your full name" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
    </div>
    <button type="submit" class="btn-login">Sign Up</button>
</form>
        <p style="text-align:center; margin-top: 20px;">Already have an account? <a href="login" style="color:#F28C28;">Login here</a></p>
    </div>
</body>
</html>