<?php
session_start();
include "../../includes/db_connect.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT request_id, title, status, created_date FROM request WHERE assignee_person_id = ? ORDER BY created_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$requests = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard — Smart Campus</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f4f7f6;
            color: #333;
        }
        .navbar {
            background: #1a3a5c;
            padding: 15px 30px;
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .navbar a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        /* Dropdown */
        .navbar .dropdown {
            position: relative;
        }
        .navbar .dropdown > span {
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            padding: 5px 0;
            user-select: none;
        }
        .navbar .dropdown > span:hover {
            text-decoration: underline;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            min-width: 200px;
            z-index: 100;
            overflow: hidden;
        }
        .dropdown:hover .dropdown-menu,
        .dropdown:focus-within .dropdown-menu {
            display: block;
        }
        .dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: #1a3a5c;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-bottom: 1px solid #f0f4f8;
            transition: background 0.15s;
        }
        .dropdown-menu a:last-child {
            border-bottom: none;
        }
        .dropdown-menu a:hover {
            background: #f0f7ff;
            text-decoration: none;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 40px;
            text-align: center;
        }
        h2, h3 {
            color: #1a3a5c;
            margin-top: 0;
        }
        p {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin: 15px auto;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            line-height: 1.8;
            max-width: 600px;
        }
        .request-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0;
            margin: 15px auto;
            max-width: 640px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .request-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.09);
            border-color: #3498db;
        }
        .request-card-title {
            display: block;
            background: #f0f7ff;
            padding: 14px 20px;
            font-weight: 700;
            font-size: 1rem;
            color: #1a3a5c;
            text-decoration: none;
            border-bottom: 1px solid #e2e8f0;
        }
        .request-card-title:hover {
            background: #dbeafe;
            color: #2980b9;
        }
        .request-card-body {
            padding: 14px 20px;
            font-size: 0.92rem;
            color: #475569;
            text-align: left;
            line-height: 1.8;
        }
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-progress  { background: #dbeafe; color: #1e40af; }
        .badge-completed { background: #d1fae5; color: #065f46; }
        select, button {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-family: 'Inter', sans-serif;
            margin-top: 10px;
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            margin-left: 10px;
        }
        button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="staff_dasboard.php">Dashboard</a>
    <div class="dropdown">
        <span>Services &#9660;</span>
        <div class="dropdown-menu">
            <a href="../message.php">Messages</a>
            <a href="../feedback.php">Feedback</a>
            <a href="../view_appoint.php">My Appointments</a>
            <a href="#" target="_blank">E-Yoklama</a>
            <a href="#" target="_blank">OBS</a>
        </div>
    </div>
    <a href="../logout.php">Logout</a>
</div>

<div class="dashboard-container">
    <h2>Staff Dashboard &mdash; <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
    <p style="color:#64748b; font-size:0.95rem; margin-top:0; border:none; box-shadow:none; padding:0;">Use the <strong>Services</strong> dropdown in the navbar to access messages, feedback, and more.</p>

<h3>My Assigned Requests</h3>
<?php if ($requests && $requests->num_rows > 0): ?>
    <?php while ($row = $requests->fetch_assoc()):
        $badgeClass = 'badge-pending';
        if ($row['status'] === 'In Progress') $badgeClass = 'badge-progress';
        if ($row['status'] === 'Completed')  $badgeClass = 'badge-completed';
    ?>
        <div class="request-card">
            <a href="../search_request.php?id=<?php echo $row['request_id']; ?>" target="_blank" class="request-card-title">
                #<?php echo $row['request_id']; ?> &mdash; <?php echo htmlspecialchars($row['title']); ?>
            </a>
            <div class="request-card-body">
                Status: <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span><br>
                Date: <?php echo htmlspecialchars($row['created_date']); ?><br>
                <form action="../process/update_status.php" method="POST" style="margin-top:10px;">
                    <input type="hidden" name="id" value="<?php echo $row['request_id']; ?>">
                    <select name="status">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <button type="submit">Update Status</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No assigned requests.</p>
<?php endif; ?>
            <form action="../process/update_status.php" method="POST" style="display:inline">
                <input type="hidden" name="id" value="<?php echo $row['request_id']; ?>">
                <select name="status">
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
                <button type="submit">Update</button>
            </form>
        </p><hr>
    <?php endwhile; ?>
<?php else: ?>
    <p>No assigned requests.</p>
<?php endif; ?>
</div>

</body>
</html>
