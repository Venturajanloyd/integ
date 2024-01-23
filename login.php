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
    $recaptchaResponse = $_POST["g-recaptcha-response"];

    $recaptchaSecretKey = "6LfcplMpAAAAAA4BFtO42fVpD2xtWbjzMHA46GAN"; 
    $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $recaptchaVerifyData = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
    ];

    $recaptchaVerifyResult = json_decode(file_get_contents($recaptchaVerifyUrl . '?' . http_build_query($recaptchaVerifyData)), true);

    if (!$recaptchaVerifyResult['success']) {
        // Captcha verification failed
        echo '<script>alert("reCAPTCHA verification failed. Please try again.");</script>';
        exit();
    }

    

    
    $maxAttempts = 3; 
    $loginAttempts = getLoginAttempts($conn, $email);

    if ($loginAttempts >= $maxAttempts) {
        echo '<script>alert("Maximum login attempts reached. Please try again later.");</script>';
    } else {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            if (password_verify($password, $user['password'])) {
               
                resetLoginAttempts($conn, $email);

                session_start();
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $email;
                header("Location: thomeact.php");
                exit();
            } else {
              
                increaseLoginAttempts($conn, $email);
                echo '<script>alert("Invalid password or email account");</script>';
            }
        } else {
            echo '<script>alert("Invalid password or email account");</script>';
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}

// Function to get the current login attempts for a user
function getLoginAttempts($conn, $email) {
    $getAttemptsSql = "SELECT login_attempts FROM user WHERE email = ?";
    $getAttemptsStmt = mysqli_prepare($conn, $getAttemptsSql);
    mysqli_stmt_bind_param($getAttemptsStmt, "s", $email);
    mysqli_stmt_execute($getAttemptsStmt);
    $result = mysqli_stmt_get_result($getAttemptsStmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($getAttemptsStmt);

    return $row ? $row['login_attempts'] : 0;
}

// Function to increase login attempts for a user
function increaseLoginAttempts($conn, $email) {
    $increaseAttemptsSql = "UPDATE user SET login_attempts = login_attempts + 1 WHERE email = ?";
    $increaseAttemptsStmt = mysqli_prepare($conn, $increaseAttemptsSql);
    mysqli_stmt_bind_param($increaseAttemptsStmt, "s", $email);
    mysqli_stmt_execute($increaseAttemptsStmt);
    mysqli_stmt_close($increaseAttemptsStmt);
}

// Function to reset login attempts for a user
function resetLoginAttempts($conn, $email) {
    $resetAttemptsSql = "UPDATE user SET login_attempts = 0 WHERE email = ?";
    $resetAttemptsStmt = mysqli_prepare($conn, $resetAttemptsSql);
    mysqli_stmt_bind_param($resetAttemptsStmt, "s", $email);
    mysqli_stmt_execute($resetAttemptsStmt);
    mysqli_stmt_close($resetAttemptsStmt);
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        <form method="post"> 
            <h1>Login</h1>
            <input type="email" name="email" placeholder="Email" style="width: 300px; height: 10px;"><br>
            <input type="password" name="password" placeholder="Password" style="width: 300px; height: 10px;" ><br>
            <div class="g-recaptcha" data-sitekey="6LfcplMpAAAAAC0TzgjsXZ46pzrR6HfFMxPpAPph" style="width: 450px; height: 80px;"></div><br>
            <button type="submit" name="btnLogin">Login</button><br>
            <a href="registration.php">Register</a>

            <br><br>
            <div><a href="forgot.php">Forgot password?</a></div>
        </form>
    </center>
</body>
</html>

