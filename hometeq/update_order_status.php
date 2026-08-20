<?php
session_start();
// Admin Check
if (!isset($_SESSION['userid']) || ($_SESSION['usertype'] !== 'A' && strtolower($_SESSION['usertype']) !== 'administrator' && strtolower($_SESSION['usertype']) !== 'admin')) {
    die("Access Denied. Administrator privileges required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("db.php");
    $orderNo = intval($_POST['orderNo']);
    $newStatus = trim($_POST['newStatus']);

    // Get current status securely
    $stmt = $conn->prepare("SELECT orderStatus FROM Orders WHERE orderNo = ?");
    $stmt->bind_param("i", $orderNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $currentStatus = isset($row['orderStatus']) ? $row['orderStatus'] : 'Pending';
        $stmt->close();

        // Validating State Transitions
        $isValidTransition = false;

        if ($currentStatus === 'Pending' && in_array($newStatus, ['Shipped', 'Cancelled'])) {
            $isValidTransition = true;
        }
        elseif ($currentStatus === 'Shipped' && in_array($newStatus, ['Delivered', 'Cancelled'])) {
            $isValidTransition = true;
        }

        if ($isValidTransition) {
            $updateStmt = $conn->prepare("UPDATE Orders SET orderStatus = ? WHERE orderNo = ?");
            $updateStmt->bind_param("si", $newStatus, $orderNo);
            $updateStmt->execute();
            $updateStmt->close();
            header("Location: admin_orders.php?msg=Success");
            exit();
        }
        else {
            // Invalid Transition Error
            header("Location: admin_orders.php?msg=InvalidTransition");
            exit();
        }
    }
    else {
        $stmt->close();
        die("Order not found.");
    }
}
else {
    die("Invalid request method.");
}
?>
