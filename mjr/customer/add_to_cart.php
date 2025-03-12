<?php
session_start();

// Assuming you have the product details from a form submission or query
$prod_id = $_POST['prod_id']; // Product ID
$prod_name = $_POST['prod_name']; // Product name
$prod_price = $_POST['prod_price']; // Product price
$quantity = $_POST['quantity']; // Quantity

// Initialize cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the product is already in the cart
if (isset($_SESSION['cart'][$prod_id])) {
    // If the product is already in the cart, update the quantity
    $_SESSION['cart'][$prod_id]['quantity'] += $quantity; // Increment the quantity
} else {
    // Add product to cart
    $_SESSION['cart'][$prod_id] = [
        'name' => $prod_name,
        'price' => $prod_price,
        'quantity' => $quantity // Set the quantity from the request
    ];
}

// Set a success message
$_SESSION['add_success_message'] = "Product added to cart successfully.";

// Return a JSON response
echo json_encode(['status' => 'success']);
exit();
?>