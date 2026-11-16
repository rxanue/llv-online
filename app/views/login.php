<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LLV Online</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #fff;
        }

        /* Kaliwang bahagi - Photo ng Resort */
        .left-panel {
            width: 50%;
            /* Palitan mo itong URL ng totoong photo ng resort niyo kapag meron na */
            background-image: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Lagyan natin ng konting dark overlay para kitang-kita kung may text man tayo ilagay dito sa future */
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.2), rgba(0,0,0,0));
        }

        /* Kanang bahagi - Form */
        .right-panel {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 10%;
            background-color: #ffffff;
        }

        .right-panel h1 {
            font-size: 32px;
            color: #1A2530; /* Dark navy blue text */
            margin-bottom: 5px;
            font-weight: 700;
        }

        .right-panel p.subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1A2530;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 8px; 
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s;
        }

        /* Pag kinlick yung input, magiging orange ang border */
        .form-group input:focus {
            border-color: #F28C28;
            box-shadow: 0 0 5px rgba(242, 140, 40, 0.3);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: #F28C28; /* Orange Button */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #d97a1f; /* Mas dark na orange pag hino-hover */
        }

        .signup-text {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #333;
        }

        .signup-text a {
            color: #F28C28;
            text-decoration: none;
            font-weight: bold;
        }

        .error-box {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c62828;
        }
    </style>
</head>
<body>

    <!-- Kaliwang bahagi (Resort Photo) -->
    <div class="left-panel">
        <!-- Wala nang HTML drawing dito, background-image na ang bahala -->
    </div>

    <!-- Kanang bahagi (Login Form) -->
    <div class="right-panel">
        <h1>Welcome to LLV Online</h1>
        <p class="subtitle">Log in to manage your stay and services</p>

        <!-- Error Display -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-box">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="auth/process" method="POST">
            <div class="form-group">
                <label>Email / Username</label>
                <input type="text" name="email" placeholder="Enter your email or username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="signup-text">
            Don't have an account? <a href="signup">Sign Up</a>
        </div>
    </div>

</body>
</html>