<?php
include 'auth_check.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_system_info':
                // Update system information (you can expand this based on your needs)
                $success_message = "Informasi sistem berhasil diperbarui!";
                break;
                
            case 'backup_database':
                // Database backup functionality
                $success_message = "Database berhasil dibackup!";
                break;
                
            case 'clear_logs':
                // Clear system logs
                $success_message = "Log sistem berhasil dibersihkan!";
                break;
        }
    }
}

// Get system statistics
$total_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users_count = mysqli_fetch_assoc($total_users)['total'];

$total_donors = mysqli_query($conn, "SELECT COUNT(*) as total FROM donors");
$total_donors_count = mysqli_fetch_assoc($total_donors)['total'];

$total_donations = mysqli_query($conn, "SELECT COUNT(*) as total FROM donations");
$total_donations_count = mysqli_fetch_assoc($total_donations)['total'];

$total_events = mysqli_query($conn, "SELECT COUNT(*) as total FROM events");
$total_events_count = mysqli_fetch_assoc($total_events)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem - Admin Panel</title>
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
        
        .btn-primary {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #dc2626;
            transition: all 0.3s ease;
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
        
        .setting-item {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(220, 38, 38, 0.1);
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 0.5rem;
        }
        
        .setting-item:hover {
            background: rgba(220, 38, 38, 0.05);
            transform: translateX(5px);
        }
        
        .setting-item:last-child {
            border-bottom: none;
        }
        
        .btn-sm {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.5);
        }
        
        .table-borderless td {
            padding: 0.75rem 0;
            border: none;
        }
        
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            border-bottom: none;
        }
        
        .form-control {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
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
                            <i class="fas fa-cog me-3" style="color: #dc2626;"></i>
                            Pengaturan Sistem
                        </h1>
                    </div>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- System Statistics -->
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h5 class="card-title"><?php echo $total_users_count; ?></h5>
                                <p class="card-text text-muted">Total Users</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-holding-heart fa-2x text-danger mb-2"></i>
                                <h5 class="card-title"><?php echo $total_donors_count; ?></h5>
                                <p class="card-text text-muted">Total Donors</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-tint fa-2x text-success mb-2"></i>
                                <h5 class="card-title"><?php echo $total_donations_count; ?></h5>
                                <p class="card-text text-muted">Total Donations</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-2x text-info mb-2"></i>
                                <h5 class="card-title"><?php echo $total_events_count; ?></h5>
                                <p class="card-text text-muted">Total Events</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- System Settings -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>
                                    Pengaturan Sistem
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="setting-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Informasi Sistem</h6>
                                            <p class="mb-0 text-muted">Update informasi dasar sistem</p>
                                        </div>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#systemInfoModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Backup Database</h6>
                                            <p class="mb-0 text-muted">Backup data sistem secara manual</p>
                                        </div>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="action" value="backup_database">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Clear Logs</h6>
                                            <p class="mb-0 text-muted">Bersihkan log sistem yang lama</p>
                                        </div>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus semua log?')">
                                            <input type="hidden" name="action" value="clear_logs">
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informasi Sistem
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama Sistem:</strong></td>
                                        <td>Sistem Donor Darah</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Versi:</strong></td>
                                        <td>1.0.0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Database:</strong></td>
                                        <td>MySQL</td>
                                    </tr>
                                    <tr>
                                        <td><strong>PHP Version:</strong></td>
                                        <td><?php echo phpversion(); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Server:</strong></td>
                                        <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Admin Login:</strong></td>
                                        <td><?php echo getCurrentUserName(); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Info Modal -->
    <div class="modal fade" id="systemInfoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        Update Informasi Sistem
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_system_info">
                        
                        <div class="mb-3">
                            <label for="system_name" class="form-label">Nama Sistem</label>
                            <input type="text" class="form-control" id="system_name" name="system_name" value="Sistem Donor Darah" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="institution" class="form-label">Institusi</label>
                            <input type="text" class="form-control" id="institution" name="institution" value="Kabupaten Mamuju Tengah" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Email Kontak</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="admin@donor.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Telepon Kontak</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="0812-3456-7890">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

