<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['UpdateProduct'])) {
    // Prevent Posting Blank Values
    if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price']) || empty($_POST['prod_stock'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $update = $_GET['update'];
        $prod_code  = $_POST['prod_code'];
        $prod_name = $_POST['prod_name'];
        $prod_img = $_FILES['prod_img']['name'];

        // Check if an image was uploaded
        if ($prod_img) {
            move_uploaded_file($_FILES["prod_img"]["tmp_name"], "assets/img/products/" . $_FILES["prod_img"]["name"]);
        } else {
            // If no new image is uploaded, retain the current image
            $prod_img = $_POST['existing_prod_img'];
        }

        $prod_desc = $_POST['prod_desc'];
        $prod_price = $_POST['prod_price'];
        $prod_stock = $_POST['prod_stock']; // Get stock quantity
        $prod_expiry_date = !empty($_POST['prod_expiry_date']) ? $_POST['prod_expiry_date'] : NULL; // Set to NULL if not provided

        // Update Captured information to a database table
        $postQuery = "UPDATE rpos_products SET prod_code =?, prod_name =?, prod_img =?, prod_desc =?, prod_price =?, prod_stock =?, prod_expiry_date =? WHERE prod_id = ?";
        $postStmt = $mysqli->prepare($postQuery);
        
        // Bind parameters
        $rc = $postStmt->bind_param('sssssssi', $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price, $prod_stock, $prod_expiry_date, $update);
        $postStmt->execute();
        
        // Check if the update was successful
        if ($postStmt) {
            $success = "Product Updated";
            header("refresh:1; url=inventory_stock.php");
            exit; // Ensure to exit after header redirect
        } else {
            $err = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');

// Check if update parameter is set
if (isset($_GET['update'])) {
    $update = $_GET['update'];
    $ret = "SELECT * FROM rpos_products WHERE prod_id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('i', $update); // Use 'i' for integer
    $stmt->execute();
    $res = $stmt->get_result();

    // Check if product exists
    if ($res->num_rows > 0) {
        $prod = $res->fetch_object();
    } else {
        // Handle the case where the product does not exist
        echo "<div class='alert alert-danger'>Product not found.</div>";
        exit; // Stop further execution
    }
} else {
    echo "<div class='alert alert-danger'>No product ID provided.</div>";
    exit; // Stop further execution
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
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
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
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($prod->prod_name); ?>" name="prod_name" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label>Product Code</label>
                    <input type="text" name="prod_code" value="<?php echo htmlspecialchars($prod->prod_code); ?>" class="form-control" required>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Image</label>
                    <input type="file" name="prod_img" class="btn btn-outline-success form-control">
                    <input type="hidden" name="existing_prod_img" value="<?php echo htmlspecialchars($prod->prod_img); ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Product Price</label>
                    <input type="text" name="prod_price" class="form-control" value="<?php echo htmlspecialchars($prod->prod_price); ?>" required>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Stock</label>
                    <input type="number" name="prod_stock" class="form-control" min="0" value="<?php echo htmlspecialchars($prod->prod_stock); ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label>Expiration Date</label>
                    <input type="date" name="prod_expiry_date" class="form-control" value="<?php echo htmlspecialchars($prod->prod_expiry_date); ?>"> <!-- Removed required attribute -->
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-12">
                    <label>Product Description</label>
                    <textarea rows="5" name="prod_desc" class="form-control" required><?php echo htmlspecialchars($prod->prod_desc); ?></textarea>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="UpdateProduct" value="Update Product" class="btn btn-success">
                    <a href="inventory_stock.php?" class="btn btn-info">
                      <i class="fas fa-info-circle"></i> Back to Inventory
                    </a>
                  </div>
                </div>
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