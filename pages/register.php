<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Smart Campus</title>
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

        .register-box {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            color: #000000;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 22px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .role-badge.admin   { background: rgba(231,76,60,0.1);  border: 1px solid rgba(231,76,60,0.2);  color: #c0392b; }
        .role-badge.student { background: rgba(52,152,219,0.1); border: 1px solid rgba(52,152,219,0.2); color: #2980b9; }
        .role-badge.staff   { background: rgba(46,204,113,0.1); border: 1px solid rgba(46,204,113,0.2); color: #27ae60; }

        .register-box h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .register-box .subtitle {
            color: #64748b;
            font-size: 0.88rem;
            margin-bottom: 28px;
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

        input[type="text"],
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

        input[type="text"]:focus,
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
            transition: filter 0.2s, transform 0.15s;
            color: #ffffff;
            margin-top: 4px;
        }

        .submit-btn:hover {
            filter: brightness(1.12);
            transform: translateY(-2px);
        }

        .submit-btn.admin   { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .submit-btn.student { background: linear-gradient(135deg, #3498db, #2980b9); }
        .submit-btn.staff   { background: linear-gradient(135deg, #2ecc71, #27ae60); }

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
    </style>
</head>
<body>

<?php
// Validate and sanitize role from URL
$allowed_roles = ['admin', 'student', 'staff'];
$role = isset($_GET['role']) && in_array($_GET['role'], $allowed_roles)
    ? $_GET['role']
    : 'student'; // default

$role_icons = ['admin' => '🛡️', 'student' => '🎓', 'staff' => '👔'];
$role_label = ucfirst($role);
$icon       = $role_icons[$role];

// Error messages
$error_msgs = [
    'empty'  => 'All fields are required.',
    'email'  => 'Please enter a valid email address.',
    'short'  => 'Password must be at least 6 characters.',
    'exists' => 'An account with that email already exists.',
    'db'     => 'Database error — please try again.',
];
?>

<div class="register-box">

    <div class="role-badge <?php echo $role; ?>">
        <?php echo $icon; ?> Registering as <?php echo $role_label; ?>
    </div>

    <h1>Create Account</h1>
    <p class="subtitle">Fill in your details to get started on Smart Campus.</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">
            <?php echo htmlspecialchars($error_msgs[$_GET['error']] ?? 'An error occurred.'); ?>
        </div>
    <?php endif; ?>

    <form action="process/register_process.php" method="POST">
        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">

        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="e.g. Hachim Abakar" required
               value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>">

        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="you@campus.edu" required
               value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Minimum 6 characters" required minlength="6">

        <button type="submit" class="submit-btn <?php echo $role; ?>">
            Register as <?php echo $role_label; ?>
        </button>
    </form>

    <div class="footer-links">
        Already have an account? <a href="login.php">Login here</a>
    </div>

    <div class="back-link">
        <a href="choose_role.php">← Change role</a>
    </div>

</div>

</body>
</html>
