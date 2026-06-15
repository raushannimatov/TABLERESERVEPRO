<?php

include '../includes/header.php';
include '../config/database.php';

$date = $_GET['date'] ?? '';

if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)){

    die("Invalid date.");

}

$timeslots = [
    "17:00",
    "18:00",
    "19:00",
    "20:00",
    "21:00"
];

$max_capacity = 20;

?>

<section class="reservation-section">

    <div class="reservation-container">
        <a href="reservation.php" class="back-btn">←</a>
        <h1>Select a Time</h1>

        <p>

    Selected date:

    <strong>

        <?php echo htmlspecialchars($date); ?>

    </strong>

</p>

<div class="timeslot-grid">

    <?php foreach($timeslots as $slot) : ?>
    <?php

        /** @var mysqli $conn */
        $stmt = mysqli_prepare(

    $conn,

    "SELECT SUM(persons) AS total_persons

    FROM reservations

    WHERE reservation_date = ?
    AND reservation_time = ?
    AND status != 'cancelled'"

);

mysqli_stmt_bind_param(

    $stmt,

    "ss",

    $date,

    $slot

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

        $data = mysqli_fetch_assoc($result);

        $total_persons = $data['total_persons'] ?? 0;

        if($total_persons >= $max_capacity){

            $status = "FULL";

        }elseif($total_persons >= 15){

            $status = "Almost Full";

        }else{

            $status = "Available";

        }

        ?>

        <?php if($status != "FULL") : ?>

            <a

            href="booking.php?date=<?php echo urlencode($date); ?>&time=<?php echo urlencode($slot); ?>"

            class="timeslot-card">

                <strong><?php echo $slot; ?></strong>

                <span><?php echo $status; ?></span>

            </a>

        <?php else : ?>

            <div class="timeslot-card full">

                <strong><?php echo $slot; ?></strong>

                <span>FULL</span>

            </div>

        <?php endif; ?>

    <?php endforeach; ?>

</div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>