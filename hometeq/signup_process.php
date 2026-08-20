<?php
session_start(); // Start session
include("db.php"); // Connect to the database

$pagename = "Sign Up Results"; // Set page name
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html"); // Include header
echo "<h4>" . $pagename . "</h4>";

mysqli_report(MYSQLI_REPORT_OFF);

// Capture form data
$userFName = trim($_POST['userFName'] ?? '');
$userSName = trim($_POST['userSName'] ?? '');
$userAddress = trim($_POST['userAddress'] ?? '');
$userPostCode = trim($_POST['userPostCode'] ?? '');
$userTelNo = trim($_POST['userTelNo'] ?? '');
$userEmail = trim($_POST['userEmail'] ?? '');
$userPassword = $_POST['userPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Check if any field is empty
if (empty($userFName) || empty($userSName) || empty($userAddress) || empty($userPostCode) ||
empty($userTelNo) || empty($userEmail) || empty($userPassword) || empty($confirmPassword)) {
    echo "<p style='color: red; text-align: center;'>All fields are required! <a href='signup.php'>Go back</a></p>";
}

// Check if passwords match
elseif ($userPassword !== $confirmPassword) {
    echo "<p style='color: red; text-align: center;'>Passwords do not match! <a href='signup.php'>Try again</a></p>";
}

// Validate email format
elseif (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo "<p style='color: red; text-align: center;'>Invalid email format! <a href='signup.php'>Try again</a></p>";
}

else {
    // Hash the password before storing
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

    // Prepare the SQL query to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO Users (userFName, userSName, userAddress, userPostCode, userTelNo, userEmail, userPassword) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $userFName, $userSName, $userAddress, $userPostCode, $userTelNo, $userEmail, $hashedPassword);

    // Execute the query and handle errors
    if ($stmt->execute()) {
        echo "<p style='color: green; text-align: center;'>Signup successful! You can now <a href='login.php'>login</a>.</p>";
    }
    else {
        $errorCode = $stmt->errno;

        // Duplicate entry (Email already exists)
        if ($errorCode == 1062) {
            echo "<p style='color: red; text-align: center;'>Email already exists! <a href='signup.php'>Try again</a></p>";
        }
        else {
            // General database error without exposing raw error to users
            error_log("Signup DB Error: " . $stmt->error);
            echo "<p style='color: red; text-align: center;'>An unexpected error occurred. Please try again later.</p>";
        }
    }
    $stmt->close();
}

include("footfile.html"); // Include footer
echo "</body>";
?>
