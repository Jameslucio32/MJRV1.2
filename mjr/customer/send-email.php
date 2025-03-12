<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

if (isset($_POST['email'], $_POST['subject'], $_POST['message'], $_FILES['file'])) {

    $mail = new PHPMailer(true);

    try {
        // Server Settings
        $mail->isSMTP();
        $mail->Host         = 'smtp.gmail.com';
        $mail->SMTPAuth     = true;
        $mail->Username     = 'customer011323@gmail.com';
        $mail->Password     = 'hnpogycwvaoodlbr'; // Use your App Password here
        $mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
        $mail->Port         = 587; // Port for TLS

        // Optional: Disable SSL verification (for debugging only)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Debugging output
        $mail->SMTPDebug = 0; // Enable verbose debug output

        // Recipient
        $mail->setFrom('customer011323@gmail.com', 'Customer Of MJR');
        $mail->addAddress($_POST['email']);
        $mail->addReplyTo('customer011323@gmail.com', 'Customer Of MJR');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $_POST['subject'];
        $mail->Body    = $_POST['message'];

        // Attachment
        $file_path = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $mail->addAttachment($file_path, $file_name);

        // Send Email
        $mail->send();
        echo "
        <script>
            alert('Email sent successfully!');
            document.location.href = 'dashboard.php';
        </script>
        ";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "
    <script>
        alert('Fill all the inputs!');
        document.location.href = 'index.php';
    </script>
    ";
}

?> 