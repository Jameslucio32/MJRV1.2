<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM rpos_products WHERE prod_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id); // Use 'i' for integer
    $result = $stmt->execute(); // Store the result of the execution
    $stmt->close(); // Close the statement

    if ($result) { // Check if the execution was successful
        $success = "Deleted";
        header("refresh:1; url=products.php");
        exit; // Ensure no further code is executed after redirection
    } else {
        $err = "Try Again Later";
    }
}

require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    ?>
    <!-- Header -->
    <div style="background-image: url(assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
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
           
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">#</th> <!-- Added column for numbering -->
                    <th scope="col">Image</th>
                    <th scope="col">Product Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_products";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  $counter = 1; // Initialize counter for numbering
                  while ($prod = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo $counter++; ?></td> <!-- Display the counter and increment it -->
                      <td>
                        <?php
                        if ($prod->prod_img) {
                          echo "<img src='assets/img/products/$prod->prod_img' height='150' width='125' class='img-thumbnail'>";
                        } else {
                          echo "<img src='assets/img/products/default.jpg' height='120' width='100' class='img-thumbnail'>";
                        }
                        ?>
                      </td>
                      <td><?php echo $prod->prod_code; ?></td>
                      <td><?php echo $prod->prod_name; ?></td>
                      <td>₱<?php echo $prod->prod_price; ?></td>
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

                        <a href="view_barcode.php?id=<?php echo $prod->prod_id; ?>">
                            <button class="btn btn-sm btn-info">
                                <i class="fas fa-barcode"></i>
                                View Barcode
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
      <?php
      require_once ('partials/_footer.php');
      ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>