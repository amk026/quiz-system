<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users and Scores</title>
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

        table {
            width: 70%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }
    </style>
</head>
<a href="logout.php">Logout</a>
<a href="display_quizzes.php">Back</a>
<body>

<h2>Users and Scores</h2>

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

// Retrieve users and their scores
$users_scores_query = "
    SELECT Users.Username, Quizzes.Title AS QuizTitle, UserQuizScores.Score
    FROM Users
    INNER JOIN UserQuizScores ON Users.UserID = UserQuizScores.UserID
    INNER JOIN Quizzes ON UserQuizScores.QuizID = Quizzes.QuizID
";

$users_scores_result = mysqli_query($conn, $users_scores_query);

if (mysqli_num_rows($users_scores_result) > 0) {
    echo "<table>
        <tr>
            <th>User</th>
            <th>Quiz Title</th>
            <th>Score</th>
        </tr>";

    while ($row = mysqli_fetch_assoc($users_scores_result)) {
        echo "<tr>
            <td>{$row['Username']}</td>
            <td>{$row['QuizTitle']}</td>
            <td>{$row['Score']}</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "No users and scores available.";
}

// Close connection
mysqli_close($conn);
?>

</body>
</html>
