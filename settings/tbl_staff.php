<?php
session_start();

// Import database connection
include "../db_conn.php";

$sql = "SELECT * FROM users";
$result = $con->query($sql);

// Check if any users are found
if ($result->num_rows === 0) {
    // Handle case when no users are found
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="add-employee mb-3 mt-3">
                        <a href="../settings/addstaff.php" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add Member
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
                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Contact Number</th>
                                    <th scope="col">User Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($userrow = $result->fetch_assoc()) {
                                    $id = $userrow["id"];
                                    $name = $userrow["name"];
                                    $username = $userrow["username"];
                                    $contactNumber = $userrow["phone_number"];
                                    $userType = $userrow["user_type"];
                                ?>
                                    <tr>
                                        <td><?php echo $name; ?></td>
                                        <td><?php echo $username; ?></td>
                                        <td><?php echo $contactNumber; ?></td>
                                        <td><?php echo $userType; ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="../settings/view_staff.php?id=<?php echo $id; ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $id; ?>">
                                                    <i class="bx bx-show-alt"></i> View
                                                </a>
                                                <a href="../settings/update_staff.php?id=<?php echo $id; ?>" class="btn btn-sm btn-info update-btn m-1" data-id="<?php echo $id; ?>">
                                                    <i class="bi bi-pencil-square"></i> Update
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            new simpleDatatables.DataTable("#myTable");
                        });
                    </script>
                </div>
            </div>
        </div>
        <style>
            /* Add this to your existing CSS or in a new style tag */
body {
    font-family: 'Arial', sans-serif;
}

.card {
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: #fff;
    border-radius: 10px;
}

.table th,
.table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.table th {
    background-color: #f8f9fa;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f3f4f6;
}

.table-hover tbody tr:hover {
    background-color: #e2e8f0;
}

.btn {
    border-radius: 5px;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.btn-info {
    background-color: #2ecc71;
    border-color: #2ecc71;
}

.btn-info:hover {
    background-color: #27ae60;
    border-color: #27ae60;
}

.alert {
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.add-employee a {
    text-decoration: none;
    color: #fff;
}

</style>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
