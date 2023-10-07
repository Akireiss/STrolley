<?php
// Include your database connection
include '../db_conn.php';

// Fetch categories from the database
$sql = "SELECT * FROM categories";
$result = $con->query($sql);

$categories = array();
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Return categories as JSON response
header('Content-Type: application/json');
echo json_encode($categories);
?>
