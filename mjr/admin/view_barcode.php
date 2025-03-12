<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');
require 'vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorPNG;

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $prod_expiry_date = $_POST['prod_expiry_date']; // Capture the expiration date

    // Insert Captured information to a database table
    $postQuery = "INSERT INTO rpos_products (prod_id, prod_code, prod_name, prod_img, prod_desc, prod_price, prod_expiry_date) VALUES(?,?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    // Bind parameters
    $rc = $postStmt->bind_param('sssssss', $prod_id, $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price, $prod_expiry_date);
    $postStmt->execute();

    // Generate Barcode after successful insertion
    if ($postStmt) {
        // Generate the barcode image using prod_code
        $generator = new BarcodeGeneratorPNG();
        $barcode_image_path = '../admin/assets/barcodes/' . $prod_code . '.png'; // Use prod_code for the filename

        // Attempt to generate and save the barcode
        $barcodeData = $generator->getBarcode($prod_code, $generator::TYPE_CODE_128); // Use prod_code for barcode data
        if (file_put_contents($barcode_image_path, $barcodeData)) {
            $success = "Product Added and Barcode generated successfully.";
        } else {
            $err = "Barcode not generated. Check directory permissions.";
        }

        header("refresh:1; url=add_product.php");
        exit; // Ensure to exit after header redirect
    } else {
        $err = " Please Try Again Or Try Later";
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
           <!-- Available Products Table -->
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
        <th scope="col">View Details</th> <!-- Change this header -->
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
                echo "<img src='assets/img/products/$prod->prod_img' height='250' width='150' class='img-thumbnail'>";
            } else {
                echo "<img src='assets/img/products/default.jpg' height='220' width='200' class='img-thumbnail'>";
            }
            ?>
        </td>
        <td><?php echo htmlspecialchars($prod->prod_code ?? ''); ?></td>
        <td><?php echo htmlspecialchars($prod->prod_name ?? ''); ?></td>
        <td class="<?php echo ($prod->prod_stock < 5) ? 'text-danger' : ''; ?>">
            <?php 
            echo htmlspecialchars($prod->prod_stock ?? ''); 
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
        <td>
            <a href="product_details.php?prod_id=<?php echo $prod->prod_id; ?>" class="btn btn-sm btn-info">View Details</a>
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