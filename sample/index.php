<?php
// Define the VAT rate
define('VAT_RATE', 0.12);

// Initialize variables
$totalNetAmount = 0;
$totalVATAmount = 0;
$totalAmountIncludingVAT = 0;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get sales input and convert to an array
    $salesInput = $_POST['sales'];
    $vatableSales = array_map('floatval', explode(',', $salesInput)); // Convert to an array of floats

    // Calculate VAT for the sales
    foreach ($vatableSales as $sale) {
        $vatAmount = $sale * VAT_RATE;
        $totalNetAmount += $sale;
        $totalVATAmount += $vatAmount;
        $totalAmountIncludingVAT += $sale + $vatAmount;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAT Receipt Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>VAT Receipt Calculator (12%)</h1>
        <form method="POST">
            <label for="sales">Enter vatable sales (comma-separated):</label>
            <input type="text" id="sales" name="sales" required>
            <input type="submit" value="Calculate VAT">
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="results">
                <h2>Receipt</h2>
                <p>Total Net Amount: <?php echo number_format($totalNetAmount, 2); ?></p>
                <p>Total VAT Amount (12%): <?php echo number_format($totalVATAmount, 2); ?></p>
                <p>Total Amount (including VAT): <?php echo number_format($totalAmountIncludingVAT, 2); ?></p>
                <form method="POST" action="print_receipt.php" target="_blank">
                    <input type="hidden" name="totalNetAmount" value="<?php echo $totalNetAmount; ?>">
                    <input type="hidden" name="totalVATAmount" value="<?php echo $totalVATAmount; ?>">
                    <input type="hidden" name="totalAmountIncludingVAT" value="<?php echo $totalAmountIncludingVAT; ?>">
                    <input type="submit" value="Print Receipt">
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>