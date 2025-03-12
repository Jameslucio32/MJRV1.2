<?php
session_start();
include('config/config.php'); // Ensure this file contains your database connection
include('config/checklogin.php');
check_login();

// Database connection
$dbuser = "root";
$dbpass = "";
$host = "localhost";
$db = "rposystem";
$mysqli = new mysqli($host, $dbuser, $dbpass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
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
    <div style="background-image: url(../admin/assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body"></div>
      </div>
    </div>
  
    <!-- Product Selection and Order Summary -->
    <div class="row">
      <div class="col-md-6">
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
                // Fetch products from the database
                $query = "SELECT * FROM `rpos_products`"; // Assuming you have a products table
                $products = mysqli_query($mysqli, $query);

                if (!$products) {
                    die("Database query failed: " . mysqli_error($mysqli));
                }

                while ($prod = mysqli_fetch_assoc($products)) {
                ?>
                  <tr>
                    <td>
                      <?php
                      if ($prod['prod_img']) {
                        echo "<img src='../admin/assets/img/products/{$prod['prod_img']}' height='50' width='50' class='img-thumbnail'>";
                      } else {
                        echo "<img src='../admin/assets/img/products/default.jpg' height='50' width='50' class='img-thumbnail'>";
                      }
                      ?>
                    </td>
                    <td><?php echo htmlspecialchars($prod['prod_code']); ?></td>
                    <td><?php echo htmlspecialchars($prod['prod_name']); ?></td>
                    <td>₱<?php echo number_format((float)$prod['prod_price'], 2, '.', ','); ?></td>
                    <td><?php echo htmlspecialchars($prod['prod_stock']); ?> pcs</td>
                    <td>
                      <?php if ($prod['prod_stock'] > 0) { ?>
                        <button class="btn btn-primary add-to-order" data-code="<?php echo htmlspecialchars($prod['prod_code']); ?>" data-name="<?php echo htmlspecialchars($prod['prod_name']); ?>" data-price="<?php echo htmlspecialchars($prod['prod_price']); ?>">Add</button>
                      <?php } else { ?>
                        <button class="btn btn-warning" disabled>Out of Stock</button>
                      <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header border-0">
            Order Summary
            <div class="header_price float-right">
              <h5 style="color: green; font-size: 40px; display: inline;">Grand Total</h5>
              <p class="pb-0 mr-2" style="font-size: 40px; color: green; display: inline;" id="totalValue">₱ 0.00</p>
            </div>
          </ div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Product Code</th>
                  <th scope="col">Product Name</th>
                  <th scope="col">Price</th>
                  <th scope="col">Qty</th>
                  <th scope="col">Sub.Total</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody id="orderTableBody">
                <!-- Order items will be dynamically added here -->
              </tbody>
            </table>
            <div class="card-footer">
              <div class="d-flex justify-content-between">
                <div>
                  <label for="discount">Discount (₱):</label>
                  <input type="number" id="discount" value="0" min="0" class="form-control" style="width: 100px;">
                </div>
                <button class="btn btn-success finish-order">Finish Order</button>
              </div>
            </div>
            <!-- VAT and Tax Display -->
            <div class="d-flex justify-content-between mt-2">
              <div>
                <h5 style="color: green;font-size: 30px;">VAT (12%): <span id="vatValue">₱ 0.00</span></h5>
                <h5 style="color: green;font-size: 30px;">Tax: <span id="taxValue">₱ 0.00</span></h5>
              </div>
            </div>
            <!-- Numeric Keypad -->
            <div class="numeric-keypad mt-3">
                <button class="num-btn" data-value="1">1</button>
                <button class="num-btn" data-value="2">2</button>
                <button class="num-btn" data-value="3">3</button>
                <button class="num-btn" data-value="4">4</button>
                <button class="num-btn" data-value="5">5</button>
                <button class="num-btn" data-value="6">6</button>
                <button class="num-btn" data-value="7">7</button>
                <button class="num-btn" data-value="8">8</button>
                <button class="num-btn" data-value="9">9</button>
                <button class="num-btn" data-value="0">0</button>
                <button class="clear-btn">Clear</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quantity Selection Modal -->
    <div id="quantityModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Select Quantity</h2>
            <input type="number" id="quantityInput" min="1" value="1" />
            <button id="confirmQuantity">Confirm</button>
        </div>
    </div>

  </div>
  <!-- Footer -->
  <?php require_once('partials/_footer.php'); ?>
</div>
<!-- Argon Scripts -->
<?php require_once('partials/_scripts.php'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/pos.js"></script>

<!-- CSS for Modal -->
<style>
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 40%; 
}

.numeric-keypad {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.num-btn, .clear-btn {
    padding: 10px;
    font-size: 18px;
    cursor: pointer;
}
</style>

</body>
</html>