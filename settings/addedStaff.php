<?php


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

if (
    isset($_POST['name'], $_POST['username'], $_POST['email'], $_POST['phone_number'], $_POST['user_type'], $_POST['password'])
) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pnumber = $_POST['phone_number'];
    $utype = $_POST['user_type'];
    $password = md5($_POST['password']);

    // // Check if the email or username already exists in the database
    // $checkSql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    // $result = $con->query($checkSql);

    // if ($result->num_rows > 0) {
    //     // Duplicate entry found
    //     header("Location: ../addstaff.php?error=Duplicate entry found for email or username.");
    //     exit;
    // }

    // Insert the data into the database
    $insertSql = "INSERT INTO users (name, email, phone_number, username, user_type, password) VALUES ('$name', '$email', '$pnumber', '$username', '$utype', '$password')";
    if ($con->query($insertSql) === true) {
        $event_type = "Added Staff"; // Change this to the appropriate event type
        $logDetails = "Added Staff: $name";
        auditTrail($event_type, $logDetails);
        $_SESSION['success'] = "User Added successfully.";
        header("Location: ../settings/tbl_staff.php");
        exit;
    } else {
        $_SESSION['error'] = "User Added Failed.";
        header("Location: ../addstaff.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Missing required fields.";
    header("Location: ../addstaff.php");
    exit;
}
?>

