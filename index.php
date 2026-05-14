<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>

<h1>
    Welcome <?php echo $_SESSION['user_name']; ?>
</h1>

<p>
    You are logged in as:
    <?php echo $_SESSION['user_role']; ?>
</p>

<a href="logout.php">
    Logout
</a>

</body>
</html>