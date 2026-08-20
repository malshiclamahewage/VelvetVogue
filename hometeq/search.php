<?php
include("db.php");
$pagename = "Search Results";
echo "<link rel='stylesheet' type='text/css' href='mystylesheet.css'>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");

echo "<h4>" . $pagename . "</h4>";
echo "<div style='padding-left:10px;'>";

// The Search Bar Form
echo "<form method='GET' action='search.php' style='margin-bottom:20px; background:#eee; padding:15px; display:inline-block;'>";
echo "<b>Search:</b> <input type='text' name='q' placeholder='Shirts, Mugs, etc...' style='padding:5px; width:250px;' required> ";
echo "<input type='submit' value='Search' style='padding:5px 15px; cursor:pointer;'>";
echo "</form>";

if (isset($_GET['q'])) {
    $rawQuery = trim($_GET['q']);
    echo "<p>Showing results for: <i>" . htmlspecialchars($rawQuery) . "</i></p>";
    $searchQuery = "%" . $rawQuery . "%";

    $stmt = $conn->prepare("SELECT prodId, prodName, prodPicNameSmall, prodDescripShort, prodPrice FROM Product WHERE prodName LIKE ? OR prodDescripShort LIKE ?");
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table style='border: 0px' cellpadding='10'>";
        while ($arrayp = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='border: 0px'><a href='prodbuy.php?u_prod_id=" . $arrayp['prodId'] . "'>";
            echo "<img src='images/" . htmlspecialchars($arrayp['prodPicNameSmall']) . "' height=200 width=200></a></td>";

            echo "<td style='border: 0px; vertical-align:top;'>";
            echo "<h5>" . htmlspecialchars($arrayp['prodName']) . "</h5>";
            echo "<p>" . htmlspecialchars($arrayp['prodDescripShort']) . "</p>";
            echo "<p><b>$" . number_format($arrayp['prodPrice'], 2) . "</b></p>";
            echo "<p><a href='prodbuy.php?u_prod_id=" . $arrayp['prodId'] . "' style='text-decoration:none; background:#333; color:white; padding:5px 10px;'>View Details</a></p>";
            echo "</td></tr>";
        }
        echo "</table>";
    }
    else {
        echo "<p style='color:red;'>No products matched your search.</p>";
    }
    $stmt->close();
}
else {
    echo "<p>Please enter a keyword into the search bar above.</p>";
}

echo "</div>";
include("footfile.html");
echo "</body>";
?>
