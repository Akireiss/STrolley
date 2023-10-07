<?php
session_start();

// Import database connection
include "../db_conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $region_id = $_POST['region_id'];
    $province_id = $_POST['province_id'];
    $municipality_id = $_POST['municipality_id'];
    $barangay_id = $_POST['barangay_id'];
    $sex = $_POST['sex'];
    $civil_status = $_POST['civil_status'];

    // Perform data validation
    // Add your validation logic here
    // For example, check if required fields are filled, validate input lengths, etc.

    // Generate a random 10-digit number for the RFID card
    $rfid_card_number = mt_rand(1000000000, 9999999999);

    // Insert the data into the database
    $sql = "INSERT INTO customer (first_name, middle_name, last_name, suffix, region_id, province_id, municipality_id, barangay_id, sex, civil_status)
            VALUES ('$firstName', '$middleName', '$lastName', '$suffix', '$region_id', '$province_id', '$municipality_id', '$barangay_id', '$sex', '$civil_status')";
    if ($con->query($sql) === true) {
        // Insert the generated RFID card number into the rfid_cards table
        $customer_id = $con->insert_id; // Get the last inserted customer ID
        $sql = "INSERT INTO rfid_cards (rfid_card_id, customer_id)
                VALUES ('$rfid_card_number', '$customer_id')";
        if ($con->query($sql) === true) {
            // Add an audit trail record for the customer addition
            // Comment out the following lines related to auditTrail()
            // $event_type = "Added Customer"; // Change this to the appropriate event type
            // $logDetails = "Added Customer: $firstName";
            // You need to define or include the auditTrail() function here
            // Example: include "audit.php"; if auditTrail() is defined in audit.php
            // auditTrail($event_type, $logDetails);
            $_SESSION['success'] = "Customer added successfully!";
        } else {
            $_SESSION['error'] = "Error inserting customer data: " . $con->error;
        }
        header("Location: ../customerRecords/tbl_customers.php");
        exit;
    } else {
        $_SESSION['error'] = "Error inserting customer data: " . $con->error;
    }
} else {
    $_SESSION['error'] = "Validation failed!";
    // Handle validation errors here
    // You can redirect or display error messages as needed
}
?>




