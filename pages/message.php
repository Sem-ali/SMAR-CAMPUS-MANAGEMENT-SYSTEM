<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../includes/db_connect.php';
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'en';
require_once "../includes/lang_{$_SESSION['lang']}.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT m.message_id, m.subject, m.sent_datetime, m.read_status,
               s.name AS sender_name,
               r.name AS receiver_name,
               m.request_id
        FROM message m
        LEFT JOIN users s ON m.sender_person_id = s.id
        LEFT JOIN users r ON m.receiver_person_id = r.id
        WHERE m.sender_person_id = ? OR m.receiver_person_id = ?
        ORDER BY m.sent_datetime DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$role = $_SESSION['role'] ?? 'student';
if ($role === 'admin') {
    $dash_url = 'dasboard/admin_dashboard.php';
} elseif ($role === 'staff') {
    $dash_url = 'dasboard/staff_dasboard.php';
} else {
    $dash_url = 'dasboard/student_dasboard.php';
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/index.css">
    <title><?=$lang['messages'] ?></title>
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f4f7f6; color: #333; display: flex; justify-content: center; min-height: 100vh; padding: 40px 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); padding: 40px; width: 100%; max-width: 900px; text-align: center; }
        h2 { color: #1a3a5c; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; background: #ffffff; }
        table, th, td { border: 1px solid #e2e8f0; }
        th, td { padding: 12px; text-align: center; }
        th { background: #e1effe; color: #1e429f; }
        a { color: #3498db; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <p style="text-align:right;">
        <a href="../switch_lang.php?lang=en">English</a> |
        <a href="../switch_lang.php?lang=tr">Türkçe</a>
        </p>
        <h2><?= $lang['messages'] ?></h2>
        <table border="1">
            <tr>
                <th><?= $lang['subject'] ?></th>
                <th><?= $lang['from'] ?></th>
                <th><?= $lang['to'] ?></th>
                <th><?= $lang['date'] ?></th>
                <th><?= $lang['status'] ?></th>
                <th><?= $lang['request'] ?></th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= htmlspecialchars($row['sender_name'] ?? 'System') ?></td>
                    <td><?= htmlspecialchars($row['receiver_name'] ?? 'System') ?></td>
                    <td><?= $row['sent_datetime'] ?></td>
                    <td><?= $row['read_status'] ?></td>
                    <td>
                        <a href="search_request.php?id=<?= $row['request_id'] ?>"><?= $lang['view'] ?></a> | 
                        <a href="give_feedback.php?request_id=<?= $row['request_id'] ?>"><?= $lang['feedback'] ?></a> | 
                        <a href="process/delete_message.php?id=<?= $row['message_id'] ?>" style="color: #e74c3c;" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
            <a href="<?= $dash_url ?>" style="background: #e2e8f0; color: #475569; padding: 10px 20px; border-radius: 8px; font-weight: bold;">&larr; Return to Dashboard</a>
            <a href="send_message.php" style="background: #3498db; color: #ffffff; padding: 10px 20px; border-radius: 8px; font-weight: bold;"><?= $lang['send_message'] ?></a>
        </div>
    </div>
</body>
</html>
                 