<?php
session_start();
include('config/config.php');

// Login 
if (isset($_POST['login'])) {
    // Use htmlspecialchars to prevent XSS attacks
    $staff_email = htmlspecialchars(trim($_POST['staff_email']));
    $staff_password = trim($_POST['staff_password']); // No need for htmlspecialchars on passwords

    // Fetch user from the database
    $query = "SELECT * FROM rpos_staff WHERE staff_email = ?";
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param('s', $staff_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($staff_password, $user['staff_password'])) { // Ensure you use the correct column name
                // Password is correct, store user ID in session
                $_SESSION['staff_id'] = $user['staff_id']; // Store user ID in session
                header("Location: dashboard.php"); // Redirect to dashboard
                exit; // Ensure no further code is executed
            } else {
                $err = "Invalid password."; // Handle incorrect password
            }
        } else {
            $err = "No account found with that email."; // Handle no user found
        }
    } else {
        // Handle statement preparation error
        $err = "Database error: Could not prepare statement.";
    }
}

require_once('partials/_head.php');
?>
<body class="bg-dark">
  <div class="main-content">
    <div class="header bg-gradient-primary py-7"> <!-- Corrected class name -->
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-black">MJR Diagnostic & Medical Supply</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <h1></h1>
              <img src="assets/img/logo.png" width="350" height="300">
              <form method="post" role="form">
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" required name="staff_email" placeholder="Email" type="email">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend"> <!-- Fixed class name -->
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input id="password" class="form-control" required name="staff_password" placeholder="Password" type="password">
                  </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id="customCheckLogin" type="checkbox" onclick="togglePasswordVisibility()">
                  <label class="custom-control-label" for="customCheckLogin">
                    <span class="text-muted">Show Password</span>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" name="login" class="btn btn-primary my-4">Log In</button>
                </div>
              </form>
              <a href="../../index.php" class="text-light">
                <button style="font-size:24px">Back<i class="fas fa-arrow-alt-circle-left"></i></button>
              </a>
              <?php if (isset($err)): ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo $err; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="forgot_pwd.php" target="_blank" class="text-light"><small>Forgot password?</small></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <?php require_once('partials/_footer.php'); ?>
  <!-- Argon Scripts -->
  <?php require_once('partials/_scripts.php'); ?>

  <script>
    function togglePasswordVisibility() {
      const passwordField = document.getElementById('password');
      const checkbox = document.getElementById('customCheckLogin');
      if (checkbox.checked) {
        passwordField.type = 'text'; // Show password
      } else {
        passwordField.type = 'password'; // Hide password
      }
    }
  </script>
</body>