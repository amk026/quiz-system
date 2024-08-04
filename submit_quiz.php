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
    $quiz_id = mysqli_real_escape_string($conn, $_POST['quiz_id']);
    $user_id = 1; // Replace with actual user ID (from authentication or session)
    $answers = $_POST['answers'];

    // Check if the user already took this quiz
    $checkUserQuizQuery = "SELECT * FROM UserQuizScores WHERE UserID = $user_id AND QuizID = $quiz_id";
    $checkUserQuizResult = mysqli_query($conn, $checkUserQuizQuery);

    if (mysqli_num_rows($checkUserQuizResult) > 0) {
        // User already took the quiz, update the score
        $updateScoreQuery = "UPDATE UserQuizScores SET Score = 0 WHERE UserID = $user_id AND QuizID = $quiz_id";
        mysqli_query($conn, $updateScoreQuery);
    } else {
        // User is taking the quiz for the first time, insert a new score
        $insertScoreQuery = "INSERT INTO UserQuizScores (UserID, QuizID, Score) VALUES ($user_id, $quiz_id, 0)";
        mysqli_query($conn, $insertScoreQuery);
    }

    // Calculate the score
    $score = 0;

    // Retrieve correct answers from the database
    $correctAnswersQuery = "SELECT QuestionID, AnswerID FROM Answers WHERE IsCorrect = 1 AND QuestionID IN (SELECT QuestionID FROM Questions WHERE QuizID = $quiz_id)";
    $correctAnswersResult = mysqli_query($conn, $correctAnswersQuery);

    $correctAnswers = array();
    while ($row = mysqli_fetch_assoc($correctAnswersResult)) {
        $correctAnswers[$row['QuestionID']] = $row['AnswerID'];
    }

    // Check submitted answers against correct answers
    foreach ($answers as $question_id => $selected_answer) {
        if (isset($correctAnswers[$question_id]) && $correctAnswers[$question_id] == $selected_answer) {
            $score++;
        }
    }

    // Update the user's score
    $updateScoreQuery = "UPDATE UserQuizScores SET Score = $score WHERE UserID = $user_id AND QuizID = $quiz_id";
    mysqli_query($conn, $updateScoreQuery);

    // Redirect to the display_scores.php page with the user ID
    header("Location: display_scores.php?user_id=$user_id");
    exit();
} else {
    echo "Invalid request.";
}

// Close connection
mysqli_close($conn);
?>
