<?php
session_start(); // Start the session

include 'db_conn.php';

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

        // Redirect the user to a password reset success page
        header("Location: password_reset_success.php");
        exit();
    } else {
        echo "<script>alert('Invalid token. Please try again.');</script>";
    }
}
?>
