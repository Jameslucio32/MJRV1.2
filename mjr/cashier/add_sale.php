<?php
// Database connection parameters
$host = 'localhost';
$db = 'rposystem';
$user = 'root'; // your database username
$pass = ''; // your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Prepare an insert statement
    $stmt = $conn->prepare("INSERT INTO sales (product_name, quantity, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $product_name, $quantity, $price);

    if ($stmt->execute()) {
        echo "New sale added successfully.";
    } else {
        echo " Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();

// Redirect back to the sales list
header("Location: sales_list.php");
exit();
?>