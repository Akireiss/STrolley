<?php
// Include your database connection code here

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $barcode = $_POST["barcode"];

    // Perform a database query to fetch product information based on the barcode
    // Replace these lines with your database connection and query
include_once('../db_conn.php');

    $query = "SELECT item_description, unit_price, weight, quantity FROM products WHERE barcode = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response = [
            'item_description' => $row['item_description'],
            'unit_price' => $row['unit_price'],
            'weight' => $row['weight'],
            'quantity' => $row['quantity'],
        ];
        echo json_encode($response);
    } else {        
        echo json_encode(['error' => 'Product not found']);
    }

    $stmt->close();
    $con->close();
}
?>