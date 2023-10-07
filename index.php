<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link to your custom CSS file -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            background-image: url('assets/img/back.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .login-container {
            width: 500px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }

        .logo img {
            max-width: 100px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .password-container {
            position: relative;
        }

        .fa-eye, .fa-eye-slash {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: #007bff;
            margin-top: 10px; /* Add margin-top here for the "Forgot Password" link */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="assets/img/DMMMSU.png" alt="">
        </div>
        <h2>Admin Login</h2>
        
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>

        <form action="php/login.php" method="POST">
            <div class="form-group">
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group password-container">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <i class="fas fa-eye" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
            <button class="btn">Login</button>
            <a href="forgot_password.php" >Forgot Password</a>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var toggleIcon = document.getElementById("togglePassword");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>