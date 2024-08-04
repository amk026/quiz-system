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

        header {
            background-color: #35424a;
            padding: 15px 0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            color: #fff;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            transition: color 0.3s ease-in-out;
        }

        nav a:hover {
            color: #45a049;
        }

        h2 {
            color: #333;
            margin-top: 60px; /* Add space below fixed header */
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
            color: #4CAF50;
            font-weight: bold;
            font-size: 18px;
            transition: color 0.3s ease-in-out;
        }

        a:hover {
            color: #45a049;
        }

        /* Style for the rectangle effect */
        .rectangle {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            border-radius: 5px;
            overflow: hidden;
        }

        .rectangle a {
            color: #fff;
            display: block;
            transition: transform 0.3s ease-in-out;
        }

        .rectangle:hover a {
            transform: scale(1.1); /* Increase size on hover */
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href='display_quizzes2.php'>Display Quizzes 2</a>
        <a href='logout.php'>Logout</a>
        <a href='display_quizzes3.php'>Display Quizzes 3</a>
        <a href='display_users_scores.php'>Display Users Scores</a>
        <div class="rectangle">
            <a href='create_quiz.php'>Create Quiz</a>
        </div>
    </nav>
</header>

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
        echo "<li><a href='add_questions.php?quiz_id={$row['QuizID']}'>{$row['Title']}</a></li>";
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
