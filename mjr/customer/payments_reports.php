<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div style="background-image: url(../admin/assets/img/theme/HEADER.png); background-size: cover;"
            class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Payment Reports
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Payment Code</th>
                                        <th scope="col">Payment Method</th>
                                        <th class="text-success" scope="col">Order Code</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Amount Paid</th>
                                        <th class="text-success" scope="col">Date Paid</th>
                                        <th class="text-success" scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $customer_id = $_SESSION['customer_id'];
                                    $ret = "SELECT p.*, o.order_status, o.prod_name FROM rpos_payments p JOIN rpos_orders o ON p.order_code = o.order_code WHERE p.customer_id = ? ORDER BY p.created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('s', $customer_id);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($payment = $res->fetch_object()) {
                                        $pay_amt = preg_replace("/[^0-9.]/", "", $payment->pay_amt);
                                        ?>
                                        <tr>
                                            <th class="text-success" scope="row">
                                                <?php echo htmlspecialchars($payment->pay_code); ?>
                                            </th>
                                            <th scope="row">
                                                <?php echo htmlspecialchars($payment->pay_method); ?>
                                            </th>
                                            <td class="text-success">
                                                <?php echo htmlspecialchars($payment->order_code); ?>
                                            </td>
                                            <td class="text-success">
                                                <?php echo htmlspecialchars($payment->prod_name); ?>
                                            </td>
                                            <td>
                                                ₱<?php echo number_format((float) $pay_amt, 2); ?>
                                            </td>
                                            <td class="text-success">
                                                <?php echo date('d/M/Y g:i', strtotime($payment->created_at)); ?>
                                            </td>
                                            <td class="text-success">
                                                <?php echo ucfirst($payment->order_status); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>