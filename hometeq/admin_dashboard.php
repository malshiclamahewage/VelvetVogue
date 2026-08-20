<?php
session_start();
$pagename = "Admin Dashboard"; // Set the page name

echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");

echo "<h4>" . $pagename . "</h4>";

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    // Not logged in - redirect or show error
    echo "<p style='color:red;'>Access Denied. You must be logged in to view this page.</p>";
    echo "<p><a href='login.php'>Login</a></p>";
}
else {
    // User is logged in, check if they are an admin
    $usertype = $_SESSION['usertype']; // Using session variable from login_process.php

    // Assuming 'A' or 'Administrator' is the type for Admin
    if ($usertype !== 'A' && strtolower($usertype) !== 'administrator' && strtolower($usertype) !== 'admin') {
        echo "<p style='color:red;'>Access Denied. Administrator privileges required.</p>";
        echo "<p><a href='index.php'>Return to Homepage</a></p>";
    }
    else {
        // Administrator Content goes here
        echo "<p>Welcome to the Admin Dashboard!</p>";
        echo "<p>From here, you can manage the website, view users, and process orders.</p>";

        echo "<hr>";
        echo "<p><b>Currently Logged In Admin:</b> " . htmlspecialchars($_SESSION['fname']) . " " . htmlspecialchars($_SESSION['sname']) . "</p>";

        // Example Links
        echo "<ul>";
        echo "<li><a href='admin_products.php'>Manage Products</a></li>";
        echo "<li><a href='admin_users.php'>Manage Users</a></li>";
        echo "<li><a href='admin_orders.php'>View Orders</a></li>";
        echo "</ul>";
    }
}

include("footfile.html");
echo "</body>";
?>
