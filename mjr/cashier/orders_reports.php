<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php
        require_once('partials/_topnav.php');
        ?>
        <!-- Header -->
        <div style="background-image: url(../admin/assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
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
                            Orders Records
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th class="text-success" scope="col">Product</th>
                                        <th scope="col">Unit Price</th>
                                        <th class="text-success" scope="col">#</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Status</th>
                                        <th class="text-success" scope="col">Payment Method</th> <!-- New header for payment method -->
                                        <th class="text-success" scope="col">Date</th>
                                        <th scope="col">Actions</th> <!-- New header for actions -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Updated SQL query to include payment method
                                    $ret = "SELECT o.*, p.pay_method FROM rpos_orders o LEFT JOIN rpos_payments p ON o.order_code = p.order_code ORDER BY o.created_at DESC";
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
                                            <td class="text-success"><?php echo $order->prod_name; ?></td>
                                            <td>₱<?php echo number_format((float)str_replace(',', '', $order->prod_price), 2); ?></td>
                                            <td class="text-success"><?php echo $order->prod_qty; ?></td>
                                            <td>₱ <?php echo number_format($total, 2); ?></td>
                                            <td>
                                                <?php if ($order->order_status == '') {
                                                    echo "<span class='badge badge-danger'>Not Paid</span>";
                                                } else {
                                                    echo "<span class='badge badge-success'>$order->order_status</span>";
                                                } ?>
                                            </td>
                                            <td class="text-success"><?php echo htmlspecialchars($order->pay_method); ?></td> <!-- Display payment method -->
                                            <td class="text-success"><?php echo date('d/M/Y g:i a', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <?php if ($order->order_status == 'Approved'): ?>
                                                    <a href="print_receipt.php?order_code=<?php echo urlencode($order->order_code); ?>" class="btn btn-sm btn-primary">
                                                        Print Receipt
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span> <!-- Optional: Display a message when not available -->
                                                <?php endif; ?>
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
            <?php
            require_once('partials/_footer.php');
            ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>