<?php

session_start();
$pagename = "Checkout";


echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";

include("headfile.html");
include("detectlogin.php");

echo "<h4>" . $pagename . "</h4>";

// Verify user authentication
if (!isset($_SESSION['userid'])) {
    echo "<p style='color:red;'><b>Action denied: You must be logged in to access checkout!</b></p>";
}
else {
    // Proceed if logged in
    if (!isset($_SESSION['basket']) || empty($_SESSION['basket'])) {
        echo "<p><b>Your basket is empty. Add items before proceeding to checkout.</b></p>";
    }
    else {
        include("db.php");

        // Calculate Total
        $total = 0;
        foreach ($_SESSION['basket'] as $prodid => $quantity) {
            $sql = "SELECT prodPrice FROM Product WHERE prodId = " . intval($prodid);
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $total += ($row['prodPrice'] * $quantity);
            }
        }

        echo "<h4>Order Summary</h4>";
        echo "<p><b>Total Amount Payable: £" . number_format($total, 2) . "</b></p><br>";

        echo "<h4>Enter Shipping & Payment Details</h4>";

        // The Checkout Form
        echo "<div class='formStyle' style='max-width: 500px; margin: 0 auto; text-align: left;'>";
        echo "<form action='checkout_process.php' method='POST'>";

        echo "<p><b>Shipping Address</b></p>";

        echo "<label>Address Line 1</label><br>";
        echo "<input type='text' name='ship_address1' style='width: 100%; margin-bottom: 10px;' required><br>";

        echo "<div style='display: flex; gap: 10px; margin-bottom: 15px;'>";
        echo "  <div style='flex: 2;'><label>City</label><br><input type='text' name='ship_city' style='width: 100%;' required></div>";
        echo "  <div style='flex: 1;'><label>Post Code</label><br><input type='text' name='ship_postcode' style='width: 100%;' required></div>";
        echo "  <div style='flex: 1;'><label>Country</label><br><input type='text' name='ship_country' style='width: 100%;' required></div>";
        echo "</div>";

        echo "<hr style='margin-bottom: 15px;'>";

        echo "<p><b>Payment Information (Mock Gateway)</b></p>";

        echo "<label>Name on Card</label><br>";
        echo "<input type='text' name='card_name' pattern='[a-zA-Z\s]+' title='Only letters and spaces are allowed' style='width: 100%; margin-bottom: 10px;' required><br>";

        echo "<label>Card Number (16 Digits)</label><br>";
        echo "<input type='text' name='card_number' maxlength='16' pattern='\d{16}' title='Please enter exactly 16 digits' style='width: 100%; margin-bottom: 10px;' required><br>";

        echo "<div style='display: flex; gap: 10px; margin-bottom: 15px;'>";
        echo "  <div style='flex: 1;'><label>Expiry Date</label><br>
                    <div style='display: flex; gap: 5px;'>
                        <select name='exp_month' style='width: 50%;' required>
                            <option value='' disabled selected>MM</option>";
        for ($m = 1; $m <= 12; $m++) {
            $month = str_pad($m, 2, '0', STR_PAD_LEFT);
            echo "<option value='$month'>$month</option>";
        }
        echo "          </select>
                        <select name='exp_year' style='width: 50%;' required>
                            <option value='' disabled selected>YYYY</option>";
        $currentYear = date('Y');
        for ($y = $currentYear; $y <= $currentYear + 10; $y++) {
            echo "<option value='$y'>$y</option>";
        }
        echo "          </select>
                    </div>
                </div>";
        echo "  <div style='flex: 1;'><label>CVV</label><br>
                    <div style='display: flex; align-items: center; border: 1px solid #ccc; padding-right: 5px; background: white;'>
                        <input type='password' id='cvv_input' name='card_cvv' maxlength='3' pattern='\d{3}' title='3 digit CVV' style='width: 100%; border: none; outline: none;' required>
                        <span id='toggle_cvv' style='cursor: pointer; padding: 0 5px;' onclick='
                            var cvvInput = document.getElementById(\"cvv_input\");
                            if (cvvInput.type === \"password\") {
                                cvvInput.type = \"text\";
                                this.innerHTML = \"👁️\";
                            } else {
                                cvvInput.type = \"password\";
                                this.innerHTML = \"👁️‍🗨️\";
                            }
                        '>👁️‍🗨️</span>
                    </div>
                </div>";
        echo "</div>";

        echo "<input type='submit' name='submit_payment' value='Confirm Payment & Place Order' class='btn' style='width: 100%; background-color: green; font-weight: bold;'>";

        echo "</form>";
        echo "</div>";
    }
}

echo "<p><a href='index.php'>Continue Shopping</a></p>";

include("footfile.html");
echo "</body>";
?>
