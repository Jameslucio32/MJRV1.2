<?php
$admin_id = $_SESSION['admin_id'];
//$login_id = $_SESSION['login_id'];
$ret = "SELECT * FROM  rpos_admin  WHERE admin_id = '$admin_id'";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {

?>
  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="dashboard.php">
    
      </a>
      <!-- User -->
      <link href="https://fonts.googleapis.com/css?family=Anton" type="text/css" href="header.css" rel="stylesheet">
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="assets/img/theme/HEADER.png">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="change_profile.php" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="logout.php" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="dashboard.php">
                <img src="assets/img/brand/1.png">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <img src="assets/img/brand/panel.png" width="200" height="180" >
        <img src="assets/img/brand/admin.gif" width="200" height="150" >
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="ni ni-tv-2 text-primary"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="hrm.php">
              <i class="fas fa-user-tie text-primary"></i> Encoders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="customes.php">
              <i class="fas fa-users text-primary"></i> Customer
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="supplier.php">
              <i class="fas fa-building text-primary"></i> Supplier
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="view_barcode.php">
              <i class="ni ni-bullet-list-67 text-primary"></i>Products
            </a>
          </li>
       <!--
          <li class="nav-item">
            <a class="nav-link" href="orders.php">
              <i class="ni ni-cart text-primary"></i> Orders
            </a>
          </li>
          -->
          <link href="https://fonts.googleapis.com/css?family=Anton" type="text/css" href="header.css" rel="stylesheet">
          <li class="nav-item">
            <a class="nav-link" href="payments.php">
              <i class="ni ni-credit-card text-primary"></i> Payments
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="orders_reports.php">
              <i class="fas fa-file-invoice-dollar text-primary"></i> Receipts
            </a>
          </li>
        <li class="nav-item">
            <a class="nav-link" href="email.php">
              <i class="fas fa-id-card-alt text-primary"></i> Email
            </a>
          </li>
        </ul>
        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        <h6 class="navbar-heading text-muted">Reporting</h6>
        <!-- Navigation -->
        <ul class="navbar-nav mb-md-3">
          <li class="nav-item">
            <a class="nav-link" href="refund.php">
              <i class="fas fa-shopping-basket"></i> Item Refund
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="orders_reports.php">
              <i class="fas fa-shopping-basket"></i> Orders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="payments_reports.php">
              <i class="fas fa-dollar-sign"></i> Sales 
            </a>
          </li>
          <?php
// Assuming you have a database connection established
include('config/config.php');

// Query to check stock levels
$low_stock_query = "SELECT COUNT(*) as low_stock_count FROM rpos_products WHERE prod_stock < 3"; // Use 'rpos_products' for stock quantity
$result = $mysqli->query($low_stock_query);

// Check for query errors
if (!$result) {
    error_log("Query Error: " . $mysqli->error);
    $low_stock_count = 0; // Default to 0 if there's an error
} else {
    $row = $result->fetch_assoc();
    $low_stock_count = $row['low_stock_count'];
}

// Determine if there are low stock products
$show_warning = $low_stock_count > 0;
?>

<!-- Navigation Item -->
<li class="nav-item">
    <a class="nav-link" href="inventory_stock.php">
        <i class="fas fa-door-closed"></i> Inventory
        <?php if ($show_warning): ?>
            <span class="badge badge-danger" title="Low stock warning!">[Warning]</span>
        <?php endif; ?>
    </a>
</li>
        <li class="nav-item">
            <a class="nav-link" href="sales_predict.php" target="_blank">
            <i class="fas fa-bullhorn"></i> Sales Prediction
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="scan_barcode.php" target="_blank">
                <i class="fas fa-camera"></i>Scanner
            </a>
        </li>
          <li class="nav-item">
            <a class="nav-link" href="archived_payments.php">
              <i class="fas fa-archive"></i> Archived Payments
            </a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" href="archived_customers.php">
              <i class="fas fa-archive"></i> Archived Customers
            </a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" href="archived_staff.php">
              <i class="fas fa-archive"></i> Archived Encoders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="archived_suppliers.php">
              <i class="fas fa-archive"></i> Archived Supplier
            </a>
          </li>
        </ul>
        <hr class="my-3">
        <ul class="navbar-nav mb-md-3">
          <li class="nav-item">
            <a class="nav-link" href="logout.php">
              <i class="fas fa-sign-out-alt text-danger"></i> Log Out
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<?php } ?>