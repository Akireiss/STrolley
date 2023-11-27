<?php
session_start();

include_once('../db_conn.php');

// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}


$sql = "SELECT * FROM users";
$result = $con->query($sql);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .receipt {
            border: 1px solid #ccc;
            padding: 10px;
            font-family: Arial, sans-serif;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .receipt-table th, .receipt-table td {
            border: 1px solid #ccc;
            padding: 5px;
        }
        .receipt-total {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <main id="main" class="main">
        <section class "section dashboard">
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="receipt">
                            <h1 class="receipt-title">General Bill</h1>
                            <table class="receipt-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Weight</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Connect to your database (replace with your database credentials)
                                    $servername = "localhost";
                                    $username = "root";
                                    $password = "";
                                    $dbname = "strolley";

                                    $conn = new mysqli($servername, $username, $password, $dbname);

                                    // Check connection
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    // Initialize total values
                                    $totalWeight = 0;
                                    $totalAmount = 0;

                                    // Get the customer_id and time from the URL parameters
                                    if (isset($_GET['id']) && isset($_GET['time'])) {
                                        $customer_id = $_GET['id'];
                                        $time = $_GET['time'];
                                        
                                        // SQL query to fetch data for the given customer_id and time
                                        $sql = "SELECT item, quantity, t_weight, t_amount FROM transaction WHERE customer_id = '$customer_id' AND TIME(created_at) = '$time'";

                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Output data of the row
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row["item"] . "</td>";
                                                echo "<td>" . $row["quantity"] . "</td>";
                                                echo "<td>" . $row["t_weight"] . "</td>";
                                                echo "<td>" . $row["t_amount"] . "</td>";
                                                echo "</tr>";

                                                // Update total values
                                                $totalWeight += $row["t_weight"];
                                                $totalAmount += $row["t_amount"];
                                            }

                                            // Display total row after the loop
                                            echo "<tr>";
                                            echo "<td colspan='2'></td>";
                                            echo "<td class='receipt-total'>Total Weight: $totalWeight</td>";
                                            echo "<td class='receipt-total'>Total Amount: $totalAmount</td>";
                                            echo "</tr>";
                                        } else {
                                            echo "No records found for customer_id: $customer_id and time: $time";
                                        }
                                    } else {
                                        echo "Customer ID or Time parameter is missing in the URL.";
                                    }

                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-secondary mt-3" onclick="goBack()">Back</button>
                        <script>
        function goBack() {
            window.history.back(); // Use the browser's history to go back
        }
    </script>
                        </div>
                    </div>
                
            </div>
        </section>
    </main>
</body>
</html>
