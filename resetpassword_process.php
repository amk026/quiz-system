<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizd";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $updatePasswordQuery = "UPDATE Users SET PasswordHash = '$new_password' WHERE Email = '$email'";
    $result = mysqli_query($conn, $updatePasswordQuery);

    if ($result) {
        header("Location: login.php");
    } else {
        echo "Error updating password: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
