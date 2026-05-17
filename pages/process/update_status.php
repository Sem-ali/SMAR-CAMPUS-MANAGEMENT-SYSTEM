<?php
session_start();
include "../../includes/db_connect.php";

$id = $_POST['id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE request SET status = ? WHERE request_id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    header("Location: ../dasboard/admin_dashboard.php");
} else {
    echo "Error updating status: " . $conn->error;
}
?>
