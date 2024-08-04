<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions and Answers</title>
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
            color: #35424a;
            margin-bottom: 10px;
        }

        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            color: #35424a;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin-left: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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

<h3>Add Questions and Answers</h3>

<form action="add_questions_process.php" method="post">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

    <label for="question">Question:</label>
    <input type="text" id="question" name="question" required>
    <br>

    <label for="answer1">Answer 1:</label>
    <input type="text" id="answer1" name="answers[]" required>
    <input type="radio" name="correct_answer" value="1" required> Correct Answer
    <br>

    <label for="answer2">Answer 2:</label>
    <input type="text" id="answer2" name="answers[]" required>
    <input type="radio" name="correct_answer" value="2"> Correct Answer
    <br>

    <label for="answer3">Answer 3:</label>
    <input type="text" id="answer3" name="answers[]" required>
    <input type="radio" name="correct_answer" value="3"> Correct Answer
    <br>

    <input type="submit" value="Add Question">
</form>
<a class="logout-btn" href="display_quizzes.php">back</a>
    <a class="logout-btn" href="logout.php">Logout</a>
</body>
</html>
