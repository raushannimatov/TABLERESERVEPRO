<?php
session_start();
if(
    !isset($_POST['csrf_token']) ||
    !isset($_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
)
{

    die("Invalid CSRF token.");

}

unset($_SESSION['csrf_token']);

include '../includes/header.php';
include '../config/database.php';

$selected_dishes = [];

if(!empty($_POST['menu_items'])){

    $ids = array_map('intval', $_POST['menu_items']);

    $ids = implode(',', $ids);

    $dish_query = mysqli_query(
        $conn,
        "SELECT name
         FROM menu_items
         WHERE id IN ($ids)"
    );

    while($dish = mysqli_fetch_assoc($dish_query)){

        $selected_dishes[] = $dish['name'];

    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = trim($_POST['name']);
    if(empty($name)){
    die("Name is required.");
    }
    $email = trim($_POST['email']);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    die("Invalid email address.");
    }
    $phone = trim($_POST['phone']);
    $persons = (int)$_POST['persons'];
    if($persons < 1 || $persons > 20){
    die("Invalid number of persons.");
    }
    $date = trim($_POST['date']);
    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)){
    die("Invalid date format.");
    }
    $time = trim($_POST['time']);
    $allowed_times = [
    "17:00",
    "18:00",
    "19:00",
    "20:00",
    "21:00"
    ];

    if(!in_array($time, $allowed_times)){
        die("Invalid time.");
}

$max_capacity = 20;

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
    $time
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

$current_persons = $data['total_persons'] ?? 0;

$available_seats = $max_capacity - $current_persons;

if($persons > $available_seats){
    die("Not enough available seats.");
}
    $stmt = mysqli_prepare(
    $conn,
    "INSERT INTO reservations
    (name, email, phone, reservation_date, reservation_time, persons)
    VALUES
    (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(

    $stmt,

    "sssssi",

    $name,
    $email,
    $phone,
    $date,
    $time,
    $persons

);

if(mysqli_stmt_execute($stmt)){

    $reservation_id = mysqli_insert_id($conn);

}else{

    die("Reservation could not be saved.");

}
    if(!empty($_POST['menu_items'])){

    foreach($_POST['menu_items'] as $menu_item_id){

    $menu_item_id = (int)$menu_item_id;

    $check_stmt = mysqli_prepare(
    $conn,
    "SELECT id
     FROM menu_items
     WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $check_stmt,
        "i",
        $menu_item_id
    );

    mysqli_stmt_execute($check_stmt);

    $check_result = mysqli_stmt_get_result($check_stmt);

    if(mysqli_num_rows($check_result) === 0){
        die("Invalid menu item.");
    }

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO reservation_items

        (reservation_id, menu_item_id)

        VALUES

        (?, ?)"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $reservation_id,

        $menu_item_id

    );

    if(!mysqli_stmt_execute($stmt)){
    die("Could not save menu items.");
}

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
                #<?php echo (int)$reservation_id; ?>
            </p>

            <p>
                <strong>Date:</strong>
                <?php echo htmlspecialchars($date); ?>
            </p>

            <p>
                <strong>Time:</strong>
                <?php echo htmlspecialchars($time); ?>
            </p>

            <p>
                <strong>Guests:</strong>
                <?php echo htmlspecialchars($persons); ?>
            </p>

            <p>
                <strong>Name:</strong>
                <?php echo htmlspecialchars($name); ?>
            </p>

            <?php if(!empty($selected_dishes)) : ?>

    <p>

        <strong>Pre-Ordered Dishes:</strong>

    </p>

    <ul style="list-style:none; padding:0;">

        <?php foreach($selected_dishes as $dish) : ?>

            <li>
                 <?php echo htmlspecialchars($dish); ?>
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