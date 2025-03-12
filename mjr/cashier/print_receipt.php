<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="MJR Diagnostic & Medical Supply Receipt">
    <meta name="author" content="MartDevelopers Inc">
    <title>MJR Diagnostic & Medical Supply</title>

    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icons/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/flogo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/logo.png">
    <link rel="manifest" href="assets/img/icons/site.webmanifest">
    <link rel="mask-icon" href="assets/img/icons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="assets/css/bootstrap.css" rel="stylesheet" id="bootstrap-css">
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <style>
        body {
            margin-top: 20px;
            font-family: Arial, sans-serif;
            font-size: 1em; 
        }

        #Receipt {
            border: 1px solid #ccc;
            padding: 20px; 
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            max-width: 200px; 
            height: auto; 
            min-height: 200px; 
            margin: auto; 
            text-align: center; 
        }

        h2 {
            margin: 10px 0; 
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0; 
        }

        .text-danger {
            color: red;
        }

        .btn {
            margin-top: 10px; 
            display: block; 
            width: 50%; 
        }
    </style>
</head>

<?php
$order_code = $_GET['order_code'];
$ret = "SELECT o.*, p.pay_method FROM rpos_orders o LEFT JOIN rpos_payments p ON o.order_code = p.order_code WHERE o.order_code = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('s', $order_code);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_object();

if ($order) {
    $prod_price = (float) str_replace(',', '', $order->prod_price); 
    $prod_qty = (int) $order->prod_qty; 
    $total = $prod_price * $prod_qty;
    $tax = ($total * 0.12);
    $vat = ($total - $tax);
?>

<body>
    <div class="container">
        <div class="row">
            <div id="Receipt" class="col-xs-10 col-sm-10 col-md-6">
                <div class="text-center">
                    <br>MJR Diagnostic & Medical Supply<br>
                    Eric A.Reyes - Proprietor<br>
                    Address:#98 Pama St. Ma.Socorro Subdivision<br>
                    Contact:09175081876/0449136691<br>
                    mjr2014diagnostic@yahoo.com
                </div>
                <h2>Receipt Details</h2>
                <div class="text-center">
                    <p><em>ApproveDate:<?php echo date('d/M/Y g:ia',strtotime($order->created_at));?></em></p>
                    <p><em>Printed Date:<?php echo date('d/M/Y g:ia'); ?></em></p>
                    <p><em class="text-success">Receipt #:<?php echo $order->order_code; ?></em></p>
                </div>
                <div class="receipt-item">
                    <span><strong>Payment Method:</strong></span>
                    <span><?php echo htmlspecialchars($order->pay_method); ?></span>
                </div>
                <div class="receipt-item">
                    <span><?php echo $order->prod_name; ?>:</span>
                    <td><?php echo number_format((float)str_replace(',', '', $order->prod_price), 2); ?></td>
                </div>
                <div class="receipt-item">
                    <span><strong>VAT Sales:</strong></span>
                    <span><?php echo number_format($vat, 2); ?></span>
                </div>
                <div class="receipt-item">
                    <span><strong>Tax (12%):</strong></span>
                    <span><?php echo number_format($tax, 2); ?></span>
                </div>
                <div class="receipt-item">
                    <span><strong>Total:</strong></span>
                    <span><?php echo number_format($total, 2); ?></span>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button id="print" onclick="printContent('Receipt');" class="btn btn-success btn-lg">
                Print <span class="fas fa-print"></span>
            </button>
            <a href="download_receipt.php?order_code=<?php echo urlencode($order->order_code); ?>" class="btn btn-primary btn-lg">
                Download <span class="fas fa-download"></span>
            </a>
            <a href="email.php?order_code=<?php echo urlencode($order->order_code); ?>" class="btn btn-warning btn-lg">
                Email <span class="fas fa-envelope"></span>
            </a>
            <a href="orders_reports.php" class="btn btn-secondary btn-lg">
                Back <span class="fas fa-arrow-left"></span>
            </a>
        </div>
    </div>
</body>

<script>
    function printContent(el) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(el).cloneNode(true);
        document.body.innerHTML = '';
        document.body.appendChild(printcontent);
        window.print();
        document.body.innerHTML = restorepage;
    }
</script>

</html>
<?php
} else {
    echo "<div class='container'><h2>No order found!</h2></div>";
}
?>