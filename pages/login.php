<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Smart Campus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body {
            min-height: 100vh;
            background: #ffffff;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-box {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 400px;
            color: #000000;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .brand-icon {
            font-size: 2.4rem;
            margin-bottom: 16px;
            display: block;
            text-align: center;
        }

        .login-box h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 6px;
            text-align: center;
        }

        .login-box .subtitle {
            color: #64748b;
            font-size: 0.88rem;
            margin-bottom: 28px;
            text-align: center;
        }

        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 18px;
            font-weight: 500;
        }

        .alert.error   { background: rgba(231,76,60,0.1);  border: 1px solid rgba(231,76,60,0.2);  color: #c0392b; }
        .alert.success { background: rgba(46,204,113,0.1); border: 1px solid rgba(46,204,113,0.2); color: #27ae60; }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            color: #000000;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            margin-bottom: 18px;
            margin-top: 0;
            transition: border-color 0.2s, background 0.2s;
            outline: none;
            box-sizing: border-box;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            background: #ffffff;
        }

        input::placeholder { color: #94a3b8; }

        .submit-btn {
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: #ffffff;
            transition: filter 0.2s, transform 0.15s;
            margin-top: 4px;
        }

        .submit-btn:hover {
            filter: brightness(1.12);
            transform: translateY(-2px);
        }

        .footer-links {
            margin-top: 22px;
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
        }

        .footer-links a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover { text-decoration: underline; }

        .back-link {
            margin-top: 12px;
            text-align: center;
        }

        .back-link a {
            color: #94a3b8;
            font-size: 0.82rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover { color: #475569; }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<?php
$role_icons = ['admin' => '🛡️', 'student' => '🎓', 'staff' => '👔'];
$reg_role   = $_GET['role'] ?? '';
?>

<div class="login-box">

    <span class="brand-icon">🏛️</span>
    <h1>Welcome Back</h1>
    <p class="subtitle">Sign in to your Smart Campus account</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">
            ❌ Invalid email or password. Please try again.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['registered'])): ?>
        <?php $icon = $role_icons[$reg_role] ?? '✅'; ?>
        <div class="alert success">
            <?php echo $icon; ?> Account created successfully! You can now log in.
        </div>
    <?php endif; ?>

    <form action="process/login_process.php" method="POST">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="you@campus.edu" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Your password" required>

        <button type="submit" class="submit-btn" id="login-btn">Login</button>
    </form>

    <div class="divider"></div>

    <div class="footer-links">
        Don't have an account? <a href="choose_role.php">Register here</a>
    </div>

    <div class="back-link">
        <a href="../index.php">← Back to Home</a>
    </div>

</div>

</body>
</html>
