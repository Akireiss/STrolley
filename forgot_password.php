<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Smart Shopping Trolley</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    
    <style>
        /* Your custom CSS styles for the "Forgot Password" form */
        .container {
            margin-top: 50px;
        }
        
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 50%;
        }
        
        .card-header {
            background-color: #fcb768;
            color: #121211;
            font-weight: bold;
            text-align: center;
            
        }
        
        .card-body {
            padding: 20px;
        }
        
        .form-group label {
            font-weight: bold;
        }
        
        .form-control {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            width: 80%;
            margin-bottom: 15px;
        }
        
        .btn-primary {
            background-color: #fcb768;
            border: none;
            border-radius: 4px;
            color: #fff;
            padding: 10px 20px;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .mt-5 {
            margin-top: 1.25rem;
        }
    </style>
</head>

<body>
    <center>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-header">Forgot Password</div>
                    <div class="card-body">
                        <form method="POST" action="send_reset_link.php">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</center>

    <!-- JavaScript to display the alert -->
    <script>
        <?php
        if (isset($_POST["email"]) && ($result && mysqli_num_rows($result) === 0)) {
            echo "alert('Email not found. Please try again.');";
        }
        ?>
    </script>
</body>

</html>
