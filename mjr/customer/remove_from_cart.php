<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the index is set and is a valid number
    if (isset($_POST['index']) && is_numeric($_POST['index'])) {
        $index = (int)$_POST['index'];

        // Check if the cart exists in the session
        if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$index])) {
            // Remove the item from the cart
            unset($_SESSION['cart'][$index]);

            // Reindex the cart array to maintain sequential keys
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            
            // Optionally, you can return a success response
            echo json_encode(['status' => 'success', 'message' => 'Item removed from cart.']);
        } else {
            // Return an error response if the item does not exist
            echo json_encode(['status' => 'error', 'message' => 'Item not found in cart.']);
        }
    } else {
        // Return an error response if the index is invalid
        echo json_encode(['status' => 'error', 'message' => 'Invalid index.']);
    }
} else {
    // Return an error response if the request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>