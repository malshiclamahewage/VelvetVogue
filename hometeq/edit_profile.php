<?php
session_start();
$pagename = "Edit Profile";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>" . $pagename . "</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");

if (!isset($_SESSION['userid'])) {
    die("<div style='padding:20px;'><p>Please log in first.</p><a href='login.php'>Login</a></div>");
}

echo "<h4>" . $pagename . "</h4>";
echo "<div style='padding-left:10px;'>";
include("db.php");
$userId = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAddress = trim($_POST['userAddress']);
    $userPostCode = trim($_POST['userPostCode']);
    $userTelNo = trim($_POST['userTelNo']);

    $stmt = $conn->prepare("UPDATE Users SET userAddress=?, userPostCode=?, userTelNo=? WHERE userId=?");
    $stmt->bind_param("sssi", $userAddress, $userPostCode, $userTelNo, $userId);
    if ($stmt->execute()) {
        echo "<p style='color:green;'><b>Profile updated successfully!</b></p>";
    }
    else {
        echo "<p style='color:red;'><b>Error updating profile.</b></p>";
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT userAddress, userPostCode, userTelNo FROM Users WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

?>
<p>Update your shipping and contact information below.</p>
<form method="POST" action="edit_profile.php" style="background:#f9f9f9; padding:20px; border:1px solid #ddd; max-width:500px;">
    <table cellpadding="8">
        <tr><td><b>Address:</b></td><td><input type="text" name="userAddress" value="<?php echo htmlspecialchars($user['userAddress']); ?>" required style="width:250px;"></td></tr>
        <tr><td><b>Post Code:</b></td><td><input type="text" name="userPostCode" value="<?php echo htmlspecialchars($user['userPostCode']); ?>" required style="width:250px;"></td></tr>
        <tr><td><b>Telephone:</b></td><td><input type="text" name="userTelNo" value="<?php echo htmlspecialchars($user['userTelNo']); ?>" required style="width:250px;"></td></tr>
        <tr><td colspan="2" style="text-align:right;"><br><input type="submit" value="Update Profile" style="background:green; color:white; padding:10px; border:none; cursor:pointer;"></td></tr>
    </table>
</form>
<br><p><a href='index.php'>Return to Homepage</a></p>
</div>
<?php
include("footfile.html");
echo "</body>";
?>
