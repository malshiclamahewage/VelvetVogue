<?php
session_start(); // Start the session($_SESSIOn used to store global data)
$pagename = "Sign In"; // Update the page name

echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
echo "<h4>" . $pagename . "</h4>"; // Display page name

// Create a login form inside a table
echo "<form action='login_process.php' method='post'>";
echo "<table>";
echo "<tr><td>Email:</td><td><input type='email' name='email' required></td></tr>";
echo "<tr><td>Password:</td><td><input type='password' name='password' required></td></tr>";
echo "<tr><td colspan='2' align='center'><input type='submit' value='Login'></td></tr>";
echo "</table>";
echo "</form>";

include("footfile.html");
echo "</body>";
?>
