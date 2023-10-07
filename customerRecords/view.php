<?php

// Start the session
session_start();

include_once('../db_conn.php');
// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM customer WHERE id = '$id'";
$result = $con->query($sql);

// Check if the customer exists
if ($result->num_rows !== 1) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT c.id, 
               CONCAT(c.first_name, ' ', c.middle_name, ' ', c.last_name, ' ', c.suffix) AS full_name,
               CONCAT(b.brgyDesc, ', ', m.citymunDesc, ', ', p.provDesc) AS address,
               c.sex,
               c.civil_status,
               rc.rfid_card_id  -- Fetch rfid_card_id from rfid_cards table
        FROM customer AS c
        LEFT JOIN barangay AS b ON c.barangay_id = b.brgyCode
        LEFT JOIN municipality AS m ON c.municipality_id = m.citymunCode
        LEFT JOIN province AS p ON c.province_id = p.provCode
        LEFT JOIN rfid_cards AS rc ON c.id = rc.customer_id  -- Join with rfid_cards table
        WHERE c.id = '$id'";


$result = $con->query($sql);
$customer = $result->fetch_assoc();

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
    <div class="card">
      
        <div class="card-body">
            <div class="id-card">
                <div class="customer-info">
                    <div class="info-item mt-5">
                        <?php echo $customer['full_name']; ?>
                    </div>
                    <div class="info-item" style="margin-top: -10px;">
                        <?php echo $customer['address']; ?>
                    </div>
                 
                    <div class="info-item text-end" style="margin-top: 90px;">
                    <?php echo $customer['rfid_card_id']; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="back-button">
                <a href="../customerRecords/tbl_customers.php" class="btn btn-primary">Back</a>
            </div>
    </section>
</main>
<style>
    /* styles.css */

.card {
    width: 500px;
    height: 250px;
    margin: 20px auto;
    border: 1px solid #ccc;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

.card-header {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    text-align: center;
}

.card-body {
    padding: 20px;
}

.id-card {
    display: flex;
    align-items: center;
}


.customer-info {
    flex-grow: 1;
}

.info-item {
    margin-bottom: 10px;
}

.back-button {
    text-align: center;
    margin-top: 20px;
}

    </style>

<?php include '../includes/footer.php'; ?>