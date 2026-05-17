<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../../includes/db_connect.php';

$author_id  = $_SESSION['user_id'];
$request_id = $_POST['request_id'];
$rating     = $_POST['rating'];
$comments   = $_POST['comments'];

$sql = "INSERT INTO feedback (rating, comments, submitted_date, request_id, author_person_id)
        VALUES (?, ?, CURDATE(), ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isii", $rating, $comments, $request_id, $author_id);

if ($stmt->execute()) {
    header('Location: ../feedback.php?ok=1');
} else {
    echo "Error: " . $conn->error;
}
?>
