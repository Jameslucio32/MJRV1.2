<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Unarchive Supplier
if (isset($_GET['unarchive'])) {
    $id = intval($_GET['unarchive']); // Ensure the ID is an integer
    $adn = "UPDATE rpos_suppliers SET archived = 0 WHERE supplier_id = ?";
    $stmt = $mysqli->prepare($adn);
    
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) { // Check if any rows were affected
            $success = "Supplier unarchived successfully!";
        } else {
            $err = "No changes made. Supplier may already be active or not found.";
        }
        
        $stmt->close();
    } else {
        $err = "Database error: " . $mysqli->error;
    }
    
    // Redirect after processing
    header("Location: archived_suppliers.php");
    exit; // Ensure no further code is executed
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
          <h1 class="text-white">Archived Suppliers</h1>
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
              <h3 class="mb-0">List of Archived Suppliers</h3>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Supplier Name</th>
                    <th scope="col">Supplier Contact Number</th>
                    <th scope="col">Supplier Email</th>
                    <th scope="col">Address</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_suppliers WHERE archived = 1 ORDER BY created_at DESC"; // Fetch archived suppliers
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($supplier = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($supplier->supplier_name); ?></td>
                      <td><?php echo htmlspecialchars($supplier->supplier_phoneno); ?></td>
                      <td><?php echo htmlspecialchars($supplier->supplier_email); ?></td>
                      <td><?php echo htmlspecialchars($supplier->address); ?></td>
                      <td>
                        <a href="archived_suppliers.php?unarchive=<?php echo $supplier->supplier_id; ?>" onclick="return confirm('Are you sure you want to unarchive this supplier?');">
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