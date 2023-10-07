<?php
session_start(); 

include "../db_conn.php";

if (
    isset($_POST['name'], $_POST['username'], $_POST['email'], $_POST['phone_number'], $_POST['user_type'])
) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pnumber = $_POST['phone_number'];
    $utype = $_POST['user_type'];

    // Check if the email or username already exists in the database
    $checkSql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $result = $con->query($checkSql);

    if ($result->num_rows > 0) {
        // Duplicate entry found
        header("Location: ../register.php?error=Duplicate entry found for email or username.");
        exit;
    }

    // Insert the data into the database
    $insertSql = "INSERT INTO users (name, email, phone_number, username, user_type) VALUES ('$name', '$email', '$pnumber', '$username', '$utype')";
    if ($con->query($insertSql) === true) {
        $_SESSION['success'] = "User Added successfully.";
        header("Location: ../table/staff.php");
        exit;

    // After successful staff registration
        $staffName = $_POST['name'];
        $staffUsername = $_POST['username'];
        $userType = $_POST['user_type'];
        $userId = $_SESSION['user_id']; // Assuming you have a user ID in session

        // Prepare the log message
        $logDetails = "Added staff: $staffName (Username: $staffUsername)";

        // Insert the audit trail record
        $insertLogQuery = "INSERT INTO audit_log (timestamp, action, user_id, user_type, details) VALUES (NOW(), 'Staff Registration', ?, ?, ?)";
        $stmt = $con->prepare($insertLogQuery);
        $stmt->bind_param("iss", $userId, $userType, $logDetails);
        $stmt->execute();
        
        // Retrieve audit trail records
        $auditQuery = "SELECT * FROM audit_log ORDER BY timestamp DESC";
        $auditResult = $con->query($auditQuery);

        // Display audit trail records
        while ($row = $auditResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['timestamp'] . "</td>";
            echo "<td>" . $row['action'] . "</td>";
            echo "<td>" . $row['user_type'] . "</td>";
            echo "<td>" . $row['details'] . "</td>";
            echo "</tr>";
        }


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

