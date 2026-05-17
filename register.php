<?php

include("config/db.php");

if(isset($_POST['register']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(name, email, phone, password_hash, role)
            VALUES(?, ?, ?, ?, 'attendee')";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $name,
        $email,
        $phone,
        $hashed_password
    );

    if(mysqli_stmt_execute($stmt))
    {
        echo "Your account has been created.";
    }
    else
    {
        echo "Registration Failed";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>

    <h2>Registration</h2>

    <form method="POST">

        <label>Name</label>
        <br>
        <input type="text" name="name" required>
        <br><br>

        <label>Email</label>
        <br>
        <input type="email" name="email" required>
        <br><br>

        <label>Phone</label>
        <br>
        <input type="text" name="phone" required>
        <br><br>

        <label>Password</label>
        <br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit" name="register">
            Register
        </button> 
        <br>

        <p>
        Already have an account?
        <a href="login.php">Back to Login</a>
        </p>

    </form>

</body>
</html>