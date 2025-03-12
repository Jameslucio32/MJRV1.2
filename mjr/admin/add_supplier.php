<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Handle form submission to add a new supplier
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_name = $_POST['supplier_name'];
    $supplier_phoneno = $_POST['supplier_phoneno'];
    $supplier_email = $_POST['supplier_email'];
    $address = $_POST['address'];

    // Prepare and bind
    $adn = "INSERT INTO rpos_suppliers (supplier_name, supplier_phoneno, supplier_email, address) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('ssss', $supplier_name, $supplier_phoneno, $supplier_email, $address);

    if ($stmt->execute()) {
        $success = "Supplier Added Successfully";
        header("refresh:1; url=supplier.php");
    } else {
        $err = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Include Header
require_once('partials/_head.php');
?>
<style>
  .new {
    background-color: #8d16c9;
    color: white;
    padding: 1px 1px;
    border: none;
    border-radius: 5px;
    text-align: center;
  }
</style>
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
                <div class="header-body">
                    <h1 class="text-white">Add New Supplier</h1>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Supplier Information</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>
                            <?php if (isset($err)): ?>
                                <div class="alert alert-danger"><?php echo $err; ?></div>
                            <?php endif; ?>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="supplier_name">Supplier Name</label>
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="supplier_phoneno">Supplier Contact Number</label>
                                    <input type="text" class="form-control" id="supplier_phoneno" name="supplier_phoneno" required>
                                </div>
                                <div class="form-group">
                                    <label for="supplier_email">Supplier Email</label>
                                    <input type="email" class="form-control" id="supplier_email" name="supplier_email">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <button type="submit" class="btn btn-primary">Add Supplier</button>
                                <a href="supplier.php" class="btn btn-secondary">Cancel</a>
                            </form>
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