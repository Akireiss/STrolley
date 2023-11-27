<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 50vh; /* Minimum viewport height to center the card */
        }

        .card {
            width: 500px; /* Adjust the card width as needed */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            overflow: hidden; /* Prevents card content from overflowing */
        }

        .card-header {
            background-color: orange;
            color: #fff;
            text-align: center;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .card-body p {
            margin-bottom: 15px;
        }

        .text-center {
            text-align: center;
        }

        .btn-primary {
            background-color: orange;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">Password Reset Success</div>
            <div class="card-body">
                <p>Your password has been successfully reset.</p>
                <p>You can now log in using your new password.</p>
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary">Log In</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
