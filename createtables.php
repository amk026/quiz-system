<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$dbname = "quizd";
$sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $dbname";
mysqli_query($conn, $sqlCreateDatabase);
   

// Close the connection
mysqli_close($conn);

// Connect to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create Quizzes table if not exists
$sqlQuizzes = "
    CREATE TABLE IF NOT EXISTS Quizzes (
        QuizID INT PRIMARY KEY AUTO_INCREMENT,
        Title VARCHAR(255) NOT NULL,
        Description TEXT,
        StartTime DATETIME,
        EndTime DATETIME
    )";

mysqli_query($conn, $sqlQuizzes);
   

// Create Questions table if not exists
$sqlQuestions = "
    CREATE TABLE IF NOT EXISTS Questions (
        QuestionID INT PRIMARY KEY AUTO_INCREMENT,
        QuizID INT,
        QuestionText TEXT,
        CONSTRAINT FK_QuizQuestion FOREIGN KEY (QuizID) REFERENCES Quizzes(QuizID)
    )";

mysqli_query($conn, $sqlQuestions);
   
// Create Answers table if not exists
$sqlAnswers = "
    CREATE TABLE IF NOT EXISTS Answers (
        AnswerID INT PRIMARY KEY AUTO_INCREMENT,
        QuestionID INT,
        AnswerText TEXT,
        IsCorrect BOOLEAN,
        CONSTRAINT FK_QuestionAnswer FOREIGN KEY (QuestionID) REFERENCES Questions(QuestionID)
    )";

mysqli_query($conn, $sqlAnswers);
    

// Create Users table if not exists
$sqlUsers = "
    CREATE TABLE IF NOT EXISTS Users (
        UserID INT PRIMARY KEY AUTO_INCREMENT,
        Username VARCHAR(50) NOT NULL,
        PasswordHash VARCHAR(255) NOT NULL,
        Email VARCHAR(255) NOT NULL
    )";

mysqli_query($conn, $sqlUsers);
  

// Create UserQuizScores table if not exists
$sqlUserQuizScores = "
    CREATE TABLE IF NOT EXISTS UserQuizScores (
        UserID INT,
        QuizID INT,
        Score INT,
        PRIMARY KEY (UserID, QuizID),
        CONSTRAINT FK_UserScore FOREIGN KEY (UserID) REFERENCES Users(UserID),
        CONSTRAINT FK_QuizScore FOREIGN KEY (QuizID) REFERENCES Quizzes(QuizID)
    )";

mysqli_query($conn, $sqlUserQuizScores);

// Close connection
mysqli_close($conn);
?>
