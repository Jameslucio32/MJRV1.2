<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

$alpha = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)); 
$beta = strtoupper(substr(md5(uniqid(rand(), true)), 5, 5)); 

if (isset($_POST['make'])) {
    if (empty($_POST["order_code"]) || empty($_POST["customer_name"]) || empty($_POST['prod_qty']) || empty($_GET['prod_price'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $order_id = $_POST['order_id'];
        $order_code  = $alpha . '-' . $beta; 
        $customer_id = $_SESSION['customer_id'];
        $customer_name = $_POST['customer_name'];
        $prod_id  = $_GET['prod_id'];
        $prod_name = $_GET['prod_name'];
        $prod_price = $_GET['prod_price'];
        $prod_qty = intval($_POST['prod_qty']); 

        $stmt = $mysqli->prepare("SELECT prod_stock, prod_expiry_date FROM rpos_products WHERE prod_id = ?");
        $stmt->bind_param("i", $prod_id);
        $stmt->execute();
        $stmt->bind_result($stock, $prod_expiry_date);
        $stmt->fetch();
        $stmt->close();

        if ($prod_qty > $stock) {
            $err = "You can only order up to $stock item(s) of this product.";
        } elseif ($prod_qty <= 0) {
            $err = "Invalid quantity. Please enter a value greater than 0.";
        } else {
            $postQuery = "INSERT INTO rpos_orders (prod_qty, order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price, prod_expiry_date) VALUES(?,?,?,?,?,?,?,?,?)";
            $postStmt = $mysqli->prepare($postQuery);
        
            $rc = $postStmt->bind_param('sssssssss', $prod_qty, $order_id, $order_code, $customer_id, $customer_name, $prod_id, $prod_name, $prod_price, $prod_expiry_date);
            $postStmt->execute();
         
            if ($postStmt) {
                $success = "Order Submitted";
                echo "<script>$('#successModal').modal('show');</script>"; // Show the modal
                header("refresh:1; url=payments.php");
                exit;
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div style="background-image: url(../admin/assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="btn btn-info">
                            <h3>Place Order</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" onsubmit="return confirmOrder();">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>Customer Name</label>
                                        <?php
                                        $customer_id = $_SESSION['customer_id'];
                                        $ret = "SELECT * FROM rpos_customers WHERE customer_id = '$customer_id'";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($cust = $res->fetch_object()) {
                                        ?>
                                            <input class="form-control" readonly name="customer_name" value="<?php echo $cust->customer_name; ?>">
                                        <?php } ?>
                                        <input type="hidden" name="order_id" value="<?php echo $orderid; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Order Code</label>
                                        <input type="text" readonly name="order_code" value="<?php echo $alpha . '-' . $beta; ?>" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <?php
                                $prod_id = $_GET['prod_id'];
                                $ret = "SELECT * FROM rpos_products WHERE prod_id = '$prod_id'";
                                $stmt = $mysqli->prepare($ret);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($prod = $res->fetch_object()) {
                                ?>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Product Price (₱)</label>
                                            <input type="text" readonly name="prod_price" value="₱ <?php echo number_format((float) str_replace(',', '', $prod->prod_price), 2); ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Available Stock</label>
                                            <input type="text" readonly name="prod_stock" value="<?php echo $prod->prod_stock; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Quantity</label>
                                            <input type="number" name="prod_qty" min="1" max="<?php echo $prod->prod_stock; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label>Product Description</label>
                                            <textarea readonly class="form-control" rows="4"><?php echo htmlspecialchars($prod->prod_desc); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Expiration Date</label>
                                            <input type="text" name="prod_expiry_date" class="form-control" value="<?php echo empty($prod->prod_expiry_date) ? 'N/A' : htmlspecialchars($prod->prod_expiry_date); ?>" readonly>
                                            <?php if (empty($prod->prod_expiry_date)) { ?>
                                                <span class="text-danger">This product did not expire</span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <br>
                                <div class="form-row">
                                    <div class="col-md-12 text-center">
                                        <div class="d-flex justify-content-center">
                                            <input type="submit" name="make" value="Proceed To Payment" class="btn btn-success mr-2">
                                            <a href="orders.php?" class="btn btn-info">
                                                <i class="fas fa-info-circle"></i> Back In Product List
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php if (isset($err)) { ?>
                                <div class="alert alert-danger mt-3"><?php echo $err; ?></div>
                            <?php } ?>
                            <?php if (isset($success)) { ?>
                                <div class="alert alert-success mt-3"><?php echo $success; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <?php require_once('partials/_scripts.php'); ?>

    <script>
        function confirmOrder() {
            return confirm("Are you sure you want to proceed with this order?");
        }
    </script>
</body>

</html>