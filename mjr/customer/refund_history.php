<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login(); // Ensure the user is logged in

// Get the customer ID from the session
$customer_id = $_SESSION['customer_id'];

require_once('partials/_head.php');
?>

<body>
    <!-- Main content -->
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
  
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>
        
        <!-- Header -->
        <div class="header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <h1 class="text-white">Your Refund History</h1>
                </div>
            </div>
        </div>
        
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Button to go back to Refund Requests -->
            <div class="row">
                <div class="col">
                    <a href="refundreq.php" class="btn btn-primary mb-3">Back to Refund Requests</a>
                </div>
            </div>
            
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Your Refund History
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Order Code</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Comments</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Proof of Payment</th>
                                        <th scope="col">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Prepare the SQL statement to fetch refund requests
                                    $ret = "SELECT * FROM refund_requests WHERE customer_id = ? ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('s', $customer_id); // Use 's' for varchar
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    
                                    // Check if there are any refund requests
                                    if ($res->num_rows > 0) {
                                        while ($refund = $res->fetch_object()) {
                                    ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($refund->order_id); ?></td> <!-- Use order_id instead of id -->
                                                <td><?php echo htmlspecialchars($refund->refund_reason); ?></td> <!-- Use refund_reason -->
                                                <td><?php echo htmlspecialchars($refund->refund_comments); ?></td> <!-- Use refund_comments -->
                                                <td><?php echo htmlspecialchars($refund->refund_status); ?></td> <!-- Use refund_status -->
                                                <td><a href="<?php echo htmlspecialchars($refund->proof_of_payment); ?>" target="_blank">View Proof</a></td> <!-- Use proof_of_payment -->
                                                <td><?php echo date('Y-m-d H:i:s', strtotime($refund->created_at)); ?></td> <!-- Use created_at -->
                                            </tr>
                                    <?php 
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No refund history found.</td></tr>";
                                    }
                                    ?>
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