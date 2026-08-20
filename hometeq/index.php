<?php
include("db.php"); // Include database connection

$pagename = "Upgrade Your Wardrobe"; // Page title

// Add stylesheet
echo "<link rel='stylesheet' type='text/css' href='mystylesheet.css'>";
echo "<title>" . $pagename . "</title>";

echo "<body>";

include("headfile.html"); // Header file
include("detectlogin.php");
echo "<h4>" . $pagename . "</h4>";

// Create a SQL query to get product details
//This SQL query selects product details (prodId, prodName, prodPicNameSmall, prodDescripShort, prodPrice) from the Product table.
$SQL = "SELECT prodId, prodName, prodPicNameSmall, prodDescripShort, prodPrice FROM Product";

// Run SQL query securely and manage custom failures gracefully
$exeSQL = mysqli_query($conn, $SQL);
if (!$exeSQL) {
    error_log("Database query failed: " . mysqli_error($conn));
    die("<p>A database error occurred. Please try again later.</p>");
}

echo "<table style='border: 0px'>";

// Loop through the product records
while ($arrayp = mysqli_fetch_array($exeSQL)) {
    echo "<tr>";

    // Display product image as a clickable link to prodbuy.php
    echo "<td style='border: 0px'>";
    echo "<a href='prodbuy.php?u_prod_id=" . htmlspecialchars($arrayp['prodId']) . "'>";
    echo "<img src='images/" . htmlspecialchars($arrayp['prodPicNameSmall']) . "' height=200 width=200>";
    echo "</a>";
    echo "</td>";

    // Display product details (name, description, price)
    echo "<td style='border: 0px'>";
    echo "<p><h5>" . htmlspecialchars($arrayp['prodName']) . "</h5>";
    echo "<p>" . htmlspecialchars($arrayp['prodDescripShort']) . "</p>";
    echo "<p><b>Price: £" . htmlspecialchars($arrayp['prodPrice']) . "</b></p>";
    echo "</td>";

    echo "</tr>";
}

echo "</table>";

include("footfile.html"); // Footer file
echo "</body>";
?>
