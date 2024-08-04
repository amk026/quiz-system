<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Scores</title>
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

        h3 {
            color: #333;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

<h2>User Scores</h2>

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

// Get the user ID from the URL parameter
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

// Retrieve user details
$user_query = "SELECT * FROM Users WHERE UserID = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);

// Display user details
if ($user_row) {
    echo "<h3>User: {$user_row['Username']}</h3>";

    // Retrieve user scores
    $scores_query = "
        SELECT Quizzes.Title AS QuizTitle, UserQuizScores.Score
        FROM UserQuizScores
        INNER JOIN Quizzes ON UserQuizScores.QuizID = Quizzes.QuizID
        WHERE UserQuizScores.UserID = $user_id
    ";

    $scores_result = mysqli_query($conn, $scores_query);

    if (mysqli_num_rows($scores_result) > 0) {
        echo "<table border='1'>
            <tr>
                <th>Quiz Title</th>
                <th>Score</th>
            </tr>";

        while ($score_row = mysqli_fetch_assoc($scores_result)) {
            echo "<tr>
                <td>{$score_row['QuizTitle']}</td>
                <td>{$score_row['Score']}</td>
            </tr>";
        }

        echo "</table>";
    } else {
        echo "No scores available for this user.";
    }
} else {
    echo "User not found.";
}

// Close connection
mysqli_close($conn);
?>
<a href="display_quizzes2.php">Back</a>
<a href="logout.php">Logout</a>
</body>
</html>
