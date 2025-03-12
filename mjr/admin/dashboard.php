<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');
require_once('partials/_analytics.php');


// Calculate total amount for all payments
$total_query = "SELECT SUM(CAST(REPLACE(pay_amt, ',', '') AS DECIMAL(10, 2))) AS total_amount FROM rpos_payments";
$total_stmt = $mysqli->prepare($total_query);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_amount_paid = $total_row['total_amount'] ? $total_row['total_amount'] : 0; // Default to 0 if NULL
?>
<body>

  <!-- Sidenav -->
  <?php require_once('partials/_sidebar.php'); ?>
  
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php require_once('partials/_topnav.php'); ?>
    
    <!-- Header -->
    <div style="background-image: url(assets/img/icons/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-5"></span>
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
     <style> 
      .card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: .375rem;
    background-color:rgb(252, 252, 252);
    background-clip: border-box;
}
.card-header {
    margin-bottom: 0;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, .05);
    background-color:hsl(161, 100.00%, 65.70%);
}
body {
    font-family: Open Sans, sans-serif;
    font-size: 1.5rem;
    font-weight: 400;
    line-height: 1.5;
    margin: 0;
    text-align: left;
    color:rgb(14, 14, 14);
    background-color:rgb(255, 255, 255);
}
</style>
          <div class="row">
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-x1-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Customer</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $customers; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg text-white rounded-circle shadow">
                         <img src="assets/img/icons/1.png" width="110" height="80">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Products</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $products; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                       <img src="assets/img/icons/2.png" width="110" height="80">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Orders</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $orders; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg text-white rounded-circle shadow">
                      <img src="assets/img/icons/3.png" width="110" height="80">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text -muted mb-0">Sales</h5>
                      <span class="h2 font-weight-bold mb-0">₱<?php echo number_format($total_amount_paid, 2); ?></span> <!-- Format sales total -->
                    </div>
                    
                    <div class="col-auto">
                      <div class="icon icon-shape bg text-white rounded-circle shadow">
                      <img src="assets/img/icons/4.png" width="110" height="80">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="border">
                  <h3 class="mb-0">Recent Orders</h3>
                </div>
                <div class="col text-right">
                  <a href="orders_reports.php" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th class="text-success" scope="col"><b>Code</b></th>
                    <th scope="col"><b>Customer Name</b></th>
                    <th class="text-success" scope="col"><b>Product</b></th>
                    <th scope="col"><b>Unit Price</b></th>
                    <th class="text-success" scope="col"><b>Qty</b></th>
                    <th scope="col"><b>Total</b></th>
                    <th scope="col"><b>Status</b></th>
                    <th class="text-success" scope="col"><b>Date</b></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_orders ORDER BY `rpos_orders`.`created_at` DESC LIMIT 7";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($order = $res->fetch_object()) {
                      $prod_price = (float) str_replace(',', '', $order->prod_price); // Remove commas if present
                      $prod_qty = (int) $order->prod_qty; // Cast to integer
                      $total = $prod_price * $prod_qty;
                  ?>
                    <tr>
                      <th class="text-success" scope="row"><?php echo htmlspecialchars($order->order_code); ?></th>
                      <td><?php echo htmlspecialchars($order->customer_name); ?></td>
                      <td class="text-success"><?php echo htmlspecialchars($order->prod_name); ?></td>
                      <td>₱<?php echo number_format($prod_price, 2); ?></td> <!-- Format unit price -->
                      <td class="text-success"><?php echo $prod_qty; ?></td>
                      <td>₱<?php echo number_format($total, 2); ?></td> <!-- Format total with commas -->
                      <td><?php if ($order->order_status == '') {
                          echo "<span class='badge badge-danger'>Not Paid</span>";
                      } else {
                          echo "<span class='badge badge-success'>" . htmlspecialchars($order->order_status) . "</span>";
                      } ?></td>
                      <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
 <style>
  .border{
    color: black;
    border: 2px solid black;
    font: 1em sans-serif;
    padding: 0.5em;
    border-radius: 0.5em;
    box-shadow: 0 0 1px rgba(0, 0, 0)
  }
 </style>
      <div class="row mt-5">
    <div class="col-xl-12">
        <div class="card shadow">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="border">
                        <h3 class="mb-0">Recent Payments</h3>
                    </div>
                    <div class="col text-right">
                        <a href="payments_reports.php" class="btn btn-sm btn-primary">See all</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <!-- Total Amount for All Payments -->
                <?php
                // Calculate total amount for all payments
                $total_query = "SELECT SUM(CAST(REPLACE(pay_amt, ',', '') AS DECIMAL(10, 2))) AS total_amount FROM rpos_payments";
                $total_stmt = $mysqli->prepare($total_query);
                $total_stmt->execute();
                $total_result = $total_stmt->get_result();
                $total_row = $total_result->fetch_assoc();
                $total_amount_paid = $total_row['total_amount'] ? $total_row['total_amount'] : 0; // Default to 0 if NULL
                ?>
                <div class="border">
                    <h5 class="text-warning">Total Payments: ₱<?php echo number_format($total_amount_paid, 2); ?></h5>
                </div>
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-success" scope="col"><b>Code</b></th>
                            <th scope="col"><b>Amount</b></th>
                            <th class='text-success' scope="col"><b>Order Code</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch recent payments (last 7)
                        $recent_payments_query = "SELECT * FROM rpos_payments ORDER BY `created_at` DESC LIMIT 7";
                        $stmt = $mysqli->prepare($recent_payments_query);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while ($payment = $res->fetch_object()) {
                        ?>
                            <tr>
                                <th class="text-success" scope="row">
                                    <?php echo htmlspecialchars($payment->pay_code); ?>
                                </th>
                                <td>
                                    <?php 
                                    // Clean the pay_amt by removing any unwanted characters
                                    $cleaned_amount = preg_replace('/[^\d.]/', '', $payment->pay_amt); // Remove non-numeric characters
                                    echo '₱' . number_format((float)$cleaned_amount, 2); // Format the amount with commas and two decimal places
                                    ?>
                                </td>
                                <td class='text-success'>
                                    <?php echo htmlspecialchars($payment->order_code); ?>
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
<!-- Argon Scripts -->
<?php
require_once('partials/_scripts.php');
?>
</body>

</html>