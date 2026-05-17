<?php
session_start();
include "../../includes/db_connect.php";

$user_id = $_SESSION['user_id'];
$date = $_POST['appointment_date'];
$time = $_POST['appointment_time'];

$start_time = $date . ' ' . $time . ':00';
$end_time = $date . ' ' . date('H:i:s', strtotime($time) + 3600);
$location = "Online";
$attendee = 1;
$request_id = 1;

$stmt = $conn->prepare("INSERT INTO appointment (subject, start_time, end_time, location, organizer_person_id, attendee_person_id, request_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$subject = "Meeting";
$stmt->bind_param("ssssiii", $subject, $start_time, $end_time, $location, $user_id, $attendee, $request_id);

if ($stmt->execute()) {
    header("Location: ../dasboard/student_dasboard.php");
} else {
    echo "Appointment error: " . $conn->error;
}
?>
