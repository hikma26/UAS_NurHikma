<?php
include 'auth_check.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $title = trim($_POST['title']);
                $message = trim($_POST['message']);
                $type = $_POST['type'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
                
                $stmt = mysqli_prepare($conn, "INSERT INTO notifications (title, message, type, is_active, expires_at) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssss", $title, $message, $type, $is_active, $expires_at);
                mysqli_stmt_execute($stmt);
                $success_message = "Notifikasi berhasil ditambahkan!";
                break;
                
            case 'edit':
                $id = $_POST['id'];
                $title = trim($_POST['title']);
                $message = trim($_POST['message']);
                $type = $_POST['type'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
                
                $stmt = mysqli_prepare($conn, "UPDATE notifications SET title = ?, message = ?, type = ?, is_active = ?, expires_at = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "sssisi", $title, $message, $type, $is_active, $expires_at, $id);
                mysqli_stmt_execute($stmt);
                $success_message = "Notifikasi berhasil diperbarui!";
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $stmt = mysqli_prepare($conn, "DELETE FROM notifications WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $success_message = "Notifikasi berhasil dihapus!";
                break;
                
            case 'toggle_status':
                $id = $_POST['id'];
                $stmt = mysqli_prepare($conn, "UPDATE notifications SET is_active = NOT is_active WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $success_message = "Status notifikasi berhasil diubah!";
                break;
        }
    }
}

// Fetch all notifications
$result = mysqli_query($conn, "SELECT * FROM notifications ORDER BY created_at DESC");
$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

// Get notification for editing
$edit_notification = null;
if (isset($_GET['edit'])) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM notifications WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_GET['edit']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $edit_notification = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Notifikasi - Admin Panel</title>
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
        .card {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        .notification-preview {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin-top: 0.5rem;
            background-color: #f8f9fa;
        }
        .marquee-preview {
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
            animation: marquee 15s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translate3d(100%, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }
        .notification-item {
            transition: all 0.3s ease;
        }
        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .badge-type {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .type-info { background-color: #0dcaf0; }
        .type-warning { background-color: #ffc107; }
        .type-success { background-color: #198754; }
        .type-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h1 class="page-title">
                            <i class="fas fa-bell-ring me-3" style="color: #dc2626;"></i>
                            Kelola Notifikasi
                        </h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                            <i class="fas fa-plus me-2"></i>
                            Tambah Notifikasi
                        </button>
                    </div>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Notifications List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Daftar Notifikasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($notifications)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada notifikasi</h5>
                                <p class="text-muted">Klik tombol "Tambah Notifikasi" untuk membuat notifikasi baru</p>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card notification-item h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0"><?php echo htmlspecialchars($notification['title']); ?></h6>
                                                    <span class="badge badge-type type-<?php echo $notification['type']; ?>">
                                                        <?php echo ucfirst($notification['type']); ?>
                                                    </span>
                                                </div>
                                                <p class="card-text text-muted small mb-2">
                                                    <?php echo htmlspecialchars(substr($notification['message'], 0, 100)) . (strlen($notification['message']) > 100 ? '...' : ''); ?>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?>
                                                    </small>
                                                    <div class="btn-group btn-group-sm">
                                                        <form method="post" class="d-inline">
                                                            <input type="hidden" name="action" value="toggle_status">
                                                            <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                                            <button type="submit" class="btn btn-outline-<?php echo $notification['is_active'] ? 'success' : 'secondary'; ?>" title="<?php echo $notification['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>">
                                                                <i class="fas fa-<?php echo $notification['is_active'] ? 'toggle-on' : 'toggle-off'; ?>"></i>
                                                            </button>
                                                        </form>
                                                        <button class="btn btn-outline-primary" onclick="editNotification(<?php echo htmlspecialchars(json_encode($notification)); ?>)" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Notification Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>
                        <span id="modalTitle">Tambah Notifikasi</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" id="notificationForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="notificationId">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Notifikasi</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required oninput="updatePreview()"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe Notifikasi</label>
                            <select class="form-select" id="type" name="type" onchange="updatePreview()">
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="success">Success</option>
                                <option value="danger">Danger</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Tanggal Kedaluwarsa (Opsional)</label>
                            <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Aktifkan notifikasi
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Preview Marquee</label>
                            <div class="notification-preview">
                                <div class="marquee-preview" id="marqueePreview">
                                    Preview akan muncul di sini...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            <span id="submitText">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updatePreview() {
            const message = document.getElementById('message').value;
            const type = document.getElementById('type').value;
            const preview = document.getElementById('marqueePreview');
            
            if (message.trim()) {
                preview.innerHTML = message;
                preview.className = 'marquee-preview alert alert-' + (type === 'danger' ? 'danger' : type === 'warning' ? 'warning' : type === 'success' ? 'success' : 'info');
            } else {
                preview.innerHTML = 'Preview akan muncul di sini...';
                preview.className = 'marquee-preview';
            }
        }

        function editNotification(notification) {
            document.getElementById('modalTitle').textContent = 'Edit Notifikasi';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('notificationId').value = notification.id;
            document.getElementById('title').value = notification.title;
            document.getElementById('message').value = notification.message;
            document.getElementById('type').value = notification.type;
            document.getElementById('expires_at').value = notification.expires_at ? notification.expires_at.replace(' ', 'T') : '';
            document.getElementById('is_active').checked = notification.is_active == 1;
            document.getElementById('submitText').textContent = 'Update';
            
            updatePreview();
            
            const modal = new bootstrap.Modal(document.getElementById('addNotificationModal'));
            modal.show();
        }

        // Reset form when modal is closed
        document.getElementById('addNotificationModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalTitle').textContent = 'Tambah Notifikasi';
            document.getElementById('formAction').value = 'add';
            document.getElementById('notificationId').value = '';
            document.getElementById('notificationForm').reset();
            document.getElementById('is_active').checked = true;
            document.getElementById('submitText').textContent = 'Simpan';
            document.getElementById('marqueePreview').innerHTML = 'Preview akan muncul di sini...';
            document.getElementById('marqueePreview').className = 'marquee-preview';
        });

        // Initialize preview on page load
        updatePreview();
    </script>
</body>
</html>

