<?php
session_start();

// Import database connection
include "../db_conn.php";

// Check if the user is not logged in and redirect to the login page
if (!isset($_SESSION['user_type'])) {
    header("Location: ../index.php"); // Adjust the path to your login page
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

?>




<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav> -->
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Sales Card -->
                    <!-- Products -->
                    <div class="col-md-3">
                        <div class="card info-card sales-card">
                            <div class="filter">
                                <li class="dropdown-header text-start"></li>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-basket-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <?php
                                        // Establish a database connection (replace 'your_database_credentials' with your actual database info)


                                        if ($con->connect_error) {
                                            die("Connection failed: " . $con->connect_error);
                                        }

                                        // Query to count all products in the "products" table
                                        $sql = "SELECT COUNT(*) AS product_count FROM products";
                                        $result = $con->query($sql);

                                        if ($result) {
                                            // Fetch the result as an associative array
                                            $row = $result->fetch_assoc();

                                            // Get the product count
                                            $productCount = $row['product_count'];

                                            // Display the product count inside the <h6> element
                                            echo "<h6>$productCount</h6>";
                                        } else {
                                            echo "Error: " . $con->error;
                                        }

                                        // // Close the database connection
                                        // $con->close();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Sales Card -->

                    <!-- Category -->
                    <div class="col-md-3">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Category</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-grid-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <?php
                                        include_once('../db_conn.php');


                                        if ($con->connect_error) {
                                            die("Connection failed: " . $con->connect_error);
                                        }

                                        // Query to count all categories in the "categories" table
                                        $sql = "SELECT COUNT(*) AS category_count FROM categories";
                                        $result = $con->query($sql);

                                        if ($result) {
                                            // Fetch the result as an associative array
                                            $row = $result->fetch_assoc();

                                            // Get the category count
                                            $categoryCount = $row['category_count'];

                                            // Display the category count inside the <h6> element
                                            echo "<h6>$categoryCount</h6>";
                                        } else {
                                            echo "Error: " . $con->error;
                                        }

                                        // // Close the database connection
                                        // $con->close();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Category -->

                    <!-- End Left side columns -->

                    <!-- Right side columns -->

                    <!-- Customers -->
                    <div class="col-md-3">
                        <div class="card info-card revenue-card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="?filter=all">All</a></li>
                                    <li><a class="dropdown-item" href="?filter=today">Today</a></li>
                                    <li><a class="dropdown-item" href="?filter=this_week">This Week</a></li>
                                    <li><a class="dropdown-item" href="?filter=this_month">This Month</a></li>
                                    <li><a class="dropdown-item" href="?filter=this_year">This Year</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Customers</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <?php
                                        include_once('../db_conn.php');

                                        // Default filter (if none is selected)
                                        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

                                        $sql = "SELECT count(*) FROM customer";

                                        // Adjust SQL query based on the selected filter
                                        if ($filter == 'today') {
                                            $sql .= " WHERE DATE(date) = CURDATE()";
                                        } elseif ($filter == 'this_week') {
                                            $sql .= " WHERE YEARWEEK(date) = YEARWEEK(NOW())";
                                        } elseif ($filter == 'this_month') {
                                            $sql .= " WHERE YEAR(date) = YEAR(NOW()) AND MONTH(date) = MONTH(NOW())";
                                        } elseif ($filter == 'this_year') {
                                            $sql .= " WHERE YEAR(date) = YEAR(NOW())";
                                        }

                                        $res = mysqli_query($con, $sql);

                                        if ($res) {
                                            $count = mysqli_fetch_array($res);
                                        } else {
                                            $count = 0; // Set count to 0 if the query fails
                                        }
                                        ?>
                                        <h6><?php echo $count[0]; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Customers -->

                    <!-- Sales -->
                    <div class="col-md-3">
                        <div class="card info-card sales-card">
                            <!-- <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                </li>
                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
        </div> -->

                            <div class="card-body">
                                <h5 class="card-title">Sales</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-price-tag-3-line"></i>
                                    </div>
                                    <div class="ps-3">
                                        <?php
                                        // Establish a database connection (replace with your own connection code)
                                        $con = mysqli_connect("localhost", "root", "", "strolley");

                                        if (!$con) {
                                            die("Connection failed: " . mysqli_connect_error());
                                        }

                                        // Get today's date in the format "YYYY-MM-DD"
                                        $todayDate = date("Y-m-d");

                                        // Query to fetch the total sales amount for today
                                        $query = "SELECT SUM(t_amount) AS totalSales FROM transaction";
                                        $result = mysqli_query($con, $query);

                                        if ($result) {
                                            $row = mysqli_fetch_assoc($result);
                                            $totalSales = $row['totalSales'];

                                            // Display the total sales amount
                                            echo "<h6>₱" . number_format($totalSales, 2) . "</h6>";

                                            // Close the result and connection
                                            mysqli_free_result($result);
                                        } else {
                                            echo "Error fetching sales data.";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="col-6">
    <div class="card">
        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                </li>
                <li><a class="dropdown-item filter-option" data-filter="today" href="#">Today</a></li>
                <li><a class="dropdown-item filter-option" data-filter="thisMonth" href="#">This Month</a></li>
                <li><a class="dropdown-item filter-option" data-filter="thisYear" href="#">This Year</a></li>
            </ul>
        </div>

        <div class="card-body">
            <h5 class="card-title">Sales Report</h5>

            <!-- Line Chart -->
            <div id="reportsChart"></div>

            <?php
            // Fetch historical sales data for the last 7 days in ascending order by date
            $historicalDataQuery = "SELECT DATE(created_at) AS date, SUM(t_amount) AS totalSales 
                    FROM transaction 
                    GROUP BY date 
                    HAVING totalSales > 0
                    ORDER BY date ASC";

            $historicalDataResult = mysqli_query($con, $historicalDataQuery);
            $historicalData = [];
            while ($row = mysqli_fetch_assoc($historicalDataResult)) {
                $historicalData[] = $row;
            }
            ?>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const historicalData = <?php echo json_encode($historicalData); ?>;
                    const dates = historicalData.map(entry => entry.date);
                    const totalSales = historicalData.map(entry => entry.totalSales);

                    const chart = new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                            name: 'Sales',
                            data: totalSales,
                        }],
                        chart: {
                            height: 310,
                            type: 'area',
                            toolbar: {
                                show: false
                            },
                        },
                        markers: {
                            size: 4
                        },
                        colors: ['#4154f1'],
                        fill: {
                            type: "gradient",
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.3,
                                opacityTo: 0.4,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        xaxis: {
                            type: 'datetime',
                            categories: dates,
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yy',
                            },
                            y: [{
                                formatter: function (value) {
                                    return "Sales: ₱" + value;
                                },
                            }],
                        }
                    });

                    // Initial rendering of the chart
                    chart.render();

                    // Function to update the chart based on the selected filter
                    function updateChart(selectedFilter) {
                        let filteredData;
                        let filteredDates;
                        let filteredTotalSales;

                        // Implement logic to filter data based on the selected filter
                        if (selectedFilter === "today") {
                            // Filter data for today
                            filteredData = historicalData.filter(entry => entry.date === new Date().toISOString().slice(0, 10));
                        } else if (selectedFilter === "thisMonth") {
                            // Filter data for this month
                            const currentDate = new Date();
                            const currentMonth = currentDate.getMonth() + 1; // months are 0-indexed
                            filteredData = historicalData.filter(entry => {
                                const entryDate = new Date(entry.date);
                                return entryDate.getMonth() + 1 === currentMonth && entryDate.getFullYear() === currentDate.getFullYear();
                            });
                        } else if (selectedFilter === "thisYear") {
                            // Filter data for this year
                            const currentYear = new Date().getFullYear();
                            filteredData = historicalData.filter(entry => new Date(entry.date).getFullYear() === currentYear);
                        } else {
                            // No filter selected, use the original data
                            filteredData = historicalData;
                        }

                        // Extract filtered data
                        filteredDates = filteredData.map(entry => entry.date);
                        filteredTotalSales = filteredData.map(entry => entry.totalSales);

                        // Update the chart series and x-axis categories
                        chart.updateSeries([{
                            name: 'Sales',
                            data: filteredTotalSales,
                        }]);

                        chart.updateOptions({
                            xaxis: {
                                categories: filteredDates,
                            }
                        });
                    }

                    // Add event listeners to filter options
                    const filterOptions = document.querySelectorAll(".filter-option");
                    filterOptions.forEach(option => {
                        option.addEventListener("click", function (e) {
                            e.preventDefault();
                            const selectedFilter = this.getAttribute("data-filter");
                            updateChart(selectedFilter);
                        });
                    });
                });
            </script>
            <!-- End Line Chart -->
        </div>
    </div>
