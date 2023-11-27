<?php
session_start();

// Import database connection
include "../db_conn.php";

if (!isset($_SESSION['user_type'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

// Fetch data from the "transactions" table
$sql = "SELECT t.customer_id, CONCAT(c.first_name, ' ', c.last_name) AS customer_name, t.created_at AS timestamp, SUM(t.t_amount) AS total_amount
        FROM transaction AS t
        INNER JOIN customer AS c ON t.customer_id = c.id
        GROUP BY t.customer_id, customer_name, timestamp
        ORDER BY timestamp DESC";


$result = $con->query($sql);

// Check if any transactions are found
if ($result->num_rows === 0) {
    // Handle the case when no transactions are found
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="add-product mb-3 mt-3">
                    <h1 class="page-header">Transactions</h1>
                </div>

                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['success']; ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php } ?>

                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php } ?>

               
                <table id="example" class="table">
    <thead>
        <tr>
            <th scope="col">Customer ID</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Total Amount</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($transaction = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $transaction['customer_id']; ?></td>
                <td><?php echo $transaction['customer_name']; ?></td>
                <td><span>₱ </span><?php echo $transaction['total_amount']; ?><span> .00</span></td>
                <td><?php echo isset($transaction['timestamp']) ? date('Y-m-d H:i:s', strtotime($transaction['timestamp'])) : 'N/A'; ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="viewtransac.php?id=<?php echo $transaction['customer_id']; ?>&time=<?php echo urlencode($transaction['timestamp']); ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $transaction['customer_id']; ?>">
                            <i class="bx bx-show-alt"></i> View
                        </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

             
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<style>
    
    table.table {
        width: 100%;
        border-collapse: collapse;
    }

    table.table th,
    table.table td {
        padding: 8px; /* Reduce padding for a more compact design */
        border: 1px solid #e0e0e0;
        font-size: 14px; /* Adjust the font size as needed */
    }

    table.table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    table.table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table.table tr:hover {
        background-color: #e0e0e0;
    }

    table.table img {
        max-width: 80px; /* Reduce the max-width of the image */
        height: auto; /* Maintain aspect ratio */
    }

    table.table .btn {
        margin: 3px;
    }

    /* Custom CSS for Edit button */
    .edit-btn {
        background-color: #007bff;
        color: #fff;
    }    table.table {
        width: 100%;
        border-collapse: collapse;
    }

    table.table th,
    table.table td {
        padding: 8px; /* Reduce padding for a more compact design */
        border: 1px solid #e0e0e0;
        font-size: 14px; /* Adjust the font size as needed */
    }

    table.table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    table.table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table.table tr:hover {
        background-color: #e0e0e0;
    }

    table.table img {
        max-width: 80px; /* Reduce the max-width of the image */
        height: auto; /* Maintain aspect ratio */
    }

    table.table .btn {
        margin: 3px;
    }

    /* Custom CSS for Edit button */
    .edit-btn {
        background-color: #007bff;
        color: #fff;
    }
    </style>
<script>
    // Initialize DataTable
    $(document).ready(function () {
        $('#example').DataTable();
    });

    // Show back-to-top button when scrolling down
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.querySelector(".back-to-top").style.display = "block";
        } else {
            document.querySelector(".back-to-top").style.display = "none";
        }
    }

    // Scroll to the top when clicking the back-to-top button
    document.querySelector(".back-to-top").onclick = function () {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    };
</script>
