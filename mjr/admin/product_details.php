<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
require 'vendor/autoload.php'; // Ensure you include the autoload file for the QR code library
use Picqer\Barcode\BarcodeGeneratorPNG;

check_login();

if (isset($_GET['prod_id'])) {
    $prod_id = $_GET['prod_id'];

    // Fetch product details from the database
    $query = "SELECT * FROM rpos_products WHERE prod_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $prod_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_object();

    if (!$product) {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}

// Generate Barcode
$generator = new BarcodeGeneratorPNG();
$barcode_image_path = 'assets/barcodes/' . $product->prod_code . '.png'; // Save path for barcode
$barcodeData = $generator->getBarcode($product->prod_code, $generator::TYPE_CODE_128);
file_put_contents($barcode_image_path, $barcodeData);

// Prepare data for QR Code
$dataForQRCode = "ID: " . $product->prod_code . ", Name: " . $product->prod_name . ", Stock: " . $product->prod_stock . ", Unit: " . (isset($product->unit) ? $product->unit : 'N/A');

// Generate QR Code URL
$qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($dataForQRCode);

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
        <div class="header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <h1 class="text-center">Product Details</h1>
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <h2><?php echo htmlspecialchars($product->prod_name); ?></h2>
                            <img src="assets/img/products/<?php echo htmlspecialchars($product->prod_img); ?>" class="img-thumbnail" height="250" width="150">
                            <p><strong>Product Code:</strong> <?php echo htmlspecialchars($product->prod_code); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($product->prod_desc); ?></p>
                            <p><strong>Price:₱</strong> <?php echo htmlspecialchars($product->prod_price); ?></p>
                            <p><strong>Stock:</strong> <?php echo htmlspecialchars($product->prod_stock); ?></p>
                            <p><strong>Expiration Date:</strong> <?php echo !empty($product->prod_expiry_date) ? htmlspecialchars($product->prod_expiry_date) : 'N/A'; ?></p>
                            
                            <h3>Barcode:</h3>
                            <img src="<?php echo $barcode_image_path; ?>" alt="Barcode" class="img-thumbnail" height="150" width="250">
                       
                            <h3>QR Code:</h3>
                            <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code" class="img-thumbnail" height="150" width="150">
                            
                        </div>
                        <a href="view_barcode.php" class="btn btn-primary">Back to Products</a>
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