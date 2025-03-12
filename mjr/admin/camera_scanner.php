<?php
session_start();
include('config/config.php');
include('config/checklogin.php');

check_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camera Scanner</title>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        #reader {
            width: 100%;
            height: 400px;
            border: 2px dashed #007bff;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        #result {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
            color: #28a745;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>
        <div class="container">
            <h1 class="text-center">Scan Product QR Code</h1>
            <div id="reader"></div>
            <div id="result" class="mt-3"></div>
            <a href="view_barcode.php" class="btn">Back to Products</a>
        </div>
    </div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Display the scanned result
            document.getElementById('result').innerHTML = `<p>Scanned Product Code: ${decodedText}</p>`;
            // Redirect to product details page
            window.location.href = `product_details.php?prod_id=${decodedText}`;
        }

        function onScanError(errorMessage) {
            // Handle scan error
            console.warn(`Scan error: ${errorMessage}`);
        }

        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: 250 };

        // Start scanning
        html5QrCode.start(
            { facingMode: "environment" }, // Use the back camera
            config,
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error(`Unable to start scanning: ${err}`);
        });
    </script>
</body>
</html>