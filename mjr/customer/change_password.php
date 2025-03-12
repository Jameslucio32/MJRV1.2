<?php
require '../vendor/autoload.php'; // Adjust the path as needed
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$mysqli = new mysqli("localhost", "root", "", "rposystem"); // Replace with your actual database name

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle OTP confirmation and password change
if (isset($_POST['confirm_otp'])) {
    $reset_email = $_POST['reset_email'];
    $entered_otp = $_POST['otp_code'];
    $new_password = $_POST['new_password'];

    // Check if the OTP exists in the database
    $query = "SELECT * FROM rpos_pass_resets WHERE reset_email = ? AND reset_code = ? AND reset_status = 'Pending'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $reset_email, $entered_otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is valid, update the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE rpos_customers SET customer_password = ? WHERE customer_email = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param('ss', $hashed_password, $reset_email);
        $update_stmt->execute();

        // Update the reset status
        // Assuming the password column is named 'passwd'
$update_query = "UPDATE rpos_customers SET customer_password = ? WHERE customer_email = ?";
$update_stmt = $mysqli->prepare($update_query);
$update_stmt->bind_param('ss', $hashed_password, $reset_email);
$update_stmt->execute();

        echo "Password has been updated successfully.";
        header("Location: index.php");
        exit;
    } else {
        echo "Invalid OTP. Please try again.";
    }
}

require_once('partials/_head.php');
?>

<body class="bg-dark">
  <div>
    <div class="main-content">
      <div class="header bg-gradient-primar py-7">
        <div class="container">
          <div class="header-body text -center mb-7">
            <div class="row justify-content-center">
              <div class="col-lg-5 col-md-6">
                <h1 class="text-white">Change Password</h1>
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
                <form method="post" role="form">
                  <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                      </div>
                      <input class="form-control" required name="reset_email" placeholder="Email" type="email" value="<?php echo htmlspecialchars($_GET['email']); ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                      </div>
                      <input class="form-control" required name="otp_code" placeholder="Enter OTP" type="text">
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                      </div>
                      <input class="form-control" required name="new_password" placeholder="New Password" type="password">
                    </div>
                  </div>
                  <button type="submit" name="confirm_otp" class="btn-primary my-4">Confirm OTP and Change Password</button>
                </form>
                <div class="text-center">
                  <a href="index.php" class="btn btn-primary my-4"><small>Log In?</small></a>
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
  </div>
</body>

</html>