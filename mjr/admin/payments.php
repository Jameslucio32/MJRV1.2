<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Approve Payment
if (isset($_GET['approve']) && isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];
    $approved_by = $_SESSION['admin_id'];

    // Fetch order details to get product ID and quantity
    $fetchOrderQuery = "SELECT prod_id, prod_qty FROM rpos_orders WHERE order_code = ?";
    $stmt = $mysqli->prepare($fetchOrderQuery);
    $stmt->bind_param('s', $order_code);
    $stmt->execute();
    $stmt->bind_result($prod_id, $quantity);
    $stmt->fetch();
    $stmt->close();

    // Reduce the product stock in the inventory
    $updateStockQuery = "UPDATE rpos_products SET prod_stock = GREATEST(prod_stock - ?, 0) WHERE prod_id = ?";
    $stmt = $mysqli->prepare($updateStockQuery);
    $stmt->bind_param('is', $quantity, $prod_id);
    
    if ($stmt->execute()) {
        // Update order status
        $updateQuery = "UPDATE rpos_orders SET order_status = 'Approved', approved_status = 'Approved', approved_by = ? WHERE order_code = ?";
        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param('ss', $approved_by, $order_code);
        
        if ($stmt->execute()) {
            $success = "Payment has been approved and inventory updated.";
        } else {
            $err = "Failed to approve the payment.";
        }
    } else {
        $err = "Failed to update the inventory.";
    }
    $stmt->close();
}

// Delete Payment
if (isset($_GET['delete']) && isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];
    $deleteQuery = "DELETE FROM rpos_orders WHERE order_code = ?";
    $stmt = $mysqli->prepare($deleteQuery);
    
    if ($stmt) {
        $stmt->bind_param('s', $order_code);
        
        if ($stmt->execute()) {
            $success = "Payment has been deleted.";
        } else {
            $err = "Failed to delete the payment.";
        }
        $stmt->close();
    } else {
        $err = "Failed to prepare the statement.";
    }
}

// Archive Payment
if (isset($_GET['archive']) && isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];

    // Fetch the payment details
    $fetchQuery = "SELECT o.order_code, o.customer_name, o.prod_price, o.prod_qty, p.pay_method as payment_method, p.proof_of_payment
                   FROM rpos_orders o 
                   LEFT JOIN rpos_payments p ON o.order_code = p.order_code 
                   WHERE o.order_code = ?";
    $stmt = $mysqli->prepare($fetchQuery);
    
    if ($stmt) {
        $stmt->bind_param('s', $order_code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $payment = $result->fetch_assoc();

            // Calculate the amount
            $amount = $payment['prod_price'] * $payment['prod_qty'];

            // Insert the payment into the archived_payments table
            $archiveQuery = "INSERT INTO archived_payments (order_code, customer_name, amount, payment_method, proof_of_payment) VALUES (?, ?, ?, ?, ?)";
            $archiveStmt = $mysqli->prepare($archiveQuery);
            if ($archiveStmt) {
                $archiveStmt->bind_param('ssdss', $payment['order_code'], $payment['customer_name'], $amount, $payment['payment_method'], $payment['proof_of_payment']);
                
                if ($archiveStmt->execute()) {
                    // Now delete the original payment record
                    $deleteQuery = "DELETE FROM rpos_orders WHERE order_code = ?";
                    $deleteStmt = $mysqli->prepare($deleteQuery);
                    if ($deleteStmt) {
                        $deleteStmt->bind_param('s', $order_code);
                        $deleteStmt->execute();
                        $success = "Payment has been archived successfully.";
                    } else {
                        $err = "Failed to prepare delete statement.";
                    }
                } else {
                    $err = "Failed to archive the payment.";
                }
                $archiveStmt->close();
            } else {
                $err = "Failed to prepare archive statement.";
            }
        } else {
            $err = "Payment not found.";
        }
        $stmt->close();
    } else {
        $err = "Failed to prepare fetch statement.";
    }
}

// Handle file upload for proof of payment
if (isset($_POST['upload_payment'])) {
    $order_code = $_POST['order_code'];
    $proof_of_payment = $_FILES['proof_of_payment'];
    $upload_dir = 'admin/uploads/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_extension = pathinfo($proof_of_payment['name'], PATHINFO_EXTENSION);
    $new_file_name = $order_code . '.' . $file_extension;
    $upload_file = $upload_dir . $new_file_name;

    if (move_uploaded_file($proof_of_payment['tmp_name'], $upload_file)) {
        $updateProofQuery = "UPDATE rpos_payments SET proof_of_payment = ? WHERE order_code = ?";
        $stmt = $mysqli->prepare($updateProofQuery);
        $stmt->bind_param('ss', $upload_file, $order_code);
        
        if ($stmt->execute()) {
            $success = "Proof of payment uploaded successfully.";
        } else {
            $err = "Failed to update proof of payment in the database.";
        }
        $stmt->close();
    } else {
        $err = "Failed to upload proof of payment.";
    }
}

