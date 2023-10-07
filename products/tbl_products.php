<?php
session_start();

// Import database connection
include "../db_conn.php";

$sql = "SELECT * FROM products";
$result = $con->query($sql);

// Check if any users are found
if ($result->num_rows === 0) {
    // Handle case when no users are found
    header("Location: ../index.php");
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
                <div class="add-employee mb-3 mt-3">
                    <h1 class = "page-header">List of Products <a href="../products/addProduct.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i>Add Products </a> </h1>
                    </a>
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
                <table id="myTable" class="table datatable">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Image</th>
                            <th scope="col">Product Item</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($userrow = $result->fetch_assoc()) {
                        $id = $userrow["id"];
                        $image = $userrow["image"];
                        $item_description = $userrow["item_description"];
                        $unit_price = $userrow["unit_price"];
                        $quantity = $userrow["quantity"];
                        $weight = $userrow["weight"];
                    ?>
                    <tr>
                    <td><?php echo $id; ?></td>
                    <td>
                       <?php
                        if (!empty($image)) {
                            $imagePath = "../images/$image";
                            if (file_exists($imagePath)) {
                                echo '<img src="' . $imagePath . '" style="max-width: 100px;" alt="Product Image">';
                            } else {
                                echo "Image not found: $imagePath<br>";
                                echo '<img src="../images/placeholder.jpg" style="max-width: 100px;" alt="No Image">';
                            }
                        } else {
                            echo '<img src="../images/placeholder.jpg" style="max-width: 100px;" alt="No Image">';
                        }
                        ?>

                    </td>
                            <td><?php echo $item_description; ?></td>
                            <td><?php echo $unit_price; ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td><?php echo $weight; ?></td>
                            <td>
                                <div class="d-flex justify-content-center">
                                  <a href="../products/viewProduct.php?id=<?php echo $id; ?>" class="btn btn-sm btn-primary view-btn m-1" data-id="<?php echo $id; ?>">
                                   <i class="bx bx-show-alt"></i>
                                   </a>
                                 <a href="../products/editProduct.php?id=<?php echo $id; ?>" class="btn btn-sm btn-info update-btn m-1" data-id="<?php echo $id; ?>">
                                 <i class="bi bi-pencil-square"></i>
                                </a>
                                </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <style>
                    #myTable th,
                    #myTable td {
                        text-align: center;
                    }
                </style>
                <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        new simpleDatatables.DataTable("#myTable");
                    });
                </script>
            </div>
        </section>
    </main>

<?php include '../includes/footer.php'; ?>
