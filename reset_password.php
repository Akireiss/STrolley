<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Reset Password</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <style>
       .container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        text-align: center; /* Center text content */
    }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: orange;
            color: #fff;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 15px;
        }

        .card-body {
            height: 150px;
            width: 300px;
            padding: 20px;
        }

        .form-group {
            position: relative;
        }

        .form-control {
            border-radius: 8px;
        }

        .show-password-checkbox {
            position: absolute;
            margin-top: 12px;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            
        }

        button[type="submit"] {
            margin-top: 30px;
            background-color: orange;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-control{
          width: 300px;
          height: 25px;
        }

        button[type="submit"]:hover {
            background-color: orange;
        }
    </style>
</head>

<body>
    <div class="container">
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
                            <div class="input-group">
                                <input type="password" id="myInput" name="password" class="form-control" minlength="8" />
                                <div class="input-group-append">
                                    <label class="show-password-checkbox">
                                        <input type="checkbox" onclick="togglePasswordVisibility()">
                                        Show Password
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("myInput");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>

</html>
