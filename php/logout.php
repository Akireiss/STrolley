<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Get the username and user type from the session before destroying it
    $username = $_SESSION['username'];
    $user_type = $_SESSION['user_type'];

    // Audit Trail Code for Logout
    date_default_timezone_set('Asia/Manila');
    $timestamp = date("Y-m-d H:i:s");
    $action = "User logout";

    // Define the event type for logout
    $event_type = "User logout"; // Change this to the appropriate event type

    $details = "User with username '$username' logged out.";

    // Establish the database connection (replace 'your_database_credentials' with your actual database info)
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $audit_sql = "INSERT INTO audit_log (timestamp, event_type, user_type, details) VALUES (?, ?, ?, ?)";
    $audit_stmt = $con->prepare($audit_sql);

    if (!$audit_stmt) {
        die("Error in preparing statement: " . $con->error);
    }

    // Bind parameters
    $audit_stmt->bind_param("ssss", $timestamp, $event_type, $user_type, $details);

    // Execute the audit trail query    
    if ($audit_stmt->execute()) {
        // The audit trail record was successfully inserted
    } else {
        // Handle any errors that may occur during the insertion
    }

    // Close the database connection
    $con->close();
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other destination
header("Location: ../index.php"); // Change 'login.php' to your login page URL
exit;
?>