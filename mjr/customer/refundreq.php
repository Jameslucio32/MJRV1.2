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
                    <h1 class="text-white">Your Refund Requests</h1>
                </div>
            </div>
        </div>
        
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Button for Refund History -->
            <div class="row">
                <div class="col">
                    <a href="refund_history.php" class="btn btn-primary mb-3">View Refund History</a>
                </div>
            </div>
            
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Your Refund Requests
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
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    // Query to fetch refund requests for the logged-in customer
                                    $ret = "SELECT * FROM refund_requests WHERE customer_id = ? ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('s', $customer_id); // Use 's' for varchar
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    if ($res->num_rows > 0) {
                                        while ($refund = $res->fetch_object()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $refund->order_id; ?></td> <!-- Use order_id -->
                                                <td><?php echo $refund->refund_reason; ?></td> <!-- Use refund_reason -->
                                                <td><?php echo $refund->refund_comments; ?></td> <!-- Use refund_comments -->
                                                <td><?php echo $refund->refund_status; ?></td> <!-- Use refund_status -->
                                                <td><a href="<?php echo $refund->proof_of_payment; ?>" target="_blank">View Proof</a></td> <!-- Use proof_of_payment -->
                                                <td><?php echo date('Y-m-d H:i:s', strtotime($refund->created_at)); ?></td> <!-- Use created_at -->
                                                <td>
                                                    <form action="cancel_refund.php" method="POST" style="display:inline;">
                                                        <input type="hidden" name="refund_id" value="<?php echo $refund->refund_id; ?>"> <!-- Use refund_id -->
                                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No refund requests found.</td></tr>";
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