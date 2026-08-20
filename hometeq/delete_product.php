<?php
session_start();
if (!isset($_SESSION['userid']) || ($_SESSION['usertype'] !== 'A' && strtolower($_SESSION['usertype']) !== 'administrator' && strtolower($_SESSION['usertype']) !== 'admin')) {
    die("Access Denied. Administrator privileges required. <a href='index.php'>Return to Homepage</a>");
}

include("db.php");
$pagename = "Delete Product";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");
echo "<h4>" . $pagename . "</h4>";

if (isset($_GET['id'])) {
    $prodId = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM Product WHERE prodId = ?");
    $stmt->bind_param("i", $prodId);

    // We catch the exception/error if it is tied to an Order
    try {
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<p style='color:green;'>Product completely removed from the database.</p>";
            }
            else {
                echo "<p style='color:red;'>Product ID not found.</p>";
            }
        }
        else {
            echo "<p style='color:red;'>Could not delete product. Error: " . $stmt->error . "</p>";
        }
    }
    catch (Exception $e) {
        echo "<p style='color:red;'>Cannot delete product because it has been ordered by a customer. Please manage the order first.</p>";
    }
    $stmt->close();
}
else {
    echo "<p style='color:red;'>No product selected.</p>";
}

echo "<p><a href='admin_products.php'>Back to Manage Products</a></p>";
include("footfile.html");
echo "</body>";
?>
