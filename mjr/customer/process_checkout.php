<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['pay'])) {
    $mysqli->begin_transaction();

    try {
        // Retrieve payment details
        $pay_code = $_POST['pay_code'];
        $customer_id = $_SESSION['customer_id']; // Assuming customer ID is stored in session
        $customer_name = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Unknown'; // Default to 'Unknown' if not set
        $pay_method = $_POST['pay_method'];
        $pay_amt = str_replace('₱', '', $_POST['pay_amt']); // Remove currency symbol for database insertion
        $proof_of_payment = $_FILES['proof_of_payment']['name'];
        $upload_dir = '../admin/uploads/';
        $upload_file = $upload_dir . basename($proof_of_payment);

        // Validate the uploaded file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf']; // Allowed file types
        if (!in_array($_FILES['proof_of_payment']['type'], $allowed_types)) {
            throw new Exception("Invalid file type. Only JPG, PNG, GIF, and PDF files are allowed.");
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $upload_file)) {
            throw new Exception("Failed to upload proof of payment.");
        }

        // Prepare the SQL statements for payment insertion
        $postQuery = "INSERT INTO rpos_payments (pay_id, pay_code, customer_id, pay_amt, pay_method, proof_of_payment) VALUES (?, ?, ?, ?, ?, ?)";
        $postStmt = $mysqli->prepare($postQuery);

        // Generate a unique payment ID
        $pay_id = uniqid('pay_');

        // Insert payment information
        $postStmt->bind_param('ssssss', $pay_id, $pay_code, $customer_id, $pay_amt, $pay_method, $upload_file);
        if (!$postStmt->execute()) {
            throw new Exception("Failed to insert payment: " . $postStmt->error);
        }

        // Prepare the SQL statement for order insertion
        $orderQuery = "INSERT INTO rpos_orders (order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price, prod_qty, order_status, created_at, proof_of_payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $orderStmt = $mysqli->prepare($orderQuery);

        // Update order status for each product in the cart $order_status = 'Pending';

        foreach ($_SESSION['cart'] as $prod_id => $item) {
            $order_id = uniqid('order_'); // Generate a unique order ID
            $order_code = $pay_code; // Use the payment code as the order code
            $prod_name = $item['name'];
            $prod_price = str_replace('₱', '', $item['price']); // Remove currency symbol for database insertion
            $prod_qty = $item['quantity'];

            // Bind parameters for order insertion
            $orderStmt->bind_param('sssssssss', $order_id, $order_code, $customer_id, $customer_name, $prod_id, $prod_name, $prod_price, $prod_qty, $order_status, $upload_file);

            // Execute the order insertion
            if (!$orderStmt->execute()) {
                throw new Exception("Failed to insert order: " . $orderStmt->error);
            }
        }

        $mysqli->commit();
        $success = "All orders are now pending. Please wait for admin approval.";
        header("refresh:2; url=payments_reports.php");
        exit;
    } catch (Exception $e) {
        $mysqli->rollback();
        $err = "Transaction failed: " . $e->getMessage();
        error_log($err);
    }
}
?>