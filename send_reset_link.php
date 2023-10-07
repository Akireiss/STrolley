<?php
session_start(); // Start the session

require 'db_conn.php';

// Include the necessary PHPMailer namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if (isset($_POST["email"])) {
    $email = $_POST["email"];

    // Check if the email exists in your database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $users = mysqli_fetch_assoc($result);

        // Generate a unique token (e.g., a random string)
        $resetToken = bin2hex(random_bytes(16));

        // Store the token and user ID in the database for password reset verification
        $userId = $users["id"];
        $sql = "INSERT INTO password_reset_tokens (user_id, token) VALUES ('$userId', '$resetToken')";
        mysqli_query($con, $sql);

        // Compose the email
        $resetLink = "http://localhost/STrolley/reset_password.php?token=$resetToken";

        // Create a PHPMailer instance
        $mail = new PHPMailer(true); // Enable exceptions

        try {
            // Configure SMTP settings (adjust these settings according to your email provider)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server address
            $mail->SMTPAuth = true;
            $mail->Username = 'valdeznicole104@gmail.com'; // Your SMTP username
            $mail->Password = 'eojjrfsplnpxefcb'; // Use an environment variable here
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Set sender and recipient
            $mail->setFrom('valdeznicole104@gmail.com', 'Gerix Ann V. Antolin'); // Sender's email and name
            $mail->addAddress($email, $users["username"]); // Recipient's email and name

            // Email subject and content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Link';
            $mail->Body = 'Hello ' . $users["username"] . ',<br><br>';
            $mail->Body .= 'We received a request to reset the password for your account. ';
            $mail->Body .= 'To complete the password reset process, please follow the instructions below:<br><br>';
            $mail->Body .= '1. Click on the following link to reset your password:<br>';
            $mail->Body .= '<a href="' . $resetLink . '">Reset Password</a><br><br>';
            $mail->Body .= '2. You will be directed to a page where you can create a new password for your account.<br><br>';
            $mail->Body .= '3. If you did not request this password reset, please ignore this email. Your account remains secure.<br><br>';
            $mail->Body .= 'Thank you for using our services.<br><br>';
            $mail->Body .= 'Best regards,<br>Balaoan Public Market';

            // Send the email
            $mail->send();

            // Redirect the user to a confirmation page
            header("Location: password_reset_confirmation.php");
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Error: " . $e->getMessage() . "');</script>";
        }
    } else {
        // JavaScript alert when email is not found
        echo "<script>alert('Email not found. Please try again.');</script>";
    }
}

?>
