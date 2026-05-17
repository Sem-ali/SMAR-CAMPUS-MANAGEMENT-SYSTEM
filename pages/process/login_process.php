<?php
session_start();
include "../../includes/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit();
}

$email    = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    die("All fields are required.");
}

// Fetch user by email
$stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if ($user && password_verify($password, $user['password'])) {
    // Store session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['email']   = $user['email'];
    $_SESSION['role']    = $user['role'];

    // Redirect based on role
    switch ($user['role']) {
        case 'admin':
            header("Location: ../dasboard/admin_dashboard.php");
            break;
        case 'staff':
            header("Location: ../dasboard/staff_dasboard.php");
            break;
        default:
            header("Location: ../dasboard/student_dasboard.php");
    }
    exit();
} else {
    // Redirect back with error
    header("Location: ../login.php?error=invalid");
    exit();
}
?>
