<?php
session_start();

// Import database connection
include "../db_conn.php";

function auditTrail($event_type, $details, $con) {
    date_default_timezone_set('Asia/Manila');
    $timestamp = date("Y-m-d H:i:s");
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Unknown';

    $insertLogQuery = "INSERT INTO audit_log (timestamp, event_type, id, user_type, details) VALUES (?, ?, ?, ?, ?)";
    $auditStmt = $con->prepare($insertLogQuery);
    $auditStmt->bind_param("sssss", $timestamp, $event_type, $id, $user_type, $details);

    if ($auditStmt->execute()) {
        // Audit trail record inserted successfully
    } else {
        // Error inserting audit trail record
        $_SESSION['error'] = "Error inserting audit trail record: " . $con->error;
    }

    $auditStmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $region_id = $_POST['region_id'];
    $province_id = $_POST['province_id'];
    $municipality_id = $_POST['municipality_id'];
    $barangay_id = $_POST['barangay_id'];
    $sex = $_POST['sex'];
    $civil_status = $_POST['civil_status'];

    // Perform data validation
    // Add your validation logic here

    // Generate a random string for the RFID card using md5 and uniqid
    $rfid_card_number = mt_rand(1000000000, 9999999999); // Generate an 11-digit random number


    // Establish a database connection
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Use prepared statements to insert customer data
    $insertQuery = "INSERT INTO customer (first_name, middle_name, last_name, suffix, region_id, province_id, municipality_id, barangay_id, sex, civil_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $con->prepare($insertQuery);
    $insertStmt->bind_param("ssssssssss", $firstName, $middleName, $lastName, $suffix, $region_id, $province_id, $municipality_id, $barangay_id, $sex, $civil_status);

    if ($insertStmt->execute()) {
        // Insert successful, continue with RFID card insertion and audit trail
        $customer_id = $con->insert_id;

        // Use prepared statement for RFID card insertion
        $rfidQuery = "INSERT INTO rfid_cards (rfid_card_id, customer_id, balance) VALUES (?, ?, 0)";
        $rfidStmt = $con->prepare($rfidQuery);
        $rfidStmt->bind_param("ss", $rfid_card_number, $customer_id);

        if ($rfidStmt->execute()) {
            // RFID card insertion successful

            // Add an audit trail record for the customer addition
            $event_type = "Added Customer";
            $logDetails = "Added Customer: $firstName";
            auditTrail($event_type, $logDetails, $con);

            $_SESSION['success'] = "Customer added successfully!";
        } else {
            $_SESSION['error'] = "Error inserting RFID card data: " . $rfidStmt->error;
        }

        $rfidStmt->close();
    } else {
        $_SESSION['error'] = "Error inserting customer data: " . $insertStmt->error;
    }

    // Close the prepared statement and the database connection
    $insertStmt->close();
    $con->close();

    header("Location: ../customerRecords/tbl_customers.php");
    exit;
} else {
    $_SESSION['error'] = "Validation failed!";
    // Handle validation errors here
}
?>

