<?php
require('fpdf.php');

class PDFReceipt extends FPDF
{
    // Header for the receipt with business information
    function Header()
    {
        // Business Name and Info
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'MJR Diagnostic & Medical Supply', 0, 1, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Eric A. Reyes - Proprietor', 0, 1, 'L');
        $this->Cell(0, 5, 'Address: #98 Pama St. Ma. Socorro', 0, 1, 'L');
        $this->Cell(0, 5, 'Subdivision Abangan Norte Marilao, Bulacan', 0, 1, 'L');
        $this->Cell(0, 5, 'Contact Number: 09175081876 / 044-913-6691', 0, 1, 'L');
        $this->Cell(0, 5, 'Email: mjr2014diagnostic@yahoo.com', 0, 1, 'L');
        
        // Date, terms, and receipt number
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Date: 07/Nov/2024 6:33 pm', 0, 1, 'R');
        $this->Cell(0, 5, 'Terms: 30 days', 0, 1, 'R');
        $this->Cell(0, 5, 'Receipt #: UTKB-6248', 0, 1, 'R');
        $this->Ln(10);
    }

    // Footer can be added if necessary
    function Footer()
    {
        // Optional footer code if needed
    }

    // Main content of the receipt
    function ReceiptBody()
    {
        // Receipt Title
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Receipt', 0, 1, 'C');
        $this->Ln(5);

        // Table Headers
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 10, 'Unit', 1, 0, 'C');
        $this->Cell(30, 10, 'Quantity', 1, 0, 'C');
        $this->Cell(40, 10, 'Unit Price', 1, 0, 'C');
        $this->Cell(40, 10, 'Amount', 1, 1, 'C');

        // Table Row with item details
        $this->SetFont('Arial', '', 10);
        $this->Cell(40, 10, 'HATDOHG', 1, 0, 'C');
        $this->Cell(30, 10, '1', 1, 0, 'C');
        $this->Cell(40, 10, '₱1000', 1, 0, 'C');
        $this->Cell(40, 10, '₱1000', 1, 1, 'C');

        // Summary section
        $this->Ln(5);
        $this->Cell(110, 10, '', 0, 0);
        $this->Cell(30, 10, 'Subtotal:', 0, 0, 'L');
        $this->Cell(30, 10, '₱1000', 0, 1, 'R');

        $this->Cell(110, 10, '', 0, 0);
        $this->Cell(30, 10, 'VATSales:', 0, 0, 'L');
        $this->Cell(30, 10, '880', 0, 1, 'R');

        $this->Cell(110, 10, '', 0, 0);
        $this->Cell(30, 10, 'Tax(12%):', 0, 0, 'L');
        $this->Cell(30, 10, '120', 0, 1, 'R');

        // Total amount in bold
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(110, 10, '', 0, 0);
        $this->Cell(30, 10, 'Total:', 0, 0, 'L');
        $this->Cell(30, 10, '₱1000', 0, 1, 'R');
    }
}

// Create the PDF
$pdf = new PDFReceipt();
$pdf->AddPage();
$pdf->ReceiptBody();
$pdf->Output('I', 'Receipt.pdf'); // 'I' to output to browser, 'F' to save to file
