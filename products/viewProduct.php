<?php

include "../db_conn.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $con->query($sql);

if ($result->num_rows !== 1) {
    header("Location: index.php");
    exit;
}

$product = $result->fetch_assoc();

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Product Details</h1>
                    </div>
                    <div class="card-body">
                        <div class="product-image">
                            <strong>Image:</strong>
                            <?php
                            if (!empty($product['image']) && file_exists("../img/" . $product['image'])) {
                                echo '<img src="../img/' . $product['image'] . '" style="max-width: 300px;">';
                            } else {
                                echo 'No image available';
                            }
                            ?>
                        </div>
                        <div class="barcode">
                            <strong>Barcode:</strong> <?php echo $product['barcode']; ?>
                        </div>
                        <div class="product-item">
                            <strong>Product Item:</strong> <?php echo $product['item_description']; ?>
                        </div>
                        <div class="unit-price">
                            <strong>Unit Price:</strong> <?php echo $product['unit_price']; ?>
                        </div>
                        <div class="quantity">
                            <strong>Quantity:</strong> <?php echo $product['quantity']; ?>
                        </div>
                        <div class="weight">
                            <strong>Weight:</strong> <?php echo $product['weight']; ?>
                        </div>
                        <div class="back-button">
                            <a href="../products/tbl_products.php" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>