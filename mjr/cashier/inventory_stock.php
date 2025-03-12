<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM rpos_products WHERE prod_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        $success = "Deleted";
        header("refresh:1; url=products.php");
        exit;
    } else {
        $err = "Try Again Later";
    }
}

if (isset($_GET['increase_stock'])) {
    $id = intval($_GET['increase_stock']);
    $adn = "UPDATE rpos_products SET prod_stock = prod_stock + 1 WHERE prod_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("refresh:1; url=products.php");
    exit;
}

if (isset($_GET['decrease_stock'])) {
    $id = intval($_GET['decrease_stock']);
    $adn = "UPDATE rpos_products SET prod_stock = GREATEST(prod_stock - 1, 0) WHERE prod_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("refresh:1; url=products.php");
    exit;
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
            <!-- Available Products Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3>Inventory Reports</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th> <!-- Numbering Column -->
                                        <th scope="col">Image</th>
                                        <th scope="col">Product Code</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_products WHERE prod_stock > 0";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $counter = 1; // Initialize counter for available products
                                    while ($prod = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td> <!-- Display the counter -->
                                            <td>
                                                <?php
                                                if ($prod->prod_img) {
                                                    echo "<img src='../admin/assets/img/products/$prod->prod_img' height='150' width='125' class='img-thumbnail'>";
                                                } else {
                                                    echo "<img src='../admin/assets/img/products/default.jpg' height='120' width='100' class='img-thumbnail'>";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($prod->prod_code); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_name); ?></td>
                                            <td>
                                                <?php 
                                                echo htmlspecialchars($prod->prod_stock); 
                                                // Check stock level for warning
                                                if ($prod->prod_stock < 5) {
                                                    echo ' <span class="badge badge-warning" title="Low stock!">Low </span>'; // Warning for low stock
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Out of Stock Products Table -->
            <div class="row mt-4">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Out of Stock Products</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th> <!-- Numbering Column -->
                                        <th scope="col">Image</th>
                                        <th scope="col">Product Code</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Warning</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_products WHERE prod_stock = 0"; // Only show products with zero stock
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $counter = 1; // Initialize counter for out-of-stock products
                                    while ($prod = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td> <!-- Display the counter -->
                                            <td>
                                                <?php
                                                if ($prod->prod_img) {
                                                    echo "<img src='assets/img/products/$prod->prod_img' height='150' width='125' class='img-thumbnail'>";
                                                } else {
                                                    echo "<img src='assets/img/products/default.jpg' height='120' width='100' class='img-thumbnail'>";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($prod->prod_code); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_name); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_stock); ?> pcs</td> <!-- Display Stock Quantity -->
                                            <td>
                                                <i class="fas fa-exclamation-triangle text-danger" title="Out of Stock"></i>
                                            </td>
                                            <td>
                                                <a href="update_product.php?update=<?php echo $prod->prod_id; ?>">
                                                    <button class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                        Update
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