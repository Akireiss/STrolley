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

   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #f5f5f5;
         margin: 0;
         padding: 0;
      }
      header {
         background-color: #8d8484;
         color: #fff;
         padding: 10px 0;
         text-align: center;
      }
      .container {
         padding: 20px;
         background-color: #fff;
         border-radius: 5px;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      h1 {
         font-size: 36px;
         margin-bottom: 20px;
      }
      p {
         font-size: 18px;
         line-height: 1.6;
         text-align: center;
      }
      .btn-primary {
         background-color: #337ab7;
         border-color: #337ab7;
      }
      .btn-primary:hover {
         background-color: #286090;
         border-color: #204d74;
      }
      .glyphicon-save {
         margin-left: 5px;
      }
      label {
    display: inline-block;
    margin-bottom: 0.5rem;
    margin-left: 62px;
}
   </style>


    <main id="main" class="main">
      <section class="section dashboard">
   <title>Backup / Export Database</title>


   <header>
      <h1>Backup / Export Database</h1>
   </header>

<body>
   <div class="container">
      <p>Backup and restore your MySQL database easily.</p>
      <p>Click the button below to create a backup file (.sql) from your database.</p>
      <p><a href="backup.php" class="btn btn-primary">Backup Database <span class="glyphicon glyphicon-save"></span></a></p>

      <br>
      <br>
      <!-- <p>Click the button below to import a backup file into your database.</p> -->
      <div class="text-center"> <!-- Center-align the form elements -->
    <!-- <form action="import.php" method="post" enctype="multipart/form-data">
        <label for="import_file">Select SQL Backup File:</label>
        <input  type="file" name="backup_file" id="import_file" accept=".sql">
        <br>
        <button type="submit" class="btn btn-primary">Import Database <span class="glyphicon glyphicon-import"></span></button>
    </form> -->
</div>

   </div>
 </main>

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
 