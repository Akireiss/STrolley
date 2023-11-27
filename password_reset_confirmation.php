<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Confirmation</title>
    
    <!-- Add your CSS styles inline -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            background-color: orange;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: orange;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password Reset Confirmation</h1>
        <p>Your password reset request has been processed successfully.</p>
        <p>An email with instructions has been sent to your email address.</p>
        <p>Please check your email and follow the instructions to reset your password.</p>
        <p>If you don't receive an email within a few minutes, please check your spam folder.</p>
        <br>
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>
