<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Role — Smart Campus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        /* ── Role Selection Page Styles ── */
        body {
            min-height: 100vh;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
            padding: 0;
        }

        .page-header {
            text-align: center;
            padding: 60px 20px 30px;
            color: #000000;
        }

        .page-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .page-header p {
            font-size: 1.05rem;
            color: #475569;
            max-width: 480px;
            margin: 0 auto;
        }

        /* ── Role Cards Grid ── */
        .role-grid {
            display: flex;
            justify-content: center;
            gap: 28px;
            flex-wrap: wrap;
            padding: 30px 24px 60px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .role-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 44px 36px 36px;
            width: 260px;
            text-align: center;
            text-decoration: none;
            color: #000000;
            cursor: pointer;
            transition: transform 0.25s ease, background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            border-radius: 20px 20px 0 0;
            transition: opacity 0.25s ease;
            opacity: 0;
        }

        .role-card:hover {
            transform: translateY(-10px);
            background: #f8fafc;
            border-color: #cbd5e1;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .role-card:hover::before {
            opacity: 1;
        }

        /* ── Individual role colours ── */
        .role-card.admin::before  { background: linear-gradient(90deg, #e74c3c, #c0392b); }
        .role-card.student::before { background: linear-gradient(90deg, #3498db, #2980b9); }
        .role-card.staff::before  { background: linear-gradient(90deg, #2ecc71, #27ae60); }

        .role-card.admin:hover   { border-color: rgba(231,76,60,0.55);  box-shadow: 0 20px 40px rgba(231,76,60,0.15); }
        .role-card.student:hover { border-color: rgba(52,152,219,0.55); box-shadow: 0 20px 40px rgba(52,152,219,0.15); }
        .role-card.staff:hover   { border-color: rgba(46,204,113,0.55); box-shadow: 0 20px 40px rgba(46,204,113,0.15); }

        /* ── Icon ── */
        .role-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 2.2rem;
            transition: transform 0.25s ease;
        }

        .role-card:hover .role-icon {
            transform: scale(1.12);
        }

        .role-card.admin   .role-icon { background: rgba(231,76,60,0.1);  }
        .role-card.student .role-icon { background: rgba(52,152,219,0.1); }
        .role-card.staff   .role-icon { background: rgba(46,204,113,0.1); }

        .role-card h2 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .role-card p {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .role-btn {
            display: inline-block;
            padding: 10px 28px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: filter 0.2s ease, transform 0.2s ease;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 0.3px;
        }

        .role-btn:hover {
            filter: brightness(1.15);
            transform: scale(1.04);
        }

        .role-card.admin   .role-btn { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .role-card.student .role-btn { background: linear-gradient(135deg, #3498db, #2980b9); }
        .role-card.staff   .role-btn { background: linear-gradient(135deg, #2ecc71, #27ae60); }

        /* ── Back link ── */
        .back-link {
            text-align: center;
            padding-bottom: 30px;
        }

        .back-link a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.2s;
        }

        .back-link a:hover { color: #475569; }

        /* ── Login prompt ── */
        .login-prompt {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            padding-bottom: 50px;
        }

        .login-prompt a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .login-prompt a:hover { text-decoration: underline; }

        @media (max-width: 640px) {
            .role-card { width: 100%; max-width: 320px; }
            .page-header h1 { font-size: 1.7rem; }
        }
    </style>
</head>
<body>

    <div class="page-header">
        <h1>Join Smart Campus</h1>
        <p>Choose your role to create the right account for you</p>
    </div>

    <div class="role-grid">

        <!-- Admin -->
        <a class="role-card admin" href="register.php?role=admin" id="role-admin">
            <div class="role-icon">🛡️</div>
            <h2>Admin</h2>
            <p>Manage the platform, oversee all requests, assign staff, and control system settings.</p>
            <span class="role-btn">Register as Admin</span>
        </a>

        <!-- Student -->
        <a class="role-card student" href="register.php?role=student" id="role-student">
            <div class="role-icon">🎓</div>
            <h2>Student</h2>
            <p>Submit service requests, book appointments, send messages and track your campus activities.</p>
            <span class="role-btn">Register as Student</span>
        </a>

        <!-- Staff -->
        <a class="role-card staff" href="register.php?role=staff" id="role-staff">
            <div class="role-icon">👔</div>
            <h2>Staff</h2>
            <p>Handle assigned student requests, update statuses, and manage day-to-day campus services.</p>
            <span class="role-btn">Register as Staff</span>
        </a>

    </div>

    <div class="login-prompt">
        Already have an account? <a href="login.php">Login here</a>
    </div>

    <div class="back-link">
        <a href="../index.php">← Back to Home</a>
    </div>

</body>
</html>
