<?php
session_start();
$pagename = "Manage Orders";
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
    // Error / Success messages
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] === 'Success')
            echo "<p style='color:green;'><b>Order status updated successfully!</b></p>";
        if ($_GET['msg'] === 'InvalidTransition')
            echo "<p style='color:red;'><b>Error: Invalid status transition.</b></p>";
    }

    $SQL = "SELECT Users.userFName, Users.userSName, Orders.orderNo, Orders.orderDateTime, Orders.orderTotal, Orders.orderStatus FROM Orders INNER JOIN Users ON Orders.userId = Users.userId ORDER BY Orders.orderNo DESC";
    $result = mysqli_query($conn, $SQL);
    if ($result) {
        echo "<table border='1' style='width:100%; text-align:left; border-collapse: collapse;' cellpadding='5'>";
        echo "<tr><th>Order No</th><th>Customer</th><th>Date</th><th>Total</th><th>Status & Action</th></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>#" . $row['orderNo'] . "</td>";
            echo "<td>" . htmlspecialchars($row['userFName']) . " " . htmlspecialchars($row['userSName']) . "</td>";
            echo "<td>" . $row['orderDateTime'] . "</td>";
            echo "<td>$" . number_format($row['orderTotal'], 2) . "</td>";

            $status = isset($row['orderStatus']) ? $row['orderStatus'] : 'Pending';
            echo "<td>";
            // Display Form if mutable
            if ($status === 'Delivered' || $status === 'Cancelled') {
                echo "<b>" . $status . "</b>";
            }
            else {
                echo "<form action='update_order_status.php' method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='orderNo' value='" . $row['orderNo'] . "'>";
                echo "<select name='newStatus'>";
                if ($status === 'Pending') {
                    echo "<option value='Shipped'>Shipped</option>";
                    echo "<option value='Cancelled'>Cancelled</option>";
                }
                elseif ($status === 'Shipped') {
                    echo "<option value='Delivered'>Delivered</option>";
                    echo "<option value='Cancelled'>Cancelled</option>";
                }
                echo "</select> ";
                echo "<input type='submit' value='Update Status'>";
                echo "<br><small>(Current: " . $status . ")</small>";
                echo "</form>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else {
        echo "<p>No orders found or error fetching orders.</p>";
    }
}
echo "<br><p><a href='admin_dashboard.php'>Back to Dashboard</a></p>";
include("footfile.html");
echo "</body>";
?>
