<?php
// Import database connection
include "../db_conn.php";

// Create or open a new file for writing (in this case, a CSV file)
$csvFileName = 'products.csv';
$csvFile = fopen($csvFileName, 'w');

// Add the CSV header (column names)
$csvHeader = array("ID", "Product Item", "Unit Price", "Quantity", "Weight");
fputcsv($csvFile, $csvHeader);

// Query the database for product data
$sql = "SELECT id, item_description, unit_price, quantity, weight FROM products";
$result = $con->query($sql);

// Loop through the database results and write them to the CSV file
while ($product = $result->fetch_assoc()) {
    $data = array($product['id'], $product['item_description'], $product['unit_price'], $product['quantity'], $product['weight']);
    fputcsv($csvFile, $data);
}

// Close the CSV file
fclose($csvFile);

// Set the HTTP response headers to force download
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=' . $csvFileName);
header('Cache-Control: max-age=0');

// Output the CSV file to the browser
readfile($csvFileName);

// Delete the temporary CSV file
unlink($csvFileName);
