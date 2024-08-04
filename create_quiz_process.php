<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizd";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_title = mysqli_real_escape_string($conn, $_POST['quiz_title']);

    // Insert quiz into the database
    $insertQuizQuery = "INSERT INTO Quizzes (Title) VALUES ('$quiz_title')";
    if (mysqli_query($conn, $insertQuizQuery)) {
        $success_message = "Quiz created successfully!";
    } else {
        $error_message = "Error creating quiz: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Created Successfully</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        main {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        p.success-message {
            color: #4CAF50;
            font-size: 18px;
            margin-bottom: 20px;
        }

        p.error-message {
            color: #f44336;
            font-size: 18px;
            margin-bottom: 20px;
        }

        a.logout-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        a.logout-btn:hover {
            background-color: #1b6ca8;
        }
    </style>
</head>
<body>

<header>
    <h1>Quiz Created Successfully</h1>
</header>

<main>
    <?php
    if (isset($success_message)) {
        echo "<p class='success-message'>$success_message</p>";
    } elseif (isset($error_message)) {
        echo "<p class='error-message'>$error_message</p>";
    }
    ?>
 <a class="logout-btn" href="display_quizzes.php">create more</a>
    <a class="logout-btn" href="logout.php">Logout</a>
</main>

</body>
</html>
