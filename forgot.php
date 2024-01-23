<?php 
session_start();
$error = array();

require "mail.php";

if(!$con = mysqli_connect("localhost","root","","forgot_db")){
    die("could not connect");
}

$mode = "enter_email";
if(isset($_GET['mode'])){
    $mode = $_GET['mode'];
}


if(count($_POST) > 0){
    switch ($mode) {
        case 'enter_email':
           
            $email = $_POST['email'];
            
            // Validate email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error[] = "Please enter a valid email";
            } elseif(!valid_email($email)){
                $error[] = "That email was not found";
            } else {
                $_SESSION['forgot']['email'] = $email;
                send_email($email);
                header("Location: forgot.php?mode=enter_code");
                die;
            }
            break;

        case 'enter_code':
            
            $code = $_POST['code'];
            $result = is_code_correct($code);

            if($result == "the code is correct"){
                $_SESSION['forgot']['code'] = $code;
                header("Location: forgot.php?mode=enter_password");
                die;
            } else {
                $error[] = $result;
            }
            break;

        case 'enter_password':
            
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            // Password security checks
            if ($password !== $password2) {
                $error[] = "Passwords do not match";
            } elseif (!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])) {
                header("Location: forgot.php");
                die;
            } elseif (strlen($password) < 8) {
                $error[] = "Password must be at least 8 characters long.";
            } elseif (!preg_match("/[0-9]+/", $password)) {
                $error[] = "Password must contain at least one number.";
            } elseif (!preg_match("/[!@#$%^&*()_+]+/", $password)) {
                $error[] = "Password must contain at least one special character.";
            } else {
                save_password($password);
                if (isset($_SESSION['forgot'])) {
                    unset($_SESSION['forgot']);
                }
                header("Location: login.php");
                die;
            }
            break;

        default:
            
            break;
    }
}

function send_email($email){
    global $con;

    $expire = time() + (60 * 5);
    $code = rand(10000,99999);
    $email = addslashes($email);

    $query = "insert into codes (email,code,expire) value ('$email','$code','$expire')";
    mysqli_query($con,$query);

 
    send_mail($email,'Password reset',"Your code is " . $code);
}

function save_password($password){
    global $con;

    $password = password_hash($password, PASSWORD_DEFAULT);
    $email = addslashes($_SESSION['forgot']['email']);

    $query = "update user set password = '$password' where email = '$email' limit 1";
    mysqli_query($con,$query);
}

function valid_email($email){
    global $con;

    $email = addslashes($email);

    $query = "select * from user where email = '$email' limit 1";       
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        return true;
    }

    return false;
}

function is_code_correct($code){
    global $con;

    $code = addslashes($code);
    $expire = time();
    $email = addslashes($_SESSION['forgot']['email']);

    $query = "select * from codes where code = '$code' && email = '$email' order by id desc limit 1";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        if($row['expire'] > $expire){
            return "the code is correct";
        } else {
            return "the code is expired";
        }
    } else {
        return "the code is incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Forgot</title>
</head>
<body>
<style type="text/css">
    
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
    *{
        font-family: tahoma;
        font-size: 13px;
    }

    form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            align: center;
        }

    .textbox{
        padding: 5px;
        width: 180px;
    }
    h1{
        color: green;
    }
    h3{
        color: green;
    }
</style>

<?php 
    switch ($mode) {
        case 'enter_email':
           
            ?>
            <form method="post" action="forgot.php?mode=enter_email"> 
                <center>
                    <h1>Forgot Password</h1>
                    <h3>Enter your email below</h3>
                </center>
                <span style="font-size: 12px;color:red;">
                    <?php 
                        foreach ($error as $err) {
                            echo $err . "<br>";
                        }
                    ?>
                </span>
                <input class="textbox" type="email" name="email" placeholder="Email"><br>
                <br style="clear: both;">
                <center><input type="submit" value="Next"></center>
                <br><br>
            </form>
            <?php                
            break;

        case 'enter_code':
           
            ?>
            <form method="post" action="forgot.php?mode=enter_code"> 
                <center>    
                    <h1>Forgot Password</h1>
                    <h3>Enter the code sent to your email</h3>
                </center>
                <span style="font-size: 12px;color:red;">
                    <?php 
                        foreach ($error as $err) {
                            echo $err . "<br>";
                        }
                    ?>
                </span>
                <center><input class="textbox" type="text" name="code" placeholder="12345"><br></center>
                <br style="clear: both;">
                <input type="submit" value="Next" style="float: right;">
                <a href="forgot.php">
                    <input type="button" value="Start Over">
                </a>
                <br><br>
                <div><a href="login.php">Login</a></div>
            </form>
            <?php
            break;

        case 'enter_password':
          
            ?>
            <form method="post" action="forgot.php?mode=enter_password"> 
                <center><h1>Forgot Password</h1>
                <h3>Enter your new password</h3></center>
                <span style="font-size: 12px;color:red;">
                    <?php 
                        foreach ($error as $err) {
                            echo $err . "<br>";
                        }
                    ?>
                </span>
                <input class="textbox" type="password" name="password" placeholder="Password" required><br>
                <input class="textbox" type="password" name="password2" placeholder="Retype Password" required><br>
                <br style="clear: both;">
                <input type="submit" value="Next" style="float: right;">
                <a href="forgot.php">
                    <input type="button" value="Start Over">
                </a>
                <br><br>
                <div><a href="login.php">Login</a></div>
            </form>
            <?php
            break;

        default:
            // code...
            break;
    }
?>
</body>
</html>
