<?php
session_start();
include "../db_conn.php";

function auditTrail($event_type, $details) {
    // You should replace 'your_database_credentials' with your actual database info
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    
    date_default_timezone_set('Asia/Manila');
    $timestamp = date("Y-m-d H:i:s");
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : 0; // Replace 0 with a default user ID if necessary
    $user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Unknown'; // Replace 'Unknown' with a default user type if necessary

    $insertLogQuery = "INSERT INTO audit_log (timestamp, event_type, id, user_type, details) VALUES (?, ?, ?, ?, ?)";
    $auditStmt = $con->prepare($insertLogQuery);
    $auditStmt->bind_param("sssss", $timestamp, $event_type, $id, $user_type, $details);

    if ($auditStmt->execute()) {
        // Audit trail record inserted successfully

    } else {
        // Error inserting audit trail record
    }

    $auditStmt->close();
    $con->close();
}


if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $user_type = $_POST['user_type'];
    $password = md5($_POST['password']);

    $updateSql = "UPDATE users SET name='$name', username='$username', email='$email', phone_number='$phone_number', user_type='$user_type', password='$password' WHERE id='$id'";

    if ($con->query($updateSql) === TRUE) {
        $event_type = "Update Staff"; // Change this to the appropriate event type
        $logDetails = "Update Staff: $name";
        auditTrail($event_type, $logDetails);
        $_SESSION['success'] = "User Updated successfully.";
        header("Location: ../settings/tbl_staff.php");
        exit;
    } else {
        $_SESSION['error'] = "User Not Updated" . $con->error;
    }
}
?>
