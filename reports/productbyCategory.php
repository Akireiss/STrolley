<?php
include "../db_conn.php";

// Check if the 'category_id' parameter is set in the URL
if (isset($_GET['category_id'])) {
    $category_id = mysqli_real_escape_string($con, $_GET['category_id']);

    // Query to retrieve products in the selected category
    $sql = "SELECT * FROM products WHERE category_id = $category_id";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }
} else {
    die("Category ID parameter not set.");
}

// Include your header, navbar, and sidebar
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
            <h1 class="page-header">Products in this Category</h1>

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

            <div class="col-md-12 d-flex justify-content-end ">
                <button class="btn btn-primary" onclick="goBack()">Back</button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Product Name</th>
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($productRow = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $productRow["item"]; ?></td>
                            <!-- Add more table data columns to display additional product details -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <script>
                // JavaScript function to navigate back to the previous page
                function goBack() {
                    window.history.back();
                }
            </script>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
