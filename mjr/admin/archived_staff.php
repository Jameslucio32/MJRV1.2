<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Unarchive Staff
if (isset($_GET['unarchive'])) {
    $id = intval($_GET['unarchive']); // Ensure the ID is an integer
    $adn = "UPDATE rpos_staff SET status = 'active' WHERE staff_id = ?";
    $stmt = $mysqli->prepare($adn);
    
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) { // Check if any rows were affected
            $success = "Encoder member unarchived successfully!";
        } else {
            $err = "No changes made. Encoder member may already be active or not found.";
        }
        
        $stmt->close();
    } else {
        $err = "Database error: " . $mysqli->error;
    }
    
    // Redirect after processing
    header("Location: archived_staff.php");
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
          <h1 class="text-white">Archived Staff Members</h1>
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
              <h3 class="mb-0">List of Archived Encoder Members</h3>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Encoder Name</th>
                    <th scope="col">Encoder Number</th>
                    <th scope="col">Encoder Email</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_staff WHERE status = 'archived' ORDER BY created_at DESC";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($staff = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($staff->staff_name); ?></td>
                      <td><?php echo htmlspecialchars($staff->staff_number); ?></td>
                      <td><?php echo htmlspecialchars($staff->staff_email); ?></td>
                      <td>
                        <a href="archived_staff.php?unarchive=<?php echo $staff->staff_id; ?>" onclick="return confirm('Are you sure you want to unarchive this Encoder member?');">
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