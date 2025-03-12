<?php
session_start();
include('config/config.php');
include('config/checklogin.php');

check_login();

if (isset($_POST['prod_code']) && isset($_POST['quantity'])) {
    $prod_code = $_POST['prod_code'];
    $quantity = (int)$_POST['quantity'];

    // Fetch product details
    $query = "SELECT * FROM rpos_products WHERE prod_code = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $prod_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_object();

    if (!$product) {
        echo json_encode(['message' => 'Product not found.']);
        exit;
    }

    // Update stock in the database
    $new_stock = $product->prod_stock + $quantity; // Increase stock by the quantity added
    $update_query = "UPDATE rpos_products SET prod_stock = ? WHERE prod_code = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('is', $new_stock, $prod_code);
    
    if ($update_stmt->execute()) {
        echo json_encode(['message' => 'Product added to inventory successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to add product to inventory.']);
    }
    exit;
}
?>