<?php

session_start();

include("config/db.php");

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0)
    {
        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password_hash']))
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: index.php");
        }
        else
        {
            echo "Wrong Password";
        }
    }
    else
    {
        echo "User Not Found";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>

<h2>Login</h2>

<form method="POST">

    <label>Email</label>
    <br>
    <input type="email" name="email" required>
    <br><br>

    <label>Password</label>
    <br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit" name="login">
        Login
    </button>

</form>

</body>
</html>