require_once('partials/_head.php');
?>

<style>
    .main-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 60px);
    }
    .footer {
        position: relative;
        bottom: 0;
        width: 100%;
        text-align: center;
        padding: 10px;
        background-color: #f8f9fa;
    }
    .proof-image {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
        border: 2px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(236, 235, 237);
    }
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }
    .close {
        position: absolute;
        top: 35px;
        right: 35px;
        color: #f252e8;
        font-size: 50px;
        font-weight: bold;
        transition: 0.3s;
    }
    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div class="container-fluid mt--5">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3>Pending Payments</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Order Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Payment Method</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Proof of Payment</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT o.order_code, o.customer_name, o.prod_price, o.prod_qty, p.pay_method as payment_method, p.proof_of_payment, o.approved_status
                                            FROM rpos_orders o 
                                            LEFT JOIN rpos_payments p ON o.order_code = p.order_code 
                                            WHERE o.order_status = 'Pending' 
                                            ORDER BY o.created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    if ($res->num_rows === 0) {
                                        echo "<tr><td colspan='7' class='text-center '>No pending payments found.</td></tr>";
                                    } else {
                                        while ($order = $res->fetch_object()) {
                                            $total_price = (isset($order->prod_price) && isset($order->prod_qty)) 
                                            ? ((float)str_replace(',', '', $order->prod_price) * (int)$order->prod_qty) 
                                            : 0;
                                        
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo htmlspecialchars($order->order_code); ?></th>
                                                <td><?php echo htmlspecialchars($order->customer_name); ?></td>
                                                <td>₱ <?php echo number_format($total_price, 2); ?></td>
                                                <td><?php echo htmlspecialchars($order->payment_method); ?></td>
                                                <td><?php echo isset($order->approved_status) ? htmlspecialchars($order->approved_status) : 'Pending Orders'; ?></td>
                                                <td>
                                                    <?php 
                                                    if (!empty($order->proof_of_payment)) {
                                                        $proof_path = htmlspecialchars($order->proof_of_payment);
                                                        echo '<img src="' . $proof_path . '" alt="Proof of Payment" class="proof-image" onclick="openModal(\'' . $proof_path . '\', \'' . htmlspecialchars($order->order_code) . '\', \'' . htmlspecialchars($order->customer_name) . '\', ' . $total_price . ', \'' . htmlspecialchars($order->payment_method) . '\')" />';
                                                    } else { 
                                                        echo 'No Proof Uploaded'; 
                                                    } 
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="payments.php?approve=true&order_code=<?php echo urlencode($order->order_code); ?>" onclick="return confirm('Are you sure you want to approve this payment?');">
                                                        <button class="btn btn-sm btn-success">Approve</button>
                                                    </a>
                                              
                                                    <a href="payments.php?archive=true&order_code=<?php echo urlencode($order->order_code); ?>" onclick="return confirm('Are you sure you want to archive this payment? This action cannot be undone.');">
                                                        <button class="btn btn-sm btn-warning">Archive</button>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php 
                                        } 
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (isset($err)) { ?>
                            <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($err); ?></div>
                        <?php } ?>
                        <?php if (isset($success)) { ?>
                            <div class="alert alert-success mt-3"><?php echo htmlspecialchars($success); ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .customerorder {
            font-size: 20px;
            text-align: center;
            font-family: "Times New Roman", Times, serif;
            padding: 10px;
            border: 3px solid #ba1ef7;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0)
        }
        </style>
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
        <div class="customerorder" id="modalDetails">
            <h4>Order Details</h4>
            <p><strong>Order Code:</strong> <span id="modalOrderCode"></span></p>
            <p><strong>Customer Name:</strong> <span id="modalCustomerName"></span></p>
            <p><strong>Amount:</strong> ₱ <span id="modalAmount"></span></p>
            <p><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
        </div>
    </div>

    <script>
        function openModal(src, orderCode, customerName, amount, paymentMethod) {
            var modal = document.getElementById("myModal");
            var img = document.getElementById("img01");
            var modalOrderCode = document.getElementById("modalOrderCode");
            var modalCustomerName = document.getElementById("modalCustomerName");
            var modalAmount = document.getElementById("modalAmount");
            var modalPaymentMethod = document.getElementById("modalPaymentMethod");

            modal.style.display = "block";
            img.src = src;
            modalOrderCode.textContent = orderCode;
            modalCustomerName.textContent = customerName;
            modalAmount.textContent = amount.toFixed(2);
            modalPaymentMethod.textContent = paymentMethod;
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        setTimeout(function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 3000);
    </script>
    
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>