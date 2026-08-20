<?php
session_start(); // Start session
$pagename = "Your Login Results"; // Update page name

echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
echo "<h4>" . $pagename . "</h4>";

include("db.php"); // Database connection

// Capture user inputs
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo "<p style='color:red;'>Both email and password fields need to be filled in.</p>";
    echo "<p><a href='login.php'>Try again</a></p>";
}
else {
    // Query to find user using a Prepared Statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM Users WHERE userEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // executes when no matching user found
        echo "<p style='color:red;'>Email not recognized, please try again.</p>";
        echo "<p><a href='login.php'>Login</a></p>";
    }
    else {
        $arrayu = $result->fetch_assoc(); // Fetch user record

        // Verify password hash securely
        if (!password_verify($password, $arrayu['userPassword'])) {
            echo "<p style='color:red;'>Incorrect password, please try again.</p>";
            echo "<p><a href='login.php'>Login</a></p>";
        }
        else {
            // Store user details in session variables
            $_SESSION['userid'] = $arrayu['userId'];
            $_SESSION['usertype'] = $arrayu['userType'];
            $_SESSION['fname'] = $arrayu['userFName'];
            $_SESSION['sname'] = $arrayu['userSName'];

            // Display success message and prevent XSS
            echo "<p>Welcome, <b>" . htmlspecialchars($_SESSION['fname']) . " " . htmlspecialchars($_SESSION['sname']) . "</b>!</p>";
            echo "<p>You are logged in as <b>" . htmlspecialchars($_SESSION['usertype']) . "</b>.</p>";

            // Check if admin to show different link
            if ($_SESSION['usertype'] === 'A' || strtolower($_SESSION['usertype']) === 'administrator' || strtolower($_SESSION['usertype']) === 'admin') {
                echo "<p><a href='index.php'>Go to Homepage</a> | <a href='admin_dashboard.php'>Go to Admin Dashboard</a></p>";
            }
            else {
                echo "<p><a href='index.php'>Go to Homepage</a></p>";
            }
        }
    }
    $stmt->close();
}

include("footfile.html");
echo "</body>";
?>
