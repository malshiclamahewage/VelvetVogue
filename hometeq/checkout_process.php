<?php
session_start();
include("db.php");

$pagename = "Order Confirmation";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");

// Admin Check
if (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === 'A' || strtolower($_SESSION['usertype']) === 'admin' || strtolower($_SESSION['usertype']) === 'administrator')) {
    die("<p style='color:red;'><b>Admins cannot place orders or checkout.</b></p><p><a href='index.php'>Go Home</a></p>");
}

echo "<h4>" . $pagename . "</h4>";

// Security & Validation Checks
if (!isset($_SESSION['userid'])) {
    die("<p style='color:red;'><b>Error: You must be logged in to checkout.</b></p>");
}

if (!isset($_SESSION['basket']) || empty($_SESSION['basket'])) {
    die("<p><b>Your basket is empty. Add items before checking out.</b></p>");
}

if (!isset($_POST['submit_payment'])) {
    die("<p style='color:red;'><b>Error: Invalid access.</b></p>");
}

// Mock Payment Validation
$cardName = trim($_POST['card_name'] ?? '');
if (empty($cardName) || !preg_match("/^[a-zA-Z\s]+$/", $cardName)) {
    die("<p style='color:red;'><b>Payment Failed: Invalid Name on Card. Only letters and spaces are allowed.</b></p>");
}

$cardNumber = preg_replace('/\s+/', '', $_POST['card_number'] ?? ''); // Remove spaces
if (strlen($cardNumber) !== 16 || !is_numeric($cardNumber)) {
    die("<p style='color:red;'><b>Payment Failed: Invalid Card Number. Must be 16 digits.</b></p>");
}

// Luhn Algorithm Check
$sum = 0;
$cardNumberStr = (string)$cardNumber;
for ($i = 0; $i < 16; $i++) {
    $digit = (int)substr($cardNumberStr, $i, 1);
    if ($i % 2 == 0) { // For 16-digit cards, even indices are doubled
        $digit *= 2;
        if ($digit > 9)
            $digit -= 9;
    }
    $sum += $digit;
}

if ($sum % 10 !== 0) {
    die("<p style='color:red;'><b>Payment Failed: Invalid Card Number structure (Luhn check failed).</b></p>");
}

// Card Type Check
$cardType = 'Unknown';
if (strpos($cardNumber, '4') === 0) {
    $cardType = 'Visa';
}
elseif (strpos($cardNumber, '5') === 0) {
    $cardType = 'MasterCard';
}

// CVV Check
$cvv = trim($_POST['card_cvv'] ?? '');
if (strlen($cvv) !== 3 || !is_numeric($cvv)) {
    die("<p style='color:red;'><b>Payment Failed: Invalid CVV. It must be exactly 3 digits.</b></p>");
}

$expMonth = $_POST['exp_month'] ?? '';
$expYear = $_POST['exp_year'] ?? '';

if (empty($expMonth) || empty($expYear)) {
    die("<p style='color:red;'><b>Payment Failed: Please provide a valid expiry date.</b></p>");
}

$expiryDate = $expYear . '-' . $expMonth;
if ($expiryDate < date('Y-m')) {
    die("<p style='color:red;'><b>Payment Failed: Card has expired or invalid expiry date. Please try again.</b></p>");
}

// Data Capture
$userId = $_SESSION['userid'];

$address1 = htmlspecialchars($_POST['ship_address1'] ?? '');
$city = htmlspecialchars($_POST['ship_city'] ?? '');
$postcode = htmlspecialchars($_POST['ship_postcode'] ?? '');
$country = htmlspecialchars($_POST['ship_country'] ?? '');
$shippingAddress = $address1 . ", " . $city . ", " . $postcode . ", " . $country;

$orderDateTime = date('Y-m-d H:i:s');

// 1. Calculate Order Total
$orderTotal = 0;
foreach ($_SESSION['basket'] as $basketKey => $quantity) {
    $parts = explode("|", $basketKey);
    $prodid = intval($parts[0]);
    $stmt = $conn->prepare("SELECT prodPrice FROM Product WHERE prodId = ?");
    $stmt->bind_param("i", $prodid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $orderTotal += ($row['prodPrice'] * $quantity);
    }
    $stmt->close();
}

// 2. Insert into Orders Table
$stmt = $conn->prepare("INSERT INTO Orders (userId, orderDateTime, orderTotal, shippingAddress, orderStatus) VALUES (?, ?, ?, ?, 'Pending')");
$stmt->bind_param("isds", $userId, $orderDateTime, $orderTotal, $shippingAddress);

if ($stmt->execute()) {
    $orderNo = $stmt->insert_id; // Get the generated Order Number
    $stmt->close();

    // 3. Process Each Item in the Basket
    foreach ($_SESSION['basket'] as $basketKey => $quantity) {
        $parts = explode("|", $basketKey);
        $prodid = intval($parts[0]);
        $size = isset($parts[1]) ? $parts[1] : '';
        $quantity = intval($quantity);

        // Fetch current price for the receipt
        $priceStmt = $conn->prepare("SELECT prodPrice FROM Product WHERE prodId = ?");
        $priceStmt->bind_param("i", $prodid);
        $priceStmt->execute();
        $priceResult = $priceStmt->get_result();
        $itemPrice = 0;
        if ($row = $priceResult->fetch_assoc()) {
            $itemPrice = $row['prodPrice'];
        }
        $priceStmt->close();

        $subTotal = $itemPrice * $quantity;

        // Insert into Order_Line (Note: Your DB should manually have an 'itemSize' column added)
        // We will fallback to a standard insert if the column isn't there, or assume you added it.
        // Assuming ALTER TABLE Order_Line ADD itemSize VARCHAR(20) has been executed.
        $lineStmt = $conn->prepare("INSERT INTO Order_Line (orderNo, prodId, itemPrice, quantity, subTotal, itemSize) VALUES (?, ?, ?, ?, ?, ?)");
        $lineStmt->bind_param("iidids", $orderNo, $prodid, $itemPrice, $quantity, $subTotal, $size);
        $lineStmt->execute();
        $lineStmt->close();

        // Deduct from Inventory
        $invStmt = $conn->prepare("UPDATE Product SET prodQuantity = prodQuantity - ? WHERE prodId = ?");
        $invStmt->bind_param("ii", $quantity, $prodid);
        $invStmt->execute();
        $invStmt->close();
    }

    // Success Message & Cleanup
    echo "<p style='color:green;'><b>Payment Successful! Your order has been placed.</b></p>";
    echo "<p>Your Order Number is: <b>#" . $orderNo . "</b></p>";
    echo "<p>Shipping to: " . $shippingAddress . "</p>";

    unset($_SESSION['basket']); // Clear basket

}
else {
    echo "<p style='color:red;'><b>Database Error: Could not place order.</b></p>";
}

echo "<p><a href='index.php'>Continue Shopping</a></p>";

include("footfile.html");
echo "</body>";
?>
