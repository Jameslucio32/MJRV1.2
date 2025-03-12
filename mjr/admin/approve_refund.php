<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_code = $_POST['order_code'];

    // Update the refund request status to Approved
    $stmt = $mysqli->prepare("UPDATE rpos_orders SET refund_status = 'Approved' WHERE order_code = ?");
    $stmt->bind_param("s", $order_code);

    if ($stmt->execute()) {
        echo "<script>alert('Refund request approved successfully!'); window.location.href='admin_refund_requests.php';</script>";
    } else {
        echo "<script>alert('Error approving refund request. Please try again.'); window.location.href='admin_refund_requests.php';</script>";
    }

    $stmt->close();
    $mysqli->close();
}
?>