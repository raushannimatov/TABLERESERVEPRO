<?php

include '../config/database.php';

$id = $_GET['id'];

$sql = "DELETE FROM reservations
WHERE id='$id'";

/** @var mysqli $conn */
mysqli_query($conn, $sql);

header("Location: index.php");

?>