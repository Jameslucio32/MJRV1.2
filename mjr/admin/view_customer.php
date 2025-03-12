<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Fetch customer information
if (isset($_GET['view']) && !empty($_GET['view'])) {
    $id = $_GET['view'];
    $stmt = $mysqli->prepare("SELECT * FROM rpos_customers WHERE customer_id = ?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $customer = $res->fetch_object();
    $stmt->close();

    if (!$customer) {
        echo "Customer not found.";
        exit;
    }
} else {
    echo "No customer ID provided.";
    // Redirect back to the customers list
    header("Location: customes.php");
    exit;
}

// Include Header
require_once('partials/_head.php');
?>
<style>
.large-title {
    font-size: 3.5rem; /* Adjust the size as needed */
    font-weight: bold; /* Optional: make the text bold */
    text-align:center;
    margin: auto;
    padding: 100px;
    background-color:#eed5f5;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0)
}
.small-title{
    font-size: 1.5rem;
    font-weight: bold;
    text-align:left;
    margin: auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0) 
}
.btn{
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align:center;
    
}
    </style>
<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>

    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
      

        <div class="container-fluid mt--8">
            <div class="row justify-content-center"> <!-- Centering the row -->
                <div class="col-lg-8 col-md-10 mt-10"> <!-- Increased margin-top to push the card even lower -->
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h2 class="mb-0 text-center">Customer Information</h2> <!-- Changed to h2 for larger text -->
                        </div>
                        <div class="card-body">
                            <h2 class="large-title">Customer Details</h2> <!-- New title added -->
                            <p class="small-title">Name: <?php echo htmlspecialchars($customer->customer_name); ?></p> <!-- Added margin-top -->
                            <p class="small-title">Contact Number: <?php echo htmlspecialchars($customer->customer_phoneno); ?></p> <!-- Added margin-top -->
                            <p class="small-title">Email: <?php echo htmlspecialchars($customer->customer_email); ?></p> <!-- Added margin-top -->
                            <p class="small-title">Street Address: <?php echo htmlspecialchars($customer->street_address); ?></p> <!-- Added margin-top -->
                            <p class="small-title">Barangay: <?php echo htmlspecialchars($customer->barangay); ?></p> <!-- Added margin-top -->
                            <p class="small-title">City: <?php echo htmlspecialchars($customer->city); ?></p> <!-- Added margin-top -->
                            <p class="small-title">Province: <?php echo htmlspecialchars($customer->province); ?></p> <!-- Added margin-top -->
                            <p class="small-title">Postal Code: <?php echo htmlspecialchars($customer->postal_code); ?></p> <!-- Added margin-top -->
                            <p class="small-title">Country: <?php echo htmlspecialchars($customer->country); ?></p> <!-- Added margin-top -->
                            <div class="btn"> <!-- Added margin-top to button -->
                                <a href="customes.php" class="btn btn-outline-secondary">Back to Customers</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>