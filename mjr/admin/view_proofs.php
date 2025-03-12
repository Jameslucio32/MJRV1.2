<?php
session_start();
include('config/config.php');

// Check if order_code is provided
if (isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];

    // Fetch payment details based on order_code
    $query = "SELECT p.proof_of_payment 
              FROM rpos_payments p 
              WHERE p.order_code = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $order_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        $proof_path = $payment['proof_of_payment'];
        
        // Check if the file exists in the uploads directory
        if (file_exists($proof_path)) {
            $proof_image = htmlspecialchars($proof_path);
        } else {
            $error_message = "Proof of payment file not found.";
        }
    } else {
        $error_message = "No payment record found for this order.";
    }
} else {
    $error_message = "No order code provided.";
}

require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>
        <!-- Page content -->
        <div class="container-fluid mt--5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="text-center">Proof of Payment</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error_message)) { ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php } elseif (isset($proof_image)) { ?>
                                <h5>Order Code: <?php echo htmlspecialchars($order_code); ?></h5>
                                <p><strong>Proof of Payment:</strong></p>
                                <img src="<?php echo $proof_image; ?>" alt="Proof of Payment" class="proof-image" />
                                <p><a href="<?php echo $proof_image; ?>" target="_blank">View Full Image</a></p>
                            <?php } ?>
                        </div>
                        <div class="card-footer text-center">
                            <a href="payments.php" class="btn btn-primary">Back to Payments</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php require_once('partials/_footer.php'); ?>
</body>
</html>