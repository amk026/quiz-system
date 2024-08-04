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

// Function to delete a question
function deleteQuestion($questionId) {
    global $conn;

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
}

// Fetch questions based on quiz ID
$quizId = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
if (!$quizId || !is_numeric($quizId)) {
    echo "Invalid quiz ID.";
    exit();
}

$sql = "SELECT * FROM Questions WHERE QuizID = $quizId";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question List</title>
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
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
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
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #1b6ca8;
        }

        a.logout-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            margin-top: 20px;
        }

        a.logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<header>
    <h1>Question List</h1>
</header>

<main>
    <!-- Display username -->
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>

    <!-- Display questions -->
    <h2>Questions for Quiz <?php echo $quizId; ?></h2>
    <table>
        <tr>
            <th>Question ID</th>
            <th>Question Text</th>
            <th>Delete</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['QuestionID'] . "</td>";
            echo "<td>" . $row['QuestionText'] . "</td>";
            echo "<td><a href='delete.php?type=question&id=" . $row['QuestionID'] . "'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <a class="logout-btn" href="logout.php">Logout</a>
    <a class="logout-btn" href="display_quizzes3.php">Back</a>
</main>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
