<?php
session_start();

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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Function to delete a quiz
function deleteQuiz($quizId) {
    global $conn;

    // Delete associated user quiz scores first
    $sqlDeleteUserQuizScores = "DELETE FROM UserQuizScores WHERE QuizID = ?";
    $stmtDeleteUserQuizScores = mysqli_prepare($conn, $sqlDeleteUserQuizScores);
    mysqli_stmt_bind_param($stmtDeleteUserQuizScores, "i", $quizId);
    mysqli_stmt_execute($stmtDeleteUserQuizScores);

    // Delete associated questions and answers
    $sqlDeleteQuestions = "DELETE FROM Questions WHERE QuizID = ?";
    $stmtDeleteQuestions = mysqli_prepare($conn, $sqlDeleteQuestions);
    mysqli_stmt_bind_param($stmtDeleteQuestions, "i", $quizId);
    mysqli_stmt_execute($stmtDeleteQuestions);

    $sqlDeleteAnswers = "DELETE FROM Answers WHERE QuestionID IN (SELECT QuestionID FROM Questions WHERE QuizID = ?)";
    $stmtDeleteAnswers = mysqli_prepare($conn, $sqlDeleteAnswers);
    mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $quizId);
    mysqli_stmt_execute($stmtDeleteAnswers);

    // Now delete the quiz
    $sqlDeleteQuiz = "DELETE FROM Quizzes WHERE QuizID = ?";
    $stmtDeleteQuiz = mysqli_prepare($conn, $sqlDeleteQuiz);
    mysqli_stmt_bind_param($stmtDeleteQuiz, "i", $quizId);
    mysqli_stmt_execute($stmtDeleteQuiz);
}

// Fetch quizzes
$sql = "SELECT * FROM Quizzes";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        header {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
        }

        h2 {
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
        }

        a:hover {
            color: #45a049;
        }

        p {
            margin-bottom: 20px;
        }

        a.logout-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        a.logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<header>
    <h1>Quiz List</h1>
</header>

<!-- Display username -->
<p>Welcome, <?php echo $_SESSION['username']; ?>!</p>

<!-- Display quizzes -->
<h2>Quizzes</h2>
<table>
    <tr>
        <th>Quiz ID</th>
        <th>Title</th>
        <th>Delete</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['QuizID'] . "</td>";
        echo "<td><a href='display_questions.php?quiz_id=" . $row['QuizID'] . "'>" . $row['Title'] . "</a></td>";
        echo "<td><a href='delete.php?type=quiz&id=" . $row['QuizID'] . "'>Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>

<a class="logout-btn" href="logout.php">Logout</a>
<a class="logout-btn" href="display_quizzes.php">Back</a>
</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>

