<?php

include '../includes/header.php';
include '../config/database.php';

$date = $_GET['date'];

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

        <?php echo $date; ?>

    </strong>

</p>

<div class="timeslot-grid">

    <?php foreach($timeslots as $slot) : ?>

        <?php

        $sql = "

        SELECT SUM(persons) AS total_persons

        FROM reservations

        WHERE reservation_date='$date'
        AND reservation_time='$slot'
        AND status != 'cancelled'

        ";

        /** @var mysqli $conn */
        $result = mysqli_query($conn, $sql);

        $data = mysqli_fetch_assoc($result);

        $total_persons = $data['total_persons'] ?? 0;

        if($total_persons >= 20){

            $status = "FULL";

        }elseif($total_persons >= 15){

            $status = "Almost Full";

        }else{

            $status = "Available";

        }

        ?>

        <?php if($status != "FULL") : ?>

            <a

            href="booking.php?date=<?php echo $date; ?>&time=<?php echo $slot; ?>"

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