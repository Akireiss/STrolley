<?php
session_start();

// Import database connection
include "../db_conn.php";

$sql = "SELECT * FROM products";
$result = $con->query($sql);

// Check if any products are found
if (!isset($_SESSION['user_type'])) {
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}


include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="add-product mb-3 mt-3">
                    <h1 class="page-header">List of Products <a href="../products/addProduct.php" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add Products
                        </a>
                    </h1>
                </div>
                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['success']; ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php } ?>

                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php } ?>

                <div class="d-flex justify-content-between mb-3">
                </div>

<!-- ... (Previous HTML Code) ... -->

                <table id="myTable" class="table datatable">
                    <thead>
                        <tr>
                            <!-- <th scope="col">ID</th> -->
                            <!-- <th scope="col">Image</th> -->
                            <th scope="col">Product Item</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity in Stocks</th>
                            <th scope="col">Measurements</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $result->fetch_assoc()) {
                            $id = $product["id"];
                            // $image = $product["image"];
                            $item = $product["item"];
                            $unit_price = $product["unit_price"];
                            $quantity = $product["quantity"];
                            $measurements = $product["measurements"];
                            $weight = $product["weight"];
                        ?>
                            <tr>
                                <!-- <td><?php echo $id; ?></td> -->
                                <!-- <td>
                    <?php
                    if (!empty($image)) {
                        $imagePath = "../images/$image";
                        if (file_exists($imagePath)) {
                            echo '<img src="' . $imagePath . '" alt="Product Image" style="max-width: 80px;">';
                        } else {
                            echo "Image not found: $imagePath<br>";
                            echo '<img src="../images/placeholder.jpg" alt="No Image" style="max-width: 80px;">';
                        }
                    } else {
                        echo '<img src="../images/placeholder.jpg" alt="No Image" style="max-width: 80px;">';
                    }
                    ?>
                </td> -->
                <td><?php echo $item; ?></td>
                <td><?php echo $unit_price; ?></td>
                <td><?php echo $quantity; ?></td>
                <td><?php echo $measurements; ?></td>
                <td><?php echo $weight; ?></td>

                <td>
                    <div class="d-flex justify-content-center">
                        <a href="../products/viewProduct.php?id=<?php echo $id; ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $id; ?>">
                            <i class="bx bx-show-alt"></i> View
                        </a>
                        <a href="../products/editProduct.php?id=<?php echo $id; ?>" class="btn btn-sm btn-secondary edit-btn m-1" data-id="<?php echo $id; ?>">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- ... (Rest of HTML Code) ... -->

<style>
    table.table {
        width: 100%;
        border-collapse: collapse;
    }

    table.table th,
    table.table td {
        padding: 8px; /* Reduce padding for a more compact design */
        border: 1px solid #e0e0e0;
        font-size: 14px; /* Adjust the font size as needed */
    }

    table.table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    table.table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table.table tr:hover {
        background-color: #e0e0e0;
    }

    table.table img {
        max-width: 80px; /* Reduce the max-width of the image */
        height: auto; /* Maintain aspect ratio */
    }

    table.table .btn {
        margin: 3px;
    }

    /* Custom CSS for Edit button */
    .edit-btn {
        background-color: #007bff;
        color: #fff;
    }
</style>


                <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        new simpleDatatables.DataTable("#myTable");
                    });
                </script>
            </div>
        </section>
    </main>

<?php include '../includes/footer.php'; ?>
