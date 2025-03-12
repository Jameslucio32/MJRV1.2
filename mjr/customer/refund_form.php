<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_code = $_POST['order_code'];
    
    // Use isset() to check if the keys exist in the $_POST array
    $refund_reason = isset($_POST['refund_reason']) ? $_POST['refund_reason'] : '';
    $additional_comments = isset($_POST['additional_comments']) ? $_POST['additional_comments'] : '';

    // Check if customer_id is set in the session
    if (isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id']; // Use customer_id from session
    } else {
        $err = "Customer ID is not available. Please log in again.";
    }

    // Handle file upload
    if (isset($_FILES["proof_of_payment"]) && $_FILES["proof_of_payment"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "../admin/uploads/refund/"; // Directory to save uploaded files
        $target_file = $target_dir . basename($_FILES["proof_of_payment"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a valid image or document
        $check = getimagesize($_FILES["proof_of_payment"]["tmp_name"]);
        if ($check === false) {
            $err = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES["proof_of_payment"]["size"] > 2000000) {
            $err = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'mp4'])) {
            $err = "Sorry, only JPG, JPEG, PNG, GIF, PDF, & MP4 files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $err = "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $target_file)) {
                // Update the order with refund information
                $insertRefundQuery = "UPDATE rpos_orders SET approved_status = 'Pending', reason = ?, comments = ?, proof_of_payment = ? WHERE order_code = ? AND customer_id = ?";
                $stmt = $mysqli->prepare($insertRefundQuery);
                $stmt->bind_param('sssss', $refund_reason, $additional_comments, $target_file, $order_code, $customer_id);

                if ($stmt->execute()) {
                    $success = "Your refund request has been submitted successfully.";
                    // Optionally redirect to a confirmation page or back to refund requests
                    // header("Location: your_refund_requests.php");
                    // exit();
                } else {
                    $err = "Failed to submit your refund request.";
                }
                $stmt->close();
            } else {
                $err = "Sorry, there was an error uploading your file.";
            }
        }
    } 
}

// Get the order code from the previous page
$order_code = $_GET['order_code'] ?? '';

// Initialize variables
$customer_name = '';
$product_name = '';
$prod_price = 0.00; 
$prod_qty = 0; 

// Retrieve product and customer information based on order_code
$product_info_query = "SELECT customer_name, prod_name, prod_price, prod_qty FROM rpos_orders WHERE order_code = ?";
$product_stmt = $mysqli->prepare($product_info_query);
$product_stmt->bind_param('s', $order_code);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows > 0) {
    $product_info = $product_result->fetch_object();
    $customer_name = $product_info->customer_name;
    $product_name = $product_info->prod_name;
    $prod_price = (float) $product_info->prod_price; 
    $prod_qty = (int) $product_info->prod_qty; 
} else {
    $err = "No product information found for this order.";
}

$total_price = $prod_price * $prod_qty;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Refund</title>
    <?php require_once('partials/_head.php'); ?>
</head>
<body>
    <div class="container">
        <h2>Request Refund for Order: <?php echo htmlspecialchars($order_code); ?></h2>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif (isset($err)): ?>
            <div class="alert alert-danger"><?php echo $err; ?></div>
        <?php endif; ?>

        <form action="refund_form.php?order_code=<?php echo htmlspecialchars($order_code); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="order_code" value="<?php echo htmlspecialchars($order_code); ?>">
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" class="form-control" id="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" id="product_name" value="<?php echo htmlspecialchars($product_name); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="prod_price">Product Price (₱):</label>
                <input type="text" class="form-control" id="prod_price" value="₱ <?php echo number_format($prod_price, 2); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="refund_reason">Reason for Refund:</label>
                <select name="refund_reason" id="refund_reason" class="form-control" required>
                    <option value="">Select Reason</option>
                    <option value="Product damaged">Product damaged</option>
                    <option value="Wrong item received">Wrong item received</option>
                    <option value="Order not received">Order not received</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="additional_comments">Additional Comments:</label>
                <textarea name="additional_comments" id="additional_comments" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="proof_of_payment">Upload Video/Picture:</label>
                <h2>[Required] Photo(s) and/or video of the product, showing physical damage (e.g. cracks, defects)</h2>
                <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Refund Request</button>
            <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
        </form>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>