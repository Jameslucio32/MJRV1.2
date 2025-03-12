<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

function generatePaymentCode() {
    return 'PAY' . strtoupper(uniqid());
}

if (isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];
    $query = "SELECT * FROM rpos_orders WHERE order_code = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $order_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_object();
        $prod_price = (float) str_replace(',', '', $order->prod_price);
        $prod_qty = (int) $order->prod_qty;
        $total = $prod_price * $prod_qty;
        $product_name = $order->prod_name; // Fetch the product name
    } else {
        $err = "Order not found.";
    }
} else {
    $err = "No order code provided.";
}

if (isset($_POST['pay'])) {
    if (empty($_POST["pay_amt"]) || empty($_POST['pay_method']) || empty($_FILES['proof_of_payment']['name']) || empty($_POST['quantity'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $pay_code = $_POST['pay_code'];
        $customer_id = $order->customer_id;
        $pay_amt = $_POST['pay_amt'];
        $pay_method = $_POST['pay_method'];
        $quantity = $_POST['quantity']; // Get the quantity from the form

        $proof_of_payment = $_FILES['proof_of_payment'];
        $upload_dir = '../admin/uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($proof_of_payment['name'], PATHINFO_EXTENSION);
        $new_file_name = $pay_code . '.' . $file_extension;
        $upload_file = $upload_dir . $new_file_name;

        if (move_uploaded_file($proof_of_payment['tmp_name'], $upload_file)) {
            $pay_id = uniqid('pay_');
            $order_status = 'Pending';
        
            $postQuery = "INSERT INTO rpos_payments (pay_id, pay_code, order_code, customer_id, pay_amt, pay_method, proof_of_payment, product_name, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $upQry = "UPDATE rpos_orders SET order_status = ? WHERE order_code = ?";
        
            $postStmt = $mysqli->prepare($postQuery);
            $upStmt = $mysqli->prepare($upQry);
        
       
            $postStmt->bind_param('sssssssss', $pay_id, $pay_code, $order_code, $customer_id, $pay_amt, $pay_method, $upload_file, $product_name, $quantity);
            $upStmt->bind_param('ss', $order_status, $order_code);
        
            if ($postStmt->execute() && $upStmt->execute()) {
                $success = "Your order is now pending. Please wait for admin approval.";
                header("refresh:2; url=payments_reports.php");
                exit;
            } else {
                $err = "Please Try Again Or Try Later: " . $postStmt->error;
            }
        } else {
            $err = "Failed to upload proof of payment.";
        }
    }
}

$generated_pay_code = generatePaymentCode();
$formatted_total = isset($total) ? ' ' . number_format($total, 2) : ' 0.00';
require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div class="container-fluid mt--8">
            <div class="row justify-content-center mt-5">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="text-center">Please Fill All Fields</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="pay_code">Payment Code <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" name="pay_code" value="<?php echo htmlspecialchars($generated_pay_code); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="product_name">Product Name <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Quantity <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" name="quantity" value="<?php echo htmlspecialchars($prod_qty); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="pay_amt">Payment Amount <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" name="pay_amt" value="<?php echo $formatted_total; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="pay_method">Payment Method <small class="text-danger">*</small></label>
                                    <select class="form-control" name="pay_method" id="pay_method" required onchange="showImage()">
                                        <option value="">Select Payment Method</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Gcash">Gcash</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="proof_of_payment">Proof of Payment <small class="text-danger">*</small></label>
                                    <input type="file" class="form-control" name="proof_of_payment" accept="image/*,application/pdf" required>
                                </div>
                                <img id="gcashImage" src="assets/img/gcash.jpg" alt="Gcash Image" style="display: none; margin-top: 10px; width: 300px; height: auto;" class="img-fluid" />
                                <button type="submit" name="pay" class="btn btn-primary">Submit Payment</button>
                                <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
                            </form>
                            <?php if (isset($err)) { ?>
                                <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($err); ?></div>
                            <?php } ?>
                            <?php if (isset($success)) { ?>
                                <div class="alert alert-success mt-3"><?php echo htmlspecialchars($success); ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showImage() {
            var paymentMethod = document.getElementById("pay_method").value;
            var gcashImage = document.getElementById("gcashImage");

            if (paymentMethod === "Gcash") {
                gcashImage.style.display = "block";
            } else {
                gcashImage.style.display = "none";
            }
        }

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>