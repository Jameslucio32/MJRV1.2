<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['remove'])) {
    $prod_id = $_GET['remove'];
    unset($_SESSION['cart'][$prod_id]);
    $_SESSION['success_message'] = "Product removed from cart successfully.";
}

if (isset($_POST['prod_id'])) {
    $prod_id = $_POST['prod_id'];
    $quantity = $_POST['quantity'];
    if ($quantity > 0) {
        $_SESSION['cart'][$prod_id]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$prod_id]);
        $_SESSION['success_message'] = "Product removed from cart successfully.";
    }
}

$total_price = 0;

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
                            <h3 class="mb-0">My Cart</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success text-center" role="alert" id="successMessage">
                                    <?php echo $_SESSION['success_message']; ?>
                                    <?php unset($_SESSION['success_message']); ?>
                                </div>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($_SESSION['cart'])): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Your cart is empty.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($_SESSION['cart'] as $prod_id => $item): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                    <td>₱<?php echo number_format(floatval(str_replace(',', '', $item['price'])), 2); ?></td>
                                                    <td>
                                                        <form method="POST" action="view_cart.php">
                                                            <input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>">
                                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 80px; display: inline;" onchange="this.form.submit();">
                                                        </form>
                                                    </td>
                                                    <td>₱<?php echo number_format(floatval(str_replace(',', '', $item['price'])) * intval($item['quantity']), 2); ?></td>
                                                    <td>
                                                        <a href="view_cart.php?remove=<?php echo $prod_id; ?>" class="btn btn-danger btn-sm">Remove</a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $total_price += floatval(str_replace(',', '', $item['price'])) * intval($item['quantity']);
                                                ?>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                                <td>₱<?php echo number_format($total_price, 2); ?></td>
                                                <td></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                            <a href="orders.php" class="btn btn-secondary">Back to Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('partials/_scripts.php'); ?>

<script>
    window.onload = function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 3000);
        }
    };
</script>
</body>
</html>