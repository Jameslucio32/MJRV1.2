<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
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
                                        <th scope="col">#</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Status</th>
                                        <th class="text-success" scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customer_id = $_SESSION['customer_id'];
                                    $ret = "SELECT * FROM rpos_orders WHERE customer_id ='$customer_id' ORDER BY `created_at` DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        // Ensure prod_price is a valid number and remove commas if necessary
                                        $prod_price = (float) str_replace(',', '', $order->prod_price); // Cast to float
                                        $prod_qty = (int) $order->prod_qty; // Cast to integer

                                        // Ensure prod_price and prod_qty are numeric
                                        if (is_numeric($prod_price) && is_numeric($prod_qty)) {
                                            $total = number_format($prod_price * $prod_qty, 2); // Calculate total price with commas
                                        } else {
                                            $total = 0; // If not numeric, set total to 0
                                        }
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td class="text-success"><?php echo $order->prod_name; ?></td>
                                            <td>₱ <?php echo number_format($prod_price, 2); ?></td> <!-- Unit price with commas -->
                                            <td class="text-success"><?php echo $order->prod_qty; ?></td>
                                            <td>₱ <?php echo $total; ?></td> <!-- Total price with commas -->
                                            <td><?php if ($order->order_status == '') {
                                                    echo "<span class='badge badge-danger'>Not Paid</span>";
                                                } else {
                                                    echo "<span class='badge badge-success'>$order->order_status</span>";
                                                } ?></td>
                                            <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#refundModal" data-order-code="<?php echo $order->order_code; ?>">Request Refund</button>
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

    <!-- Refund Request Modal -->
    <div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="refundModalLabel">Request Refund</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Order Code:</strong> <span id="modalOrderCodeDisplay"></span></p>
                    <p>Please contact customer support to request a refund for this order.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Argon Scripts -->
    <?php require_once('partials/_scripts.php'); ?>

    <script>
        // Set the order code in the modal when it is opened
        $('#refundModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var orderCode = button.data('order-code'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('#modalOrderCodeDisplay').text(orderCode); // Set the order code in the modal display
        });
    </script>
</body>

</html>