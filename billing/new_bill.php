<?php
session_start();
include_once('../db_conn.php');

if (!isset($_SESSION['user_type'])) {
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Establish a MySQLi database connection
$mysqli = new mysqli("localhost", "root", "", "strolley");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Initialize product information
$productInfo = null;

// Define a function to fetch product information by barcode
function getProductInfoByBarcode($mysqli, $barcode) {
    $sql = "SELECT * FROM products WHERE barcode = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode = $_POST['barcode'];
    $productInfo = getProductInfoByBarcode($mysqli, $barcode);
}

// Handle the "Add to Cart" button click
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // You can add code here to handle adding the product to the cart, e.g., store it in a session variable.
    // Example: $_SESSION['cart'][] = $productInfo;
    // You can implement your cart logic here.
}
?>

<main id="main" class="main">
    <div class="col-md-12 mb-lg-0 mb-4">
        <div class="card mt-4">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-6 d-flex align-items-center">
                        <h6 class="mb-0">Payment Method</h6>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header pb-0 px-3">
                            <h6 class="mb-0">Billing Information</h6>
                        </div>
                        <div class="card-body">
                        <form method="post" id="barcodeForm">
                            <label for="barcode">Barcode:</label>
                            <input type="text" name="barcode" id="barcode" required value="<?php echo isset($_POST['barcode']) ? $_POST['barcode'] : ''; ?>">
                        </form>

                            <?php if (!empty($productInfo)) : ?>
                                <ul class="list-group">
                                    <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                        <div class="d-flex flex-column">
                                            <span class="mb-2 text-xs">Item Description: <span class="text-dark font-weight-bold ms-sm-2"><?php echo $productInfo['item']; ?></span></span>
                                            <span class="mb-2 text-xs">Quantity: <span class="text-dark font-weight-bold ms-sm-2"><?php echo $productInfo['quantity']; ?></span></span>
                                            <span class="mb-2 text-xs">Unit Price: <span class="text-dark ms-sm-2 font-weight-bold"><?php echo $productInfo['unit_price']; ?></span></span>
                                            <span class="text-xs">Weight: <span class="text-dark ms-sm-2 font-weight-bold"><?php echo $productInfo['weight']; ?></span></span>
                                        </div>
                                    </li>
                                </ul>
                                <form method="post">
                                    <input type="hidden" name="barcode" value="<?php echo $productInfo['barcode']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Use JavaScript to automatically submit the form when barcode input changes
    const barcodeInput = document.getElementById('barcode');
    const barcodeForm = document.getElementById('barcodeForm');

    barcodeInput.addEventListener('input', function () {
        barcodeForm.submit();
    });
</script>
