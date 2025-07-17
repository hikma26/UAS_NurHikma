<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Load data
    function LoadData()
    {
        include 'auth_check.php';

        $data = [];

        // Fetch stock data
        $stockData = mysqli_query($conn, "SELECT CONCAT(blood_type, rhesus) AS blood, quantity FROM blood_stock");

        while ($row = mysqli_fetch_assoc($stockData)) {
            $data[] = [$row['blood'], $row['quantity'] . ' kantong'];
        }

        return $data;
    }

    // Table
    function BasicTable($header, $data)
    {
        // Header
        foreach ($header as $col) {
            $this->Cell(70, 7, $col, 1);
        }
        $this->Ln();
        // Data
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(70, 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 12);

// Load data
$header = ['Golongan Darah', 'Jumlah Stok'];
$data = $pdf->LoadData();
$pdf->AddPage();
$pdf->Cell(0, 10, 'Laporan Stok Darah', 0, 1, 'C');
$pdf->Ln(10);
$pdf->BasicTable($header, $data);
$pdf->Output('I', 'laporan_stok_darah.pdf');


