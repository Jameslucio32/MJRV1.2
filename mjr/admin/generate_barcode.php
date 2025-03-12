<?php
session_start();
include('config/config.php');
require 'vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_POST['prod_id'])) {
    $prod_id = $_POST['prod_id'];

    // Generate the barcode image
    $generator = new BarcodeGeneratorPNG();
    $barcode_image_path = '../admin/assets/barcodes/' . $prod_id . '.png'; // Ensure the filename is unique

    // Attempt to generate and save the barcode
    $barcodeData = $generator->getBarcode($prod_id, $generator::TYPE_CODE_128);
    if (file_put_contents($barcode_image_path, $barcodeData)) {
        $_SESSION['success'] = "Barcode generated successfully.";
    } else {
        $_SESSION['error'] = "Barcode not generated. Check directory permissions.";
    }

    header("Location: view_barcode.php"); 
    exit;
}
?>