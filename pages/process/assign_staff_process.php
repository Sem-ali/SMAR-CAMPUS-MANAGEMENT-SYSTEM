<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../../includes/db_connect.php';

$request_id = $_POST['request_id'];
$staff_id   = $_POST['staff_id'];

$sql = "UPDATE request SET assignee_person_id = ? WHERE request_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $staff_id, $request_id);

if ($stmt->execute()) {
    header('Location: ../search_request.php?id=' . $request_id . '&assigned=1');
} else {
    echo "Assignment failed: " . $conn->error;
}
?>
