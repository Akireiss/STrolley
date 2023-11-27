<?php
// Include your database connection file
include "../db_conn.php";

// Fetch inventory data from the database
$sql = "SELECT * FROM inventory";
$result = $con->query($sql);

// Set the response header to indicate that this is a CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="inventory_data.csv"');

// Create a file handle for writing
$csvFile = fopen('php://output', 'w');

// Add a header row to the CSV
fputcsv($csvFile, ['Item Description', 'Address', 'Method', 'Unit Price', 'Quantity in Stocks', 'Unit of Measurement', 'Total Weight', 'Total Cost']);

// Loop through the inventory data and add it to the CSV
while ($item = $result->fetch_assoc()) {
    fputcsv($csvFile, [
        $item["item_description"],
        $item["address"],
        $item["inventory_valuation_method"],
        $item["unit_price"],
        $item["quantity_in_stocks"],
        $item["unit_of_measurement"],
        $item["total_weight"],
        $item["total_cost"]
    ]);
}

// Close the CSV file
fclose($csvFile);
?>
