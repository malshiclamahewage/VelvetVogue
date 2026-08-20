<?php
// Start session if not already started
/*
PHP_SESSION_NONE is a constant used with session_status() in order to check whether session exists but has not started yet
PHP_SESSION_NONE=1 means that no session exists, saying that session_start(); has not yet been called
*/


/*session_status() is a built in php function which returns the current session state as integer value
 
*/
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

// Check if the user is logged in
if (isset($_SESSION['userid'])) {
    echo "<div style='text-align:right; padding:10px; font-weight:bold;'>";
    echo $_SESSION['fname'] . " " . $_SESSION['sname'] . " | User type: " . $_SESSION['usertype'];
    echo "</div>";
}
?>
