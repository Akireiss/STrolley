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

    if (!$auditStmt) {
        die("Error preparing statement: " . $con->error);
    }

    $auditStmt->bind_param("ssiss", $timestamp, $event_type, $id, $user_type, $details);

    if ($auditStmt->execute()) {
        $auditStmt->close();
        $con->close();
    } else {
        die("Error executing statement: " . $auditStmt->error);
    }
}

// Check if the 'id' parameter is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: ../products/tbl_products.php");
    exit;
}

$id = $_GET['id'];

// Fetch the product information
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $con->query($sql);

// Check if a product with the specified ID exists
if ($result->num_rows !== 1) {
    header("Location: ../products/tbl_products.php");
    exit;
}

$product = $result->fetch_assoc();

// Handle form submission for updating the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item'];
    $category_id = $_POST['category_id'];
    $unit_price = $_POST['unit_price'];
    $quantity = $_POST['quantity'];
    $measurements = $_POST['measurements'];
    $weight = $_POST['weight'];
    $total_cost = $_POST['total_cost'];

    // Check if an image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Allow certain file formats (you can modify this as needed)
            $allowed_formats = array("jpg", "jpeg", "png", "gif");
            if (in_array($imageFileType, $allowed_formats)) {
                // Upload the image
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // Update the product's image path in the database
                    $image_path = "../images/" . basename($_FILES['image']['name']); // Relative path
                    $updateSql = "UPDATE products SET 
                        item = '$item',
                        category_id = '$category_id',
                        unit_price = '$unit_price',
                        quantity = '$quantity',
                        measurements = '$measurements',
                        weight = '$weight',
                        total_cost = '$total_cost',
                        image = '$image_path'
                        WHERE id = '$id'";

                    if ($con->query($updateSql) === TRUE) {
                        $event_type = "Updated Product";
                        $logDetails = "Updated product: $item";
                        auditTrail($event_type, $logDetails);

                        // Product updated successfully
                        $_SESSION['success'] = "Product updated successfully!";
                        header("Location: ../products/tbl_products.php");
                        exit;
                    } else {
                        $_SESSION['error'] = "Error updating product: " . $con->error;
                    }
                } else {
                    // Error moving image
                    $_SESSION['error'] = "Error moving image to target directory.";
                }
            } else {
                // Invalid file format
                $_SESSION['error'] = "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        } else {
            // Not an actual image
            $_SESSION['error'] = "The uploaded file is not a valid image.";
        }
    } else {
        // Handle product update without changing the image (if no image is selected)
        $updateSql = "UPDATE products SET 
            item = '$item',
            category_id = '$category_id',
            unit_price = '$unit_price',
            quantity = '$quantity',
            measurements = '$measurements',
            weight = '$weight',
            total_cost = '$total_cost'
            WHERE id = '$id'";

        if ($con->query($updateSql) === TRUE) {
            $event_type = "Updated Product";
            $logDetails = "Updated product: $item";
            auditTrail($event_type, $logDetails);

            // Product updated successfully
            $_SESSION['success'] = "Product updated successfully!";
            header("Location: ../products/tbl_products.php");
            exit;
        } else {
            $_SESSION['error'] = "Error updating product: " . $con->error;
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>
