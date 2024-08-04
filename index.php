<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0; /* Set a background color */
            color: #333; /* Change text color to a dark shade */
            margin: 0;
            padding: 0;
            text-align: center;
            height: 100vh; /* Set height to viewport height */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        header {
            background-color: #35424a;
            padding: 15px 0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: center;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            margin: 0 20px;
            font-weight: bold;
            font-size: 18px;
            transition: color 0.3s ease-in-out;
        }

        nav a:hover {
            color: #00bcd4;
        }

        h1 {
            color: #00bcd4;
            margin-bottom: 20px;
            font-size: 36px;
        }

        p {
            font-size: 18px;
            color: #35424a;
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="signup.php">Sign Up</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<h1>Test your knowledge about computer science</h1>
<?php
// Include the file to create the database and tables
require_once('createtables.php');
?>

</body>
</html>
