<?php

session_start();

include '../config/database.php';

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM admins WHERE username = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $username
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if($admin = mysqli_fetch_assoc($result)){

        if(password_verify(
            $password,
            $admin['password']
        )){
            session_regenerate_id(true);
            $_SESSION['admin'] = $admin['username'];

            header("Location: index.php");
            exit();

        }

    }

    $error = "Invalid login credentials.";

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