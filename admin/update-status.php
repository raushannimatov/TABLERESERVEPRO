<?php

session_start();

if(!isset($_SESSION['admin'])){

    header("Location: login.php");
    exit();

}

include '../config/database.php';

$id = (int) $_GET['id'];

$status = $_GET['status'];

$allowed_statuses = [

    'pending',
    'accepted',
    'cancelled'

];

if(!in_array($status, $allowed_statuses)){

    die("Invalid status.");

}

$stmt = mysqli_prepare(

    $conn,

    "UPDATE reservations
    SET status = ?
    WHERE id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "si",

    $status,

    $id

);

mysqli_stmt_execute($stmt);

header("Location: index.php");
exit();

?>