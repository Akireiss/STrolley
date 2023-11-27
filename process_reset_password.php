<?php
session_start(); // Start the session

include 'db_conn.php';

function auditTrail($event_type, $details) {
    // You should replace 'your_database_credentials' with your actual database info
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

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

if (isset($_POST["token"]) && isset($_POST["password"])) {
    $token = $_POST["token"];
    $password = $_POST["password"];
    $encryptedPassword = md5($password); // Encrypt the new password

    // Debugging: Print the token value
    var_dump($token);

    // Check if the token is valid
    $sql = "SELECT * FROM password_reset_tokens WHERE token = '$token'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userId = $row["user_id"];

        // Update the user's password in the database
        $sql = "UPDATE users SET password = '$encryptedPassword' WHERE id = '$userId'";
        mysqli_query($con, $sql);

        // Delete the used token
        $sql = "DELETE FROM password_reset_tokens WHERE token = '$token'";
        mysqli_query($con, $sql);

        $event_type = "Reset Password"; // Change this to the appropriate event type
        $logDetails = "Reset Password";
        auditTrail($event_type, $logDetails);

        // Redirect the user to a password reset success page
        header("Location: password_reset_success.php");
        exit();
    } else {
        echo "<script>alert('Invalid token. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        
        form {
            width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: orange;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Password Reset Form -->
    <form method="post" action="">
        <label for="password">New Password:</label>
        <input type="password" name="password" required>
        
        <label for="token">Confirm Password:</label>
        <input type="text" name="token" required>

        <!-- Add the style attribute to make the button orange -->
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
