<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Front Page</title>
    <style>
        /* Reset some default styles */
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            text-align: center;
            padding: 20px;
        }

        header {
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
        }

        h1 {
            font-size: 36px;
            margin-top: 20px;
        }

        p {
            font-size: 18px;
            margin-top: 10px;
        }

    
        .btn {
            display: inline-block;
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
        }

      
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to my Website</h1>
    </header>
    
    <main>
        <a href="login.php" class="btn">Log Out</a>
    </main>
</body>
</html>
