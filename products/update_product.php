<?php
session_start();

include "../db_conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $item_description = $_POST['item_description'];
        $unit_price = $_POST['unit_price'];
        $quantity = $_POST['quantity'];
        $weight = $_POST['weight'];
        
        // Assuming you have a products table
        $updateSql = "UPDATE products SET item_description = ?, unit_price = ?, quantity = ?, weight = ? WHERE id = ?";
        
        $stmt = $con->prepare($updateSql);
        $stmt->bind_param("ssdsi", $item_description, $unit_price, $quantity, $weight, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product updated successfully!";
            $stmt->close();
            header("Location: ../products/tbl_products.php"); // Redirect to the products list page
            exit;
        } else {
            $_SESSION['error'] = "Error updating product: " . $stmt->error;
            $stmt->close();
        }
    }
}

header("Location: update.php?id=$id"); // Redirect back to the update page if an error occurs
exit;
?>
