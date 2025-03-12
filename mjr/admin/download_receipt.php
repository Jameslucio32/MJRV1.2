<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (!isset($_GET['order_code'])) {
    die("Order code not specified.");
}

$order_code = $_GET['order_code'];

$ret = "SELECT o.*, p.pay_method FROM rpos_orders o LEFT JOIN rpos_payments p ON o.order_code = p.order_code WHERE o.order_code = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param("s", $order_code);
$stmt->execute();
$res = $stmt->get_result();

if ($order = $res->fetch_object()) {
   
    if (!file_exists('libs/fpdf.php')) {
        die("FPDF library not found.");
    }
    require('libs/fpdf.php');

   
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);


    $pdf->Cell(0, 10, 'MJR Diagnostic & Medical Supply', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Eric A. Reyes - Proprietor', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Address: #98 Pama St. Ma. Socorro Subdivision', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Contact: 09175081876 / 0449136691', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Email: mjr2014diagnostic@yahoo.com', 0, 1, 'C');
    $pdf->Ln(10); 

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Receipt', 0, 1, 'C');
    $pdf->Ln(10); 

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Approve Date: ' . date('d/M/Y g:i a', strtotime($order->created_at)), 0, 1);
    $pdf->Cell(0, 10, 'Printed Date: ' . date('d/M/Y g:i a'), 0, 1);
    $pdf->Cell(0, 10, 'Receipt #: ' . $order->order_code, 0, 1);
    $pdf->Ln(10); 
   
    $prod_price = (float) str_replace(',', '', $order->prod_price);
    $prod_qty = (int) $order->prod_qty;
    $total = $prod_price * $prod_qty;
    $tax = ($total * 0.12);
    $vat = ($total - $tax);


    $pdf->Cell(40, 10, 'Product Name: ' . $order->prod_name, 0, 1);
    $pdf->Cell(0, 10, 'Payment Method: ' . htmlspecialchars($order->pay_method), 0, 1);
    $pdf->Cell(40, 10, 'Quantity: ' . $prod_qty, 0, 1); 
    $pdf->Cell(40, 10, 'Unit Price: ' . number_format($prod_price, 2), 0, 1);
    $pdf->Cell(40, 10, 'Total: ' . number_format($total, 2), 0, 1);
    $pdf->Cell(40, 10, 'Tax (12%): ' . number_format($tax, 2), 0, 1);
    $pdf->Cell(40, 10, 'VAT Sales: ' . number_format($vat, 2), 0, 1);

    // Output the PDF
    $pdf->Output('D', 'Receipt_' . $order->order_code . '.pdf');
    exit;
} else {
    die("Order not found.");
}
?>