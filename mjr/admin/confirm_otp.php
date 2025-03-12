<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php'; // Adjust the path as needed
require_once('partials/_head.php'); // Include your head partial

// Database connection
$mysqli = new mysqli("localhost", "root", "", "rposystem"); // Update with your actual database details

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$err = '';
$success = '';

// Handle form submission
if (isset($_POST['confirm_otp'])) {
    $reset_email = $_POST['reset_email'];
    $entered_otp = $_POST['otp_code'];

    // Debugging: Print the email and entered OTP
    error_log("Email: $reset_email, Entered OTP: $entered_otp");

    // Check if the OTP exists in the database
    $query = "SELECT * FROM rpos_pass_resets WHERE reset_email = ? AND reset_code = ? AND reset_status = 'Pending'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $reset_email, $entered_otp);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging: Check how many rows were returned
    error_log("Number of rows found: " . $result->num_rows);

    if ($result->num_rows > 0) {
        // OTP is valid, allow user to reset their password
        $success = "OTP verified successfully. You can now reset your password.";
        // Optionally, you can redirect to a password reset form
         header("Location: reset_password.php?email=" . urlencode($reset_email));
    } else {
        $err = "Invalid OTP. Please try again.";
    }
}
?>

<body class="bg-dark">
  <div>
    <div class="main-content">
      <div class="header bg-gradient-primar py-7">
        <div class="container">
          <div class="header-body text-center mb-7">
            <div class="row justify-content-center">
              <div class="col-lg-5 col-md-6">
                <h1 class="text-white">Confirm Your OTP</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Page content -->
      <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
          <div class="col-lg-5 col-md-7">
            <div class="card">
              <div class="card-body px-lg-5 py-lg-5">
                <?php if ($err): ?>
                  <div class="alert alert-danger"><?php echo $err; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                  <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="post" role="form">
                  <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-key-25"></i></span>
                      </div>
                      <input class="form-control" required name="otp_code" placeholder="Enter OTP" type="text">
                    </div>
                  </div>
                  <input type="hidden" name="reset_email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                  <div class="text-center">
                    <button type="submit" name="confirm_otp" class="btn btn-primary my-4">Confirm OTP</button>
                  </div>
                </form>
 </div>
            </div>
            <div class="row mt-3">
              <div class="col -6">
                <a href="index.php" class="text-light"><small>Log In?</small></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer -->
    <?php
    require_once('partials/_footer.php');
    ?>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
  </div>
</body>

</html>