<?php

session_start();

include("config/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
}

if(!isset($_GET['id']))
{
    die("Event ID Missing");
}

$event_id = $_GET['id'];

$sql = "
SELECT events.*, categories.name AS category_name
FROM events
LEFT JOIN categories
ON events.category_id = categories.id
WHERE events.id = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $event_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$event = mysqli_fetch_assoc($result);

if(!$event)
{
    die("Event Not Found");
}

$tier_sql = "
SELECT *
FROM ticket_tiers
WHERE event_id = ?
";

$tier_stmt = mysqli_prepare($conn, $tier_sql);

mysqli_stmt_bind_param($tier_stmt, "i", $event_id);

mysqli_stmt_execute($tier_stmt);

$tier_result = mysqli_stmt_get_result($tier_stmt);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Event Details</title>
</head>

<body>

<h1>
    <?php echo $event['title']; ?>
</h1>

<p>
    <strong>Category:</strong>
    <?php echo $event['category_name']; ?>
</p>

<p>
    <strong>City:</strong>
    <?php echo $event['city']; ?>
</p>

<p>
    <strong>Venue:</strong>
    <?php echo $event['venue_name_override']; ?>
</p>

<p>
    <strong>Event Start:</strong>
    <?php echo $event['event_datetime']; ?>
</p>

<p>
    <strong>Event End:</strong>
    <?php echo $event['end_datetime']; ?>
</p>

<p>
    <strong>Description:</strong>
</p>

<p>
    <?php echo $event['description']; ?>
</p>

<br>

<hr>

<h2>Ticket Tiers</h2>

<?php

while($tier = mysqli_fetch_assoc($tier_result))
{
?>

<div style="border:1px solid gray; padding:10px; margin-bottom:15px;">

    <h3>
        <?php echo $tier['name']; ?>
    </h3>

    <p>
        <?php echo $tier['description']; ?>
    </p>

    <p>
        <strong>Price:</strong>
        ৳ <?php echo $tier['price']; ?>
    </p>

    <p>
        <strong>Total Seats:</strong>
        <?php echo $tier['total_seats']; ?>
    </p>

    <p>
        <strong>Sales Start:</strong>
        <?php echo $tier['sales_start']; ?>
    </p>

    <p>
        <strong>Sales End:</strong>
        <?php echo $tier['sales_end']; ?>
    </p>

</div>

<?php
}
?>

<a href="index.php">
    Back to Events
</a>

</body>
</html>