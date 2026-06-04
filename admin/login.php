<?php

session_start();

include '../config/database.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins
    WHERE username='$username'
    AND password='$password'";

    /** @var mysqli $conn */
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $_SESSION['admin'] = $username;

        header("Location: index.php");

    }else{

        $error = "Invalid login credentials.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

    <link rel="stylesheet"
    href="/TableReservePro/assets/css/style.css">

</head>

<body>

<section class="reservation-section">

    <div class="reservation-container">

        <h1>Admin Login</h1>

        <?php if(isset($error)) : ?>

            <div class="error-message">
                <?php echo $error; ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <input type="text"
            name="username"
            placeholder="Username"
            required>

            <input type="password"
            name="password"
            placeholder="Password"
            required>

            <button type="submit"
            name="login"
            class="btn">

                Login

            </button>

        </form>

    </div>

</section>

</body>
</html>