<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- DataTables CSS link -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">

    <title>Product Categories</title>

    <!-- Your existing header code here -->
    <!-- Add your existing DataTables JavaScript link -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

    <!-- Add Bootstrap JavaScript and Popper.js links -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<?php
session_start();
include "../db_conn.php";

$sql = "SELECT * FROM categories";
$result = $con->query($sql);

if ($result->num_rows === 0) {
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
        <div class="container">
            <div class="card">
            <h1 class="page-header">Product Categories</h1>

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
            <div id="buttons-container" class="col-md-6"></div>
            <div class="table-container">
            <table id="example" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Categories</th>
                        <th>Products</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($categoryRow = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$categoryRow['category_name']}</td>";

                        $categoryId = $categoryRow['category_id'];
                        $productSql = "SELECT item FROM products WHERE category_id = '$categoryId'";
                        $productResult = $con->query($productSql);

                        echo "<td>";
                        while ($productRow = $productResult->fetch_assoc()) {
                            echo "{$productRow['item']}<br>";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
            </div>
        </div>
        </div>
    </section>
</main>
<style>
    #myTable th,
    #myTable td {
        text-align: center;
    }

    table.table {
        border-collapse: collapse;
        width: 100%;
        background-color: #fff;
    }

    table.table th {
        padding: 12px;
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
        max-width: 100px;
    }

    table.table .btn {
        margin: 3px;
    }

    /* Custom CSS for Edit button */
    .edit-btn {
        background-color: #007bff;
        color: #fff;
    }

    .table-container {
        padding: 20px; /* Adjust the padding as needed */
    }

    table.table {
        border-collapse: collapse;
        width: 100%;
        background-color: #fff;
    }

    </style>


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
                    title: 'Category Report',
                    className: 'btn btn-secondary mb-2',
                    customize: function(win) {
                        // Add custom styling for the print view
                        $(win.document.body).find('table').addClass('table-striped table-bordered p-5');

                        // Replace <br> tags with newline characters (\n) in the second column for line breaks
                        $(win.document.body).find('td:nth-child(2)').each(function() {
                            var productsText = $(this).html().replace(/<br>/g, '\n');
                            $(this).html(productsText);
                        });
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i>&nbsp;Generate Excel',
                    className: 'btn btn-success mx-2 mb-2'
                },
                // {
                //     extend: 'pdf',
                //     text: '<i class="fas fa-file-pdf"></i>&nbsp;Generate PDF',
                //     className: 'btn btn-danger mx-2 mb-2'
                // },
            ]
        });

        // Move buttons container to a designated div
        table.buttons().container()
            .appendTo('#buttons-container');

        // Initialize tooltips for better user experience
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
;










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