<!-- 
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">Customer Registration</h5>
                        <!-- <p class="text-muted">Complete the form below to add new staff</p> -->
                    </div>
                    <form class="row g-3 needs-validation" action="#" method="post" novalidate>
                        <div class="col-md-3 position-relative">
                            <label class="form-label">First_Name<font color="red">*</font></label>
                            <input type="text" class="form-control" id="validationTooltip01" name="name" required autofocus="autofocus">
                            <div class="invalid-tooltip">
                                First name field is required.
                            </div>
                        </div>

                        <div class="col-md-3 position-relative">
                            <label class="form-label">Middle_Name</label>
                            <input type="text" class="form-control" id="validationTooltip01" name="name">
                        </div>

                        <div class="col-md-3 position-relative">
                            <label class="form-label">Last_Name<font color="red">*</font></label>
                            <input type="text" class="form-control" id="validationTooltip01" name="name" required autofocus="autofocus">
                            <div class="invalid-tooltip">
                                Last name field is required.
                            </div>
                        </div>

                        <div class="col-md-3 position-relative">
                            <label class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="validationTooltip01" name="name">
                        </div>

                        <!-- <div class="col-md-6 position-relative">
                            <label class="form-label">Address<font color="red">*</font></label> 
                            <input type="text" class="form-control" id="validationTooltip01" name="username" required>
                            <div class="invalid-tooltip">
                                Address field is required.
                            </div>
                        </div> -->

    <!-- <form class="row g-3 needs-validation" novalidate action = "#" enctype="multipart/form-data" method="POST">
        <div class="col-md-3 position-relative">
            <label class="form-label">Region<font color = "red">*</font></label>
                <div class="col-sm-12">
                    <select class="form-select" aria-label="Default select example" name = "region" id="region" required>
                      <option value="" selected disabled>Select Region</option>
                      <?php
                        $sql = "SELECT * FROM region";
                        $result=mysqli_query($con, $sql);
                        while($row=mysqli_fetch_array($result)){
                          echo ucwords('<option value="'.$row['regCode'].'">' . $row['regDesc'] . '</option>');
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-3 position-relative">
                  <label class="form-label">Province<font color = "red">*</font></label>
                  <div class="col-sm-12">
                    <select class="form-select" aria-label="Default select example" name = "province" id="province" required>
                      <option value="" selected disabled>Select Province</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-3 position-relative">
                  <label class="form-label">City/Municipality<font color = "red">*</font></label>
                  <div class="col-sm-12">
                    <select class="form-select" aria-label="Default select example" name = "municipality" id="city" required>
                      <option value="" selected disabled>Select City</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-3 position-relative">
                  <label class="form-label">Barangay<font color = "red">*</font></label>
                  <div class="col-sm-12">
                    <select class="form-select" aria-label="Default select example" name = "barangay" id="barangay" required>
                      <option value="" selected disabled>Select Barangay</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-6 position-relative">
                    <label class="form-label">Sex<font color="red">*</font></label>
                    <div class="col-sm-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" id="sexMale" value="Male" required>
                            <label class="form-check-label" for="sexMale">
                                Male
                            </label>
                        </div>
                 <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" id="sexFemale" value="Female">
                            <label class="form-check-label" for="sexFemale">
                                Female
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" id="sexOther" value="Other">
                            <label class="form-check-label" for="sexOther">
                                Other
                            </label>
                        </div>
                        <div class="invalid-tooltip">
                            Sex field is required.
                        </div>
                    </div>
                </div>

                        <div class="col-md-6 position-relative">
                            <label class="form-label">Civil Status<font color="red">*</font></label>
                            <div class="col-sm-12">
                                <select class="form-select" aria-label="Default select example" name="user_type" id="validationTooltip03" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="Admin">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Others">Others</option>
                                </select>
                                <div class="invalid-tooltip">
                                    Civil Status field is required.
                                </div>
                            </div>
                        </div>


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
$(document).ready(function(){
  $("#region").on('change',function(){
    var regionId = $(this).val();
    if(regionId){
            $.ajax({
                type:'POST',
                url:'ajaxData.php',
                data:'regionId='+regionId,
                success:function(html){
                    $('#province').html(html);
                    $('#city').html('<option value="">Select City</option>'); 
                }
            }); 
        }
  });

  $('#province').on('change', function(){
        var provinceId = $(this).val();
        if(provinceId){
            $.ajax({
                type:'POST',
                url:'ajaxData.php',
                data:'provinceId='+provinceId,
                success:function(html){
                    $('#city').html(html);
                    $('#barangay').html('<option value="">Select Barangay</option>');
                }
            }); 
        }else{
            $('#barangay').html('<option value="">Select Barangay</option>'); 
        }
    });

  $('#city').on('change', function(){
        var cityId = $(this).val();
        if(cityId){
            $.ajax({
                type:'POST',
                url:'ajaxData.php',
                data:'cityId='+cityId,
                success:function(html){
                    $('#barangay').html(html);
                }
            }); 
        }
    });
});
</script>
</body>

</html> -->