</div>



                    <div class="col-6">
                        <div class="card top-selling overflow-auto">
                            <!-- <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                </li>
                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
        </div> -->
                            <div class="card-body pb-0">
                                <h5 class="card-title">Top Selling</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <!-- <tr>
                        <th scope="col">Image</th> -->
                                        <th scope="col">Product</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Sold</th>
                                        <!-- <th scope="col">Revenue</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Database connection configuration
                                        $host = "localhost";
                                        $username = "root";
                                        $password = "";
                                        $database = "strolley";

                                        // Create a database connection
                                        $mysqli = new mysqli($host, $username, $password, $database);

                                        // Check if the connection was successful
                                        if ($mysqli->connect_error) {
                                            die("Connection failed: " . $mysqli->connect_error);
                                        }

                                        // Query to fetch the top-selling products and calculate revenue
                                        $query = "
                    SELECT p.image AS Image, t.item AS Product, p.unit_price AS Price, COUNT(t.customer_id) AS Sold, SUM(p.quantity * p.unit_price) AS Revenue
                    FROM transaction t
                    LEFT JOIN products p ON t.item = p.item
                    GROUP BY t.item
                    ORDER BY Sold DESC
                    LIMIT 10; -- You can adjust the limit as needed to get the top N products
                ";

                                        // Execute the query
                                        $result = $mysqli->query($query);

                                        // Check if the query was successful
                                        if ($result) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<tr>';
                                                // echo '<td><img src="' . $row['Image'] . '" alt="Product Image" class="img-thumbnail" width="100"></td>';
                                                echo '<td>' . $row['Product'] . '</td>';
                                                echo '<td>' . $row['Price'] . '</td>';
                                                echo '<td>' . $row['Sold'] . '</td>';
                                                // echo '<td>' . $row['Revenue'] . '</td>';
                                                echo '</tr>';
                                            }

                                            // Close the result set
                                            $result->close();
                                        } else {
                                            echo "Query failed: " . $mysqli->error;
                                        }

                                        // Close the database connection
                                        $mysqli->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>










    </section>
</main>

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
        &copy; Copyright <> <strong><span>DMMMSU Multipurpose Cooperative</span></strong>.
    </div>
    <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      
    </div>
</footer><!-- End Footer -->



<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


<script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/chart.js/chart.umd.js"></script>
<script src="../assets/vendor/echarts/echarts.min.js"></script>
<script src="../assets/vendor/quill/quill.min.js"></script>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../assets/vendor/php-email-form/validate.js"></script>


<script src="../assets/js/main.js"></script>

</body>

</html>