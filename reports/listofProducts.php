<?php
session_start();

// Import database connection
include "../db_conn.php";

$sql = "SELECT * FROM products";
$result = $con->query($sql);

// Check if any products are found
if ($result->num_rows === 0) {
    // Handle case when no products are found
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
            <div class="col-md-4">
                <h1 class="page-header">List of Products</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="generate_report.php" class="btn btn-primary" style="margin-right: 10px;">Generate Report</a>
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
            <table id="myTable" class="table datatable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Product Item</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Weight</th>
                        <th scope="col">Barcode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $product["id"]; ?></td>
                            <td><?php echo $product["item_description"]; ?></td>
                            <td><?php echo $product["unit_price"]; ?></td>
                            <td><?php echo $product["quantity"]; ?></td>
                            <td><?php echo $product["weight"]; ?></td>
                            <td><?php echo $product["barcode"]; ?></td>
                            <td>
                                
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <style>
                #myTable th,
                #myTable td {
                    text-align: center;
                }
                .generate-report-button {
                    text-align: right;
                    margin-top: 20px;
                }
            </style>
            <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    new simpleDatatables.DataTable("#myTable");
                });
            </script>
        </div>
    </section>
</main>
