
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>User Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: url('bg.jpg') no-repeat center center fixed;
            color: green;
        }

        .order-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        .order-header {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .order-details {
            margin-left: 20px;
        }

        .order-details hr {
            margin-top: 15px;
            margin-bottom: 15px;
            border: 0;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>

<form method="post" action="">
    <label for="email">Enter Email:</label>
    <input type="text" id="email" name="email" required>
    <input type="submit" value="Submit">
     </form>


<section>
     <center><a class="btn btn-primary mt-2" href="thomeact.php" role="button">Home Page</a></center>
     </section>
</body>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>


<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forgot_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : "johnericson010@gmail.com"; 
} else {
    $email = "johnericson010@gmail.com"; 
}

$sql = "SELECT * FROM orders WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Order ID: " . $row["id"] . "<br>";
        echo "Name: " .$row["name"]. "<br>";
        echo "Product: " . $row["product"] . "<br>";
        echo "Quantity: " . $row["quantity"] . "<br>";
        echo "Price: ₱" . number_format($row["price"], 2) . "<br>";

        // Calculate and display total price
        $totalPrice = $row["quantity"] * $row["price"];
        echo "Total Price: ₱" . number_format($totalPrice, 2) . "<br>";

        echo "Order Date: " . $row["order_date"] . "<br>";
        echo "<hr>";
    }
} else {
    echo "No orders found for this email address.";
}

$stmt->close();
$conn->close();
?>


<footer class="text-center py-3 bg-dark text-white mt-5">
    <p>&copy; 2023 R GARAGE - Motorcycle Parts</p>
  </footer>