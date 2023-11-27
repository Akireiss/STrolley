<?php
session_start();

include_once('../db_conn.php');

// Generate CSV data
$csvData = "ID,Name,Address,Sex,Civil Status\n";

$sql = "SELECT c.id, 
               CONCAT(c.first_name, ' ', c.middle_name, ' ', c.last_name, ' ', c.suffix) AS full_name,
               CONCAT(b.brgyDesc, ', ', m.citymunDesc, ', ', p.provDesc) AS address,
               c.sex,
               c.civil_status
        FROM customer AS c
        LEFT JOIN barangay AS b ON c.barangay_id = b.brgyCode
        LEFT JOIN municipality AS m ON c.municipality_id = m.citymunCode
        LEFT JOIN province AS p ON c.province_id = p.provCode";

$result = $con->query($sql);

while ($customer = $result->fetch_assoc()) {
    $csvData .= $customer['id'] . ',';
    $csvData .= '"' . $customer['full_name'] . '",';
    $csvData .= '"' . $customer['address'] . '",';
    $csvData .= $customer['sex'] . ',';
    $csvData .= $customer['civil_status'] . "\n";
}

// Set the headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="customer_data.csv');

// Output the CSV data
echo $csvData;
exit;
?>
