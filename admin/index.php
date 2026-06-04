<?php

session_start();

include '../config/database.php';

/** @var mysqli $conn */
$total_query = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM reservations");

$total = mysqli_fetch_assoc($total_query)['total'];

$accepted_query = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM reservations
WHERE status='accepted'");

$accepted = mysqli_fetch_assoc($accepted_query)['total'];

$pending_query = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM reservations
WHERE status='pending'");

$pending = mysqli_fetch_assoc($pending_query)['total'];

$cancelled_query = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM reservations
WHERE status='cancelled'");

$cancelled = mysqli_fetch_assoc($cancelled_query)['total'];

if(!isset($_SESSION['admin'])){

    header("Location: login.php");

}

$sql = "SELECT * FROM reservations
ORDER BY created_at DESC";

/** @var mysqli $conn */
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet"
    href="/TableReservePro/assets/css/style.css">

</head>

<body>

<div class="dashboard">

    <div class="dashboard-header">

        <h1>Reservations Dashboard</h1>

        <a href="logout.php" class="btn">
            Logout
        </a>

    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Reservations</h3>
            <span><?php echo $total; ?></span>
        </div>

        <div class="stat-card">
            <h3>Accepted</h3>
            <span><?php echo $accepted; ?></span>
        </div>

        <div class="stat-card">
            <h3>Pending</h3>
            <span><?php echo $pending; ?></span>
        </div>

        <div class="stat-card">
            <h3>Cancelled</h3>
            <span><?php echo $cancelled; ?></span>
        </div>

    </div>

    <div class="table-wrapper">

        <table>

            <tr>

                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Time</th>
                <th>Persons</th>
                <th>Dishes</th>
                <th>Status</th>
                <th>Actions</th>

            </tr>

            <?php while($row = mysqli_fetch_assoc($result)) : ?>

                <tr>

                    <td><?php echo $row['name']; ?></td>

                    <td><?php echo $row['email']; ?></td>

                    <td><?php echo $row['reservation_date']; ?></td>

                    <td><?php echo $row['reservation_time']; ?></td>

                    <td><?php echo $row['persons']; ?></td>

                    <td>

                    <?php

                    $dishes_query = mysqli_query($conn,

                    "SELECT menu_items.name

                    FROM reservation_items

                    INNER JOIN menu_items

                    ON reservation_items.menu_item_id = menu_items.id

                    WHERE reservation_items.reservation_id = ".$row['id']);

                    while($dish = mysqli_fetch_assoc($dishes_query)){

                        echo "- " . $dish['name'] . "<br>";

                    }

                    ?>

                    </td>

                    <td>

                        <?php if($row['status'] == 'pending') : ?>

                            <span class="status pending">                                                                                   
                                Pending 
                            </span>

                        <?php elseif($row['status'] == 'accepted') : ?>

                            <span class="status accepted">
                                Accepted
                            </span>

                        <?php else : ?>

                            <span class="status cancelled">
                                Cancelled
                            </span>

                        <?php endif; ?>

                    </td>

                    <td class="actions">

                        <a href="update-status.php?id=<?php echo $row['id']; ?>&status=accepted"
                        class="accept-btn">
                            Accept
                        </a>

                        <a href="update-status.php?id=<?php echo $row['id']; ?>&status=cancelled"
                        class="cancel-btn">
                            Cancel
                        </a>

                        <a href="delete-reservation.php?id=<?php echo $row['id']; ?>"
                        class="delete-btn">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        </table>

    </div>

</div>

</body>
</html>