<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['pay'])) {
    $mysqli->begin_transaction();

    try {
        $pay_code = generatePaymentCode();
        $customer_id = $_SESSION['customer_id']; 
        $order_status = 'Pending';

        $postQuery = "INSERT INTO rpos_payments (pay_id, pay_code, order_code, customer_id, pay_amt, pay_method, proof_of_payment, product_name, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $upQry = "UPDATE rpos_orders SET order_status = ? WHERE order_code = ?";

        $postStmt = $mysqli->prepare($postQuery);
        $upStmt = $mysqli->prepare($upQry);

        foreach ($_POST['product_name'] as $index => $product_name) {
            $quantity = $_POST['quantity'][$index];
            $pay_amt = $_POST['pay_amt'][$index];
            $pay_method = $_POST['pay_method'][$index];
            $proof_of_payment = $_FILES['proof_of_payment']['name'][$index];
            $upload_dir = '../admin/uploads/';
            $upload_file = $upload_dir . basename($proof_of_payment);

            if (move_uploaded_file($_FILES['proof_of_payment']['tmp_name'][$index], $upload_file)) {
                $pay_id = uniqid('pay_');

                $postStmt->bind_param('sssssssss', $pay_id, $pay_code, $order_code, $customer_id, $pay_amt, $pay_method, $upload_file, $product_name, $quantity);
                $upStmt->bind_param('ss', $order_status, $order_code);

                if (!$postStmt->execute() || !$upStmt->execute()) {
                    throw new Exception("Failed to process order: " . $postStmt->error);
                }
            } else {
                throw new Exception("Failed to upload proof of payment for order: " . $product_name);
            }
        }

        $mysqli->commit();
        $success = "All orders are now pending. Please wait for admin approval.";
        header("refresh:2; url=payments_reports.php");
        exit;
    } catch (Exception $e) {
        $mysqli->rollback();
        $err = "Transaction failed: " . $e->getMessage();
    }
}
?>