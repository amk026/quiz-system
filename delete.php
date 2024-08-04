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

    $sqlDeleteAnswers = "DELETE FROM Answers WHERE QuestionID IN (SELECT QuestionID FROM Questions WHERE QuizID = ?)";
    $stmtDeleteAnswers = mysqli_prepare($conn, $sqlDeleteAnswers);
    mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $quizId);
    mysqli_stmt_execute($stmtDeleteAnswers);

    
    $sqlDeleteQuestions = "DELETE FROM Questions WHERE QuizID = ?";
    $stmtDeleteQuestions = mysqli_prepare($conn, $sqlDeleteQuestions);
    mysqli_stmt_bind_param($stmtDeleteQuestions, "i", $quizId);
    mysqli_stmt_execute($stmtDeleteQuestions);

   

    // Now delete the quiz
    $sqlDeleteQuiz = "DELETE FROM Quizzes WHERE QuizID = ?";
    $stmtDeleteQuiz = mysqli_prepare($conn, $sqlDeleteQuiz);
    mysqli_stmt_bind_param($stmtDeleteQuiz, "i", $quizId);
    mysqli_stmt_execute($stmtDeleteQuiz);
}

// Function to delete a question
// Function to delete a question
function deleteQuestion($questionId) {
    global $conn;

    // Fetch quiz ID before deleting the question
    $sqlFetchQuizId = "SELECT QuizID FROM Questions WHERE QuestionID = ?";
    $stmtFetchQuizId = mysqli_prepare($conn, $sqlFetchQuizId);
    mysqli_stmt_bind_param($stmtFetchQuizId, "i", $questionId);
    mysqli_stmt_execute($stmtFetchQuizId);
    $resultFetchQuizId = mysqli_stmt_get_result($stmtFetchQuizId);
    $rowFetchQuizId = mysqli_fetch_assoc($resultFetchQuizId);

    if (!$rowFetchQuizId) {
        echo "Failed to fetch quiz ID.";
        exit();
    }

    $quizId = $rowFetchQuizId['QuizID'];

    // Delete associated answers
    $sqlDeleteAnswers = "DELETE FROM Answers WHERE QuestionID = ?";
    $stmtDeleteAnswers = mysqli_prepare($conn, $sqlDeleteAnswers);
    mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $questionId);
    mysqli_stmt_execute($stmtDeleteAnswers);

    // Now delete the question
    $sqlDeleteQuestion = "DELETE FROM Questions WHERE QuestionID = ?";
    $stmtDeleteQuestion = mysqli_prepare($conn, $sqlDeleteQuestion);
    mysqli_stmt_bind_param($stmtDeleteQuestion, "i", $questionId);
    mysqli_stmt_execute($stmtDeleteQuestion);

    // Debugging information
    echo "Question ID: $questionId deleted successfully.";

    // Redirect to display_questions.php with quiz_id parameter
    header("Location: display_questions.php?quiz_id=$quizId");
    exit(); // Exit to prevent further execution
}



// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"]) && isset($_GET["id"])) {
    $type = $_GET["type"];
    $id = $_GET["id"];

    // Validate input
    if (!is_numeric($id)) {
        echo "Invalid ID.";
        exit();
    }

    if ($type === "quiz") {
        deleteQuiz($id);
        header("Location: display_quizzes3.php"); // Redirect to the quizzes page
    } elseif ($type === "question") {
        deleteQuestion($id);
    }
}

// Redirect back to the main page if the type is not specified
header("Location: display_quizzes3.php");

?>

<?php
// Close connection
mysqli_close($conn);
?>
