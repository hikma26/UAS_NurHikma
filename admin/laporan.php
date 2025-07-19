<?php
include 'auth_check.php';

// Cek koneksi dan validasi query
function safe_query($conn, $query) {
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "<div class='alert alert-danger'>Query gagal: " . mysqli_error($conn) . "</div>";
        return false;
    }
    return $result;
}

// Data pendonor
$pendonor = safe_query($conn, "
    SELECT d.*, CONCAT(d.blood_type, d.rhesus) AS blood_type_display
    FROM donors d
    WHERE d.is_active = 1
    ORDER BY d.created_at DESC
");

// Data permintaan darah
$permintaan = safe_query($conn, "
    SELECT r.*, CONCAT(r.blood_type, r.rhesus) AS blood_type_display
    FROM blood_requests r
    ORDER BY r.created_at DESC
");

// Data donasi
$donasi = safe_query($conn, "
    SELECT d.*, dn.name AS donor_name, CONCAT(d.blood_type, d.rhesus) AS blood_type_display
    FROM donations d
    JOIN donors dn ON d.donor_id = dn.id
    ORDER BY d.created_at DESC
");

// Data events
$events = safe_query($conn, "
    SELECT e.*
    FROM events e
    ORDER BY e.created_at DESC
");

// Statistik
$stats = [
    'total_donors' => mysqli_fetch_assoc(safe_query($conn, "SELECT COUNT(*) as count FROM donors WHERE is_active = 1"))['count'],
    'total_donations' => mysqli_fetch_assoc(safe_query($conn, "SELECT COUNT(*) as count FROM donations"))['count'],
    'total_requests' => mysqli_fetch_assoc(safe_query($conn, "SELECT COUNT(*) as count FROM blood_requests"))['count'],
    'total_events' => mysqli_fetch_assoc(safe_query($conn, "SELECT COUNT(*) as count FROM events"))['count'],
];

// Statistik stok darah
$stok_darah = safe_query($conn, "
    SELECT blood_type, rhesus, quantity, min_stock
    FROM blood_stock
    ORDER BY blood_type, rhesus
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sistem - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            padding-top: 1.5rem;
        }
        
        .main-container {
            margin-left: 100px; /* Space for admin toggle button */
            margin-right: 2rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .main-container {
                margin-left: 1rem;
                margin-right: 1rem;
                margin-top: 1rem;
            }
        }
        
        .page-title {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-bottom: none;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: translate(30%, -30%);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #dc2626;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(220, 38, 38, 0.2);
            border-left-color: #b91c1c;
        }
        
        .stats-card .card-body {
            padding: 2rem;
        }
        
        .stats-card i {
            transition: all 0.3s ease;
        }
        
        .stats-card:hover i {
            transform: scale(1.2) rotate(5deg);
        }
        
        .table {
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        .table th {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            text-align: center;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.875rem;
            border-color: rgba(220, 38, 38, 0.1);
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(220, 38, 38, 0.05);
            transform: scale(1.01);
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 12px;
            font-weight: 500;
        }
        
        .status-pending { 
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .status-approved { 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .status-rejected { 
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .status-completed { 
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
        }
        
        .btn-export {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        
        .btn-export:hover {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
        }
        
        .btn-outline-primary {
            border: 2px solid #dc2626;
            color: #dc2626;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border-color: #dc2626;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
        }
        
        .text-primary {
            color: #dc2626 !important;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h1 class="page-title">
                            <i class="fas fa-chart-bar me-3" style="color: #dc2626;"></i>
                            Laporan Sistem
                        </h1>
                        <div>
                            <button class="btn btn-export me-2" onclick="exportToPdf()">
                                <i class="fas fa-file-pdf me-2"></i>
                                Export PDF
                            </button>
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>
                                Print
                            </button>
                        </div>
                    </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['total_donors']; ?></h5>
                                <p class="card-text text-muted">Total Pendonor</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-tint fa-2x text-success mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['total_donations']; ?></h5>
                                <p class="card-text text-muted">Total Donasi</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-holding-medical fa-2x text-danger mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['total_requests']; ?></h5>
                                <p class="card-text text-muted">Total Permintaan</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-2x text-info mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['total_events']; ?></h5>
                                <p class="card-text text-muted">Total Events</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stok Darah -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-burn me-2"></i>
                            Stok Darah
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Golongan Darah</th>
                                        <th>Stok Tersedia</th>
                                        <th>Minimal Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($stok_darah && mysqli_num_rows($stok_darah) > 0): $no = 1; ?>
                                        <?php while ($s = mysqli_fetch_assoc($stok_darah)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($s['blood_type'] . $s['rhesus']) ?></td>
                                            <td><?= $s['quantity'] ?> kantong</td>
                                            <td><?= $s['min_stock'] ?> kantong</td>
                                            <td>
                                                <?php if ($s['quantity'] <= $s['min_stock']): ?>
                                                    <span class="badge bg-danger">Stok Menipis</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Stok Aman</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center">Tidak ada data stok darah.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Data Pendonor -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Data Pendonor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Golongan Darah</th>
                                        <th>Gender</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                        <th>Jumlah Donasi</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($pendonor && mysqli_num_rows($pendonor) > 0): $no = 1; ?>
                                        <?php while ($d = mysqli_fetch_assoc($pendonor)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($d['name']) ?></td>
                                            <td><?= htmlspecialchars($d['blood_type_display']) ?></td>
                                            <td><?= $d['gender'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                            <td><?= htmlspecialchars($d['phone']) ?></td>
                                            <td><?= htmlspecialchars($d['email']) ?></td>
                                            <td><?= $d['donation_count'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="8" class="text-center">Tidak ada data pendonor.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Data Permintaan Darah -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-hand-holding-medical me-2"></i>
                            Data Permintaan Darah
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pasien</th>
                                        <th>Rumah Sakit</th>
                                        <th>Golongan Darah</th>
                                        <th>Jumlah Butuh</th>
                                        <th>Urgency</th>
                                        <th>Kontak</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($permintaan && mysqli_num_rows($permintaan) > 0): $no = 1; ?>
                                        <?php while ($r = mysqli_fetch_assoc($permintaan)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($r['patient_name']) ?></td>
                                            <td><?= htmlspecialchars($r['hospital']) ?></td>
                                            <td><?= htmlspecialchars($r['blood_type_display']) ?></td>
                                            <td><?= $r['quantity_needed'] ?> kantong</td>
                                            <td>
                                                <?php
                                                $urgency_class = '';
                                                switch($r['urgency']) {
                                                    case 'emergency': $urgency_class = 'bg-danger'; break;
                                                    case 'urgent': $urgency_class = 'bg-warning'; break;
                                                    default: $urgency_class = 'bg-info';
                                                }
                                                ?>
                                                <span class="badge <?= $urgency_class ?>"><?= ucfirst($r['urgency']) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($r['contact_phone']) ?></td>
                                            <td>
                                                <span class="badge status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="9" class="text-center">Tidak ada permintaan darah.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Data Events -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Data Events
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Event</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Lokasi</th>
                                        <th>Target</th>
                                        <th>Terdaftar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($events && mysqli_num_rows($events) > 0): $no = 1; ?>
                                        <?php while ($e = mysqli_fetch_assoc($events)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($e['title']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($e['event_date'])) ?></td>
                                            <td><?= $e['start_time'] ?> - <?= $e['end_time'] ?></td>
                                            <td><?= htmlspecialchars($e['location']) ?></td>
                                            <td><?= $e['target_donors'] ?> orang</td>
                                            <td><?= $e['registered_donors'] ?> orang</td>
                                            <td>
                                                <span class="badge status-<?= $e['status'] ?>"><?= ucfirst($e['status']) ?></span>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="8" class="text-center">Tidak ada data events.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportToPdf() {
            // Export to PDF functionality
            window.open('export_pdf_tcpdf.php', '_blank');
        }
    </script>
</body>
</html>

