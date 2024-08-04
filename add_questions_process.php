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
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $answers = $_POST['answers'];
    $correct_answer = $_POST['correct_answer'];

    // Insert question into the database
    $insertQuestionQuery = "INSERT INTO Questions (QuizID, QuestionText) VALUES ('$quiz_id', '$question')";
    mysqli_query($conn, $insertQuestionQuery);

    // Get the ID of the last inserted question
    $question_id = mysqli_insert_id($conn);

    // Insert answers into the database
    foreach ($answers as $key => $answer) {
        $isCorrect = ($correct_answer == $key + 1) ? 1 : 0;
        $insertAnswerQuery = "INSERT INTO Answers (QuestionID, AnswerText, IsCorrect) VALUES ('$question_id', '$answer', '$isCorrect')";
        mysqli_query($conn, $insertAnswerQuery);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Added Successfully</title>
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
    <h1>Question Added Successfully</h1>
</header>

<main>
    <p class="success-message">Question added successfully!</p>

    <a class="logout-btn" href="display_quizzes.php">add more</a>
    <a class="logout-btn" href="logout.php">Logout</a>
</main>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
