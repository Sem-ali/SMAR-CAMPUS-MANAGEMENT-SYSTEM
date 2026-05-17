<?php
session_start();
include "../../includes/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../register.php");
    exit();
}

$name     = trim($_POST['name']     ?? '');
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';

// Validate and sanitize role
$allowed_roles = ['admin', 'student', 'staff'];
$role = isset($_POST['role']) && in_array($_POST['role'], $allowed_roles)
    ? $_POST['role']
    : 'student';

// ── Validation ──
if (empty($name) || empty($email) || empty($password)) {
    header("Location: ../register.php?role=$role&error=empty");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../register.php?role=$role&error=email");
    exit();
}

if (strlen($password) < 6) {
    header("Location: ../register.php?role=$role&error=short");
    exit();
}

// ── Check duplicate email ──
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $check->close();
    header("Location: ../register.php?role=$role&error=exists");
    exit();
}
$check->close();

// ── Insert user ──
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt   = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashed, $role);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../login.php?registered=1&role=$role");
    exit();
} else {
    $stmt->close();
    header("Location: ../register.php?role=$role&error=db");
    exit();
}
?>
