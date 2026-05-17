<?php
session_start();
include "../../includes/db_connect.php";

$user_id = $_SESSION['user_id'];
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$service_id = 1;

$stmt = $conn->prepare("INSERT INTO request (title, description, requester_person_id, service_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssii", $title, $description, $user_id, $service_id);

if ($stmt->execute()) {
    $request_id = $conn->insert_id;
    
    // Automated feedback message
    $system_sender = 1; // Assuming 1 is Admin/System
    $subject = "Request Received: " . $title;
    $body = "We have received your request and it is currently Pending. We will update the status soon.";
    
    $msg_stmt = $conn->prepare("INSERT INTO message (subject, body, sent_datetime, read_status, sender_person_id, receiver_person_id, request_id) VALUES (?, ?, NOW(), 'unread', ?, ?, ?)");
    $msg_stmt->bind_param("ssiii", $subject, $body, $system_sender, $user_id, $request_id);
    $msg_stmt->execute();

    header("Location: ../dasboard/student_dasboard.php");
} else {
    echo "Error: " . $conn->error;
}
?>
