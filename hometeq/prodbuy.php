<?php
include("db.php"); // Database connection

$pagename = "A stylish choice for your wardrobe";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";

echo "<body>";

include("headfile.html");
echo "<h4>" . $pagename . "</h4>";
// Retrieve the product ID from the URL
$prodid = $_GET['u_prod_id']; //$_GET-superglobal array in PHP that is used to collect data sent via the URL parameters
//Retrieves the value of the URL parameter [u_prod_id]
echo "<p>Selected Product ID: " . $prodid . "</p>"; //Displays the selected product ID in a paragraph.

// Fetch product details from the database
$SQL = "SELECT prodId, prodName, prodPicNameLarge, prodDescripLong, prodPrice, prodQuantity 
        FROM Product 
        WHERE prodId = $prodid";

$result = mysqli_query($conn, $SQL); //Runs the SQL query on the database connection ($conn).
//If successful:$result stores the result set (a collection of rows).

if (mysqli_num_rows($result) > 0) {
    // Fetch product details
    $row = mysqli_fetch_assoc($result);

    // Assign retrieved data to variables
    $prodName = $row['prodName'];
    $prodImage = $row['prodPicNameLarge'];
    $prodDesc = $row['prodDescripLong'];
    $prodPrice = $row['prodPrice'];
    $prodStock = $row['prodQuantity'];

    // Display the product details
    echo "<div style='display: flex; gap: 20px;'>";

    // Product image on the left
    echo "<div><img src='images/" . $prodImage . "' alt='" . $prodName . "' width='300'></div>";

    // Product details on the right
    echo "<div>";
    echo "<h2>" . $prodName . "</h2>";
    echo "<p>" . $prodDesc . "</p>";
    echo "<p><strong>Price:</strong> $" . $prodPrice . "</p>";
    echo "<p><strong>Stock available:</strong> " . $prodStock . "</p>";

    // Admin Check for Purchasing
    if (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === 'A' || strtolower($_SESSION['usertype']) === 'admin' || strtolower($_SESSION['usertype']) === 'administrator')) {
        echo "<p style='color:red;'><b>Admins cannot purchase items.</b></p>";
    }
    else {
        // Form for purchasing
        echo "<form action='basket.php' method='post'>";
        echo "<p><b>Select Size:</b> <select name='p_size' style='margin-right:20px;'><option value='Small'>Small</option><option value='Medium'>Medium</option><option value='Large'>Large</option></select></p>";
        echo "<p>Number to be purchased: ";
        echo "<select name='p_quantity'>";

        // Generate dropdown options dynamically based on stock level
        for ($i = 1; $i <= $prodStock; $i++) {
            echo "<option value='" . $i . "'>" . $i . "</option>";
        }

        echo "</select></p>";

        // Hidden field to pass the product ID
        echo "<input type='hidden' name='h_prodid' value='" . $prodid . "'>";
        echo "<input type='submit' name='submitbtn' value='ADD TO BASKET' id='submitbtn'>";
        echo "</form>";
    }

    echo "</div>"; // End of right section
    echo "</div>"; // End of main container

    // --- REVIEWS SECTION ---
    echo "<hr style='margin-top:40px; margin-bottom:20px;'>";
    echo "<h3>Customer Reviews</h3>";

    if (isset($_GET['msg']) && $_GET['msg'] === 'ReviewSuccess') {
        echo "<p style='color:green;'><b>Thank you for your review!</b></p>";
    }

    // Display Existing Reviews
    // Note: requires `Reviews` table to be created
    try {
        $revSql = "SELECT Users.userFName, Reviews.rating, Reviews.reviewText FROM Reviews INNER JOIN Users ON Reviews.userId = Users.userId WHERE Reviews.prodId = ?";
        $stmtRev = $conn->prepare($revSql);
        $stmtRev->bind_param("i", $prodid);
        $stmtRev->execute();
        $revResult = $stmtRev->get_result();

        if ($revResult->num_rows > 0) {
            while ($revRow = $revResult->fetch_assoc()) {
                echo "<div style='border-bottom:1px solid #ccc; padding:10px 0;'>";
                echo "<b>" . htmlspecialchars($revRow['userFName']) . "</b> - ";
                echo str_repeat("⭐", $revRow['rating']) . "<br>";
                echo "<i>" . htmlspecialchars($revRow['reviewText']) . "</i>";
                echo "</div>";
            }
        }
        else {
            echo "<p>There are no reviews for this product yet. Be the first!</p>";
        }
        $stmtRev->close();
    }
    catch (Exception $e) {
        echo "<i><small>(Reviews database table needs to be set up manually via phpMyAdmin: CREATE TABLE Reviews (id INT AUTO_INCREMENT PRIMARY KEY, prodId INT, userId INT, rating INT, reviewText TEXT);)</small></i>";
    }

    // Add Review Form (Only for logged in customers)
    if (isset($_SESSION['userid']) && (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'A')) {
        echo "<div style='margin-top:20px; background:#f9f9f9; padding:15px; max-width:500px;'>";
        echo "<h4>Leave a Review</h4>";
        echo "<form method='POST' action='add_review.php'>";
        echo "<input type='hidden' name='prodId' value='" . $prodid . "'>";
        echo "<p>Rating: <select name='rating'>
            <option value='5'>⭐⭐⭐⭐⭐ 5 Stars</option>
            <option value='4'>⭐⭐⭐⭐ 4 Stars</option>
            <option value='3'>⭐⭐⭐ 3 Stars</option>
            <option value='2'>⭐⭐ 2 Stars</option>
            <option value='1'>⭐ 1 Star</option>
        </select></p>";
        echo "<p>Your Review:<br><textarea name='reviewText' rows='4' style='width:100%;' required></textarea></p>";
        echo "<input type='submit' value='Submit Review' style='background:black; color:white; padding:10px;'>";
        echo "</form>";
        echo "</div>";
    }
    elseif (!isset($_SESSION['userid'])) {
        echo "<p>Please <a href='login.php'>log in</a> to leave a review.</p>";
    }
}
else {
    echo "<p>Product not found.</p>";
}

mysqli_close($conn);
include("footfile.html");
echo "</body>";
?>