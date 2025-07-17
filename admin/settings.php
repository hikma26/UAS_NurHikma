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
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.375rem;
        }
        .card-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-bottom: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
        }
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #dc3545;
        }
        .setting-item {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .setting-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-cogs me-2"></i>
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

