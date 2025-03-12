<?php
// Define the VAT rate
define('VAT_RATE', 0.12);

// Function to calculate VAT and total amount
function calculateVAT($sales) {
    $totalNetAmount = 0;
    $totalVATAmount = 0;
    $totalAmountIncludingVAT = 0;

    foreach ($sales as $sale) {
        $vatAmount = $sale * VAT_RATE;
        $totalNetAmount += $sale;
        $totalVATAmount += $vatAmount;
        $totalAmountIncludingVAT += $sale + $vatAmount;
    }

    return [
        'totalNetAmount' => $totalNetAmount,
        'totalVATAmount' => $totalVATAmount,
        'totalAmountIncludingVAT' => $totalAmountIncludingVAT,
    ];
}

// Example sales data
$salesData = [100, 200, 300]; // Replace with your sales amounts

// Calculate VAT
$result = calculateVAT($salesData);

// Output results
echo "Total Net Amount: " . number_format($result['totalNetAmount'], 2) . "\n";
echo "Total VAT Amount (12%): " . number_format($result['totalVATAmount'], 2) . "\n";
echo "Total Amount (including VAT): " . number_format($result['totalAmountIncludingVAT'], 2) . "\n";
?>