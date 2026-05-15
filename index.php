<?php

session_start();

include("config/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
}

$sql = "
SELECT events.*, categories.name AS category_name
FROM events
LEFT JOIN categories
ON events.category_id = categories.id
WHERE status='published'
ORDER BY event_datetime ASC
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>All Events</title>
</head>

<body>

<h1>Upcoming Events</h1>

<p>
Welcome,
<?php echo $_SESSION['user_name']; ?>
</p>

<a href="my_ticket.php">My Tickets</a>
<a href="logout.php">Logout</a>

<hr>

<?php

while($event = mysqli_fetch_assoc($result))
{
?>

<div style="border:1px solid black; padding:15px; margin-bottom:20px;">

    <h2>
        <a href="event_details.php?id=<?php echo $event['id']; ?>">
        <?php echo $event['title']; ?>
        </a>
    </h2>

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
        <strong>Date:</strong>
        <?php echo $event['event_datetime']; ?>
    </p>

    <p>
        <?php echo $event['description']; ?>
    </p>

</div>

<?php
}
?>

</body>
</html>