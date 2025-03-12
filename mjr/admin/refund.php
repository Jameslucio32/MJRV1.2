<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login(); // Ensure the user is logged in and is an admin
require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>
        <!-- Header -->
        <div class="header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <h1 class="text-white">Customer Refund Requests</h1>
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Refund Requests
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Order Code</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Comments</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Proof</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch only orders with refund requests that are "Refund Requested"
                                    $ret = "SELECT * FROM refund_requests WHERE refund_status = 'Pending' ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    
                                    while ($refund = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($refund->order_id); ?></td>
                                            <td><?php echo htmlspecialchars($refund->refund_reason); ?></td>
                                            <td><?php echo htmlspecialchars($refund->refund_comments); ?></td>
                                            <td><span class='badge badge-warning'><?php echo htmlspecialchars($refund->refund_status); ?></span></td>
                                            <td><a href="<?php echo htmlspecialchars($refund->proof_of_payment); ?>" target="_blank">View Proof</a></td>
                                            <td><?php echo date('Y-m-d H:i:s', strtotime($refund->created_at)); ?></td>
                                            <td>
                                                <form action="approve_refund.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="refund_id" value="<?php echo $refund->refund_id; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                </form>
                                                <form action="reject_refund.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="refund_id" value="<?php echo $refund->refund_id; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>