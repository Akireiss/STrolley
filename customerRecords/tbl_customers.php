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

// Check if any users are found

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>
<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="add-employee mb-3 mt-3">
                    <a href="../customerRecords/addCustomer.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i>Add Customer
                    </a>
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
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Sex</th>
                            <th scope="col">Civil Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($userrow = $result->fetch_assoc()) {
                            $id = $userrow["id"];
                            $fullname = $userrow["full_name"];
                            $address = $userrow["address"];
                            $sex = $userrow["sex"];
                            $civil_status = $userrow["civil_status"];
                        ?>
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $fullname; ?></td>
                                <td><?php echo $address; ?></td>
                                <td><?php echo $sex; ?></td>
                                <td><?php echo $civil_status; ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="../customerRecords/view.php?id=<?php echo $id; ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $id; ?>">
                                            <i class="bx bx-show-alt"></i>
                                        </a>
                                        <a href="../customerRecords/update.php?id=<?php echo $id; ?>" class="btn btn-sm btn-info update-btn m-1" data-id="<?php echo $id; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </div>
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
                </style>
                <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        new simpleDatatables.DataTable("#myTable");
                    });
                </script>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
