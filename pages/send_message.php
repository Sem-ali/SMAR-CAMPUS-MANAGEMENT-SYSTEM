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

$sql = "SELECT request_id, title FROM request
        WHERE requester_person_id = ? OR assignee_person_id = ?
        ORDER BY created_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $person_id, $person_id);
$stmt->execute();
$requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$sql2 = "SELECT id as person_id, name as first_name, '' as last_name FROM users WHERE id != ? ORDER BY name";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $person_id);
$stmt2->execute();
$persons = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/index.css">
    <title><?= $lang['send_message'] ?></title>
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f4f7f6; color: #333; display: flex; justify-content: center; min-height: 100vh; padding: 40px 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); padding: 40px; width: 100%; max-width: 600px; text-align: center; }
        h2 { color: #1a3a5c; margin-bottom: 20px; }
        form { display: flex; flex-direction: column; align-items: center; text-align: center; }
        label { font-weight: 600; color: #475569; margin: 10px 0 5px; }
        input, select, textarea { width: 100%; max-width: 400px; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-family: 'Inter', sans-serif; background: #f8fafc; box-sizing: border-box; }
        button { background: #3498db; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 20px; transition: background 0.2s; }
        button:hover { background: #2980b9; }
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
        <h2><?= $lang['send_message'] ?></h2>
        <form action="process/message_process.php" method="POST">
            <label><?= $lang['to'] ?>:</label>
            <select name="receiver_id" required>
                <option value=""><?= $lang['select_recipient'] ?></option>
                <?php foreach ($persons as $p): ?>
                    <option value="<?= $p['person_id'] ?>">
                        <?= $p['first_name'] . ' ' . $p['last_name'] ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label><?= $lang['subject'] ?> (opsiyonel):</label>
            <input type="text" name="subject"><br><br>

            <label><?= $lang['body'] ?>:</label>
            <textarea name="body" required></textarea><br><br>

            <label><?= $lang['related_request'] ?>:</label>
            <select name="request_id" required>
                <option value=""><?= $lang['select_request'] ?></option>
                <?php foreach ($requests as $req): ?>
                    <option value="<?= $req['request_id'] ?>">
                        <?= htmlspecialchars($req['title']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit"><?= $lang['send'] ?></button>
        </form>
    </div>
</body>
</html>
            