<?php
session_start();
include "../db_conn.php";

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $user_type = $_POST['user_type'];

    $updateSql = "UPDATE users SET name='$name', username='$username', email='$email', phone_number='$phone_number', user_type='$user_type' WHERE id='$id'";

    if ($con->query($updateSql) === TRUE) {
        $_SESSION['success'] = "User Updated successfully.";
        header("Location: ../table/staff.php");
        exit;
    } else {
        $_SESSION['error'] = "User Not Updated" . $con->error;
    }
}
?>
