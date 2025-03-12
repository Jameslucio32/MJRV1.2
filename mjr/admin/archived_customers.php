<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Unarchive Customer
if (isset($_GET['unarchive'])) {
    $id = $_GET['unarchive'];
    $adn = "UPDATE rpos_customers SET archived = 0 WHERE customer_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Customer Unarchived";
        header("refresh:1; url=archived_customers.php");
    } else {
        $err = "Try Again Later";
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
    <div style="background-image: url(assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
          <h1 class="text-white">Archived Customers</h1>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--8">
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">List of Archived Customers</h3>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Contact Number</th>
                    <th scope="col">Customer Email</th>
                    <th scope="col">Street Address</th>
                    <th scope="col">Barangay</th>
                    <th scope="col">City</th>
                    <th scope="col">Province</th>
                    <th scope="col">Postal Code</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_customers WHERE archived = 1 ORDER BY created_at DESC";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($cust = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($cust->customer_name); ?></td>
                      <td><?php echo htmlspecialchars($cust->customer_phoneno); ?></td>
                      <td><?php echo htmlspecialchars($cust->customer_email); ?></td>
                      <td><?php echo htmlspecialchars($cust->street_address); ?></td>
                      <td><?php echo htmlspecialchars ($cust->barangay); ?></td>
                      <td><?php echo htmlspecialchars($cust->city); ?></td>
                      <td><?php echo htmlspecialchars($cust->province); ?></td>
                      <td><?php echo htmlspecialchars($cust->postal_code); ?></td>
                      <td>
                        <a href="archived_customers.php?unarchive=<?php echo $cust->customer_id; ?>">
                          <button class="btn btn-sm btn-success">
                            <i class="fas fa-undo"></i>
                            Unarchive
                          </button>
                        </a>
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
  <?php require_once('partials/_scripts.php'); ?>
</body>

</html>