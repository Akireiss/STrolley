<?php
session_start();

include "../db_conn.php"; // Include your database connection

// Retrieve audit trail records
$auditQuery = "SELECT * FROM audit_log ORDER BY timestamp DESC";
$auditResult = $con->query($auditQuery);

// Include header and navigation
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>


<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Audit Trail</h5>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Timestamp</th>
                                <th>Event Type</th> <!-- Rename Action to Event Type -->
                                <th>User Type</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $auditResult->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td> <!-- Display ID -->
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['timestamp'])); ?></td> <!-- Format timestamp -->
                                    <td><?php echo $row['event_type']; ?></td> <!-- Display event_type -->
                                    <td><?php echo $row['user_type']; ?></td>
                                    <td><?php echo $row['details']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
