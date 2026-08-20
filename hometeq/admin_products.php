<?php
session_start();
$pagename = "Manage Products";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");
echo "<h4>" . $pagename . "</h4>";

if (!isset($_SESSION['userid']) || ($_SESSION['usertype'] !== 'A' && strtolower($_SESSION['usertype']) !== 'administrator' && strtolower($_SESSION['usertype']) !== 'admin')) {
    echo "<p style='color:red;'>Access Denied. Administrator privileges required.</p>";
    echo "<p><a href='index.php'>Return to Homepage</a></p>";
}
else {
    include("db.php");
    echo "<p><a href='add_product.php' style='padding:5px; background:#ddd; font-weight:bold; text-decoration:none;'>+ Add New Product</a></p>";
    $SQL = "SELECT prodId, prodName, prodPrice, prodQuantity FROM Product";
    $result = mysqli_query($conn, $SQL);
    if ($result) {
        echo "<table border='1' style='width:100%; text-align:left; border-collapse: collapse;' cellpadding='5'>";
        echo "<tr><th>ProductID</th><th>Name</th><th>Price</th><th>Stock Left</th><th>Action</th></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['prodId'] . "</td>";
            echo "<td>" . htmlspecialchars($row['prodName']) . "</td>";
            echo "<td>$" . number_format($row['prodPrice'], 2) . "</td>";
            echo "<td>" . $row['prodQuantity'] . "</td>";
            echo "<td>
                <a href='edit_product.php?id=" . $row['prodId'] . "'>Edit</a> | 
                <a href='delete_product.php?id=" . $row['prodId'] . "' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a>
            </td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else {
        echo "<p>No products found or error fetching products.</p>";
    }
}
echo "<br><p><a href='admin_dashboard.php'>Back to Dashboard</a></p>";
include("footfile.html");
echo "</body>";
?>
