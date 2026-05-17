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

// Feedback given
$sql1 = "SELECT f.feedback_id, f.rating, f.comments, f.submitted_date,
               r.title AS request_title, r.request_id
        FROM feedback f
        JOIN request r ON f.request_id = r.request_id
        WHERE f.author_person_id = ?
        ORDER BY f.submitted_date DESC";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $person_id);
$stmt1->execute();
$given = $stmt1->get_result();

// Feedback received
$sql2 = "SELECT f.feedback_id, f.rating, f.comments, f.submitted_date,
               p.name AS author_name
        FROM feedback f
        JOIN request r ON f.request_id = r.request_id
        JOIN users p ON f.author_person_id = p.id
        WHERE r.requester_person_id = ?
        ORDER BY f.submitted_date DESC";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $person_id);
$stmt2->execute();
$received = $stmt2->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/index.css">
    <title><?= $lang['feedback'] ?></title>
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f4f7f6; color: #333; display: flex; justify-content: center; min-height: 100vh; padding: 40px 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); padding: 40px; width: 100%; max-width: 900px; text-align: center; }
        h2, h3 { color: #1a3a5c; margin-top: 20px; }
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
        <h2><?= $lang['feedback'] ?></h2>

        <h3><?= $lang['feedback_given'] ?></h3>
        <table border="1">
            <tr><th><?= $lang['request'] ?></th><th><?= $lang['rating'] ?></th><th><?= $lang['comments'] ?>
            </th><th><?= $lang['submitted_date'] ?></th></tr>
            <?php while ($row = $given->fetch_assoc()): ?>
                <tr>
                    <td><a href="search_request.php?id=<?= $row['request_id'] ?>">
                    <?= htmlspecialchars($row['request_title']) ?></a></td>
                    <td><?= $row['rating'] ?>/5</td>
                    <td><?= htmlspecialchars($row['comments']) ?></td>
                    <td><?= $row['submitted_date'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h3><?= $lang['feedback_received'] ?></h3>
        <table border="1">
            <tr><th><?= $lang['from'] ?></th><th><?= $lang['rating'] ?></th><th>
                    <?= $lang['comments'] ?></th><th>
                    <?= $lang['submitted_date'] ?></th></tr>
            <?php while ($row = $received->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['author_name']) ?></td>
                    <td><?= $row['rating'] ?>/5</td>
                    <td><?= htmlspecialchars($row['comments']) ?></td>
                    <td><?= $row['submitted_date'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <p><a href="give_feedback.php"><?= $lang['give_feedback'] ?></a></p>
    </div>
</body>
</html>
                