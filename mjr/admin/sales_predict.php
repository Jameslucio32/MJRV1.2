<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');

$payment_data = [];
$payment_dates = [];
$total_payments = 0;
$sales_query = "SELECT * FROM rpos_payments";


for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $mysqli->prepare("SELECT SUM(pay_amt) as total_amount FROM rpos_payments WHERE DATE(created_at) = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $daily_amount = $row['total_amount'] ? (float)$row['total_amount'] : 0;
    $payment_data[] = $daily_amount;
    $payment_dates[] = date('d/M', strtotime($date));
    
    $total_payments += $daily_amount;
}


$total_amount_paid_query = "SELECT SUM(pay_amt) as total_amount_paid FROM rpos_payments";
$total_amount_paid_result = $mysqli->query($total_amount_paid_query); 
$total_amount_paid_row = $total_amount_paid_result->fetch_assoc();
$total_amount_paid = isset($total_amount_paid_row['total_amount_paid']) ? (float)$total_amount_paid_row['total_amount_paid'] : 0;


$most_bought_items_query = "
    SELECT product_name, SUM(quantity) AS total_quantity 
    FROM rpos_payments 
    GROUP BY product_name 
    ORDER BY total_quantity DESC 
    LIMIT 10";
$most_bought_items_result = $mysqli->query($most_bought_items_query);
$most_bought_items = mysqli_fetch_all($most_bought_items_result, MYSQLI_ASSOC);


$monthly_sales_query = "SELECT MONTH(created_at) AS month, SUM(pay_amt) AS total_sales FROM rpos_payments GROUP BY MONTH(created_at)";
$monthly_sales_result = $mysqli->query($monthly_sales_query);
$monthly_sales = mysqli_fetch_all($monthly_sales_result, MYSQLI_ASSOC);

$weekly_sales_query = "SELECT WEEK(created_at) AS week, SUM(pay_amt) AS total_sales FROM rpos_payments GROUP BY WEEK(created_at)";
$weekly_sales_result = $mysqli->query($weekly_sales_query);
$weekly_sales = mysqli_fetch_all($weekly_sales_result, MYSQLI_ASSOC);
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div style="background-image: url(assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-5"></span>
        </div>
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col-xl-12 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Payment Reports</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                <tr>
                                            <th class="text-success" scope="col">Payment Code</th>
                                            <th scope="col">Payment Method</th>
                                            <th class="text-success" scope="col">Order Code</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Quantity</th> 
                                            <th scope="col">Amount Paid</th>
                                            <th class="text-success" scope="col">Date Paid</th>
                                            <th class="text-success" scope="col">Proof of Payment</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php
                                        $ret = "SELECT * FROM rpos_payments ORDER BY `created_at` DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        
                                        $total_amount_paid = 0;
                                        while ($payment = $res->fetch_object()) {
                                            
                                            $raw_amount = str_replace(',', '', $payment->pay_amt);
                                            $amount = !empty($raw_amount) ? (float)$raw_amount : 0;
                                            
                                      
                                            $total_amount_paid += $amount;
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
                                                <td>
                                                    <?php echo htmlspecialchars($payment->product_name); ?> 
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($payment->quantity); ?>
                                                </td>
                                                <td>
                                                    ₱<?php echo number_format($amount, 2); ?> 
                                                </td>
                                                <td class="text-success">
                                                    <?php echo date('d/M/Y g:i a', strtotime($payment->created_at)); ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($payment->proof_of_payment)) { ?>
                                                        <img src="<?php echo htmlspecialchars($payment->proof_of_payment); ?>" alt="Proof of Payment" style="max-width: 100px; max-height: 100px;"/>
                                                    <?php } else { ?>
                                                        No Proof Uploaded
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <!-- Total Row -->
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Total:</td>
                                            <td class="font-weight-bold">
                                                ₱<?php echo number_format($total_amount_paid, 2); ?>
                                            </td>
                                            <td colspan="2"></td> <!-- Empty cells for alignment -->
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Most Bought Products</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Product Name</th>
                                        <th scope="col">Total Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($most_bought_items as $item) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                            <td><?php echo htmlspecialchars($item['total_quantity']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Monthly Sales</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Month</th>
                                        <th scope="col">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($monthly_sales as $sale) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($sale['month']); ?></td>
                                            <td>₱<?php echo number_format((float)$sale['total_sales'], 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Weekly Sales</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Week</th>
                                        <th scope="col">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($weekly_sales as $sale) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($sale['week']); ?></td>
                                            <td>₱<?php echo number_format((float)$sale['total_sales'], 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Total Sales</h3>
                        </div>
                        <div class="card-body">
                            <h4>Total Sales Amount: ₱<?php echo number_format($total_amount_paid, 2); ?></h4>
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