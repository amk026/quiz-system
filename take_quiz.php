<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
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

        form {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #333;
        }

        p {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

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

// Get the quiz ID from the URL parameter
$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : 0;

// Retrieve quiz details
$quiz_query = "SELECT * FROM Quizzes WHERE QuizID = $quiz_id";
$quiz_result = mysqli_query($conn, $quiz_query);
$quiz_row = mysqli_fetch_assoc($quiz_result);

// Display quiz title
if ($quiz_row) {
    echo "<h2>{$quiz_row['Title']}</h2>";
} else {
    echo "Quiz not found.";
    exit;
}

?>

<form action="submit_quiz.php" method="post">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

    <h3>Questions:</h3>

    <?php
    // Retrieve questions for the quiz
    $questions_query = "SELECT * FROM Questions WHERE QuizID = $quiz_id";
    $questions_result = mysqli_query($conn, $questions_query);

    if (mysqli_num_rows($questions_result) > 0) {
        while ($question_row = mysqli_fetch_assoc($questions_result)) {
            $question_id = $question_row['QuestionID'];
            echo "<p>{$question_row['QuestionText']}</p>";

            // Retrieve answers for the question
            $answers_query = "SELECT * FROM Answers WHERE QuestionID = $question_id";
            $answers_result = mysqli_query($conn, $answers_query);

            while ($answer_row = mysqli_fetch_assoc($answers_result)) {
                $answer_id = $answer_row['AnswerID'];
                echo "<label><input type='radio' name='answers[$question_id]' value='$answer_id'> {$answer_row['AnswerText']}</label><br>";
            }
        }
    } else {
        echo "No questions available for this quiz.";
    }
    ?>

    <br>
    <input type="submit" value="Submit Quiz">
</form>
<a href="display_quizzes.php">Back</a>
<a href="logout.php">Logout</a>
</body>
</html>
