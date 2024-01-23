<?php


	if(!$con = mysqli_connect("localhost","root","","forgot_db")){

		die("could not connect");
	}

	// $password = password_hash('password', PASSWORD_DEFAULT);
	// $query = "update user set password = '$password' ";
	// mysqli_query($con,$query);


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Home</title>
</head>
<body>

<h1>Home Page</h1>
<a href="login.php">Goto Log In</a>
</body>
</html>