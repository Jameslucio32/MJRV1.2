<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');

// Fetch payment data for the last 30 days
$payment_data = [];
$payment_dates = [];
$total_payments = 0; // Initialize total payments

// Calculate total payments for the last 30 days
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $mysqli->prepare("SELECT SUM(pay_amt) as total_amount FROM rpos_payments WHERE DATE(created_at) = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $daily_amount = $row['total_amount'] ? (float)$row['total_amount'] : 0; // Default to 0 if no payments
    $payment_data[] = $daily_amount;
    $payment_dates[] = date('d/M', strtotime($date)); // Format date for the label
    
    // Accumulate total payments
    $total_payments += $daily_amount;
}

// Calculate total amount paid from all records
$total_amount_paid_query = "SELECT SUM(pay_amt) as total_amount_paid FROM rpos_payments";
$total_amount_paid_result = $mysqli->query($total_amount_paid_query);

// Check for query errors
if (!$total_amount_paid_result) {
    error_log("Query Error: " . $mysqli->error);
    die("Query failed: " . $mysqli->error);
}

$total_amount_paid_row = $total_amount_paid_result->fetch_assoc();
$total_amount_paid = isset($total_amount_paid_row['total_amount_paid']) ? (float)$total_amount_paid_row['total_amount_paid'] : 0; // Default to 0 if no payments

// Debugging output
error_log("Total Amount Paid Row: " . print_r($total_amount_paid_row, true));
?>

<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>
        <!-- Header -->
        <div style="background-image: url(assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-5"></span>
        </div>
    
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Line Graph Section -->
            <div class="row">
                <div class="col-xl-12 mb-5 mb-xl-0">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Sales Over the Last 30 Days</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="paymentLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Payment Reports
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                        <tr>
                                            <th class="text-success" scope="col">Payment Code</th>
                                            <th scope="col">Payment Method</th>
                                            <th class="text-success" scope="col">Order Code</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Quantity</th> 
                                            <th scope="col">Amount Paid</th>
                                            <th class="text-success" scope="col">Date Paid</th>
                                            <th class="text-success" scope="col">Proof of Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM rpos_payments ORDER BY `created_at` DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        
                                        $total_amount_paid = 0;
                                        while ($payment = $res->fetch_object()) {
                                            
                                            $raw_amount = str_replace(',', '', $payment->pay_amt);
                                            $amount = !empty($raw_amount) ? (float)$raw_amount : 0;
                                            
                                      
                                            $total_amount_paid += $amount;
                                        ?>
                                            <tr>
                                                <th class="text-success" scope="row">
                                                    <?php echo htmlspecialchars($payment->pay_code); ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo htmlspecialchars($payment->pay_method); ?>
                                                </th>
                                                <td class="text-success">
                                                    <?php echo htmlspecialchars($payment->order_code); ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($payment->product_name); ?> <!-- Display Product Name -->
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($payment->quantity); ?> <!-- Display Quantity -->
                                                </td>
                                                <td>
                                                    ₱<?php echo number_format($amount, 2); ?> 
                                                </td>
                                                <td class="text-success">
                                                    <?php echo date('d/M/Y g:i a', strtotime($payment->created_at)); ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($payment->proof_of_payment)) { ?>
                                                        <img src="<?php echo htmlspecialchars($payment->proof_of_payment); ?>" alt="Proof of Payment" style="max-width: 100px; max-height: 100px;"/>
                                                    <?php } else { ?>
                                                        No Proof Uploaded
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <!-- Total Row -->
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Total:</td>
                                            <td class="font-weight-bold">
                                                ₱<?php echo number_format($total_amount_paid, 2); ?>
                                            </td>
                                            <td colspan="2"></td> <!-- Empty cells for alignment -->
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
    <script>
        const ctx = document.getElementById('paymentLineChart').getContext('2d');

        // Check if data is available
        const paymentDates = <?php echo json_encode($payment_dates); ?>;
        const paymentData = <?php echo json_encode($payment_data); ?>;

        if (paymentDates.length > 0 && paymentData.length > 0) {
            const paymentLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: paymentDates,
                    datasets: [{
                        label: 'Total Payments (₱)',
                        data: paymentData,
                        borderColor: 'rgba(241, 47, 245)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount (₱)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
        } else {
            console.error('No data available for the chart.');
        }
    </script>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>