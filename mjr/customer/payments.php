<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Cancel Order
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM rpos_orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=payments.php");
    } else {
        $err = "Try Again Later";
    }
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
                            <a href="orders.php" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> <i class="fas fa-cart-plus"></i>
                                Make A New Order
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Quantity</th> <!-- Quantity Column -->
                                        <th scope="col">Date</th>
                                        <th scope="col">Total Payment</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customer_id = $_SESSION['customer_id'];
                                    $ret = "SELECT * FROM rpos_orders WHERE order_status = '' AND customer_id = ? ORDER BY `created_at` DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('s', $customer_id);
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    while ($order = $res->fetch_object()) {
                                        // Calculate total payment (prod_price * prod_qty)
                                        $prod_price = (float) str_replace(',', '', $order->prod_price); // Remove commas if present and convert to float
                                        $prod_qty = (int) $order->prod_qty; // Cast to integer for quantity
                                        $total_payment = number_format($prod_price * $prod_qty, 2); // Total amount (formatted with commas)
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td><?php echo $order->prod_name; ?></td>
                                            <td><?php echo $order->prod_qty; ?></td> <!-- Quantity Column Display -->
                                            <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                            <td>₱ <?php echo $total_payment; ?></td> <!-- Total Payment Column -->
                                            <td>
                                                <a href="pay_order.php?order_code=<?php echo $order->order_code; ?>&customer_id=<?php echo $order->customer_id; ?>&order_status=Paid">
                                                    <button class="btn btn-sm btn-success">
                                                        <i class="fas fa-handshake"></i>
                                                        Pay Order
                                                    </button>
                                                </a>
                                                <button class="btn btn-sm btn-danger" onclick="confirmCancel('<?php echo $order->order_id; ?>')">
                                                    <i class="fas fa-window-close"></i>
                                                    Cancel Order
                                                </button>
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
    <script>
function confirmCancel(orderId) {
    if (confirm("Are you sure you want to cancel this order?")) {
        window.location.href = "payments.php?cancel=" + orderId;
    }
}
</script>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>
