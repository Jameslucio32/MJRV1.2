<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

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
    <div style="background-image: url(../admin/assets/img/theme/HEADER.png); background-size: cover;"
      class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body"></div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--8">
      <!-- Search Bar -->
      <div class="row mb-3">
        <div class="col">
          <form method="GET" action="" class="form-inline">
            <input type="text" name="search" id="search" class="form-control" placeholder="Search for products..."
              required onkeyup="showSuggestions(this.value)">
            <div id="suggestions" class="suggestions-box"></div>
            <button type="submit" class="btn btn-primary ml-2">Search</button>
          </form>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col text-right">
            <a href="view_cart.php" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i>
                View Cart
            </a>
        </div>
      </div>

      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              Select Any Product To Make An Order
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Product Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="productTableBody">
                  <?php
                  $search = isset($_GET['search']) ? $_GET['search'] : '';
                  $searchQuery = $search ? "WHERE prod_name LIKE ? OR prod_code LIKE ?" : "";
                  $ret = "SELECT * FROM rpos_products $searchQuery ORDER BY `rpos_products`.`created_at` DESC";
                  $stmt = $mysqli->prepare($ret);

                  if ($search) {
                    $searchTerm = "%$search%";
                    $stmt->bind_param("ss", $searchTerm, $searchTerm);
                  }

                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prod = $res->fetch_object()) {
                    ?>
                    <tr>
                      <td>
                        <?php
                        if ($prod->prod_img) {
                          echo "<img src='../admin/assets/img/products/$prod->prod_img' height='150' width='125' class='img-thumbnail'>";
                        } else {
                          echo "<img src='../admin/assets/img/products/default.jpg' height='110' width='100' class='img-thumbnail'>";
                        }
                        ?>
                      </td>
                      <td><?php echo htmlspecialchars($prod->prod_code); ?></td>
                      <td><?php echo htmlspecialchars($prod->prod_name); ?></td>
                      <td>₱<?php echo number_format((float) str_replace(',', '', $prod->prod_price), 2); ?></td>
                      <td><?php echo htmlspecialchars($prod->prod_stock); ?> pcs</td>
                      <td>
                        <?php if ($prod->prod_stock > 0) { ?>
                          <div class="text-center">
                            <form action="make_oder.php" method="GET">
                              <input type="hidden" name="prod_id" value="<?php echo $prod->prod_id; ?>">
                              <input type="hidden" name="prod_name" value="<?php echo $prod->prod_name; ?>">
                              <input type="hidden" name="prod_price" value="<?php echo $prod->prod_price; ?>">
                              <input type="hidden" name="quantity" value="1">
                              <button type="submit" class="btn btn-danger mb-2">
                                <i class="fas fa-cart-plus"></i>
                                Order Now
                              </button>
                            </form>
                            <button type="button" class="btn btn-info"
                              onclick="openModal('<?php echo htmlspecialchars($prod->prod_name); ?>', '<?php echo number_format((float) str_replace(',', '', $prod->prod_price), 2); ?>', '<?php echo $prod->prod_id; ?>')">
                              <i class="fas fa-plus"></i>
                              Add to Cart
                          </button>
                          </div>
                        <?php } else { ?>
                          <button class="btn btn-warning" disabled>
                            <i class="fas fa-times"></i>
                            Out of Stock
                          </button>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p id="modalProductName" class="font-weight-bold"></p>
                    <p id="modalProductPrice" class="text-muted"></p>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" class="form-control" value="1" min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addToCartButton">Add to Cart</button>
            </div>
            <div class="modal-footer">
                <p class="text-muted" id="additionalMessage" style="margin-top: 10px;">You can adjust the quantity before adding to the cart.</p>
                <div id="modalSuccessMessage" class="alert alert-success" style="display: none;">
                    Product added to cart successfully!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(productName, productPrice, productId) {
        document.getElementById("modalProductName").innerText = "Product: " + productName;
        document.getElementById("modalProductPrice").innerText = "Price: ₱" + productPrice;
        document.getElementById("addToCartButton").setAttribute("data-product-id", productId);
        document.getElementById("quantity").value = 1; 
        document.getElementById("modalSuccessMessage").style.display = "none"; 
        $('#addToCartModal').modal('show'); 
    }

    document.getElementById("addToCartButton").addEventListener("click", function() {
        const productId = this.getAttribute("data-product-id");
        const quantity = document.getElementById("quantity").value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "add_to_cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (this.status === 200) {
                const response = JSON.parse(this.responseText);
                if (response.status === 'success') {
                    updateCartDisplay();
                    document.getElementById("modalSuccessMessage").style.display = "block"; 

                    // Hide the success message after 3 seconds
                    setTimeout(function() {
                        document.getElementById("modalSuccessMessage").style.display = "none";
                    }, 3000);
                } else {
                    alert(response.message); 
                }
            } else {
                console.error("Error adding to cart");
            }
        };
        xhr.send("prod_id=" + productId + "&quantity=" + quantity + "&prod_name=" + encodeURIComponent(document.getElementById("modalProductName").innerText) + "&prod_price=" + encodeURIComponent(document.getElementById("modalProductPrice").innerText.split('₱')[1])); 
    });
</script>

<style>
    .modal-header {
        background-color: #007bff; /* Bootstrap primary color */
        color: white;
    }
    .alert {
        margin-top: 10px;
    }
</style>
    </div>
    <!-- Footer -->
    <?php require_once('partials/_footer.php'); ?>
  </div>
</div>
<!-- Argon Scripts -->
<?php require_once('partials/_scripts.php'); ?>

<style>
  .suggestions-box {
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    background-color: white;
    z-index: 1000;
    width: calc(100% - 30px);
    display: none; 
  }

  .suggestion-item {
    padding: 10px;
    cursor: pointer;
  }

  .suggestion-item:hover {
    background-color: #f0f0f0;
  }

  #search {
    position: relative;
  }
</style>
</body>
</html>