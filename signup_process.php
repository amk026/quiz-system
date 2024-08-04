<?php

require_once 'dbconnect.php';
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username is already taken
    $checkUsernameQuery = "SELECT UserID FROM Users WHERE Username='$username'";
    $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);

    if (mysqli_num_rows($checkUsernameResult) > 0) {
        echo "Username is already taken. Please choose a different one.";
    } else {
        // Insert user into the database
        $insertUserQuery = "INSERT INTO Users (Username, PasswordHash, Email) VALUES ('$username', '$password', '$email')";

        if (mysqli_query($conn, $insertUserQuery)) {
            // Redirect to login.php after successful registration
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $insertUserQuery . "<br>" . mysqli_error($conn);
        }
    }
}

// Close connection
mysqli_close($conn);
?>
