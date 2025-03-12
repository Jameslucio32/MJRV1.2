<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Retrieve parameters from the URL
$prod_id = isset($_GET['prod_id']) ? $_GET['prod_id'] : '';
$prod_price = isset($_GET['prod_price']) ? $_GET['prod_price'] : '';
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';

// Fetch customer name if customer_id is provided
$customer_name = '';
if ($customer_id) {
    $query = "SELECT customer_name FROM rpos_customers WHERE customer_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $customer_name = $row['customer_name'];
    }
}

// Fetch product details if prod_id is provided
$product_details = [];
if ($prod_id) {
    $query = "SELECT * FROM rpos_products WHERE prod_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $prod_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $product_details = $row;
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
    <div class="container-fluid mt--0">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Please Fill All Fields</h3>
            </div>
            <div class="card-body">
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-4">
                    <label>Client Name</label>
                    <select class="form-control" name="customer_name" id="custName" onChange="getCustomer(this.value)">
                      <option value="">Select Client Name</option>
                      <?php
                      // Load All Customers
                      $ret = "SELECT * FROM rpos_customers";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      while ($cust = $res->fetch_object()) {
                      ?>
                        <option value="<?php echo $cust->customer_id; ?>" <?php echo ($cust->customer_name == $customer_name) ? 'selected' : ''; ?>>
                          <?php echo $cust->customer_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                    <input type="hidden" name="order_id" value="<?php echo $orderid; ?>" class="form-control">
                  </div>

                  <div class="col-md-4">
                    <label>Client ID</label>
                    <input type="text" name="customer_id" readonly id="customerID" value="<?php echo htmlspecialchars($customer_id); ?>" class="form-control">
                  </div>

                  <div class="col-md-4">
                    <label>Order Code</label>
                    <input type="text" name="order_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" readonly>
                  </div>
                </div>
                <hr>
                <?php if ($prod_id && !empty($product_details)) { ?>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product ID</label>
                    <input type="text" readonly name="prod_id" value="<?php echo htmlspecialchars($product_details['prod_id']); ?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Product Name</label>
                    <p class="form-control-plaintext"><?php echo htmlspecialchars($product_details['prod_name']); ?></p>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Price (₱)</label>
                    <input type="text" readonly name="prod_price" value="₱ <?php echo number_format($product_details['prod_price'], 2); ?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Product Quantity</label>
                    <input type="number" name="prod_qty" class="form-control" value="" min="1" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Total Order Amount (₱)</label>
                    <input type="text" readonly name="total_amount" id="totalAmount" class="form-control">
                  </div>
                </div>
              <?php } ?>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="make" value="Make Order" class="btn btn-success">
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
      <!-- Footer -->
      
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
  <script>
    // Calculate total amount based on product price and quantity
    document.querySelector('input[name="prod_qty"]').addEventListener('input', function() {
      const price = parseFloat(document.querySelector('input[name="prod_price"]').value.replace('₱ ', '').replace(',', ''));
      const quantity = parseInt(this.value) || 0;
      const total = price * quantity;
      document.getElementById('totalAmount').value = '₱ ' + total.toFixed(2);
    });
  </script>
</body>

</html>