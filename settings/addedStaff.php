<?php
session_start(); 

include "../db_conn.php";

if (
    isset($_POST['name'], $_POST['username'], $_POST['email'], $_POST['phone_number'], $_POST['user_type'], $_POST['password'])
) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pnumber = $_POST['phone_number'];
    $utype = $_POST['user_type'];
    $password = md5($_POST['password']);

    // Check if the email or username already exists in the database
    $checkSql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $result = $con->query($checkSql);

    if ($result->num_rows > 0) {
        // Duplicate entry found
        header("Location: ../register.php?error=Duplicate entry found for email or username.");
        exit;
    }

    // Insert the data into the database
    $insertSql = "INSERT INTO users (name, email, phone_number, username, user_type, password) VALUES ('$name', '$email', '$pnumber', '$username', '$utype', '$password')";
    if ($con->query($insertSql) === true) {
        $_SESSION['success'] = "User Added successfully.";
        header("Location: ../settings/tbl_staff.php");
        exit;
    } else {
        $_SESSION['error'] = "User Added Failed.";
        header("Location: ../register.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Missing required fields.";
    header("Location: ../register.php");
    exit;
}
?>

