<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Quizzes</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            text-align: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            display: block;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<a href="logout.php">Logout</a>
<a href="display_quizzes.php">Back</a>
<body>

<h2>Quizzes</h2>

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

// Retrieve quizzes from the database
$quizzes_query = "SELECT * FROM Quizzes";
$quizzes_result = mysqli_query($conn, $quizzes_query);

if (mysqli_num_rows($quizzes_result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($quizzes_result)) {
        echo "<li><a href='take_quiz.php?quiz_id={$row['QuizID']}'>{$row['Title']}</a></li>";
    }
    echo "</ul>";
} else {
    echo "No quizzes available.";
}

// Close connection
mysqli_close($conn);
?>

</body>
</html>
