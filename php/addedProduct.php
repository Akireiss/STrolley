<?php
session_start();
include "../db_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_description = $_POST['item_description'];
    $unit_price = $_POST['unit_price'];
    $quantity = $_POST['quantity'];
    $weight = $_POST['weight'];

    // Generate a unique barcode data
    $barcodeData = generateBarcodeData();

    // Generate a barcode image as binary data
    $barcodeImage = generateBarcodeImage($barcodeData);

    // Insert the data into the database
    $insertSql = "INSERT INTO products (item_description, unit_price, quantity, weight, barcode, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insertSql);

    if (!$stmt) {
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit();
    }

    // Bind parameters
    $stmt->bind_param("sddbbs", $item_description, $unit_price, $quantity, $weight, $barcodeData, $barcodeImage);

    if ($stmt->execute()) {
        $stmt->close();
        $con->close();
        $_SESSION['success'] = "Product Added successfully.";
        header("Location: ../products/tbl_products.php");
        exit();
    } else {
        $_SESSION['error'] = "Error adding product: " . $stmt->error;
        header("Location: ../addedProduct.php");
        exit();
    }
}

// Function to generate a unique barcode data (you can replace this logic)
function generateBarcodeData() {
    // Generate a unique barcode data, e.g., based on the current timestamp
    return 'BC' . time();
}

// Function to generate a barcode image as binary data (PNG format)
function generateBarcodeImage($barcodeData) {
    // Create a new image resource (replace with your preferred image dimensions)
    $image = imagecreate(200, 50);

    // Set background color and text color
    $bgColor = imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, 0, 0, 0);

    // Add the barcode text
    imagestring($image, 5, 5, 5, $barcodeData, $textColor);

    // Create a PNG image (you can use other formats as well)
    ob_start();
    imagepng($image);
    $barcodeImage = ob_get_contents();
    ob_end_clean();

    // Clean up: Destroy the image resource
    imagedestroy($image);

    return $barcodeImage;
}
?>
