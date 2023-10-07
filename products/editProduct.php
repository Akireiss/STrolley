<?php
session_start();
include "../db_conn.php";

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
    $item_description = $_POST['item_description'];
    $category_id = $_POST['category_id'];
    $unit_price = $_POST['unit_price'];
    $quantity = $_POST['quantity'];
    $weight = $_POST['weight'];

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
                        item_description = '$item_description',
                        category_id = '$category_id',
                        unit_price = '$unit_price',
                        quantity = '$quantity',
                        weight = '$weight',
                        image = '$image_path'
                        WHERE id = '$id'";

                    if ($con->query($updateSql) === TRUE) {
                        // Product updated successfully
                        $_SESSION['success'] = "Product updated successfully!";
                        header("Location: ../products/tbl_products.php"); // Redirect to the products list page
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
            item_description = '$item_description',
            category_id = '$category_id',
            unit_price = '$unit_price',
            quantity = '$quantity',
            weight = '$weight'
            WHERE id = '$id'";

        if ($con->query($updateSql) === TRUE) {
            // Product updated successfully
            $_SESSION['success'] = "Product updated successfully!";
            header("Location: ../products/tbl_products.php"); // Redirect to the products list page
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

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Product</h5>
                </div>

                <form class="row g-3 needs-validation" action="../products/editProduct.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    
                    <div class="col-md-10 position-relative">
                        <label class="form-label">Item Description<font color="red">*</font></label>
                        <input type="text" class="form-control" id="validationTooltip01" name="item_description" required autofocus="autofocus" value="<?php echo $product['item_description']; ?>">
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

                    <div class="col-md-4 position-relative">
                        <label class="form-label">Weight<font color="red">*</font></label>
                        <input type="text" class="form-control" id="weight" name="weight" required autofocus="autofocus" value="<?php echo $product['weight']; ?>">
                    </div>

                    <div class="form-group">
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <label class="col-md-4" align="right" for="image">Upload Image:</label>
                                <div class="col-md-8">
                                    <input type="file" name="image" value="" id="image" />
                                    <!-- Display the current image -->
                                    <img src="../images/<?php echo $product['image']; ?>" alt="Current Image" width="100">
                                </div>
                            </div>
                        </div>
                    </div>

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
