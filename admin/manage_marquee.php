<?php
session_start();
include '../config/koneksi.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $message = mysqli_real_escape_string($conn, $_POST['message']);
                $active = isset($_POST['active']) ? 1 : 0;
                
                $query = "INSERT INTO marquee_notifications (message, active, created_at) VALUES ('$message', $active, NOW())";
                if (mysqli_query($conn, $query)) {
                    $success = "Marquee notification added successfully!";
                } else {
                    $error = "Error adding notification: " . mysqli_error($conn);
                }
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $message = mysqli_real_escape_string($conn, $_POST['message']);
                $active = isset($_POST['active']) ? 1 : 0;
                
                $query = "UPDATE marquee_notifications SET message='$message', active=$active, updated_at=NOW() WHERE id=$id";
                if (mysqli_query($conn, $query)) {
                    $success = "Marquee notification updated successfully!";
                } else {
                    $error = "Error updating notification: " . mysqli_error($conn);
                }
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                $query = "DELETE FROM marquee_notifications WHERE id=$id";
                if (mysqli_query($conn, $query)) {
                    $success = "Marquee notification deleted successfully!";
                } else {
                    $error = "Error deleting notification: " . mysqli_error($conn);
                }
                break;
                
            case 'toggle_status':
                $id = (int)$_POST['id'];
                $query = "UPDATE marquee_notifications SET active = CASE WHEN active = 1 THEN 0 ELSE 1 END, updated_at=NOW() WHERE id=$id";
                if (mysqli_query($conn, $query)) {
                    $success = "Notification status updated successfully!";
                } else {
                    $error = "Error updating status: " . mysqli_error($conn);
                }
                break;
        }
    }
}

// Fetch all marquee notifications
$query = "SELECT * FROM marquee_notifications ORDER BY created_at DESC";
$notifications = mysqli_query($conn, $query);

// Get notification for editing
$edit_notification = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = "SELECT * FROM marquee_notifications WHERE id=$edit_id";
    $edit_result = mysqli_query($conn, $edit_query);
    $edit_notification = mysqli_fetch_assoc($edit_result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Marquee Notifications - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e53e3e;
            --secondary-color: #fff5f5;
            --accent-color: #feb2b2;
            --text-dark: #2d3748;
            --text-light: #718096;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c53030 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c53030 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c53030 100%);
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229, 62, 62, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            border: none;
            border-radius: 8px;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            border: none;
            border-radius: 8px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            border: none;
            border-radius: 8px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .table th {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c53030 100%);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #edf2f7;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-active {
            background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
            color: #22543d;
        }

        .status-inactive {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            color: #742a2a;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
            color: #22543d;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            color: #742a2a;
        }

        .page-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #ffffff 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .marquee-preview {
            background: linear-gradient(45deg, var(--primary-color), #c53030);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .marquee-text {
            white-space: nowrap;
            animation: scroll-left 15s linear infinite;
        }

        @keyframes scroll-left {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .table-responsive {
            border-radius: 10px;
        }

        .back-btn {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 85, 104, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tint me-2"></i>Blood Donation System - Admin
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="../logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-bullhorn me-2"></i>Manage Marquee Notifications
                    </h1>
                    <p class="text-muted mb-0">Create and manage scrolling announcements for the user dashboard</p>
                </div>
                <a href="dashboard.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Add/Edit Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-<?php echo $edit_notification ? 'edit' : 'plus'; ?> me-2"></i>
                            <?php echo $edit_notification ? 'Edit' : 'Add New'; ?> Notification
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="<?php echo $edit_notification ? 'edit' : 'add'; ?>">
                            <?php if ($edit_notification): ?>
                                <input type="hidden" name="id" value="<?php echo $edit_notification['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment-alt me-1"></i>Message
                                </label>
                                <textarea class="form-control" id="message" name="message" rows="3" required 
                                          placeholder="Enter marquee notification message..."><?php echo $edit_notification ? htmlspecialchars($edit_notification['message']) : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" 
                                           <?php echo (!$edit_notification || $edit_notification['active']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="active">
                                        <i class="fas fa-eye me-1"></i>Active (Show on dashboard)
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Preview -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-preview me-1"></i>Preview
                                </label>
                                <div class="marquee-preview">
                                    <div class="marquee-text" id="preview-text">
                                        <?php echo $edit_notification ? htmlspecialchars($edit_notification['message']) : 'Your notification message will appear here...'; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                <?php echo $edit_notification ? 'Update' : 'Add'; ?> Notification
                            </button>
                            
                            <?php if ($edit_notification): ?>
                                <a href="manage_marquee.php" class="btn btn-secondary w-100 mt-2">
                                    <i class="fas fa-times me-2"></i>Cancel Edit
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>All Notifications
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($notifications) > 0): ?>
                                        <?php while ($notification = mysqli_fetch_assoc($notifications)): ?>
                                            <tr>
                                                <td><strong>#<?php echo $notification['id']; ?></strong></td>
                                                <td>
                                                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?php echo htmlspecialchars($notification['message']); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?php echo $notification['active'] ? 'status-active' : 'status-inactive'; ?>">
                                                        <i class="fas fa-<?php echo $notification['active'] ? 'check' : 'times'; ?> me-1"></i>
                                                        <?php echo $notification['active'] ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo date('M d, Y', strtotime($notification['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="?edit=<?php echo $notification['id']; ?>" 
                                                           class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <form method="POST" style="display: inline;" 
                                                              onsubmit="return confirm('Toggle status for this notification?');">
                                                            <input type="hidden" name="action" value="toggle_status">
                                                            <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                                            <button type="submit" class="btn btn-success btn-sm" title="Toggle Status">
                                                                <i class="fas fa-toggle-<?php echo $notification['active'] ? 'on' : 'off'; ?>"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <form method="POST" style="display: inline;" 
                                                              onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="fas fa-inbox me-2"></i>No notifications found. Create your first notification!
                                            </td>
                                        </tr>
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
        // Live preview of marquee message
        document.getElementById('message').addEventListener('input', function() {
            const previewText = document.getElementById('preview-text');
            previewText.textContent = this.value || 'Your notification message will appear here...';
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>

