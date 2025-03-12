<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Add Staff
if (isset($_POST['addStaff'])) {
    // Prevent Posting Blank Values
    if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $staff_number = $_POST['staff_number'];
        $staff_name = $_POST['staff_name'];
        $staff_email = $_POST['staff_email'];
        $staff_password = password_hash($_POST['staff_password'], PASSWORD_DEFAULT); // Use password_hash for secure password storage

        // Check if email already exists
        $checkEmailQuery = "SELECT * FROM rpos_staff WHERE staff_email = ?";
        $checkEmailStmt = $mysqli->prepare($checkEmailQuery);
        $checkEmailStmt->bind_param('s', $staff_email);
        $checkEmailStmt->execute();
        $result = $checkEmailStmt->get_result();

        if ($result->num_rows > 0) {
            $err = "Email already exists. Please use a different email.";
        } else {
            // Insert Captured information to a database table
            $postQuery = "INSERT INTO rpos_staff (staff_number, staff_name, staff_email, staff_password) VALUES(?,?,?,?)";
            $postStmt = $mysqli->prepare($postQuery);
            // Bind parameters
            $rc = $postStmt->bind_param('ssss', $staff_number, $staff_name, $staff_email, $staff_password);
            $postStmt->execute();

            // Check if the insert was successful
            if ($postStmt) {
                $success = "Staff Added Successfully!";
                header("refresh:1; url=hrm.php"); // Redirect after 1 second
                exit; // Ensure no further code is executed
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
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
              <h3>Please Fill All Fields</h3>
            </div>
            <div class="card-body">
              <?php if (isset($err)): ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo $err; ?>
                </div>
              <?php endif; ?>
              <?php if (isset($success)): ?>
                <div class="alert alert-success" role="alert">
                  <?php echo $success; ?>
                </div>
              <?php endif; ?>
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Encoder Number</label>
                    <input type="text" name="staff_number" class="form-control" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label>Encoder Name</label>
                    <input type="text" name="staff_name" class="form-control" required>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Encoder Email</label>
                    <input type="email" name="staff_email" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label>Encoder Password</label>
                    <input type="password" name="staff_password" class="form-control" required>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addStaff" value="Add Staff" class="btn btn-success">
                  </div>
                </div>
              </form>
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