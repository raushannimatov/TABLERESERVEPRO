<?php

include '../config/database.php';

$id = $_GET['id'];
$status = $_GET['status'];

$sql = "UPDATE reservations
SET status='$status'
WHERE id='$id'";

/** @var mysqli $conn */
mysqli_query($conn, $sql);

header("Location: index.php");

?>