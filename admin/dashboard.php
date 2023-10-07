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

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  

   <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
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
                  <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
            
            <div class="card-body">
                  <h5 class="card-title">Sales<span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>1020</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Customers -->
          </div>
        </div>

         <!-- Reports -->
            <div class="col-6">
              <div class="card">

                <div class="card-body">
                  <h5 class="card-title">Daily Sales<span>/Today</span></h5>

                  <!-- Line Chart -->
                  <div id="reportsChart"></div>

                  <script>
                    document.addEventListener("DOMContentLoaded", () => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Sales',
                          data: [31, 40, 28, 51, 42, 82, 56],
                        }, {
                          name: 'Customers',
                          data: [15, 11, 32, 18, 9, 24, 11]
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
                        colors: ['#4154f1', '#2eca6a', '#ff771d'],
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
                          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
                        },
                        tooltip: {
                          x: {
                            format: 'dd/MM/yy HH:mm'
                          },
                        }
                      }).render();
                    });
                  </script>
                  <!-- End Line Chart -->

                </div>

              </div>
            </div><!-- End Reports -->


            <!-- Top Selling -->
            <div class="col-6">
              <div class="card top-selling overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Today</span></h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-1.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Ut inventore ipsa voluptas nulla</a></td>
                        <td>$64</td>
                        <td class="fw-bold">124</td>
                        <td>$5,828</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-2.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Exercitationem similique doloremque</a></td>
                        <td>$46</td>
                        <td class="fw-bold">98</td>
                        <td>$4,508</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-3.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Doloribus nisi exercitationem</a></td>
                        <td>$59</td>
                        <td class="fw-bold">74</td>
                        <td>$4,366</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-4.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Officiis quaerat sint rerum error</a></td>
                        <td>$32</td>
                        <td class="fw-bold">63</td>
                        <td>$2,016</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-5.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Sit unde debitis delectus repellendus</a></td>
                        <td>$79</td>
                        <td class="fw-bold">41</td>
                        <td>$3,239</td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->
  </section>
</main>

              <!-- ======= Footer ======= -->
            <footer id="footer" class="footer">
              <div class="copyright">
                &copy; Copyright <strong><span>DMMMSU-North La Union Campus</span></strong>. All Rights Reserved
                 </div>
              <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
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