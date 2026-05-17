<?php
session_start();
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en','tr'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
// Go back to the previous page or index
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $referer);
exit();
?>
