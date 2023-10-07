<?php
// Start the session
session_start(); // s

include_once('../db_conn.php');
// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

?>

<?php include "../db_conn.php" ?>
<?php include '../includes/header.php' ?>

    <?php include '../includes/navbar.php' ?>
    <?php include '../includes/sidebar.php' ?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">Customer Registration</h5>
                        <!-- <p class="text-muted">Complete the form below to add new staff</p> -->
                    </div>
                    <form class="row g-3 needs-validation" action="../php/addedCustomer.php" method="post" novalidate>
                        <div class="col-md-3 position-relative">
                            <label class="form-label">First_Name<font color="red">*</font></label>
                            <input type="text" class="form-control" id="validationTooltip01" name="first_name" required autofocus="autofocus">
                            <div class="invalid-tooltip">
                                First name field is required.
                            </div>
                        </div>

                        <div class="col-md-3 position-relative">
                            <label class="form-label">Middle_Name</label>
                            <input type="text" class="form-control" id="validationTooltip01" name="middle_name">
                        </div>

                        <div class="col-md-3 position-relative">
                            <label class="form-label">Last_Name<font color="red">*</font></label>
                            <input type="text" class="form-control" id="validationTooltip01" name="last_name" required autofocus="autofocus">
                            <div class="invalid-tooltip">
                                Last name field is required.
                            </div>
                        </div>

                        <div class="col-md-3 position-relative">
                            <label class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="validationTooltip01" name="suffix">
                        </div>

                        <!-- <div class="col-md-6 position-relative">
                            <label class="form-label">Address<font color="red">*</font></label> 
                            <input type="text" class="form-control" id="validationTooltip01" name="username" required>
                            <div class="invalid-tooltip">
                                Address field is required.
                            </div>
                        </div> -->

                            <div class="col-md-3 position-relative">
                                <label class="form-label">Region<font color = "red">*</font></label>
                                    <div class="col-sm-12">
                                        <select class="form-select" aria-label="Default select example" name = "region_id" id="region" required>
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
                                        <select class="form-select" aria-label="Default select example" name = "province_id" id="province" required>
                                          <option value="" selected disabled>Select Province</option>
                                        </select>
                                      </div>
                                    </div>

                                    <div class="col-md-3 position-relative">
                                      <label class="form-label">City/Municipality<font color = "red">*</font></label>
                                      <div class="col-sm-12">
                                        <select class="form-select" aria-label="Default select example" name = "municipality_id" id="city" required>
                                          <option value="" selected disabled>Select City</option>
                                        </select>
                                      </div>
                                    </div>

                                    <div class="col-md-3 position-relative">
                                      <label class="form-label">Barangay<font color = "red">*</font></label>
                                      <div class="col-sm-12">
                                        <select class="form-select" aria-label="Default select example" name = "barangay_id" id="barangay" required>
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
                                                    M
                                                </label>
                                            </div>
                                     <div class="form-check">
                                                <input class="form-check-input" type="radio" name="sex" id="sexFemale" value="Female">
                                                <label class="form-check-label" for="sexFemale">
                                                    F
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
                                                    <select class="form-select" aria-label="Default select example" name="civil_status" id="validationTooltip03" required>
                                                        <option value="" selected disabled>Select Status</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>                                
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
</html>
