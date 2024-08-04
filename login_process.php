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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Retrieve user information from the database
    $getUserQuery = "SELECT * FROM Users WHERE Username='$username'";
    $userResult = mysqli_query($conn, $getUserQuery);

    if (mysqli_num_rows($userResult) > 0) {
        $row = mysqli_fetch_assoc($userResult);

        // Verify password
        if (password_verify($password, $row['PasswordHash'])) {
            echo "Login successful! Welcome, " . $row['Username'] . "!";

            // Store user information in the session for future use
            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['username'] = $row['Username'];

            // Redirect based on the username
            if ($row['Username'] == 'Cleverson') {
                header("Location: display_quizzes.php");
            } else {
                header("Location: display_quizzes2.php");
            }
            exit(); // Ensure no further code is executed after the redirection
        } else {
            // Incorrect password, redirect to login page with flag
            header("Location: login.php?incorrect_password=true");
            exit();
        }
    } else {
        // User not found, redirect to login page
        header("Location: login.php");
        exit();
    }
}

// Close connection
mysqli_close($conn);
?>
