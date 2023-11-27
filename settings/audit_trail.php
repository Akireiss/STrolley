<?php
session_start();

include "../db_conn.php";

$auditQuery = "SELECT * FROM audit_log ORDER BY timestamp DESC";
$auditResult = $con->query($auditQuery);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow p-4">
                    <h5 class="card-title text-center text-primary mb-4">Audit Trail</h5>
                    <div class="table-responsive">
                        <table class="datatable table table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Timestamp</th>
                                    <th>Event Type</th>
                                    <th>User Type</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $auditResult->fetch_assoc()) { ?>
                                    <tr class="table-row" data-href="viewtransac.php?id=<?php echo $row['id']; ?>">
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo date('F j, Y H:i:s', strtotime($row['timestamp'])); ?></td>
                                        <td><?php echo $row['event_type']; ?></td>
                                        <td><?php echo $row['user_type']; ?></td>
                                        <td><?php echo $row['details']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead th {
        background-color: #343a40;
        color: #fff;
        border-color: #454d55;
        cursor: pointer;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tableRows = document.querySelectorAll('.table-row');
        tableRows.forEach(function (row) {
            row.addEventListener('click', function () {
                // Show a confirmation dialog before navigating
                var result = confirm("Do you want to view details?");
                if (result) {
                    window.location.href = row.dataset.href;
                }
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
