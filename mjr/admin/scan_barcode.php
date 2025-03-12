<?php
session_start();
include('config/config.php');
include('config/checklogin.php');

check_login();

if (isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];

    // Fetch product details from the database
    $query = "SELECT * FROM rpos_products WHERE prod_code = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $barcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_object();

    if (!$product) {
        echo json_encode(['error' => 'Product not found.']);
        exit;
    }

   
    echo json_encode($product);
    exit;
}
require_once('partials/_head.php');
?>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
    }
    #productInfo {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px auto; 
        width: 50%;
        max-width: 600px; 
        text-align: center; 
    }
    h1 {
        text-align: center;
        color: #333;
    }

    #barcodeInput {
        text-align: center; 
        width: 50%;
        padding: 10px;
        margin: 10px auto;
        display: block;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #startScan {
        display: block;
        width: 50%;
        padding: 10px;
        margin: 10px auto;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #startScan:hover {
        background-color: #218838;
    }

    #camera {
        text-align: center;
        margin: 20px 0;
    }

    #productInfo {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        width: 80%;
    }

    #productInfo h2 {
        margin-top: 0;
    }

    #quantity {
        width: 50%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        padding: 10px;
        margin: 5px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #addToCart {
        background-color: #007bff;
        color: white;
    }

    #addToCart:hover {
        background-color: #0056b3;
    }

    #addToInventory {
        background-color: #ffc107;
        color: black;
    }

    #addToInventory:hover {
        background-color: #e0a800;
    }
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="en">
<?php require_once('partials/_sidebar.php'); ?>
<?php require_once('partials/_topnav.php'); ?>
<head>
    <meta charset="UTF-8">
    <title>Scan Barcode</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
</head>
<body>
    <h1>Scan Product Barcode</h1>
    <input type="text" id="barcodeInput" placeholder="Scan barcode here" autofocus>
    <button id="startScan">Start Scanning</button>
    <div id="camera" style="display:none;">
        <video id="video" width="300" height="200" autoplay></video>
    </div>
    <div id="productInfo" style="display:none;">
        <h2 id="productName"></h2>
        <p><strong>Product Code:</strong> <span id="productCode"></span ></p>
        <p><strong>Description:</strong> <span id="productDesc"></span></p>
        <p><strong>Price:₱</strong> <span id="productPrice"></span></p>
        <p><strong>Stock:</strong> <span id="productStock"></span></p>
        <input type="number" id="quantity" placeholder="Enter quantity" min="1" max="" value="">
       <!-- <button id="addToCart">Add to Cart</button>-->
        <button id="addToInventory">Add to Inventory</button>
    </div>

    <script>
    $(document).ready(function() {
        $('#barcodeInput').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                var barcode = $(this).val();
                fetchProductDetails(barcode);
            }
        });

        $('#startScan').on('click', function() {
            $('#camera').show();
            startCamera();
        });

        function startCamera() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#video'), // video element
                    constraints: {
                        facingMode: "environment" // Use the rear camera
                    },
                },
                decoder: {
                    readers: ["code_128_reader"] // Specify the barcode type
                },
            }, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(function(data) {
                var barcode = data.codeResult.code;
                $('#barcodeInput').val(barcode);
                fetchProductDetails(barcode);
                Quagga.stop(); // Stop scanning after detecting a barcode
                $('#camera').hide(); // Hide camera after scanning
            });
        }

        function fetchProductDetails(barcode) {
            $.post('scan_barcode.php', { barcode: barcode }, function(data) {
                var product = JSON.parse(data);
                if (product.error) {
                    alert(product.error);
                } else {
                    $('#productName').text(product.prod_name);
                    $('#productCode').text(product.prod_code);
                    $('#productDesc').text(product.prod_desc);
                    $('#productPrice').text(product.prod_price);
                    $('#productStock').text(product.prod_stock);
                    $('#quantity').attr('max', product.prod_stock);
                    $('#productInfo').show();
                }
            });
        }

        $('#addToInventory').on('click', function() {
            var quantity = $('#quantity').val();
            var productCode = $('#productCode').text();
            $.post('add_to_inventory.php', { prod_code: productCode, quantity: quantity }, function(response) {
                alert(response.message);
                $('#barcodeInput').val('');
                $('#productInfo').hide();
            }, 'json');
        });

        $('#addToCart').on('click', function() {
            var quantity = $('#quantity').val();
            var productCode = $('#productCode').text();
            $.post('add_to_cart.php', { prod_code: productCode, quantity: quantity }, function(response) {
                alert(response.message);
                $('#barcodeInput').val('');
                $('#productInfo').hide();
            }, 'json');
        });
    });
    </script>
     <?php require_once('partials/_footer.php'); ?>
      <?php require_once('partials/_scripts.php'); ?>
</body>
</html>