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
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="col-md-4">
                    <h1 class="page-header">List of Products</h1>
                </div>
                <!-- <div class="col-md-6 text-right">
         
                    <button class="btn btn-primary generate-report-button" onclick="printList()">Generate Report</button>
                </div> -->
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
               <style>
    .generate-report-button {
        text-align: right;
        margin-top: 20px;
    }

    /* Customize the cursor and style only the "Total Weight" and "Total Cost" columns */
    .total-column {
        cursor: default; /* Prevent cursor from changing */
        /* Add your custom styling for the "Total Weight" and "Total Cost" columns */
        background-color: #f0f0f0; /* Gray background color */
        color: #333; /* Text color */
        font-weight: bold;
    }

    .table-container {
        max-height: 500px; /* Set the maximum height for the container */
        overflow: auto; /* Add scrollbars when content overflows */
    }

    .gray-background {
        background-color: #f0f0f0; /* Gray background color */
        height: 20px; /* Adjust the height as needed */
    }

    /* Adjust font size and cell padding for the table */
    .table th,
    .table td {
        font-size: 14px; /* Adjust the font size as needed */
        padding: 8px; /* Adjust the cell padding as needed */
    }
</style>
<div id="buttons-container" class="col-md-6"></div>
                <div class="container">
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Product Item</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Measurement</th>
                                <th scope="col">Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($product = $result->fetch_assoc()) { ?>
                                <tr>
                                <td><?php echo $product["item"]; ?></td>
                                    <td><?php echo $product["unit_price"]; ?></td>
                                    <td><?php echo $product["quantity"]; ?></td>
                                    <td><?php echo $product["measurements"]; ?></td>
                                    <td><?php echo $product["weight"]; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
      
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            lengthChange: true,
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "All"]
            ],
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i>&nbsp;Print',
                    title: 'Product Report',
                    className: 'btn btn-secondary mb-2' // No need for margin on the first button
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i>&nbsp;Generate Excel',
                    className: 'btn btn-success mx-2 mb-2' // Added margin to the left and right
                },
                // {
                //     extend: 'pdf',
                //     text: '<i class="fas fa-file-pdf"></i>&nbsp;Generate PDF',
                //     className: 'btn btn-danger mx-2 mb-2' // Added margin to the left
                // },
                // {
                //     extend: 'colvis',
                //     text: '<i class="fas fa-columns"></i>&nbsp;Column Visibility',
                //     className: 'btn btn-primary mb-2' // Added margin-bottom for the Column Visibility button
                // }
            ]
        });

        // Move buttons container to a designated div
        table.buttons().container()
            .appendTo('#buttons-container');

        // Initialize tooltips for better user experience
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>



<!-- Add Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<!-- Add a container for buttons -->





<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css"> -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>