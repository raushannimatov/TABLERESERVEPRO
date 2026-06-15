<?php

session_start();

if(!isset($_SESSION['admin'])){

    header("Location: login.php");
    exit();

}

include '../config/database.php';

$id = (int) $_GET['id'];

$stmt = mysqli_prepare(

    $conn,

    "DELETE FROM reservations
    WHERE id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $id

);

mysqli_stmt_execute($stmt);

header("Location: index.php");
exit();

?>