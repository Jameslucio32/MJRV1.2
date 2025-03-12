<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['UpdateProduct'])) {
    // Prevent Posting Blank Values
    if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $update = $_GET['update'];
        $prod_code  = $_POST['prod_code'];
        $prod_name = $_POST['prod_name'];
        $prod_img = $_FILES['prod_img']['name'];
        
        // Move uploaded file
        if (!empty($prod_img)) {
            move_uploaded_file($_FILES["prod_img"]["tmp_name"], "../admin/assets/img/products/" . $_FILES["prod_img"]["name"]);
        } else {
            // If no new image is uploaded, keep the old image
            $prod_img = $prod->prod_img; // Assuming $prod is available here
        }

        $prod_desc = $_POST['prod_desc'];
        $prod_price = $_POST['prod_price']; // This will be ignored since it's read-only

        // Insert Captured information to a database table
        $postQuery = "UPDATE rpos_products SET prod_code =?, prod_name =?, prod_img =?, prod_desc =? WHERE prod_id = ?";
        $postStmt = $mysqli->prepare($postQuery);
        // Bind parameters
        $rc = $postStmt->bind_param('sssss', $prod_code, $prod_name, $prod_img, $prod_desc, $update);
        $postStmt->execute();
        // Declare a variable which will be passed to alert function
        if ($postStmt) {
            $success = "Product Updated";
            header("refresh:1; url=products.php");
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
    <?php
    require_once('partials/_topnav.php');
    $update = $_GET['update'];
    $ret = "SELECT * FROM rpos_products WHERE prod_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($prod = $res->fetch_object()) {
    ?>
      <!-- Header -->
      <div style="background-image: url(../admin/assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
        <span class="mask bg-gradient-dark opacity-5"></span>
        <div class="container-fluid">
          <div class="header-body">
          </div>
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
                    </div>
                    <div class="col-md-6">
                      <label>Product Price</label>
                      <input type="text" name="prod_price" class="form-control" value="<?php echo htmlspecialchars($prod->prod_price); ?>" readonly>
                    </div>
                  </div>
                  <hr 
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
                    </div>
                  </div>
                </form>
                <?php
                // Display error or success messages
                if (isset($err)) {
                    echo "<div class='alert alert-danger'>$err</div>";
                }
                if (isset($success)) {
                    echo "<div class='alert alert-success'>$success</div>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <!-- Footer -->
      <?php
      require_once('partials/_footer.php');
    }
      ?>
      </div>
  </div>
  <!-- Argon Scripts -->
  <?php require_once('partials/_scripts.php'); ?>
</body>

</html>