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

// Create a new PHPMailer instance
$mail = new PHPMailer(true); // Passing `true` enables exceptions

// Function to send OTP email
function sendOtpEmail($to, $otp) {
    global $mail; // Use the global $mail instance

    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'mjrdiagnosticmedicalsupply@gmail.com'; // SMTP username
        $mail->Password = 'vspzoovsnlpupcgl'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('mjrdiagnosticmedicalsupply@gmail.com', 'Mailer');
        $mail->addAddress($to); // Add recipient's email

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Your OTP code is: <b>' . $otp . '</b>';
        $mail->AltBody = 'Your OTP code is: ' . $otp;

        // Send the email
        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false; // Email sending failed
    }
}

// Handle OTP request
if (isset($_POST['request_otp'])) {
    if (!filter_var($_POST['reset_email'], FILTER_VALIDATE_EMAIL)) {
        $err = 'Invalid Email';
    } else {
        $reset_email = $_POST['reset_email'];
        $checkEmail = mysqli_query($mysqli, "SELECT `admin_email` FROM `rpos_admin` WHERE `admin_email` = '$reset_email'") or exit(mysqli_error($mysqli));
        
        if (mysqli_num_rows($checkEmail) > 0) {
            // Generate OTP
            $otp = rand(100000, 999999); // Generate a 6-digit OTP
            
            // Send OTP to the user's email
            if (sendOtpEmail($reset_email, $otp)) {
                // Store OTP and email in the database
                $reset_token = sha1(md5($otp)); // Using OTP as token for simplicity
                $reset_status = "Pending";

                $query = "INSERT INTO rpos_pass_resets (reset_email, reset_code, reset_token, reset_status) VALUES (?, ?, ?, ?)";
                $reset = $mysqli->prepare($query);
                $reset->bind_param('ssss', $reset_email, $otp, $reset_token, $reset_status);
                $reset->execute();

                if ($reset) {
                    // Redirect to OTP confirmation page with email in the query string
                    header("Location: change_password.php?email=" . urlencode($reset_email));
                    exit;
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            } else {
                $err = "Failed to send OTP. Please try again.";
            }
        } else {
            $err = "No account with that email";
        }
    }
}

// Handle OTP confirmation and password change
if (isset($_POST['confirm_otp'])) {
    $reset_email = $_POST['reset_email'];
    $entered_otp = $_POST['otp_code'];
    $new_password = $_POST['new_password']; // Fixed typo here

    // Check if the OTP exists in the database
    $query = "SELECT * FROM rpos_pass_resets WHERE reset_email = ? AND reset_code = ? AND reset_status = 'Pending'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $reset_email, $entered_otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is valid, update the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
        $update_query = "UPDATE rpos_admin SET password = ? WHERE admin_email = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param('ss', $hashed_password, $reset_email);
        $update_stmt->execute();

        // Update the reset status
        $update_status_query = "UPDATE rpos_pass_resets SET reset_status = 'Used' WHERE reset_email = ?";
        $status_stmt = $mysqli->prepare($update_status_query);
        $status_stmt->bind_param('s', $reset_email);
        $status_stmt->execute();

        $success = "Password has been updated successfully.";
        header("Location: login.php"); // Redirect to login page after successful password change
        exit;
    } else {
        $err = "Invalid OTP. Please try again.";
    }
}

require_once('partials/_head.php');
?>

<body class="bg-dark">
  <div>
    <div class="main-content">
      <div class="header bg-gradient-primar py-7">
        <div class="container">
          <div class="header-body text-center mb-7">
            <div class="row justify-content-center">
              <div class="col-lg-5 col-md-6">
                <h1 class="text-white">MJR Diagnostic & Medical Supply</h1>
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
                <?php if (!isset($_GET['email'])): ?>
                  <form method="post" role="form">
                    <div class="form-group mb-3">
                      <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                        </div>
                        <input class="form-control" required name="reset_email" placeholder="Email" type="email">
                      </div>
                    </div>
                    <button type="submit" name="request_otp" class="btn-primary my-4">Send OTP</button>
                  </form>
                <?php else: ?>
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
                <?php endif; ?>
                <div class="text-center">
                  <a href="index.php" class="btn btn-primary my-4"><small>Log In?</small></a>
                </div>
                <a href="index.php" class="text-light"><button style="font-size:24px">Back<i class="fas fa-arrow-alt-circle-left"></i></button></i><small></small></a>
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