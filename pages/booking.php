<?php

include '../includes/header.php';
include '../config/database.php';

/** @var mysqli $conn */
$menu_items = mysqli_query($conn,
"SELECT * FROM menu_items
ORDER BY category, name");

$date = $_GET['date'];
$time = $_GET['time'];

$max_capacity = 20;

$sql = "

SELECT SUM(persons) AS total_persons

FROM reservations

WHERE reservation_date='$date'
AND reservation_time='$time'
AND status != 'cancelled'

";

/** @var mysqli $conn */
$result = mysqli_query($conn, $sql);

$data = mysqli_fetch_assoc($result);

$current_persons = $data['total_persons'] ?? 0;

$available_seats = $max_capacity - $current_persons;

?>

<section class="reservation-section">

    <div class="reservation-container">
        <a href="timeslots.php?date=<?php echo $date; ?>" class="back-btn">←</a>
        <h1>Complete Reservation</h1>

        <div class="booking-summary">

            <p>

                <strong>Date:</strong>

                <?php echo $date; ?>

            </p>

            <p>

                <strong>Time:</strong>

                <?php echo $time; ?>

            </p>

            <p>

                <strong>Available Seats:</strong>
                <?php echo $available_seats; ?>/20

            </p>

        </div>

        <form action="confirmation.php" method="POST">
            <div class="dish-selector">

                <button
                type="button"
                id="openMenuModal"
                class="btn">

                    Select Dishes Beforehand (Optional)

                </button>

                <div id="selectedCount">
                No dishes selected
                </div>

                <div id="selectedDishes"></div>

                <div id="selectedTotal"></div>

            </div>

            <input
            type="hidden"
            name="date"
            value="<?php echo $date; ?>">

            <input
            type="hidden"
            name="time"
            value="<?php echo $time; ?>">

            <input
            type="text"
            name="name"
            placeholder="Full Name"
            required>

            <input
            type="email"
            name="email"
            placeholder="Email Address"
            required>

            <input
            type="text"
            name="phone"
            placeholder="Phone Number">

            <input
            type="number"
            name="persons"
            placeholder="Number of Persons"
            min="1"
            max="<?php echo $available_seats; ?>"
            required>

            <button
            type="submit"
            class="btn">

                Complete Reservation

            </button>

           <div id="menuModal" class="modal">

    <div class="modal-content">

        <span id="closeModal" class="close-btn">

            &times;

        </span>

        <h2>Select Dishes</h2>

        <h3 class="menu-section-title">
            Starters
        </h3>

        <div class="menu-popup-grid">

            <?php
/** @var mysqli $conn */
            $starters = mysqli_query($conn,
            "SELECT * FROM menu_items
            WHERE category='Starter'
            ORDER BY name");

            while($item = mysqli_fetch_assoc($starters)) :

            ?>

                <label class="menu-popup-item">

                    <input
                    type="checkbox"
                    name="menu_items[]"
                    value="<?php echo $item['id']; ?>">

                    <span>

                        <?php echo $item['name']; ?>

                        (€<?php echo number_format($item['price'],2); ?>)

                    </span>

                </label>

            <?php endwhile; ?>

        </div>

        <h3 class="menu-section-title">
            Main Courses
        </h3>

        <div class="menu-popup-grid">

            <?php

            /** @var mysqli $conn */
            $mains = mysqli_query($conn,
            "SELECT * FROM menu_items
            WHERE category='Main'
            ORDER BY name");

            while($item = mysqli_fetch_assoc($mains)) :

            ?>

                <label class="menu-popup-item">

                    <input
                    type="checkbox"
                    name="menu_items[]"
                    value="<?php echo $item['id']; ?>">

                    <span>

                        <?php echo $item['name']; ?>

                        (€<?php echo number_format($item['price'],2); ?>)

                    </span>

                </label>

            <?php endwhile; ?>

        </div>

        <h3 class="menu-section-title">
            Desserts
        </h3>

        <div class="menu-popup-grid">

            <?php

            /** @var mysqli $conn */
            $desserts = mysqli_query($conn,
            "SELECT * FROM menu_items
            WHERE category='Dessert'
            ORDER BY name");

            while($item = mysqli_fetch_assoc($desserts)) :

            ?>

                <label class="menu-popup-item">

                    <input
                    type="checkbox"
                    name="menu_items[]"
                    value="<?php echo $item['id']; ?>">

                    <span>

                        <?php echo $item['name']; ?>

                        (€<?php echo number_format($item['price'],2); ?>)

                    </span>

                </label>

            <?php endwhile; ?>

        </div>

        <h3 class="menu-section-title">
            Drinks
        </h3>

        <div class="menu-popup-grid">

            <?php

            $drinks = mysqli_query($conn,
            "SELECT * FROM menu_items
            WHERE category='Drink'
            ORDER BY name");

            while($item = mysqli_fetch_assoc($drinks)) :

            ?>

                <label class="menu-popup-item">

                    <input
                    type="checkbox"
                    name="menu_items[]"
                    value="<?php echo $item['id']; ?>">

                    <span>

                        <?php echo $item['name']; ?>

                        (€<?php echo number_format($item['price'],2); ?>)

                    </span>

                </label>

            <?php endwhile; ?>

            </div>

    </div>

</div>

</div>

        </form>

    </div>

</section>

<?php include '../includes/footer.php'; ?>