<?php
session_start();
include "../includes/db.php";

$id = $_POST['id'];
$staff_id = $_POST['staff_id'];

$sql = "UPDATE demande SET assigned_to = :staff_id WHERE id = :id";

$stid = oci_parse($conn, $sql);

oci_bind_by_name($stid, ":staff_id", $staff_id);
oci_bind_by_name($stid, ":id", $id);

if (oci_execute($stid)) {
    header("Location: admin_dashboard.php");
} else {
    echo "Assignment error!";
}
?>
