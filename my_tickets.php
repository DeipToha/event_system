<?php 

session_start();

include("config/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT
    bookings.*,
    events.title AS event_title,
    events.event_datetime,
    events.city,
    ticket_tiers.name AS tier_name

    FROM bookings

    LEFT JOIN events
    ON bookings.event_id = events.id

    LEFT JOIN ticket_tiers
    ON bookings.tier_id = ticket_tiers.id

    WHERE bookings.attendee_id = ?

    ORDER BY bookings.created_at DESC
    "
;  

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html>

<head>
    <title>My Tickets</title>
</head>

<body>
    <h1>My Tickets</h1>
    <p>
        Welcome,
        <?php echo $_SESSION['user_name']; ?>
    </p>
    <a href="index.php">Back to Events</a>
    <hr>

    <?php
    if(mysqli_num_rows($result) > 0)
    {
        while($booking = mysqli_fetch_assoc($result))
        {
    ?>
        <div style="border:1px solid black; padding:15px; margin-bottom:20px;">
            <h2><?php echo $booking['event_title']; ?></h2>
            <p>
                <strong>Event Date & Time:</strong>
                <?php echo date("F j, Y, g:i a", strtotime($booking['event_datetime'])); ?>
            </p>
            <p>
                <strong>City:</strong>
                <?php echo $booking['city']; ?>
            </p>
            <p>
                <strong>Ticket Tier:</strong>
                <?php echo $booking['tier_name']; ?>
            </p>
            <p>
                <strong>Quantity:</strong>
                <?php echo $booking['quantity']; ?>
            </p>
            <p>
                <strong>Total Price:</strong>
                ৳<?php echo number_format($booking['total_price'], 2); ?>
            </p>
            <p>
                <strong>Ticket Code:</strong>
                <?php echo $booking['ticket_code']; ?>
            </p>
            <p>
                <strong>Status:</strong>
                <?php echo ucfirst($booking['status']); ?>
            </p>
        </div>
        <?php
        }
    }
    else
    {
        echo "<p>No Booking Found.</p>";
    }
    ?>

</body>
</html>