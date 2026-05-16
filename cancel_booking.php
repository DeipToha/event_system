<?php

session_start();

include("config/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id']))
{
    die("Booking ID missing");
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "
UPDATE bookings
SET status='cancelled'
WHERE id = ?
AND attendee_id = ?
AND status='active'
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $booking_id,
    $user_id
);

if(mysqli_stmt_execute($stmt))
{
    header("Location: my_tickets.php");
    exit();
}
else
{
    echo "Cancellation failed";
}

?>