<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Archive Customer
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];
    $adn = "UPDATE rpos_customers SET archived = 1 WHERE customer_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Customer Archived";
        header("refresh:1; url=customes.php");
    } else {
        $err = "Try Again Later";
    }
}

// Unarchive Customer
if (isset($_GET['unarchive'])) {
    $id = $_GET['unarchive'];
    $adn = "UPDATE rpos_customers SET archived = 0 WHERE customer_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Customer Unarchived";
        header("refresh:1; url=customes.php");
    } else {
        $err = "Try Again Later";
    }
}

// Delete Customer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $adn = "DELETE FROM rpos_customers WHERE customer_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Customer Deleted";
        header("refresh:1; url=customes.php");
    } else {
        $err = "Try Again Later";
    }
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
                            <a href="add_customer.php" class="btn btn-outline-success">
                                <i class="fas fa-user-plus"></i>
                                Add New Customer
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th> <!-- Numbering Column -->
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Customer Contact Number</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_customers WHERE archived = 0 ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $counter = 1; // Initialize counter
                                    while ($cust = $res->fetch_object()) {
                                        $isNew = (strtotime($cust->created_at) >= strtotime('-10 days'));
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td> <!-- Display the counter -->
                                            <td>
                                                <?php echo htmlspecialchars($cust->customer_name); ?>
                                                <?php if ($isNew): ?>
                                                    <span class="new">New</span> 
                                                <?php endif; ?>
 </td>
                                            <td><?php echo htmlspecialchars($cust->customer_phoneno); ?></td>
                                            <td>
                                                <a href="customes.php?archive=<?php echo $cust->customer_id; ?>">
                                                    <button class="btn btn-sm btn-warning">
                                                        <i class="fas fa-archive"></i>
                                                        Archive
                                                    </button>
                                                </a>
                                                <a href="view_customer.php?view=<?php echo urlencode($cust->customer_id); ?>">
                                                    <button class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
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
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>