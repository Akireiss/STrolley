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

function auditTrail($event_type, $details)
{
    // Replace 'your_database_credentials' with your actual database info
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

$alertMessage = ''; // Initialize an empty alert message

$customer_info_query = "SELECT rfid_cards.customer_id, customer.first_name, customer.last_name, rfid_cards.balance
                        FROM rfid_cards
                        JOIN customer ON rfid_cards.customer_id = customer.id";
$customer_info_result = mysqli_query($con, $customer_info_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $rfid_card_id = $_POST['rfid_card_id'];
    $amount = $_POST['amount'];

    // Perform any necessary validation on the data here

    // Insert the data into your database table (load)
    $load_sql = "INSERT INTO `load` (rfid_card_id, amount) VALUES (?, ?)";
    $load_stmt = mysqli_prepare($con, $load_sql);

    if ($load_stmt) {
        mysqli_stmt_bind_param($load_stmt, "ss", $rfid_card_id, $amount);
        if (mysqli_stmt_execute($load_stmt)) {
            // Transaction was successfully added in the 'load' table

            // Update the 'balance' in the 'rfid_cards' table
            $update_sql = "UPDATE `rfid_cards` SET balance = balance + ? WHERE rfid_card_id = ?";
            $update_stmt = mysqli_prepare($con, $update_sql);

            if ($update_stmt) {
                mysqli_stmt_bind_param($update_stmt, "ss", $amount, $rfid_card_id);
                if (mysqli_stmt_execute($update_stmt)) {

                    $event_type = "Add Load"; // Change this to the appropriate event type
                    $logDetails = "Add Load: $amount    ";
                    auditTrail($event_type, $logDetails);
                    // Balance updated successfully in 'rfid_cards' table
                    $alertMessage = "Transaction loaded successfully!";

                    // Reload customer information after the transaction
                    $customer_info_result = mysqli_query($con, $customer_info_query);
                } else {
                    // Error occurred while updating balance in 'rfid_cards' table
                    $alertMessage = "Error: " . mysqli_error($con);
                }
                mysqli_stmt_close($update_stmt);
            } else {
                // Error in preparing the SQL statement for balance update
                $alertMessage = "Error: " . mysqli_error($con);
            }
            mysqli_stmt_close($load_stmt);
        } else {
            // Error occurred while adding the transaction to 'load' table
            $alertMessage = "Error: " . mysqli_error($con);
        }
    } else {
        // Error in preparing the SQL statement for 'load' table
        $alertMessage = "Error: " . mysqli_error($con);
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>
<!-- Add this JavaScript code to display the alert -->
<script>
    // Check if the alert message is not empty, then show an alert
    <?php if (!empty($alertMessage)) { ?>
        alert("<?php echo $alertMessage; ?>");
    <?php } ?>
</script>

<main id="main" class="main">
    <div class="col-md-12 mb-lg0 mb-4">
        <div class="card mt-4">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-6 d-flex align-items-center">
                        <h6 class="mb-0">Add New Load</h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body pt-4 p-3">
                            <form action="loadMoney.php" method="POST">
                                <div class="mb-3">
                                    <label for="rfid" class="form-label">RFID Card ID</label>
                                    <input type="text" class="form-control" id="rfid" name="rfid_card_id" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="text" class="form-control" id="amount" name="amount" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Transaction</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <table id="myTable" class="table datatable">
                <thead>
                    <tr>
                        <th scope="col">NAME</th>
                        <th scope="col">BALANCE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the result set and display each row in the table
                    while ($row = mysqli_fetch_assoc($customer_info_result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td><?php echo $row['balance']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    new simpleDatatables.DataTable("#myTable");
                });
            </script>
        </div>
    </div>
</main>