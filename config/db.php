<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "event_management_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn)
{
    die("Connection Failed");
}

// echo "Database Connected Successfully";

?>