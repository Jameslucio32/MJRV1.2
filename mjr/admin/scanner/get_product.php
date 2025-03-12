<?php
 $dbuser="root";
 $dbpass="";
 $host="localhost";
 $db="rposystem";
 $mysqli=new mysqli($host,$dbuser, $dbpass, $db);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the request
$product_id = $_GET['prod_id'];

// Prepare and execute the SQL statement
$sql = "SELECT * FROM rpos_products WHERE prod_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a product was found
if ($result->num_rows > 0) {
    // Output data of each row
    $product = $result->fetch_assoc();
    echo json_encode($product);
} else {
    echo json_encode(["error" => "Product not found"]);
}

$stmt->close();
$conn->close();
?>