<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['addProduct'])) {
  
  // Prevent Posting Blank Values
  if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price']) || empty($_POST['prod_expiry_date'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $prod_id = $_POST['prod_id'];
    $prod_code  = $_POST['prod_code'];
    $prod_name = $_POST['prod_name'];
    $prod_img = $_FILES['prod_img']['name'];
    move_uploaded_file($_FILES["prod_img"]["tmp_name"], "../admin/assets/img/products/" . $_FILES["prod_img"]["name"]);
    $prod_desc = $_POST['prod_desc'];
    $prod_price = $_POST['prod_price'];
    $prod_expiry_date = $_POST['prod_expiry_date']; 

 
    $postQuery = "INSERT INTO rpos_products (prod_id, prod_code, prod_name, prod_img, prod_desc, prod_price, prod_expiry_date) VALUES(?,?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
 
    $rc = $postStmt->bind_param('sssssss', $prod_id, $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price, $prod_expiry_date);
    $postStmt->execute();
    if ($postStmt) {
      $success = "Product Added";
      header("refresh:1; url=add_product.php");
      exit;
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
 
    <?php require_once('partials/_sidebar.php'); ?>

    <div class="main-content">
    
        <?php require_once('partials/_topnav.php'); ?>
    
        <div style="background-image: url(assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>
     
        <div class="container-fluid mt--8">
     
           <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <a href="add_product.php" class="btn btn-outline-success">
                                <i class="fas fa-leaf"></i>
                                Add New Product
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope ="col">Image</th>
                                        <th scope="col">Product Code</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Expiration Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_products WHERE prod_stock >= 0"; 
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $counter = 1; 
                                    while ($prod = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
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
                                            <td class="<?php echo ($prod->prod_stock < 5) ? 'text-danger' : ''; ?>">
                                                <?php 
                                                echo htmlspecialchars($prod->prod_stock); 
                                                if ($prod->prod_stock < 5 && $prod->prod_stock > 0) {
                                                    echo ' <span class="badge badge-warning" title="Low stock!">Low</span>'; 
                                                } elseif ($prod->prod_stock == 0) {
                                                    echo ' <span class="badge badge-danger" title="Out of Stock">Out</span>'; 
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (!empty($prod->prod_expiry_date)) {
                                                    $expiryDate = new DateTime($prod->prod_expiry_date);
                                                    $currentDate = new DateTime();
                                                    
                                                    if ($expiryDate < $currentDate) {
                                                        echo 'N/A'; 
                                                    } else {
                                                        echo htmlspecialchars($prod->prod_expiry_date); 
                                                    }
                                                } else {
                                                    echo 'N/A'; 
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="products.php?delete=<?php echo $prod->prod_id; ?>" onclick="return confirm('Are you sure you want to delete this product?');">
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </a>
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
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Product Code</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Expiration Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_products WHERE prod_stock = 0"; // Only show products with zero stock
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $counter = 1; // Initialize counter for numbering
                                    while ($prod = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
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
                                            <td><?php echo htmlspecialchars($prod->prod_stock); ?> pcs</td>
                                            <td>
                                                <?php
                                                if (!empty($prod->prod_expiry_date)) {
                                                    $expiryDate = new DateTime($prod->prod_expiry_date);
                                                    $currentDate = new DateTime();
                                                    
                                                    if ($expiryDate < $currentDate) {
                                                        echo 'N/A'; 
                                                    } else {
                                                        echo htmlspecialchars($prod->prod_expiry_date); 
                                                    }
                                                } else {
                                                    echo 'N/A'; 
                                                }
                                                ?>
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