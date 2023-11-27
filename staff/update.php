<?php
session_start();
include "../db_conn.php";

function auditTrail($event_type, $details) {
    // You should replace 'your_database_credentials' with your actual database info
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

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

if (isset($_GET['id'])) {
    $id = $_GET["id"];

    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = $con->query($sql);

    $event_type = "Update Staff"; // Change this to the appropriate event type
    $logDetails = "Update Staff";
    auditTrail($event_type, $logDetails);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $username = $row['username'];
        $email = $row['email'];
        $phone_number = $row['phone_number'];
        $user_type = $row['user_type'];
    } else {
        // Handle case when user is not found
        echo "User not found.";
        exit;
    }
} else {
    // Handle case when id parameter is missing
    echo "User ID is missing.";
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
                    <h5 class="card-title">Edit User</h5>
                    <!-- <p class="text-muted">Complete the form below to update staff details</p> -->
                </div>
                <form class="row g-3 needs-validation" action="manage.php" method="POST" novalidate>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="col-md-12 position-relative">
                        <label class="form-label">Name<font color="red">*</font></label>
                        <input type="text" class="form-control" id="validationTooltip01" name="name" required autofocus="autofocus" value="<?php echo $name; ?>">
                        <div class="invalid-tooltip">
                            The Fullname field is required.
                        </div>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">Username<font color="red">*</font></label>
                        <input type="text" class="form-control" id="validationTooltip01" name="username" required  value="<?php echo $username; ?>">
                        <div class="invalid-tooltip">
                            The Username field is required.
                        </div>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">Email Address<font color="red">*</font></label>
                        <input type="email" class="form-control" id="validationTooltip01" name="email" required  value="<?php echo $email; ?>">
                        <div class="invalid-tooltip">
                            The Email Address field is required.
                        </div>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">Phone Number (Format: 09XXXXXXXXX)<font color="red">*</font></label>
                        <input type="text" class="form-control" id="validationTooltip01" name="phone_number" maxlength="11" required  value="<?php echo $phone_number; ?>">
                        <div class="invalid-tooltip">
                            The Contact Number field is required.
                        </div>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">User Type<font color="red">*</font></label>
                        <div class="col-sm-12">
                            <select class="form-select" aria-label="Default select example" name="user_type" id="validationTooltip03" required>
                                <option value="" selected disabled>Select User Type</option>
                                <option value="Admin" <?php echo ($user_type == 'Admin') ? 'selected' : ''; ?>>Administrator</option>
                                <option value="Staff" <?php echo ($user_type == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                                <option value="Brgy. Secretary" <?php echo ($user_type == 'Brgy. Secretary') ? 'selected' : ''; ?>>Brgy. Secretary</option>
                            </select>
                            <div class="invalid-tooltip">
                                The User Type field is required.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-warning mb-3" name="submit">Save User</button>
                        <button type="reset" class="btn btn-primary mb-3" onclick="history.back()">Back</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
