<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../includes/db_connect.php';
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'en';
require_once "../includes/lang_{$_SESSION['lang']}.php";

$person_id = $_SESSION['user_id'];

$sql = "SELECT a.appointment_id, a.subject, a.start_time, a.end_time,
               a.location, a.status,
               o.name AS organizer,
               t.name AS attendee,
               a.request_id
        FROM appointment a
        JOIN users o ON a.organizer_person_id = o.id
        JOIN users t ON a.attendee_person_id = t.id
        WHERE a.organizer_person_id = ? OR a.attendee_person_id = ?
        ORDER BY a.start_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $person_id, $person_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/index.css">
    <title><?= $lang['appointments'] ?></title>
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f4f7f6; color: #333; display: flex; justify-content: center; min-height: 100vh; padding: 40px 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); padding: 40px; width: 100%; max-width: 1000px; text-align: center; }
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
        <h2><?= $lang['appointments'] ?></h2>
        <p><a href="create_appoint.php"><?= $lang['new_appointment'] ?></a></p>
        <table border="1">
            <tr>
                <th><?= $lang['subject'] ?></th>
                <th><?= $lang['organizer'] ?></th>
                <th><?= $lang['attendee'] ?></th>
                <th><?= $lang['start'] ?></th>
                <th><?= $lang['end'] ?></th>
                <th><?= $lang['location'] ?></th>
                <th><?= $lang['status'] ?></th>
                <th><?= $lang['request'] ?></th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['subject'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['organizer']) ?></td>
                    <td><?= htmlspecialchars($row['attendee']) ?></td>
                    <td><?= $row['start_time'] ?></td>
                    <td><?= $row['end_time'] ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><a href="search_request.php?id=<?= $row['request_id'] ?>"><?= $lang['view'] ?></a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
                