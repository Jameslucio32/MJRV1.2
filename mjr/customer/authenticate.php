// Prepare and execute the query to fetch customer details
$stmt = $mysqli->prepare("SELECT customer_id, customer_name FROM rpos_customer WHERE customer_email = ? AND customer_password = ?");
$stmt->bind_param("ss", $email, $password); // Use hashed password in production
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Fetch the customer details
    $stmt->bind_result($customer_id, $customer_name);
    $stmt->fetch();

    // Store customer details in session
    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['customer_name'] = $customer_name; // Store the customer name

    // Redirect to the checkout page or wherever needed
    header("Location: checkout.php");
    exit();
} else {
    // Handle login failure
    $error = "Invalid email or password.";
}

$stmt->close();