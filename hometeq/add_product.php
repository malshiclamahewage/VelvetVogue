<?php
session_start();
// Admin Check
if (!isset($_SESSION['userid']) || ($_SESSION['usertype'] !== 'A' && strtolower($_SESSION['usertype']) !== 'administrator' && strtolower($_SESSION['usertype']) !== 'admin')) {
    die("Access Denied. Administrator privileges required. <a href='index.php'>Return to Homepage</a>");
}

include("db.php");
$pagename = "Add New Product";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");
echo "<h4>" . $pagename . "</h4>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prodName = trim($_POST['prodName'] ?? '');
    $prodPicNameSmall = trim($_POST['prodPicNameSmall'] ?? '');
    $prodPicNameLarge = trim($_POST['prodPicNameLarge'] ?? '');
    $prodDescripShort = trim($_POST['prodDescripShort'] ?? '');
    $prodDescripLong = trim($_POST['prodDescripLong'] ?? '');
    $prodPrice = trim($_POST['prodPrice'] ?? '');
    $prodQuantity = trim($_POST['prodQuantity'] ?? '');

    if ($prodName === '' || $prodPrice === '' || $prodQuantity === '') {
        echo "<p style='color:red;'>Name, Price, and Quantity are required.</p>";
    }
    elseif (floatval($prodPrice) < 0 || intval($prodQuantity) < 0) {
        echo "<p style='color:red;'>Price and Quantity cannot be negative.</p>";
    }
    else {
        $stmt = $conn->prepare("INSERT INTO Product (prodName, prodPicNameSmall, prodPicNameLarge, prodDescripShort, prodDescripLong, prodPrice, prodQuantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdi", $prodName, $prodPicNameSmall, $prodPicNameLarge, $prodDescripShort, $prodDescripLong, $prodPrice, $prodQuantity);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Product added successfully!</p>";
        }
        else {
            echo "<p style='color:red;'>Error adding product: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
?>

<form action="add_product.php" method="POST">
    <table border="0" cellpadding="5">
        <tr><td>Product Name:*</td><td><input type="text" name="prodName" required></td></tr>
        <tr><td>Small Pic Name (e.g. image_small.jpg):</td><td><input type="text" name="prodPicNameSmall"></td></tr>
        <tr><td>Large Pic Name (e.g. image_large.jpg):</td><td><input type="text" name="prodPicNameLarge"></td></tr>
        <tr><td>Short Description:</td><td><input type="text" name="prodDescripShort" style="width:300px;"></td></tr>
        <tr><td>Long Description:</td><td><textarea name="prodDescripLong" rows="4" cols="50"></textarea></td></tr>
        <tr><td>Price:*</td><td><input type="number" step="0.01" name="prodPrice" min="0" required></td></tr>
        <tr><td>Initial Quantity:*</td><td><input type="number" name="prodQuantity" min="0" required></td></tr>
        <tr><td colspan="2"><input type="submit" value="Add Product"></td></tr>
    </table>
</form>

<br><p><a href='admin_products.php'>Back to Manage Products</a></p>
<?php
include("footfile.html");
echo "</body>";
?>
