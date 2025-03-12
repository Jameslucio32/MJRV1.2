<?php
session_start();
include('../mjr/admin/config/config.php'); // Include your database configuration
include('../mjr/admin/config/checklogin.php'); // Include your login check
check_login(); // Ensure the user is logged in

if (isset($_GET['prod_code'])) { // Check if 'prod_code' is set
    $prod_code = $_GET['prod_code']; // Get the product code from the request

    // Prepare the SQL query to fetch product details
    $query = "SELECT * FROM rpos_products WHERE prod_code = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param('s', $prod_code); // Bind the product code parameter
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result set

        if ($result->num_rows > 0) {
            $product = $result->fetch_object(); // Fetch the product as an object
            echo json_encode(['success' => true, 'product' => $product]); // Return product details
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found.']); // No product found
        }

        $stmt->close(); // Close the statement
    } else {
        echo json_encode(['success' => false, 'message' => 'Database query failed.']); // Query preparation failed
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No product code provided.']); // No product code provided
}
?>