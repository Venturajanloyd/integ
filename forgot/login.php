
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forgot_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["btnLogin"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($password, $user['password'])) {
           
                session_start();
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $email;
                header("Location: thomeact.php");
                exit();
            
        } else {
            //  $errorMsg = "Invalid password!";
            echo '<script>alert("Invalid password or email account");</script>';
        }
    } else {
        echo '<script>alert("Invalid password or email account");</script>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <style>
        body {
            background-color: yellow;
        }
    </style>
</head>
<body>
    <center>
        <form method="post"> 
            <h1 style="color: blue;">Login</h1>
            <input type="email" name="email" placeholder="Email" style="width: 250px; padding: 10px;"><br>
            <input type="password" name="password" placeholder="Password" style="width: 250px; padding: 10px;"><br>
            <br style="clear: both;">
            <button type="submit" name="btnLogin" style="background-color: #4CAF50; color: white; padding: 10px;">Login</button>
            <br><br>
            <div><a href="forgot.php" style="text-decoration: none; color: #007bff;">Forgot password?</a></div>
        </form>
    </center>
</body>
</html>
