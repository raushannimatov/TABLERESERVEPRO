<?php

include '../includes/header.php';
include '../config/database.php';

$selected_dishes = [];

if(isset($_POST['menu_items'])){

    $ids = implode(',', $_POST['menu_items']);
    /** @var mysqli $conn */
    $dish_query = mysqli_query($conn,

    "SELECT name
    FROM menu_items
    WHERE id IN ($ids)");

    while($dish = mysqli_fetch_assoc($dish_query)){

        $selected_dishes[] = $dish['name'];

    }

}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $persons = $_POST['persons'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $sql = "INSERT INTO reservations
    (name, email, phone, reservation_date, reservation_time, persons)

    VALUES

    ('$name', '$email', '$phone', '$date', '$time', '$persons')";
    /** @var mysqli $conn */
    if(mysqli_query($conn, $sql)){

        $reservation_id = mysqli_insert_id($conn);

    }else{

        die("Reservation could not be saved.");

    }
    if(isset($_POST['menu_items'])){

    foreach($_POST['menu_items'] as $menu_item_id){

        mysqli_query($conn,

        "INSERT INTO reservation_items

        (reservation_id, menu_item_id)

        VALUES
    
        ('$reservation_id', '$menu_item_id')");

    }

}

}else{

    header("Location: reservation.php");
    exit();

}

?>

<section class="reservation-section">

    <div class="reservation-container confirmation-box">

        <div class="confirmation-icon">

            ✓

        </div>

        <h1>Reservation Confirmed</h1>

        <p>

            Thank you for choosing TableReserve Pro.

        </p>

        <div class="booking-summary">

            <p>
                <strong>Reservation ID:</strong>
                #<?php echo $reservation_id; ?>
            </p>

            <p>
                <strong>Date:</strong>
                <?php echo $date; ?>
            </p>

            <p>
                <strong>Time:</strong>
                <?php echo $time; ?>
            </p>

            <p>
                <strong>Guests:</strong>
                <?php echo $persons; ?>
            </p>

            <p>
                <strong>Name:</strong>
                <?php echo $name; ?>
            </p>

            <?php if(!empty($selected_dishes)) : ?>

    <p>

        <strong>Pre-Ordered Dishes:</strong>

    </p>

    <ul style="list-style:none; padding:0;">

        <?php foreach($selected_dishes as $dish) : ?>

            <li>

                 <?php echo $dish; ?>

            </li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>

        </div>

        <a href="/TableReservePro/index.php" class="btn">

            Back To Home

        </a>

    </div>

</section>

<?php include '../includes/footer.php'; ?>