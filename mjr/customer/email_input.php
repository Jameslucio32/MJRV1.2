<?php
session_start();
include('config/config.php');

require '../vendor/autoload.php'; // Adjust the path as needed
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Email sending function
function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mjrdiagnosticmedicalsupply@gmail.com'; // Your SMTP username
        $mail->Password   = 'vspzoovsnlpupcgl'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('mjrdiagnosticmedicalsupply@gmail.com', 'MJR');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is: <strong>$otp</strong>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle OTP sending
if (isset($_POST['sendOTP'])) {
    $customer_email = $_POST['customer_email'];

    // Generate a random OTP
    $otp = rand(100000, 999999); // 6-digit OTP

    // Store the OTP and email in session
    $_SESSION['customer_email'] = $customer_email;
    $_SESSION['otp'] = $otp;

    // Send OTP email
    if (sendOTPEmail($customer_email, $otp)) {
        header("Location: verify_email.php"); // Redirect to OTP verification page
        exit();
    } else {
        $err = "Failed to send OTP email.";
    }
}

require_once('partials/_head.php');
?>

<body>
    <div class="container">
        <h1>Enter Your Email</h1>
        <form method="post" action="">
            <div>
                <label for="customer_email">Email:</label>
                <input type="email" name="customer_email" required>
            </div>
            <button type="submit" name="sendOTP">Send OTP</button>
        </form>
        <?php if (isset($err)) echo "<p>$err</p>"; ?>
    </div>
</body>
</html>