<?php
include 'auth_check.php';

// Create PDF using basic PHP and HTML to PDF conversion
// This is a simpler approach without external libraries

// Set header for PDF download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="laporan_donor_darah.pdf"');

// Generate HTML content for PDF
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Donor Darah</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #dc3545; margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #dc3545; color: white; }
        .stats { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .stat-card { text-align: center; padding: 15px; border: 1px solid #ddd; }
        .section-title { color: #dc3545; font-size: 18px; margin: 20px 0 10px 0; }
    </style>
</head>
<body>';

$html .= '<div class="header">
    <h1>LAPORAN SISTEM DONOR DARAH</h1>
    <p>Kabupaten Mamuju Tengah</p>
    <p>Tanggal: ' . date('d F Y') . '</p>
</div>';

// Get statistics
$stats = [];
$stats['total_donors'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM donors WHERE is_active = 1"))['count'];
$stats['total_donations'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM donations"))['count'];
$stats['total_requests'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM blood_requests"))['count'];
$stats['total_events'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM events"))['count'];

$html .= '<div class="stats">
    <div class="stat-card">
        <h3>' . $stats['total_donors'] . '</h3>
        <p>Total Pendonor</p>
    </div>
    <div class="stat-card">
        <h3>' . $stats['total_donations'] . '</h3>
        <p>Total Donasi</p>
    </div>
    <div class="stat-card">
        <h3>' . $stats['total_requests'] . '</h3>
        <p>Total Permintaan</p>
    </div>
    <div class="stat-card">
        <h3>' . $stats['total_events'] . '</h3>
        <p>Total Events</p>
    </div>
</div>';

// Blood Stock Table
$html .= '<h2 class="section-title">STOK DARAH</h2>';
$html .= '<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Golongan Darah</th>
            <th>Stok Tersedia</th>
            <th>Minimal Stok</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';

$stok_darah = mysqli_query($conn, "SELECT blood_type, rhesus, quantity, min_stock FROM blood_stock ORDER BY blood_type, rhesus");
$no = 1;
while ($s = mysqli_fetch_assoc($stok_darah)) {
    $status = ($s['quantity'] <= $s['min_stock']) ? 'Stok Menipis' : 'Stok Aman';
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . $s['blood_type'] . $s['rhesus'] . '</td>
        <td>' . $s['quantity'] . ' kantong</td>
        <td>' . $s['min_stock'] . ' kantong</td>
        <td>' . $status . '</td>
    </tr>';
}
$html .= '</tbody></table>';

// Donors Table
$html .= '<h2 class="section-title">DATA PENDONOR</h2>';
$html .= '<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Golongan Darah</th>
            <th>Gender</th>
            <th>Telepon</th>
            <th>Jumlah Donasi</th>
        </tr>
    </thead>
    <tbody>';

$pendonor = mysqli_query($conn, "SELECT d.*, CONCAT(d.blood_type, d.rhesus) AS blood_type_display FROM donors d WHERE d.is_active = 1 ORDER BY d.created_at DESC LIMIT 20");
$no = 1;
while ($d = mysqli_fetch_assoc($pendonor)) {
    $gender = ($d['gender'] == 'L') ? 'Laki-laki' : 'Perempuan';
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($d['name']) . '</td>
        <td>' . $d['blood_type_display'] . '</td>
        <td>' . $gender . '</td>
        <td>' . htmlspecialchars($d['phone']) . '</td>
        <td>' . $d['donation_count'] . '</td>
    </tr>';
}
$html .= '</tbody></table>';

// Blood Requests Table
$html .= '<h2 class="section-title">PERMINTAAN DARAH</h2>';
$html .= '<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Pasien</th>
            <th>Rumah Sakit</th>
            <th>Golongan Darah</th>
            <th>Jumlah Butuh</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';

$permintaan = mysqli_query($conn, "SELECT r.*, CONCAT(r.blood_type, r.rhesus) AS blood_type_display FROM blood_requests r ORDER BY r.created_at DESC LIMIT 20");
$no = 1;
while ($r = mysqli_fetch_assoc($permintaan)) {
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($r['patient_name']) . '</td>
        <td>' . htmlspecialchars($r['hospital']) . '</td>
        <td>' . $r['blood_type_display'] . '</td>
        <td>' . $r['quantity_needed'] . ' kantong</td>
        <td>' . ucfirst($r['status']) . '</td>
    </tr>';
}
$html .= '</tbody></table>';

$html .= '<div style="margin-top: 50px; text-align: center; color: #666;">
    <p>Laporan ini dibuat secara otomatis oleh Sistem Donor Darah</p>
    <p>Tanggal Cetak: ' . date('d F Y H:i:s') . '</p>
</div>';

$html .= '</body></html>';

// Use wkhtmltopdf if available, otherwise use browser print
if (extension_loaded('wkhtmltopdf')) {
    // Use wkhtmltopdf extension
    $pdf = new WkHtmlToPdf();
    $pdf->addPage($html);
    echo $pdf->toString();
} else {
    // Fallback: Save as HTML and let browser handle PDF conversion
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: inline; filename="laporan_donor_darah.html"');
    echo $html;
    
    // Add JavaScript to automatically print
    echo '<script>
        window.onload = function() {
            window.print();
        };
    </script>';
}
?>

