<?php
session_start();
$pagename = "My Orders";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");

if (!isset($_SESSION['userid']) || (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === 'A' || strtolower($_SESSION['usertype']) === 'admin' || strtolower($_SESSION['usertype']) === 'administrator'))) {
    die("<div style='padding:20px;'><p>Only customers can view their order history.</p><a href='index.php'>Go Home</a></div>");
}

echo "<h4>" . $pagename . "</h4>";
echo "<div style='padding-left:10px;'>";
include("db.php");
$userId = $_SESSION['userid'];

$sql = "SELECT orderNo, orderDateTime, orderTotal, orderStatus FROM Orders WHERE userId = ? ORDER BY orderNo DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' style='width:90%; border-collapse:collapse;' cellpadding='8'>";
    echo "<tr style='background-color:#eee;'><th>Order Number</th><th>Date Placed</th><th>Total Amount</th><th>Shipping Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>#" . $row['orderNo'] . "</td>";
        echo "<td>" . $row['orderDateTime'] . "</td>";
        echo "<td>$" . number_format($row['orderTotal'], 2) . "</td>";
        echo "<td><b>" . htmlspecialchars($row['orderStatus'] ?? 'Pending') . "</b></td>";
        echo "</tr>";
    }
    echo "</table>";
}
else {
    echo "<p>You haven't placed any orders with us yet!</p>";
}
$stmt->close();
echo "<br><p><a href='index.php'>Continue Shopping</a></p>";
echo "</div>";
include("footfile.html");
echo "</body>";
?>
