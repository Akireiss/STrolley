<?php
session_start();

include "../db_conn.php"; // Include your database connection

// After successful staff registration
$staffName = $_POST['name'];
$staffUsername = $_POST['username'];
$userType = $_POST['user_type'];
$userId = $_SESSION['user_id']; // Assuming you have a user ID in session

// Prepare the log message
$logDetails = "Added staff: $staffName (Username: $staffUsername)";

// Insert the audit trail record
$insertLogQuery = "INSERT INTO audit_log (timestamp, action, user_id, user_type, details) VALUES (NOW(), 'Staff Registration', ?, ?, ?)";
$stmt = $con->prepare($insertLogQuery);
$stmt->bind_param("iss", $userId, $userType, $logDetails);

if ($stmt->execute()) {
    // Audit trail record inserted successfully
    $_SESSION['audit_success'] = "Audit trail record added successfully.";
} else {
    // Error inserting audit trail record
    $_SESSION['audit_error'] = "Failed to add audit trail record.";
}

// Redirect back to the page where the staff was added
header("Location: ../settings/tbl_staff.php");
exit;
?>
