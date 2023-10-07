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
                        <i class="bi bi-plus"></i>Add Staff
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
                            <th scope="col">Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Contact Number</th>
                            <th scope="col">UserType</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($userrow = $result->fetch_assoc()) {
                            $id = $userrow["id"];
                            $name = $userrow["name"];
                            $username = $userrow["username"];
                            $email = $userrow["email"];
                            $contactNumber = $userrow["phone_number"];
                            $userType = $userrow["user_type"];
                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $username; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $contactNumber; ?></td>
                                <td><?php echo $userType; ?></td>
                                <td>
                                <div class="d-flex justify-content-center">
                                  <a href="../settings/view_staff.php?id=<?php echo $id; ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $id; ?>">
                                   <i class="bx bx-show-alt"></i>
                                   </a>
                                 <a href="../settings/update_staff.php?id=<?php echo $id; ?>" class="btn btn-sm btn-info update-btn m-1" data-id="<?php echo $id; ?>">
                                 <i class="bi bi-pencil-square"></i>
                                </a>
                                </div>
                                </td>
                            </tr>
                        <?php } ?>
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
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
