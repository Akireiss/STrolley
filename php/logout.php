<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Debugging: Print a message to check if the script is executed
echo "Logged out successfully";

// Redirect to the login page or any other destination
header("Location: ../index.php"); // Change 'login.php' to your login page URL
exit;
?>
