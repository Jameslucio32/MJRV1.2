<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Check user role
$isAdmin = $_SESSION['staff_id'] === 'Encoder'; // Assuming user_role is set in session

// Cancel Order
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM rpos_orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id); // Use 's' for varchar
    $stmt->execute();
    if ($stmt) {
        $success = "Order cancelled successfully.";
        header("refresh:1; url=payments.php");
    } else {
        $err = "Try Again Later";
    }
    $stmt->close();
}

// Approve Order (Admin only)
if ($isAdmin && isset($_GET['approve'])) {
    $order_id = $_GET['approve'];

    // Get order details
    $orderQuery = "SELECT prod_id, prod_qty FROM rpos_orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($orderQuery);
    $stmt->bind_param('s', $order_id); // Use 's' for varchar
    $stmt->execute();
    $stmt->bind_result($prod_id, $quantity);
    $stmt->fetch();
    $stmt->close();

    // Update product stock
    $updateStock = "UPDATE rpos_products SET prod_stock = prod_stock - ? WHERE prod_id = ?";
    $stmt = $mysqli->prepare($updateStock);
    $stmt->bind_param('is', $quantity, $prod_id); // Use 'i' for integer and 's' for varchar
    $stmt->execute();
    $stmt->close();

    // Update order status
    $updateOrderStatus = "UPDATE rpos_orders SET order_status = 'Approved' WHERE order_id = ?";
    $stmt = $mysqli->prepare($updateOrderStatus);
    $stmt->bind_param('s', $order_id); // Use 's' for varchar
    $stmt->execute();
    $stmt->close();

    $success = "Order approved successfully.";
    header("refresh:1; url=payments.php");
    exit;
}

// Reject Order (Admin only)
if ($isAdmin && isset($_GET['reject'])) {
    $order_id = $_GET['reject'];
    $adn = "DELETE FROM rpos_orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $order_id); // Use 's' for varchar
    $stmt->execute();
    if ($stmt) {
        $success = "Order rejected successfully.";
        header("refresh:1; url=payments.php");
    } else {
        $err = "Try Again Later";
    }
    $stmt->close();
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
        <!-- Header -->
        <div style="background-image: url(../admin/assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Pending Orders</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_orders WHERE order_status = 'Pending' ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        $prod_price = (float) str_replace(',', '', $order->prod_price); // Remove commas if present
                                        $prod_qty = (int) $order->prod_qty; // Cast to integer
                                        $total = $prod_price * $prod_qty;
                                        
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td><?php echo $order->prod_name; ?></td>
                                            <td>₱ <?php echo number_format($total, 2); ?></td>
                                            <td><?php echo date('d/M/Y g:i a', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <span class="badge badge-warning">Waiting for Approval By Admin</span>
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
    <script>
        document.querySelectorAll('.approved-button').forEach(button => {
            button.addEventListener('click', function() {
                // Add the animation class
                this.classList.add('approved');
                // Optionally, you can disable the button to prevent multiple clicks
                this.disabled = true;
            });
        });
    </script>
</body>

</html>