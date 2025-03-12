<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_code = $_POST['order_code'];
    $reason = $_POST['reason'];
    $comments = $_POST['comments'];
    $customer_id = $_SESSION['customer_id'];
    $proof_of_payment = null;

    // Handle file upload for proof of payment
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/refund"; // Ensure this directory exists and is writable
        $target_file = $target_dir . basename($_FILES["proof_of_payment"]["name"]);
 if (move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $target_file)) {
            $proof_of_payment = $target_file; // Store the file path
        } else {
            echo "<script>alert('Error uploading proof of payment.'); window.location.href='orders_reports.php';</script>";
            exit;
        }
    }

    // Insert refund request into the database
    $stmt = $mysqli->prepare("INSERT INTO rpos_refund_requests (order_code, customer_id, reason, comments, proof_of_payment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $order_code, $customer_id, $reason, $comments, $proof_of_payment);

    if ($stmt->execute()) {
        echo "<script>alert('Refund request submitted successfully!'); window.location.href='orders_reports.php';</script>";
    } else {
        echo "<script>alert('Error submitting refund request. Please try again.'); window.location.href='orders_reports.php';</script>";
    }

    $stmt->close();
    $mysqli->close();
}
?>