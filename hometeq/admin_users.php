<?php
session_start();
$pagename = "Manage Users";
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
    $SQL = "SELECT userId, userFName, userSName, userEmail, userType FROM Users";
    $result = mysqli_query($conn, $SQL);
    if ($result) {
        echo "<table border='1' style='width:100%; text-align:left; border-collapse: collapse;' cellpadding='5'>";
        echo "<tr><th>UserID</th><th>Name</th><th>Email</th><th>Type</th></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['userId'] . "</td>";
            echo "<td>" . htmlspecialchars($row['userFName']) . " " . htmlspecialchars($row['userSName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['userEmail']) . "</td>";
            echo "<td>" . htmlspecialchars($row['userType']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else {
        echo "<p>No users found or error fetching users.</p>";
    }
}
echo "<br><p><a href='admin_dashboard.php'>Back to Dashboard</a></p>";
include("footfile.html");
echo "</body>";
?>
