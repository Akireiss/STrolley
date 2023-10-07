<?php
session_start();

// Import database connection
include "../db_conn.php";

$sql = "SELECT * FROM categories";
$result = $con->query($sql);

// Check if any categories are found
if ($result->num_rows === 0) {
    // Handle the case when no categories are found
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>
<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
            <h1 class="page-header">Product Categories</h1>

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

            <div class="row">
                <?php while ($categoryRow = $result->fetch_assoc()) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $categoryRow["category_name"]; ?></h5>
                                <a href="productByCategory.php?category_id=<?php echo urlencode($categoryRow["category_id"]); ?>" class="btn btn-primary">View Products</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>


<?php include '../includes/footer.php'; ?>
