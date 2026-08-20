<?php
session_start();
// Admin Check
if (!isset($_SESSION['userid']) || ($_SESSION['usertype'] !== 'A' && strtolower($_SESSION['usertype']) !== 'administrator' && strtolower($_SESSION['usertype']) !== 'admin')) {
    die("Access Denied. Administrator privileges required. <a href='index.php'>Return to Homepage</a>");
}

include("db.php");
$pagename = "Edit Product";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");
echo "<h4>" . $pagename . "</h4>";

if (!isset($_GET['id']) && $_SERVER["REQUEST_METHOD"] != "POST") {
    die("<p style='color:red;'>No product selected.</p><p><a href='admin_products.php'>Back to Manage Products</a></p>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prodId = intval($_POST['prodId']);
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
        $stmt = $conn->prepare("UPDATE Product SET prodName=?, prodPicNameSmall=?, prodPicNameLarge=?, prodDescripShort=?, prodDescripLong=?, prodPrice=?, prodQuantity=? WHERE prodId=?");
        $stmt->bind_param("sssssdii", $prodName, $prodPicNameSmall, $prodPicNameLarge, $prodDescripShort, $prodDescripLong, $prodPrice, $prodQuantity, $prodId);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Product updated successfully!</p>";
        }
        else {
            echo "<p style='color:red;'>Error updating product: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

$prodId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['prodId']) ? intval($_POST['prodId']) : 0);

$stmt = $conn->prepare("SELECT * FROM Product WHERE prodId = ?");
$stmt->bind_param("i", $prodId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
?>

<form action="edit_product.php" method="POST">
    <input type="hidden" name="prodId" value="<?php echo htmlspecialchars($row['prodId']); ?>">
    <table border="0" cellpadding="5">
        <tr><td>Product Name:*</td><td><input type="text" name="prodName" value="<?php echo htmlspecialchars($row['prodName']); ?>" required></td></tr>
        <tr><td>Small Pic Name:</td><td><input type="text" name="prodPicNameSmall" value="<?php echo htmlspecialchars($row['prodPicNameSmall']); ?>"></td></tr>
        <tr><td>Large Pic Name:</td><td><input type="text" name="prodPicNameLarge" value="<?php echo htmlspecialchars($row['prodPicNameLarge']); ?>"></td></tr>
        <tr><td>Short Description:</td><td><input type="text" name="prodDescripShort" style="width:300px;" value="<?php echo htmlspecialchars($row['prodDescripShort']); ?>"></td></tr>
        <tr><td>Long Description:</td><td><textarea name="prodDescripLong" rows="4" cols="50"><?php echo htmlspecialchars($row['prodDescripLong']); ?></textarea></td></tr>
        <tr><td>Price:*</td><td><input type="number" step="0.01" name="prodPrice" value="<?php echo htmlspecialchars($row['prodPrice']); ?>" min="0" required></td></tr>
        <tr><td>Quantity:*</td><td><input type="number" name="prodQuantity" value="<?php echo htmlspecialchars($row['prodQuantity']); ?>" min="0" required></td></tr>
        <tr><td colspan="2"><input type="submit" value="Save Changes"></td></tr>
    </table>
</form>

<?php
}
else {
    echo "<p style='color:red;'>Product not found.</p>";
}
$stmt->close();

?>
<br><p><a href='admin_products.php'>Back to Manage Products</a></p>
<?php
include("footfile.html");
echo "</body>";
?>
