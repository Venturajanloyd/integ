<div class="row">
  <div class="col-md-4 mb-4">
    <div class="card">
      <!-- <img src="pictures/cvtaerox.jpg" class="card-img-top"> -->
      <div class="card-body bg-info">
        <center>
          <form action="order_process1.php" method="post">

            <label for="product">Product name:</label>
            <select id="product" name="product" required>
              <option value="">select product</option>
              <option value="p1">RS8 TARAGSIT CVT SET FOR AEROX/NMAX</option>
              <option value="p2">RS8 TARAGSIT CVT SET FOR HONDA BEAT</option>
              <option value="p3">RS8 TARAGSIT CVT SET FOR MIO 125/M3</option>
            </select><br><br>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" required><br><br>

            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <center><button type="submit" class="btn btn-primary mt-2">Order Now</button></center>
            <center><button type="btn" class="btn btn-primary mt-2"><a href="order_details.php">order list</a></button></center>
          </form>
        </center>
      </div>
    </div>
  </div>
</div>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product = $_POST["product"];
    $quantity = $_POST["quantity"];
    $name = $_POST["name"];
    $email = $_POST["email"];

    $prices = [
        "p1" => 11000,
        "p2" => 12000,
        "p3" => 13000,
    ];


    if (array_key_exists($product, $prices)) {
       
        $price = $prices[$product];

       
        $totalPrice = $quantity * $price;

       
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "forgot_db";

        $conn = mysqli_connect($servername, $username, $password, $dbname);

     
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

       
        $sql = "INSERT INTO orders (product, quantity, name, email, price, total_price) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissdd", $product, $quantity, $name, $email, $price, $totalPrice);

        if ($stmt->execute()) {
            echo '<script>alert("Order Placed Successfully");</script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        
        echo '<script>alert("Invalid product selected");</script>';
    }
} else {
    
    exit();
}
?>
