<?php
session_start();
include('config/config.php');

// Login logic
if (isset($_POST['login'])) {
    $admin_email = htmlspecialchars(trim($_POST['admin_email']));
    $admin_password = trim($_POST['admin_password']);

    $query = "SELECT * FROM rpos_admin WHERE admin_email = ?";
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param('s', $admin_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($admin_password, $user['admin_password'])) {
                $_SESSION['admin_id'] = $user['admin_id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $err = "Invalid password.";
            }
        } else {
            $err = "No account found with that email.";
        }
    } else {
        $err = "Database error: Could not prepare statement.";
    }
}

require_once('partials/_head.php');
?>

<body>
  <div class="main-content">
    <div class="header bg-gradient-primary py-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <h1 class="text-white">MJR Diagnostic & Medical Supply</h1>
        </div>
      </div>
    </div>
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-light shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center mb-4">
                <img src="assets/img/icons/logo.png" width="150" height="150" alt="Logo">
              </div>
              <form method="post" role="form">
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" required name="admin_email" placeholder="Email" type="email">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input id="password" class="form-control" required name="admin_password" placeholder="Password" type="password">
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
                <div class="text-center mb-4">
                <a href="../../index.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back
                </a>
              </div>
              </form>
              <div class="text-center">
                <a href="forgot_pwd.php" class="text-muted"><small>Forgot password?</small></a>
              </div>
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
      passwordField.type = checkbox.checked ? 'text' : 'password'; // Toggle password visibility
    }
  </script>
</body>