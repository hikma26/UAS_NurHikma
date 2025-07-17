<?php
include 'auth_check.php';

// Get recent activities from different tables
$recent_activities = [];

// Get recent donations
$donations = mysqli_query($conn, "
    SELECT 'donation' as type, d.id, d.created_at, dn.name as donor_name, d.status, d.blood_type, d.rhesus
    FROM donations d 
    JOIN donors dn ON d.donor_id = dn.id
    ORDER BY d.created_at DESC 
    LIMIT 10
");

if ($donations) {
    while ($row = mysqli_fetch_assoc($donations)) {
        $recent_activities[] = $row;
    }
}

// Get recent blood requests
$requests = mysqli_query($conn, "
    SELECT 'blood_request' as type, id, created_at, patient_name, hospital, status, blood_type, rhesus
    FROM blood_requests 
    ORDER BY created_at DESC 
    LIMIT 10
");

if ($requests) {
    while ($row = mysqli_fetch_assoc($requests)) {
        $recent_activities[] = $row;
    }
}

// Get recent events
$events = mysqli_query($conn, "
    SELECT 'event' as type, id, created_at, title, event_date, status, location
    FROM events 
    ORDER BY created_at DESC 
    LIMIT 10
");

if ($events) {
    while ($row = mysqli_fetch_assoc($events)) {
        $recent_activities[] = $row;
    }
}

// Get recent user registrations
$users = mysqli_query($conn, "
    SELECT 'user' as type, id, created_at, username, full_name, role, email
    FROM users 
    ORDER BY created_at DESC 
    LIMIT 10
");

if ($users) {
    while ($row = mysqli_fetch_assoc($users)) {
        $recent_activities[] = $row;
    }
}

// Sort all activities by created_at
usort($recent_activities, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

// Get only the 20 most recent activities
$recent_activities = array_slice($recent_activities, 0, 20);

// Get statistics
$stats = [
    'today_donations' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM donations WHERE DATE(created_at) = CURDATE()"))['count'],
    'today_requests' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM blood_requests WHERE DATE(created_at) = CURDATE()"))['count'],
    'today_events' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM events WHERE DATE(created_at) = CURDATE()"))['count'],
    'today_users' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()"))['count'],
];

function getActivityIcon($type) {
    switch ($type) {
        case 'donation': return 'fas fa-tint';
        case 'blood_request': return 'fas fa-hand-holding-medical';
        case 'event': return 'fas fa-calendar-alt';
        case 'user': return 'fas fa-user-plus';
        default: return 'fas fa-circle';
    }
}

function getActivityColor($type) {
    switch ($type) {
        case 'donation': return 'text-success';
        case 'blood_request': return 'text-danger';
        case 'event': return 'text-info';
        case 'user': return 'text-primary';
        default: return 'text-secondary';
    }
}

function getActivityTitle($activity) {
    switch ($activity['type']) {
        case 'donation':
            return "Donor darah baru: " . $activity['donor_name'];
        case 'blood_request':
            return "Permintaan darah: " . $activity['patient_name'] . " di " . $activity['hospital'];
        case 'event':
            return "Event baru: " . $activity['title'];
        case 'user':
            return "User baru: " . $activity['full_name'] . " (" . $activity['role'] . ")";
        default:
            return "Aktivitas tidak dikenal";
    }
}

function getActivityDescription($activity) {
    switch ($activity['type']) {
        case 'donation':
            return "Golongan darah: " . $activity['blood_type'] . $activity['rhesus'] . " - Status: " . $activity['status'];
        case 'blood_request':
            return "Golongan darah: " . $activity['blood_type'] . $activity['rhesus'] . " - Status: " . $activity['status'];
        case 'event':
            return "Tanggal: " . date('d/m/Y', strtotime($activity['event_date'])) . " - Lokasi: " . $activity['location'];
        case 'user':
            return "Username: " . $activity['username'] . " - Email: " . $activity['email'];
        default:
            return "";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas Sistem - Admin Panel</title>
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
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #dc3545;
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .activity-item {
            border-left: 3px solid #dee2e6;
            padding-left: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .activity-item:hover {
            border-left-color: #dc3545;
            background-color: #f8f9fa;
        }
        .activity-time {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .activity-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 0.875rem;
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #dc3545;
            border: 2px solid #fff;
            box-shadow: 0 0 0 3px #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Aktivitas Sistem
                    </h1>
                    <button class="btn btn-outline-primary" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>
                        Refresh
                    </button>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-tint fa-2x text-success mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['today_donations']; ?></h5>
                                <p class="card-text text-muted">Donasi Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-holding-medical fa-2x text-danger mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['today_requests']; ?></h5>
                                <p class="card-text text-muted">Permintaan Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-2x text-info mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['today_events']; ?></h5>
                                <p class="card-text text-muted">Event Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                                <h5 class="card-title"><?php echo $stats['today_users']; ?></h5>
                                <p class="card-text text-muted">User Baru Hari Ini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recent_activities)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada aktivitas</h5>
                                <p class="text-muted">Aktivitas sistem akan muncul di sini</p>
                            </div>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach ($recent_activities as $activity): ?>
                                    <div class="timeline-item">
                                        <div class="d-flex align-items-start">
                                            <div class="activity-icon bg-light <?php echo getActivityColor($activity['type']); ?>">
                                                <i class="<?php echo getActivityIcon($activity['type']); ?>"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1"><?php echo getActivityTitle($activity); ?></h6>
                                                        <p class="mb-1 text-muted"><?php echo getActivityDescription($activity); ?></p>
                                                    </div>
                                                    <small class="activity-time">
                                                        <?php echo date('d/m/Y H:i', strtotime($activity['created_at'])); ?>
                                                    </small>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh setiap 5 menit
        setInterval(function() {
            location.reload();
        }, 300000);
    </script>
</body>
</html>

