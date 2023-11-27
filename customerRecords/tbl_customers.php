<?php
session_start();

include_once('../db_conn.php');

// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

// Modify the SQL query to include JOIN operations
$sql = "SELECT c.id, 
               CONCAT(c.first_name, ' ', c.middle_name, ' ', c.last_name, ' ', c.suffix) AS full_name,
               CONCAT(b.brgyDesc, ', ', m.citymunDesc, ', ', p.provDesc) AS address,
               c.sex,
               c.civil_status
        FROM customer AS c
        LEFT JOIN barangay AS b ON c.barangay_id = b.brgyCode
        LEFT JOIN municipality AS m ON c.municipality_id = m.citymunCode
        LEFT JOIN province AS p ON c.province_id = p.provCode";

// Execute the SQL query
$result = $con->query($sql);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

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
</style>



<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="add-customer mb-3 mt-3">
                    <h1 class="page-header">List of Customers <a href="../customerRecords/addCustomer.php" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add Customer
                        </a>
                    </h1>
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
                <div class="d-flex justify-content-between mb-3">
                </div>
                <table id="myTable" class="table  table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Sex</th>
                            <th scope="col">Civil Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($customer = $result->fetch_assoc()) {
                            $id = $customer["id"];
                            $fullname = $customer["full_name"];
                            $address = $customer["address"];
                            $sex = $customer["sex"];
                            $civil_status = $customer["civil_status"];
                        ?>
                            <tr>
                                <td><?php echo $fullname; ?></td>
                                <td><?php echo $address; ?></td>
                                <td><?php echo $sex; ?></td>
                                <td><?php echo $civil_status; ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="../customerRecords/view.php?id=<?php echo $id; ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $id; ?>">
                                            <i class="bi bi-gear"></i> Generate
                                        </a>
                                        <a href="../customerRecords/show.php?id=<?php echo $id; ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $id; ?>">
                                            <i class="bx bx-show-alt"></i> View
                                        </a>
                                        <a href="../customerRecords/update.php?id=<?php echo $id; ?>" class="btn btn-sm btn-info edit-btn m-1" data-id="<?php echo $id; ?>">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        new simpleDatatables.DataTable("#myTable");
                    });
                </script>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
