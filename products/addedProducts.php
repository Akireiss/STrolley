<?php

use Picqer\Barcode\BarcodeGeneratorPNG;
session_start();
include "../db_conn.php";

// Function to generate and save barcode image
function generateBarcodeImage($barcode) {
    require_once 'vendor/autoload.php'; // Include the library

    // Set up the barcode generator
    $barcodeGenerator = new BarcodeGeneratorPNG();

    // Generate the barcode image
    $barcodeImage = $barcodeGenerator->getBarcode($barcode, $barcodeGenerator::TYPE_CODE_128, 2, 60);

    // Define the directory where you want to save the barcode images
    $barcodeDirectory = 'products/images/';

    // Ensure the directory exists, create it if necessary
    if (!file_exists($barcodeDirectory)) {
        mkdir($barcodeDirectory, 0777, true);
    }

    // Generate a unique filename for the barcode image
    $filename = $barcode . '.png';

    // Save the barcode image to the directory
    $barcodeImagePath = $barcodeDirectory . $filename;
    file_put_contents($barcodeImagePath, $barcodeImage);

    // Return the file path of the saved barcode image
    return $barcodeImagePath;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_description = $_POST['item_description'];
    $unit_price = $_POST['unit_price'];
    $quantity = $_POST['quantity'];
    $weight = $_POST['weight'];
    $category_id = $_POST['category_id'];   

    // Check if an image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['tmp_name'];

        // Define the target directory where images will be stored
        $targetDirectory = '../images/';
        $target = $targetDirectory . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        // Error moving image
        $_SESSION['error'] = "Error moving image to the target directory.";
        header("Location: ../products/addProduct.php");
        exit();
    }

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

    // Generate a barcode for the product
    $barcode = strtoupper(md5(uniqid(rand(), true))); // Generate a unique identifier as a barcode

    // Save the barcode as an image using the function
    $barcodeImage = generateBarcodeImage($barcode);

    // Insert the data into the database along with the barcode image path
    $insertSql = "INSERT INTO products (item_description, unit_price, quantity, weight, category_id, image, barcode) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insertSql);

    if (!$stmt) {
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit();
    }

    $stmt->bind_param("sidsiss", $item_description, $unit_price, $quantity, $weight, $category_id, $target, $barcodeImage);

    if ($stmt->execute()) {
        $stmt->close();
        $con->close();
        $_SESSION['success'] = "Product Added successfully.";
        header("Location: ../products/tbl_products.php");
        exit();
    } else {
        $_SESSION['error'] = "Error adding product: " . $stmt->error;
        $stmt->close(); // Close the statement here in case of error
        $con->close();
        header("Location: ../products/addProduct.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../products/addProduct.php");
    exit();
}
?>
