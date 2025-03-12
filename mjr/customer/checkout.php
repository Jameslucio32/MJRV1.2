<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Redirect to cart if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: view_cart.php");
    exit();
}

$total_price = 0;

// Calculate total price
foreach ($_SESSION['cart'] as $item) {
    $total_price += floatval(str_replace(',', '', $item['price'])) * intval($item['quantity']);
}

function generatePaymentCode() {
    return 'PAY' . strtoupper(uniqid());
}

$generated_pay_code = generatePaymentCode();
$formatted_total = number_format($total_price, 2);

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    
    <div class="main-content d-flex align-items-center" style="min-height: 100vh;">
        <?php require_once('partials/_topnav.php'); ?>
        
        <div class="container-fluid mt--8">
            <div class="row justify-content-center w-100">
                <div class="col-lg-10 col-md-12"> 
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="text-center">Checkout</h3>
                        </div>
                        <div class="card-body">
                            <h4>Your Cart Summary</h4>
                            <table class="table align-items-center table-flush text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $prod_id => $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td>₱<?php echo number_format(floatval(str_replace(',', '', $item['price'])), 2); ?></td>
                                            <td><?php echo intval($item['quantity']); ?></td>
                                            <td>₱<?php echo number_format(floatval(str_replace(',', '', $item['price'])) * intval($item['quantity']), 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td>₱<?php echo $formatted_total; ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <h4 class="mt-4">Payment Details</h4>
                            <form method="POST" action="process_checkout.php" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="pay_code">Payment Code <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" name="pay_code" value="<?php echo htmlspecialchars($generated_pay_code); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="pay_amt">Payment Amount <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" name="pay_amt" value="₱<?php echo $formatted_total; ?>" readonly>
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
                                <img id="gcashImage" src="assets/img/gcash.jpg" alt="Gcash Image" style="display: none; margin-top: 10px; width: 300px; height: auto;" class="img -fluid" />
                                <button type="submit" name="pay" class="btn btn-primary">Confirm Order</button>
                                <button type="button" class="btn btn-secondary" onclick="goBack()">Back to Cart</button>
                            </form>
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