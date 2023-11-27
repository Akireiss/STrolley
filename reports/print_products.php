<?php
session_start();

// Import database connection
include "../db_conn.php";

$sql = "SELECT * FROM products";
$result = $con->query($sql);

// Check if any products are found
if ($result->num_rows === 0) {
    // Handle case when no products are found
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="../assets/vendor/simple-datatables/simple-datatables.css">
    <style>
        /* Add your custom CSS styles here for the print version */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
        }

        thead {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>List of Products</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Item</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $product["id"]; ?></td>
                    <td><?php echo $product["item_description"]; ?></td>
                    <td><?php echo $product["unit_price"]; ?></td>
                    <td><?php echo $product["quantity"]; ?></td>
                    <td><?php echo $product["weight"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
