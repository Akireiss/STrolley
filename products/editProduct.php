<?php
session_start();

// Import database connection
include "../db_conn.php";


// Check if any products are found
if (!isset($_SESSION['user_type'])) {
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

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


include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Product</h5>
                </div>

                <form class="row g-3 needs-validation" action="../products/update_product.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Item Description<font color="red">*</font></label>
                        <input type="text" class="form-control" id="validationTooltip01" name="item" required autofocus="autofocus" value="<?php echo $product['item']; ?>">
                    </div>

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Category<font color="red">*</font></label>
                        <select class="form-select" aria-label="Default select example" name="category_id" id="category_id" required>
                            <option value="" selected disabled>Select Category</option>
                            <?php
                            // Fetch categories from the database
                            $categorySql = "SELECT * FROM categories";
                            $categoryResult = $con->query($categorySql);

                            while ($categoryRow = $categoryResult->fetch_assoc()) {
                                $categoryId = $categoryRow["category_id"];
                                $categoryName = $categoryRow["category_name"];
                                $selected = ($categoryId == $product['category_id']) ? 'selected' : '';
                                echo "<option value=\"$categoryId\" $selected>$categoryName</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Unit Price<font color="red">*</font></label>
                        <input class="form-control input-sm" id="unit_price" step="any" name="unit_price" required autofocus="autofocus" placeholder="&#8369 Price" type="number" value="<?php echo $product['unit_price']; ?>">
                    </div>

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Quantity<font color="red">*</font></label>
                        <input class="form-control input-sm" id="quantity" step="any" name="quantity" required autofocus="autofocus" placeholder="Quantity" type="number" value="<?php echo $product['quantity']; ?>">
                    </div>
                    <div class="col-md-3 position-relative">
                <label class="form-label">Address<font color="red">*</font></label>
                <input class="form-control input-sm" id="quantity" name="Address" required autofocus="autofocus" placeholder="Quantity" type="text" value="Sapilang, Bacnotan, La Union" disabled>
            </div>

                    <div class="col-md-3 position-relative">
                    <label class="form-label">Measurements<font color="red">*</font></label>
                    <input type="text" class="form-control" id="validationTooltip01" name="measurements" value="<?php echo $product['measurements']; ?>" required autofocus="autofocus">
                </div>

                    <div class="col-md-3 position-relative">
                        <label class="form-label">Weight<font color="red">*</font></label>
                        <input type="text" class="form-control" id="weight" name="weight" required autofocus="autofocus" value="<?php echo $product['weight']; ?>">
                    </div>

                    <div class="col-md-3 position-relative">
                    <label class="form-label">Total Cost<font color="red">*</font></label>
                    <input type="text" class="form-control" id="validationTooltip01" name="total_cost"  required autofocus="autofocus" value="<?php echo $product['total_cost']; ?>">
                </div>

                    <!-- <div class="form-group">
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <label class="col-md-4" align="right" for="image">Upload Image:</label>
                                <div class="col-md-8">
                                    <input type="file" name="image" value="" id="image" />
                                   
                                    <img src="../images/<?php echo $product['image']; ?>" alt="Current Image" width="100">
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-warning mb-3" name="submit">Update Product</button>
                                <a href="tbl_products.php" class="btn btn-primary mb-3">Back</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>