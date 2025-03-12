<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

require_once('partials/_head.php');
?>

<style>
    /* Add custom styles for centering */
    .main-content {
        display: flex;
        flex-direction: column;
        justify-content: center; /* Center vertically */
        align-items: center; /* Center horizontally */
        min-height: calc(100vh - 60px); /* Adjust based on your header/footer height */
    }
    .footer {
        position: relative;
        bottom: 0;
        width: 100%;
        text-align: center;
        padding: 10px;
        background-color: #f8f9fa; /* Light background for footer */
    }
</style>

<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>
        <!-- Page content -->
        <div class="container-fluid mt--5"> <!-- Adjust margin-top here -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3>Archived Payments</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Order Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Payment Method</th>
                                        <th scope="col">Proof of Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query to fetch archived payments
                                    $ret = "SELECT * FROM archived_payments ORDER BY order_code DESC"; // Adjust the ORDER BY clause as needed
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    // Check if the result is empty
                                    if ($res->num_rows === 0) {
                                        echo "<tr><td colspan='5' class='text-center'>No archived payments found.</td></tr>";
                                    } else {
                                        while ($archive = $res->fetch_object()) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo htmlspecialchars($archive->order_code); ?></th>
                                                <td><?php echo htmlspecialchars($archive->customer_name); ?></td>
                                                <td>₱<?php echo number_format($archive->amount, 2); ?></td>
                                                <td><?php echo htmlspecialchars($archive->payment_method); ?></td>
                                                <td>
                                                    <?php if (!empty($archive->proof_of_payment)) { ?>
                                                        <a href="<?php echo htmlspecialchars($archive->proof_of_payment); ?>" target="_blank">View Proof</a>
                                                    <?php } else { ?>
                                                        No Proof Uploaded
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php 
                                        } 
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>