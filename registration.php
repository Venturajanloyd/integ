<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration</title>
    <style>
        body {
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 5;
            padding: 5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            align: center;
        }

        h1 {
            color: green;
        }

        input, button {
            width: 250px;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: black;
        }

        a {
            text-decoration: none;
            color: green;
        }

        a{
            width: 250px;
            padding: 10px;
            margin-bottom: 10px;
        }
        
    </style>
</head>
<body>
    <center>
        <form id="registration-form" method="post" action="registration.php"> 
            <h1>Register</h1>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit" name="btnRegister">Register</button><br>
            <a href="login.php">Already have an account? Login here.</a>
          </form>
    </center>
</body>
</html>


<?php
if (isset($_POST["btnRegister"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $registration_date = date('Y-m-d H:i:s'); 

    // Validation 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format");</script>';
        exit();
    }

    if (strlen($password) < 8 || !preg_match("/[0-9]+/", $password) || !preg_match("/[!@#$%^&*()_+]+/", $password)) {
        echo '<script>alert("Password must be at least 8 characters long and include at least one number and one special character");</script>';
        exit();
    }

    if ($password !== $confirm_password) {
        echo '<script>alert("Passwords do not match");</script>';
        exit();
    }

    // Hash the passwords
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $hashed_confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT);

 
    $conn = mysqli_connect('localhost', 'root', '', 'forgot_db');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO user (email, password, registration_date, confirm_password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $email, $hashed_password, $registration_date, $hashed_confirm_password);

    if ($stmt->execute()) {
        echo '<script>alert("Registration Complete");</script>';
    } else {
        echo '<script>alert("Registration failed. Please try again.");</script>';
    }

    $stmt->close();
    $conn->close();
}
?>

