<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Delete Supplier
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM rpos_suppliers WHERE supplier_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $success = "Deleted successfully!";
    } else {
        $err = "No supplier found with that ID.";
    }
    $stmt->close();
    header("refresh:1; url=supplier.php");
    exit;
}

// Archive Supplier
if (isset($_GET['archive'])) {
    $id = intval($_GET['archive']);
    $adn = "UPDATE rpos_suppliers SET archived = 1 WHERE supplier_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $success = "Supplier archived successfully!";
    } else {
        $err = "No changes made. Supplier may already be archived or not found.";
    }
    $stmt->close();
    header("refresh:1; url=supplier.php");
    exit;
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
          <h1 class="text-white">Supplier List</h1>
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
              <a href="add_supplier.php" class="btn btn-outline-success"><i class="fas fa-user-plus"></i>Add New Supplier</a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Supplier ID</th>
                    <th scope="col">Supplier Name</th>
                    <th scope="col">Supplier Contact Number</th>
                    <th scope="col">Supplier Email</th>
                    <th scope="col">Address</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_suppliers WHERE archived = 0"; // Exclude archived suppliers
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($supplier = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($supplier->supplier_id); ?></td>
                      <td><?php echo htmlspecialchars($supplier->supplier_name); ?></td>
                      <td><?php echo htmlspecialchars($supplier->supplier_phoneno); ?></td>
                      <td><?php echo htmlspecialchars($supplier->supplier_email); ?></td>
                      <td><?php echo htmlspecialchars($supplier->address); ?></td>
                      <td>
                        <a href="supplier.php?archive=<?php echo $supplier->supplier_id; ?>" onclick="return confirm('Are you sure you want to archive this supplier?');">
                          <button class="btn btn-sm btn-warning">
                            <i class="fas fa-archive"></i>
                            Archive
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