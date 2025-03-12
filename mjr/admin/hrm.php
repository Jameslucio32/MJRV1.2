<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Delete Staff
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM rpos_staff WHERE staff_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $success = "Deleted successfully!";
    } else {
        $err = "No staff member found with that ID.";
    }
    $stmt->close();
    header("refresh:1; url=hrm.php");
    exit;
}

// Archive Staff
if (isset($_GET['archive'])) {
    $id = intval($_GET['archive']);
    $adn = "UPDATE rpos_staff SET status = 'archived' WHERE staff_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $success = "Staff member archived successfully!";
    } else {
        $err = "No changes made. Staff member may already be archived or not found.";
    }
    $stmt->close();
    header("refresh:1; url=hrm.php");
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
        <div class="header-body"></div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--8">
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <a href="add_staff.php" class="btn btn-outline-success"><i class="fas fa-user-plus"></i>Add New Encoder</a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Encoder Number</th>
                    <th scope="col">Encoder Name</th>
                    <th scope="col">Encoder Email</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_staff WHERE status != 'archived'"; // Exclude archived staff
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($staff = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($staff->staff_number); ?></td>
                      <td><?php echo htmlspecialchars($staff->staff_name); ?></td>
                      <td><?php echo htmlspecialchars($staff->staff_email); ?></td>
                      <td>
                     
                        <a href="hrm.php?archive=<?php echo $staff->staff_id; ?>" onclick="return confirm('Are you sure you want to archive this staff member?');">
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