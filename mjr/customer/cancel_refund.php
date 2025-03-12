<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login(); // Ensure the user is logged in

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $refund_id = $_POST['refund_id'];

    // Prepare the SQL statement to delete the refund request
    $stmt = $mysqli->prepare("DELETE FROM refund_requests WHERE id = ? AND customer_id = ?");
    $stmt->bind_param('ii', $refund_id, $_SESSION['customer_id']); // Bind the refund ID and customer ID

    if ($stmt->execute()) {
        // Redirect back to the refund requests page with a success message
        $_SESSION['success'] = "Refund request canceled and deleted successfully.";
    } else {
        // Redirect back with an error message
        $_SESSION['error'] = "Failed to cancel and delete the refund request. Please try again.";
    }
    $stmt->close();
    header("Location: refundreq.php"); // Redirect to the refund requests page
    exit();
}
?>