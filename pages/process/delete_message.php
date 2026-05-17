<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../../includes/db_connect.php';

$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = (int)$_SESSION['user_id'];

if ($message_id > 0) {
    // Only allow deletion if the user is the sender or receiver
    $stmt = $conn->prepare("DELETE FROM message WHERE message_id = ? AND (sender_person_id = ? OR receiver_person_id = ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iii", $message_id, $user_id, $user_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
}

header("Location: ../message.php");
exit();
?>
