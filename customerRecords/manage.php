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



// Check if the form is submitted
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $province = $_POST['province'];
    $municipality = $_POST['municipality'];
    $barangay = $_POST['barangay'];
    $sex = $_POST['sex'];
    $civil_status = $_POST['civil_status'];

    $updateSql = "UPDATE customer SET first_name='$first_name', middle_name='$middle_name', last_name='$last_name', suffix='$suffix', province='$province', municipality='$municipality', barangay='$barangay', sex='$sex', civil_status='$civil_status' WHERE id='$id'";

    if ($con->query($updateSql) === TRUE) {
        $_SESSION['success'] = "Customer Updated successfully.";
        header("Location: ../table/customer.php");
        exit;
    } else {
        $_SESSION['error'] = "Customer Not Updated" . $con->error;
    }
}

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM customer WHERE id='$id'";
    $result = $con->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        $suffix = $row['suffix'];
        $permanent_address = $row['permanent_address'];
        $civil_status = $row['civil_status'];
    } else {
        // Handle case when user is not found
        $_SESSION['error'] = "User not found.";
        header("Location: ../table/customer.php");
        exit;
    }
} else {
    // Handle case when 'id' parameter is missing
    $_SESSION['error'] = "User ID is missing.";
    header("Location: ../table/customer.php");
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
                <div class="card-body">
                    <h5 class="card-title">Edit Customer</h5>
                </div>
                <form class="row g-3" action="edit.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="col-md-3 position-relative">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" required>
                    </div>

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="<?php echo $middle_name; ?>">
                    </div>

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>" required>
                    </div>

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Suffix</label>
                        <input type="text" class="form-control" name="suffix" value="<?php echo $suffix; ?>">
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">Permanent Address</label>
                        <input type="text" class="form-control" name="permanent_address" value="<?php echo $permanent_address; ?>" required>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">Civil Status</label>
                        <select class="form-select" aria-label="Default select example" name="civil_status" required>
                            <option value="" selected disabled>Select Status</option>
                            <option value="Single" <?php if ($civil_status === 'Single') echo 'selected'; ?>>Single</option>
                            <option value="Married" <?php if ($civil_status === 'Married') echo 'selected'; ?>>Married</option>
                            <option value="Others" <?php if ($civil_status === 'Others') echo 'selected'; ?>>Others</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-warning mb-3" name="submit">Update Customer</button>
                        <a href="../table/customer.php" class="btn btn-primary mb-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
