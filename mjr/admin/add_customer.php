<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Add Customer
if (isset($_POST['addCustomer'])) {
    // Prevent Posting Blank Values
    if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email']) || empty($_POST['customer_password']) || empty($_POST['street_address']) || empty($_POST['barangay']) || empty($_POST['city']) || empty($_POST['province']) || empty($_POST['postal_code']) || empty($_POST['country'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $customer_name = $_POST['customer_name'];
        $customer_phoneno = $_POST['customer_phoneno'];
        $customer_email = $_POST['customer_email'];
        $customer_password = sha1(md5($_POST['customer_password'])); // Hash This 
        $customer_id = $_POST['customer_id'];
        
        // New address fields
        $street_address = $_POST['street_address'];
        $barangay = $_POST['barangay'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $postal_code = $_POST['postal_code'];
        $country = $_POST['country'];

        // Insert Captured information to a database table
        $postQuery = "INSERT INTO rpos_customers (customer_id, customer_name, customer_phoneno, customer_email, customer_password, street_address, barangay, city, province, postal_code, country) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $postStmt = $mysqli->prepare($postQuery);
        // bind parameters
        $rc = $postStmt->bind_param('sssssssssss', $customer_id, $customer_name, $customer_phoneno, $customer_email, $customer_password, $street_address, $barangay, $city, $province, $postal_code, $country);
        $postStmt->execute();
        // declare a variable which will be passed to alert function
        if ($postStmt) {
            $success = "Customer Added";
            header("refresh:1; url=customes.php");
        } else {
            $err = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');
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
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3>Please Fill All Fields</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>Customer Name</label>
                                        <input type="text" name="customer_name" class="form-control" required>
                                        <input type="hidden" name="customer_id" value="<?php echo $cus_id; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Customer Phone Number</label>
                                        <input type="text" name="customer_phoneno" class="form-control" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>Customer Email</label>
                                        <input type="email" name="customer_email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Customer Password</label>
                                        <input type="password" name="customer_password" class="form-control" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>Street Address</label>
                                        <input type="text" name="street_address" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Barangay</label>
                                        <input type="text" name="barangay" class="form-control" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Province</label>
                                        <input type="text" name="province" class="form-control" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Country</label>
                                        <input type="text" name="country" class="form-control" required>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <input type="submit" name="addCustomer" value="Add Customer" class="btn btn-success">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information Section -->
            <?php if (isset($success)): ?>
                <div class="row mt-4">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <h3 class="text-center">Customer Information</h3>
                            </div>
                            <div class="card-body">
                                <h5>Name: <?php echo htmlspecialchars($customer_name); ?></h5>
                                <p>Phone Number: <?php echo htmlspecialchars($customer_phoneno); ?></p>
                                <p>Email: <?php echo htmlspecialchars($customer_email); ?></p>
                                <p>Address: <?php echo htmlspecialchars($street_address . ', ' . $barangay . ', ' . $city . ', ' . $province . ', ' . $postal_code . ', ' . $country); ?></p>
                                <p>Password: <?php echo htmlspecialchars($_POST['customer_password']); // Note: Displaying passwords is not recommended ?></p>
                                <div class="text-center mt-4">
                                    <a href="customes.php" class="btn btn-outline-secondary">Back to Customers</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>