<?php
session_start(); // Start session

$pagename = "Logout"; // Set the page title

echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html"); // Include navigation bar

echo "<h4>" . $pagename . "</h4>";

// Destroy session to log out user
session_unset(); // Unset all session variables
session_destroy(); // Destroy session

// Display logout message
echo "<p style='color: green; font-weight: bold;'>You have successfully logged out.</p>";
echo "<p><a href='index.php'>Return to Home Tech</a></p>";

include("footfile.html"); // Include footer
echo "</body>";
?>
