<?php 
session_start();
$pagename = "Clear Smart Basket"; // Set the page name

echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>".$pagename."</title>";
echo "<body>";

include("headfile.html");
echo "<h4>".$pagename."</h4>";

include("db.php"); // Connect to the database

// Clear the basket
//unset() is a built in php function used to remove variables,array elements or session variables
unset($_SESSION['basket']);

// Display confirmation message
echo "<p><b>Your basket has been cleared.</b></p>";

// Provide a link back to the basket page
echo "<p><a href='basket.php'>Return to Basket</a></p>";

include("footfile.html");
echo "</body>";
?>
