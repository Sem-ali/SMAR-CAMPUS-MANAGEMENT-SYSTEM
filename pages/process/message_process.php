<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../../includes/db_connect.php';

$sender_id   = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$subject     = !empty($_POST['subject']) ? $_POST['subject'] : NULL;
$body        = $_POST['body'];
$request_id  = $_POST['request_id'];

$sql = "INSERT INTO message (subject, body, sent_datetime, read_status,
            sender_person_id, receiver_person_id, request_id)
        VALUES (?, ?, NOW(), 'Unread', ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiii", $subject, $body, $sender_id, $receiver_id, $request_id);

if ($stmt->execute()) {
    header('Location: ../message.php?sent=ok');
} else {
    echo "Error: " . $conn->error;
}
?>
