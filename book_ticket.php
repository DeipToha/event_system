<?php

session_start();

include("config/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
}

if(!isset($_POST['user_id']))
{
    $attendee_id = $_SESSION['user_id'];

    $event_id = $_POST['event_id'];
    $tier_id = $_POST['tier_id'];
    $quantity = $_POST['quantity'];

    $tier_sql = "SELECT * FROM ticket_tiers WHERE id = ? AND event_id = ?";

    $tier_stmt = mysqli_prepare($conn, $tier_sql);
    mysqli_stmt_bind_param($tier_stmt, "ii", $tier_id, $event_id);
    mysqli_stmt_execute($tier_stmt);

    $tier_result = mysqli_stmt_get_result($tier_stmt);
    $tier = mysqli_fetch_assoc($tier_result);

    if(!$tier)
    {
        die("Invalid Ticket Tier");
    }


    $booked_sql = "
    SELECT SUM(quantity) AS total_booked
    FROM bookings
    WHERE tier_id = ?
    AND status = 'active'
    ";

    $booked_stmt = mysqli_prepare($conn, $booked_sql);
    mysqli_stmt_bind_param($booked_stmt, "i", $tier['id']);
    mysqli_stmt_execute($booked_stmt);
    $booked_result = mysqli_stmt_get_result($booked_stmt);
    $booked_data = mysqli_fetch_assoc($booked_result);
    $total_booked = $booked_data['total_booked'] ?? 0;

    $remaining_seats = $tier['total_seats'] - $total_booked;

    if($quantity > $remaining_seats)
    {
        die("Not enough seats available.");
    }

    $total_price = $tier['price'] * $quantity;
    $ticket_code = strtoupper(uniqid("TICKET_"));

    $insert_sql = 
    "INSERT INTO bookings
    (attendee_id, event_id, tier_id, quantity, total_price, ticket_code)
    VALUES (?, ?, ?, ?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "iiiids", $attendee_id, $event_id, $tier_id, $quantity, $total_price, $ticket_code);
    

    if(mysqli_stmt_execute($insert_stmt))
    {
        echo "Booking Successful.";
        echo "<br>";
        echo "Ticket Code: " . $ticket_code;
    }
    else
    {
        echo "Booking Failed";
    }

}
?>