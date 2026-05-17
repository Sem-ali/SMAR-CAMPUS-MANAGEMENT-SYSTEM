<?php
$host = 'localhost';
$user = 'root';           // default XAMPP
$pass = '';               // default empty
$db   = 'smart_campus';   // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>