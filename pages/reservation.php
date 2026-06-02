<?php

include '../includes/header.php';
include '../config/database.php';

$date = date("Y-m-d");

$timeslots = [
    "17:00",
    "18:00",
    "19:00",
    "20:00",
    "21:00"
];

$max_capacity = 20;

$calendar_days = [];

for ($i = 0; $i < 30; $i++) {

    $current_date = date('Y-m-d', strtotime("+$i days"));

    $day_name = date('l', strtotime($current_date));
/** @var mysqli $conn */
    $day_sql = mysqli_query($conn,"SELECT * FROM opening_daysWHERE day_name='$day_name'");

    $day_data = mysqli_fetch_assoc($day_sql);

    $available_slots = 0;

    foreach ($timeslots as $slot) {

        $slot_sql = "

        SELECT SUM(persons) AS total_persons

        FROM reservations

        WHERE reservation_date='$current_date'
        AND reservation_time='$slot'
        AND status != 'cancelled'

        ";

        $slot_result = mysqli_query($conn, $slot_sql);

        $slot_data = mysqli_fetch_assoc($slot_result);

        $total_persons = $slot_data['total_persons'] ?? 0;

        if ($total_persons < $max_capacity) {
            $available_slots++;
        }
    }

    $is_open = $day_data['is_open'];

    if ($available_slots == 0) {
        $is_open = 0;
    }

    $calendar_days[] = [

        'date' => $current_date,
        'day' => date('d', strtotime($current_date)),
        'month' => date('M', strtotime($current_date)),
        'is_open' => $is_open

    ];
}

?>

<section class="reservation-section">

    <div class="reservation-container">

        <h1>Reserve Your Table</h1>

        <div class="booking-calendar">

            <h3>Select a Date</h3>

            <div class="calendar-grid">

                <?php foreach($calendar_days as $calendar_day) : ?>

                    <a href="timeslots.php?date=<?php echo $calendar_day['date']; ?>"
                       class="calendar-day <?php echo $calendar_day['is_open'] ? 'open' : 'closed'; ?>">

                        <span class="day-number">
                            <?php echo $calendar_day['day']; ?>
                        </span>

                        <span class="day-month">
                            <?php echo $calendar_day['month']; ?>
                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>
