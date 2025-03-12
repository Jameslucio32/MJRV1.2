<?php
session_start();
include('config/config.php');
include('config/checklogin.php');

check_login();

if (isset($_POST['prod_code']) && isset($_POST['quantity'])) {
    $prod_code = $_POST['prod_code'];
    $quantity = (int)$_POST['quantity'];

    // Fetch product details
    $query = "SELECT * FROM rpos_products WHERE prod_code = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $prod_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_object();

    if (!$product) {
        echo json_encode(['message' => 'Product not found.']);
        exit;
    }

    // Check stock availability
    if ($quantity > $product->prod_stock) {
        echo json_encode(['message' => 'Not enough stock available.']);
        exit;
    }

    // Logic to add product to cart (e.g., store in session)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$prod_code])) {
        $_SESSION['cart'][$prod_code]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$prod_code] = [
            'name' => $product->prod_name,
            'price' => $product->prod_price,
            'quantity' => $quantity
        ];
    }

    // Update stock in the database
    $new_stock = $product->prod_stock - $quantity;
    $update_query = "UPDATE rpos_products SET prod_stock = ? WHERE prod_code = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('is', $new_stock, $prod_code);
    $update_stmt->execute();

    echo json_encode(['message' => 'Product added to cart successfully.']);
    exit;
}
?>
<script>
    $(document).ready(function() {
        $('#barcodeInput').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                var barcode = $(this).val();
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
        });

        $('#addToCart').on('click', function() {
            var quantity = $('#quantity').val();
            // Here you can implement the logic to add the product to the cart
            alert('Added ' + quantity + ' of ' + $('#productName').text() + ' to cart.');
            // Optionally, reset the input field
            $('#barcodeInput').val('');
            $('#productInfo').hide();
        });
    });
</script>