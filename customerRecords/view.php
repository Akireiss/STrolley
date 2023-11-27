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
               rc.rfid_card_id
        FROM customer AS c
        LEFT JOIN barangay AS b ON c.barangay_id = b.brgyCode
        LEFT JOIN municipality AS m ON c.municipality_id = m.citymunCode
        LEFT JOIN province AS p ON c.province_id = p.provCode
        LEFT JOIN rfid_cards AS rc ON c.id = rc.customer_id
        WHERE c.id = '$id'";


$result = $con->query($sql);
$customer = $result->fetch_assoc();

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
            <div class="card">
                <div class="card-body">
        <!-- <div class="header">
            <h1>Customer Details</h1>
        </div> -->
        <div class="rfid-card">
            <div class="customer-info">
              <?php echo $customer['full_name']; ?>
            </div>
            <div class="customer-info">
                 <?php echo $customer['address']; ?>
            </div>
            <form id="rfidForm" action="process_scan.php" method="post">
                <input type="hidden" name="rfid_card_id" value="<?php echo $customer['rfid_card_id']; ?>">
            </form>
            
        </div>

        <div class="back-button">
            <a href="../customerRecords/tbl_customers.php" class="btn btn-primary">Back</a>
        </div>
        </div>
            </div>
        </div>
    </section>
</main>
<style>
    body {
        background-color: #f8f9fa;
    }

    .rfid-card {
        height: 200px;
        max-width: 400px;
        margin: 50px auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .id-card-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .customer-info {
        margin-bottom: 15px;
    }
</style>
<?php include '../includes/footer.php'; ?>