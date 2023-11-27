<?php
// Start the session
session_start();

include_once('../db_conn.php');

function auditTrail($event_type, $details)
{
    // Replace 'your_database_credentials' with your actual database info
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    date_default_timezone_set('Asia/Manila');
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

// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: tbl_customers.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM customer WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the customer exists
if ($result->num_rows !== 1) {
    header("Location: tbl_customers.php");
    exit;
}

$customer = $result->fetch_assoc();

// Check if the form is submitted for updating customer details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the form data
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $suffix = mysqli_real_escape_string($con, $_POST['suffix']);
    $province_id = mysqli_real_escape_string($con, $_POST['province_id']);
    $municipality_id = mysqli_real_escape_string($con, $_POST['municipality_id']);
    $barangay_id = mysqli_real_escape_string($con, $_POST['barangay_id']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
    $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);

    // Update customer details in the database using prepared statements
    $updateSql = "UPDATE customer SET 
        first_name = ?,
        middle_name = ?,
        last_name = ?,
        suffix = ?,
        province_id = ?,
        municipality_id = ?,
        barangay_id = ?,
        sex = ?,
        civil_status = ?
        WHERE id = ?";

    $stmt = $con->prepare($updateSql);
    $stmt->bind_param("sssssssssi", $first_name, $middle_name, $last_name, $suffix, $province_id, $municipality_id, $barangay_id, $sex, $civil_status, $id);

    if ($stmt->execute()) {
        $event_type = "Update Customer"; // Change this to the appropriate event type
        $logDetails = "Updated Customer ID: $id";
        auditTrail($event_type, $logDetails);
        $_SESSION['success'] = "Customer details updated successfully";
        header("Location: tbl_customers.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating customer details: " . $stmt->error;
    }
}

// Fetch the data for the Region dropdown
$sql = "SELECT regCode, regDesc FROM region";
$regionsResult = mysqli_query($con, $sql);

// Fetch the data for the Province dropdown based on the selected region
$selectedRegionCode = $customer['region_id']; // Assuming you have this value from the customer record
$provinceSql = "SELECT provCode, provDesc FROM province WHERE regCode = ?";
$provinceStmt = $con->prepare($provinceSql);
$provinceStmt->bind_param("s", $selectedRegionCode);
$provinceStmt->execute();
$provinceResult = $provinceStmt->get_result();

// Fetch the data for the Municipality dropdown based on the selected province
$selectedProvinceCode = $customer['province_id']; // Assuming you have this value from the customer record
$municipalitySql = "SELECT citymunCode, citymunDesc FROM municipality WHERE provCode = ?";
$municipalityStmt = $con->prepare($municipalitySql);
$municipalityStmt->bind_param("s", $selectedProvinceCode);
$municipalityStmt->execute();
$municipalityResult = $municipalityStmt->get_result();

// Fetch the data for the Barangay dropdown based on the selected municipality
$selectedMunicipalityCode = $customer['municipality_id']; // Assuming you have this value from the customer record
$barangaySql = "SELECT brgyCode, brgyDesc FROM barangay WHERE citymunCode = ?";
$barangayStmt = $con->prepare($barangaySql);
$barangayStmt->bind_param("s", $selectedMunicipalityCode);
$barangayStmt->execute();
$barangayResult = $barangayStmt->get_result();

$selectedBarangayCode = $customer['barangay_id']; // Assuming you have this value from the customer record
$barangaySql = "SELECT brgyCode, brgyDesc FROM barangay WHERE citymunCode = ?";
$barangayStmt = $con->prepare($barangaySql);
$barangayStmt->bind_param("s", $selectedMunicipalityCode); // Use the same selectedMunicipalityCode
$barangayStmt->execute();
$barangayResult = $barangayStmt->get_result();

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Customer Details</h5>
                </div>
                <form class="row g-3 needs-validation" action="" method="post" novalidate>
                    <!-- First Name Field -->
                    <div class="col-md-3 position-relative">
                        <label class="form-label">First Name<font color="red">*</font></label>
                        <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($customer['first_name']); ?>" required autofocus="autofocus">
                        <div class="invalid-tooltip">
                            First name field is required.
                        </div>
                    </div>

                    <!-- Middle Name Field -->
                    <div class="col-md-3 position-relative">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($customer['middle_name']); ?>" name="middle_name">
                    </div>

                    <!-- Last Name Field -->
                    <div class="col-md-3 position-relative">
                        <label class="form-label">Last Name<font color="red">*</font></label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($customer['last_name']); ?>" required>
                        <div class="invalid-tooltip">
                            Last name field is required.
                        </div>
                    </div>

                    <!-- Suffix Field -->
                    <div class="col-md-3 position-relative">
                        <label class="form-label">Suffix</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($customer['suffix']); ?>" name="suffix">
                    </div>

                    <!-- Region Field -->
                    <div class="col-md-3 position-relative">
                        <label class="form-label">Region<font color="red">*</font></label>
                        <select class="form-select" aria-label="Default select example" name="region_id" id="region" required>
                            <option value="" selected disabled>Select Region</option>
                            <?php
                            while ($row = mysqli_fetch_array($regionsResult)) {
                                $selected = ($row['regCode'] == $selectedRegionCode) ? 'selected' : '';
                                echo '<option value="' . $row['regCode'] . '" ' . $selected . '>' . htmlspecialchars($row['regDesc']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Province Field -->
                    <div class="col-md-3 position-relative">
                        <label class="form-label">Province<font color="red">*</font></label>
                        <select class="form-select" aria-label="Default select example" name="province_id" id="province" required>
                            <option value="" selected disabled>Select Province</option>
                            <?php
                            while ($row = $provinceResult->fetch_assoc()) {
                                $selected = ($row['provCode'] == $selectedProvinceCode) ? 'selected' : '';
                                echo '<option value="' . $row['provCode'] . '" ' . $selected . '>' . htmlspecialchars($row['provDesc']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- City/Municipality Field -->
                    <div class="col-md-3 position-relative">
                        <label class="form-label">City/Municipality<font color="red">*</font></label>
                        <select class="form-select" aria-label="Default select example" name="municipality_id" id="city" required>
                            <option value="" selected disabled>Select City</option>
                            <?php
                            while ($row = $municipalityResult->fetch_assoc()) {
                                $selected = ($row['citymunCode'] == $selectedMunicipalityCode) ? 'selected' : '';
                                echo '<option value="' . $row['citymunCode'] . '" ' . $selected . '>' . htmlspecialchars($row['citymunDesc']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Barangay Field -->
                    <div class="col-md-3 position-relative">
    <label class="form-label">Barangay<font color="red">*</font></label>
    <select class="form-select" aria-label="Default select example" name="barangay_id" id="barangay" required>
        <option value="" selected disabled>Select Barangay</option>
        <?php
        while ($row = $barangayResult->fetch_assoc()) {
            $brgyCode = $row['brgyCode'];
            $brgyDesc = $row['brgyDesc'];
            $selected = ($brgyCode == $selectedBarangayCode) ? 'selected' : '';
            echo '<option value="' . $brgyCode . '" ' . $selected . '>' . htmlspecialchars($brgyDesc) . '</option>';
        }
        ?>
    </select>
</div>

                    <!-- Sex Field -->
                    <div class="col-md-6 position-relative">
                        <label class="form-label">Sex<font color="red">*</font></label>
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sex" id="sexMale" value="Male" required <?php if ($customer['sex'] === 'Male') echo 'checked'; ?>>
                                <label class="form-check-label" for="sexMale">
                                    Male
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sex" id="sexFemale" value="Female" <?php if ($customer['sex'] === 'Female') echo 'checked'; ?>>
                                <label class="form-check-label" for="sexFemale">
                                    Female
                                </label>
                            </div>
                            <div class="invalid-tooltip">
                                Sex field is required.
                            </div>
                        </div>
                    </div>

                    <!-- Civil Status Field -->
                    <div class="col-md-6 position-relative">
                        <label class="form-label">Civil Status<font color="red">*</font></label>
                        <div class="col-sm-12">
                            <select class="form-select" aria-label="Default select example" name="civil_status" id="civil_status" required>
                                <option value="" selected disabled>Select Status</option>
                                <option value="Single" <?php echo ($customer['civil_status'] === 'Single') ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo ($customer['civil_status'] === 'Married') ? 'selected' : ''; ?>>Married</option>
                            </select>
                            <div class="invalid-tooltip">
                                Civil Status field is required.
                            </div>
                        </div>
                    </div>

                    <!-- Save and Cancel Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-warning mb-3" name="submit">Save Customer</button>
                        <a href="tbl_customers.php" class="btn btn-primary mb-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php' ?>

<script>
    $(document).ready(function() {
        $("#region").on('change', function() {
            var regionId = $(this).val();
            if (regionId) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxData.php', // Replace with your AJAX handler
                    data: 'regionId=' + regionId,
                    success: function(html) {
                        $('#province').html(html);
                        $('#city').html('<option value="">Select City</option>');
                        $('#barangay').html('<option value="">Select Barangay</option>');
                    }
                });
            }
        });

        $('#province').on('change', function() {
            var provinceId = $(this).val();
            if (provinceId) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxData.php', // Replace with your AJAX handler
                    data: 'provinceId=' + provinceId,
                    success: function(html) {
                        $('#city').html(html);
                        $('#barangay').html('<option value="">Select Barangay</option>');
                    }
                });
            }
        });

        $('#city').on('change', function() {
            var cityId = $(this).val();
            if (cityId) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxData.php', // Replace with your AJAX handler
                    data: 'cityId=' + cityId,
                    success: function(html) {
                        $('#barangay').html(html);
                    }
                });
            }
        });
    });
</script>
</body>

</html>