<?php
// Start the session
session_start();

include_once('../db_conn.php');
// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<main id="main" class="main">
    <div class="col-md-12 mb-lg-0 mb-4">
        <div class="card mt-4">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-6 d-flex align-items-center">
                        <h6 class="mb-0">Payment Method</h6>
                    </div>
                    <div class="add-employee mb-3 mt-3">
                        <a href="../billing/new_bill.php" class="btn btn-primary">
                            <i class="bi bi-plus"></i>Add New Bill
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header pb-0 px-3">
                            <h6 class="mb-0">Billing Information</h6>
                        </div>
                        <div class="card-body pt-4 p-3">
                            <ul class="list-group">
                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-3 text-sm">Oliver Liam</h6>
                                        <span class="mb-2 text-xs">Company Name: <span class="text-dark font-weight-bold ms-sm-2">Viking Burrito</span></span>
                                        <span class="mb-2 text-xs">Email Address: <span class="text-dark ms-sm-2 font-weight-bold">oliver@burrito.com</span></span>
                                        <span class="text-xs">VAT Number: <span class="text-dark ms-sm-2 font-weight-bold">FRB1235476</span></span>
                                        
                                        <!-- Action buttons: View and Edit -->
                                        <div class="mt-3">
                                            <a href="#" class="btn btn-primary btn-sm me-2">View</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Edit</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
