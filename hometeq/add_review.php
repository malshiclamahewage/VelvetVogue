<?php
session_start();

if (!isset($_SESSION['userid']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Action Denied.");
}

include("db.php");
$prodId = intval($_POST['prodId']);
$userId = $_SESSION['userid'];
$rating = intval($_POST['rating']);
$reviewText = trim($_POST['reviewText']);

if ($rating < 1 || $rating > 5 || empty($reviewText)) {
    die("Invalid review data.");
}

$stmt = $conn->prepare("INSERT INTO Reviews (prodId, userId, rating, reviewText) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $prodId, $userId, $rating, $reviewText);

if ($stmt->execute()) {
    header("Location: prodbuy.php?u_prod_id=" . $prodId . "&msg=ReviewSuccess");
}
else {
    header("Location: prodbuy.php?u_prod_id=" . $prodId . "&msg=ReviewFailed");
}
$stmt->close();
?>
