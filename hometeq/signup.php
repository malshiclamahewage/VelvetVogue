<?php
$pagename="Sign Up"; // Set the page name
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>".$pagename."</title>";
echo "<body>";
include ("headfile.html"); // Include header file
echo "<h4 style='text-align: center;'>".$pagename."</h4>"; // Center page name

// Center the form
echo "<div style='display: flex; justify-content: center; align-items: center; height: 70vh;'>";

echo "<form action='signup_process.php' method='post' style='border: 1px solid #ccc; padding: 20px; border-radius: 8px; background: #f9f9f9;'>";
echo "<table>";

echo "<tr><td>First Name:</td><td><input type='text' name='userFName' required></td></tr>";
echo "<tr><td>Last Name:</td><td><input type='text' name='userSName' required></td></tr>";
echo "<tr><td>Address:</td><td><input type='text' name='userAddress' required></td></tr>";
echo "<tr><td>Post Code:</td><td><input type='text' name='userPostCode' required></td></tr>";
echo "<tr><td>Telephone Number:</td><td><input type='text' name='userTelNo' required></td></tr>";
echo "<tr><td>Email:</td><td><input type='email' name='userEmail' required></td></tr>";
echo "<tr><td>Password:</td><td><input type='password' name='userPassword' required></td></tr>";
echo "<tr><td>Confirm Password:</td><td><input type='password' name='confirmPassword' required></td></tr>";

echo "<tr><td colspan='2' align='center'>";
echo "<input type='submit' value='Sign Up' style='margin-right: 10px;'>";
echo "<input type='reset' value='Clear Form'>";
echo "</td></tr>";

echo "</table>";
echo "</form>";
echo "</div>";

include("footfile.html"); // Include footer file
echo "</body>";
//superglobal-valid everywhere in the website
?>
