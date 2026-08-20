<?php

session_start();
$pagename = "Your Shopping Bag"; // Set the page name

echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";

include("headfile.html");
echo "<h4>" . $pagename . "</h4>";

include("db.php"); // Connect to the database

// Admin Check
if (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === 'A' || strtolower($_SESSION['usertype']) === 'admin' || strtolower($_SESSION['usertype']) === 'administrator')) {
    die("<p style='color:red;'><b>Admins cannot view or manage the shopping basket.</b></p><p><a href='index.php'>Go Home</a></p>");
}

// Handle basket clearing
if (isset($_POST['clear_basket'])) {
    unset($_SESSION['basket']);
    echo "<p><b>Your basket has been cleared.</b></p>";
}

// Handle item removal
if (isset($_POST['delprodid'])) {
    $delprodidStr = $_POST['delprodid'];
    if (isset($_SESSION['basket'][$delprodidStr])) {
        unset($_SESSION['basket'][$delprodidStr]); // Remove item from basket
        echo "<p><b>1 item removed from the basket.</b></p>";
    }
}

// Check if a new product is added
if (isset($_POST['h_prodid']) && isset($_POST['p_quantity'])) {
    $newprodid = intval($_POST['h_prodid']);
    $reququantity = intval($_POST['p_quantity']);
    $size = isset($_POST['p_size']) ? $_POST['p_size'] : 'Default';

    // Store the selected product and quantity in the session, appended with size
    // Using format "prodId|Size"
    $basketKey = $newprodid . "|" . $size;
    $_SESSION['basket'][$basketKey] = $reququantity;

    // Display a confirmation message
    echo "<p><b>1 item added to the basket ($size)</b></p>";
}

// Initialize total price
$total = 0;

// Check if basket is set and not empty
if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Size</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>";

    // Loop through basket session array
    foreach ($_SESSION['basket'] as $basketKey => $quantity) {
        $parts = explode("|", $basketKey);
        $prodid = intval($parts[0]);
        $size = isset($parts[1]) ? $parts[1] : '';

        // Retrieve product details from database
        $sql = "SELECT prodName, prodPrice FROM Product WHERE prodId = $prodid";
        $result = mysqli_query($conn, $sql);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $prodName = $row['prodName'];
            $prodPrice = $row['prodPrice'];
            $subtotal = $prodPrice * $quantity;
            $total += $subtotal;

            // Display product details in table with remove button
            echo "<tr>
                    <td>$prodName</td>
                    <td>$size</td>
                    <td>£$prodPrice</td>
                    <td>$quantity</td>
                    <td>£$subtotal</td>
                    <td>
                        <form method='post' action='basket.php'>
                            <input type='hidden' name='delprodid' value='$basketKey'>
                            <input type='submit' value='Remove'>
                        </form>
                    </td>
                  </tr>";
        }
    }

    // Display total price
    echo "<tr><td colspan='4'><b>Total</b></td><td><b>£$total</b></td><td></td></tr>";
    echo "</table>";

    // Add Clear Basket button
    echo "<form method='post' action='basket.php' style='margin-top: 10px;'>";
    echo "<input type='submit' name='clear_basket' value='Clear Basket' style='background-color: red; color: white; padding: 5px 10px; border: none; cursor: pointer;'>";
    echo "</form>";

    // Add Checkout button if basket is not empty and user is logged in
    if (isset($_SESSION['userid'])) {
        echo "<form method='post' action='checkout_form.php' style='margin-top: 10px;'>";
        echo "<input type='submit' name='checkout' value='Proceed to Checkout' style='background-color: green; color: white; padding: 5px 10px; border: none; cursor: pointer;'>";
        echo "</form>";
    }
    else {
        echo "<p style='margin-top: 15px; color: red;'><b>Please <a href='signup.php'>Sign Up</a> or <a href='login.php'>Login</a> to proceed to checkout!</b></p>";
    }


}
else {
    if (!isset($_POST['clear_basket'])) {
        echo "<p><b>Your basket is empty.</b></p>";
    }
}

// Sign Up and Login links
if (!isset($_SESSION['userid'])) {
    echo "<p><a href='signup.php'>Sign Up</a> | <a href='login.php'>Login</a></p>";
}

include("footfile.html");
echo "</body>";

/*$_SESSION is a superglobal variable in php
 allow you to store info that persists upon multiple pages
 used to store session variables
 unlike cookies session data is stored in server
 If session_start(); is not used, $_SESSION will not work.
 session_destroy();-Completely destroys the session, including the session ID.(log out)
 session_unset();-Clears all session variables but keeps the session active. */
?>
