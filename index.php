<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif; /* Change the font if needed */
            background-color: #f5f5f5; /* Background color for the remaining space */
        }

        .container {
            display: flex;
            width: 80%; /* Adjust the width of the container as needed */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }

        .image-container {
            flex: 1;
            overflow: hidden;
        }

        .image-container img {
            width: 97%;
            height: 100%;
            /* object-fit: cover; */
        }

        .login-container {
            flex: 1;
            padding: 30px;
            text-align: center;
            margin-right: 10px;
        }

        .logo img {
            max-width: 150px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .password-container {
            position: relative;
        }

        .fa-eye,
        .fa-eye-slash {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .alert {
            background-color: #e74c3c;
            color: #fff;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #3498db;
            font-size: 16px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="image-container">
            <img src="assets/img/quick.jpg" alt="Background Image">
        </div>
        <div class="login-container">
            <div class="logo" style="margin-top: 30px;">
                <img src="assets/img/DMMMSU.png" alt="Logo">
            </div>
            <h2 style="margin-top: -20px;">Admin Login</h2>

            <form action="php/login.php" method="POST">
            <?php
              session_start();
              if (isset($_SESSION['error'])) {
                  echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                  unset($_SESSION['error']);
              }
              ?>

                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group password-container">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <i class="fas fa-eye" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                </div>
                <button class="btn">Login</button>
                <!-- <a href="forgot_password.php">Forgot Password</a> -->
            </form>
        </div>
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
