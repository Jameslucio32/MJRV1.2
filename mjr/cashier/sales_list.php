<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');

// Handle form submission to add a new sale
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the required fields are set
    if (isset($_POST['customer_name'], $_POST['product_name'], $_POST['quantity'], $_POST['unit_price'])) {
        $customer_name = $_POST['customer_name'];
        $product_name = $_POST['product_name'];
        $quantity = $_POST['quantity'];
        $unit_price = $_POST['unit_price'];
        $total_price = $quantity * $unit_price;

        // Insert the new sale into the database
        $stmt = $mysqli->prepare("INSERT INTO rpos_orders (customer_name, prod_name, prod_qty, prod_price, total_price, order_status) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die('MySQL prepare error: ' . $mysqli->error);
        }

        $order_status = "Not Paid"; // Default status
        $stmt->bind_param("ssidsi", $customer_name, $product_name, $quantity, $unit_price, $total_price, $order_status);
        $stmt->execute();
        $stmt->close();
    } else {
        // Handle the case where required fields are not set
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Calculate weekly sales
$weekly_sales = [];
$current_week_start = date('Y-m-d', strtotime('monday this week'));
$current_week_end = date('Y-m-d', strtotime('sunday this week'));

for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", strtotime($current_week_start)));
    $stmt = $mysqli->prepare("SELECT SUM(total_price) as total_sales FROM rpos_orders WHERE DATE(created_at) = ?");
    
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $weekly_sales[$date] = $row['total_sales'] ? $row['total_sales'] : 0;
}

// Prepare data for the chart
$chart_labels = [];
$chart_data = [];

foreach ($weekly_sales as $date => $total_sales) {
    $chart_labels[] = date('l, d M', strtotime($date)); // Format the date for the label
    $chart_data[] = $total_sales; // Total sales for the day
}

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
            <span class="mask bg-gradient-dark opacity-2"></span>
            <div class="container-fluid">
             
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Form to Add New Sale -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Add New Sale</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" class="form-control" name="customer_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" name="product_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" name="quantity" required>
                                </div>
                                <div class="form-group">
                                    <label for="unit_price">Unit Price</label>
                                    <input type="number" class="form-control" name="unit_price" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Sale</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Line Graph -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Weekly Sales Overview</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Line graph configuration
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Total Sales',
                    data: <?php echo json_encode($chart_data); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
       
    </script>
</body>