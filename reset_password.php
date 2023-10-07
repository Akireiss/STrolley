<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
                        <style>
                      .form-outline {
                        position: relative;
                      }

                      .eye-icon {
                        position: absolute;
                        top: 49%;
                        right: 40px;
                        transform: translateY(-50%);
                        cursor: pointer;
                        max-width: 20px;
                        max-height: 30px;
                      }
                    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-header">Reset Password</div>
                    <div class="card-body">
                        <?php
                        // Check if the token is present in the URL
                        if (isset($_GET["token"])) {
                            $token = $_GET["token"];
                            // You can also validate the token here if needed
                        } else {
                            // Handle the case where the token is missing or invalid
                            echo "<p>Invalid or missing token.</p>";
                            exit;
                        }
                        ?>

                        <form method="POST" action="process_reset_password.php">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" id="myInput" name="password" class="form-control form-control-lg" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" />
                    <label class="form-label" for="phoneNumber">Password</label>
                    <img class="eye-icon" src="img/eye.jpg" alt="Eye" onclick="togglePasswordVisibility()">  
                  </div>
                    <script>
                      function togglePasswordVisibility() {
                        var passwordInput = document.getElementById("myInput");
                        var eyeIcon = document.querySelector(".eye-icon");

                        if (passwordInput.type === "password") {
                          passwordInput.type = "text";
                          eyeIcon.src = "img/eye.jpg"; // Update the source to the open eye image
                        } else {
                          passwordInput.type = "password";
                          eyeIcon.src = "img/eye.jpg"; // Update the source to the closed eye image
                        }
                      }
                    </script>
                            </div>
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
