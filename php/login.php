<?php
// login.php

// Start the session
session_start();

include_once('../db_conn.php');

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize error message
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $password = md5(test_input($_POST["password"])); // Hash the password

    // Establish the database connection (replace 'your_database_credentials' with your actual database info)
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Authentication successful
        $user_data = $result->fetch_assoc();
        $name = $user_data['name']; // Replace 'name' with the actual field name for the user's name
        $_SESSION['name'] = $name; // Store the user's name in the session
        
        $user_type = $user_data['user_type']; // Retrieve the user_type from your user data
        $_SESSION['user_type'] = $user_type; // Store user information in the session

        // Audit Trail Code
        date_default_timezone_set('Asia/Manila');
        $timestamp = date("Y-m-d H:i:s");
        $action = "User login";

        // Define the event type
        $event_type = "User login"; // Change this to the appropriate event type

        $details = "User with username '$username' logged in.";

        // No need to redefine $user_type, use the one retrieved from the database

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

        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        // Authentication failed, display an error message
        $_SESSION['error'] = "Incorrect username or password!";
        header("Location: ../index.php");
        exit;
    }
}
?>
