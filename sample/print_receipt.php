<?php
if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
    $totalNetAmount = $_POST['totalNetAmount'];
    $totalVATAmount = $_POST['totalVATAmount'];
    $totalAmountIncludingVAT = $_POST['totalAmountIncludingVAT'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .receipt {
            border: 1px solid #000;
            padding: 20px;
            width: 300px;
            margin: auto;
        }
        h2 {
            text-align: center;
        }
        .total {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h2>Receipt</h2>
        <p>Total Net Amount: <?php echo number_format($totalNetAmount, 2); ?></p>
        <p>Total VAT Amount (12%): <?php echo number_format($totalVATAmount, 2); ?></p>
        <p>Total Amount (including VAT): <?php echo number_format($totalAmountIncludingVAT, 2); ?></p>
        <button onclick="window.print();">Print this receipt</button>
    </div>
</body>
</html>