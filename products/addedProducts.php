<?php
session_start();
include "../db_conn.php";

function auditTrail($event_type, $details) {
    $con = new mysqli('localhost', 'root', '', 'STROLLEY');
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    date_default_timezone_set('Asia/Manila');
    $timestamp = date("Y-m-d H:i:s");
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Unknown';

    $insertLogQuery = "INSERT INTO audit_log (timestamp, event_type, id, user_type, details) VALUES (?, ?, ?, ?, ?)";
    $auditStmt = $con->prepare($insertLogQuery);
    $auditStmt->bind_param("sssss", $timestamp, $event_type, $id, $user_type, $details);

    if (!$auditStmt->execute()) {
        // Handle error - log or display an error message
    }

    $auditStmt->close();
    $con->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST['item'];
    $unit_price = floatval($_POST['unit_price']);
    $quantity = intval($_POST['quantity']);
    $measurements = $_POST['measurements'];
    $barcode = $_POST['barcode'];
    $weight = floatval($_POST['weight']);
    $category_id = intval($_POST['category_id']);
    $total_cost = floatval($_POST['total_cost']);

    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // Format decimal values with two decimal places
    $unit_price = number_format($unit_price, 2, '.', '');
    $weight = number_format($weight, 2, '.', '');
    $total_cost = number_format($total_cost, 2, '.', '');

    // Check if the generated barcode already exists in the database
    $checkBarcodeSql = "SELECT barcode FROM products WHERE barcode = ?";
    $checkBarcodeStmt = $con->prepare($checkBarcodeSql);
    $checkBarcodeStmt->bind_param("s", $barcode);
    $checkBarcodeStmt->execute();
    $checkBarcodeResult = $checkBarcodeStmt->get_result();

    // Check if an image was uploaded
    // if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    //     $image = $_FILES['image']['tmp_name'];

    //     // Define the target directory where images will be stored
    //     $targetDirectory = '../images/';
    //     $target = $targetDirectory . basename($_FILES["image"]["name"]);
    //     move_uploaded_file($_FILES['image']['tmp_name'], $target);
    // } else {
    //     $_SESSION['error'] = "Error moving image to the target directory.";
    //     header("Location: ../products/addProduct.php");
    //     exit();
    // }

    // Validate the category ID before proceeding
    $categorySql = "SELECT * FROM categories WHERE category_id = ?";
    $categoryStmt = $con->prepare($categorySql);
    $categoryStmt->bind_param("i", $category_id);
    $categoryStmt->execute();
    $categoryResult = $categoryStmt->get_result();

    if ($categoryResult->num_rows === 0) {
        $_SESSION['error'] = "Invalid category ID.";
        header("Location: ../products/addedProducts.php");
        exit();
    }

    // Insert into products table
    $insertProductSql = "INSERT INTO products (item, unit_price, quantity, measurements, weight, total_cost, category_id, barcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $productStmt = $con->prepare($insertProductSql);

    if (!$productStmt) {
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit();
    }

    $productStmt->bind_param("sddsdsis", $item, $unit_price, $quantity, $measurements, $weight, $total_cost, $category_id, $barcode);  


    // $target,

    if ($productStmt->execute()) {
        // Get the product_id of the inserted product
        $productId = $productStmt->insert_id;

        // Insert into inventory table
        $inventorySql = "INSERT INTO inventory (product_id, address) VALUES (?, ?)";
        $inventoryStmt = $con->prepare($inventorySql);

        $inventoryStmt->bind_param("is", $productId, $address);

        if ($inventoryStmt->execute()) {
            // Add an audit trail record for the product addition
            $event_type = "Added Product";
            $logDetails = "Added product: $item";
            auditTrail($event_type, $logDetails);

            $_SESSION['success'] = "Product Added successfully.";
            header("Location: ../products/tbl_products.php");
            exit();
        } else {
            $_SESSION['error'] = "Error adding product to inventory: " . $inventoryStmt->error;
            $inventoryStmt->close();
        }
    } else {
        $_SESSION['error'] = "Error adding product: " . $productStmt->error;
        $productStmt->close();
    }

    $con->close();
    header("Location: ../products/addProduct.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../products/addProduct.php");
    exit();
}
?>
