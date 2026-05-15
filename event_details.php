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

<a href="index.php">
    Back to Events
</a>

</body>
</html>