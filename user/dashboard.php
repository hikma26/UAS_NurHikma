<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
if (!$query) {
    die("Query Error (users): " . mysqli_error($conn));
}
$user = mysqli_fetch_assoc($query);

// Check if user exists
if (!$user) {
    // User not found, redirect to login
    session_destroy();
    header("Location: ../login.php");
    exit;
}

// Statistik
$q1 = mysqli_query($conn, "SELECT COUNT(*) as count FROM donors");
$total_donors = $q1 ? mysqli_fetch_assoc($q1)['count'] : 0;

$q2 = mysqli_query($conn, "SELECT COUNT(*) as count FROM blood_requests WHERE status = 'pending'");
$active_requests = $q2 ? mysqli_fetch_assoc($q2)['count'] : 0;

$q3 = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'user'");
$total_users = $q3 ? mysqli_fetch_assoc($q3)['count'] : 0;

// Stok darah
$blood_stock = mysqli_query($conn, "SELECT CONCAT(blood_type, rhesus) as type, quantity as jumlah 
                                    FROM blood_stock 
                                    ORDER BY blood_type, rhesus");
if (!$blood_stock) {
    die("Query Error (stock): " . mysqli_error($conn));
}

// Jadwal donor
$recent_schedule = mysqli_query($conn, "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
if (!$recent_schedule) {
    die("Query Error (events): " . mysqli_error($conn));
}

// Notifikasi untuk marquee dan dashboard
$notifications_query = mysqli_query($conn, "SELECT * FROM notifications WHERE is_active = 1 AND (expires_at IS NULL OR expires_at > NOW()) ORDER BY created_at DESC LIMIT 5");
$notifications = [];
if ($notifications_query) {
    while ($row = mysqli_fetch_assoc($notifications_query)) {
        $notifications[] = $row;
    }
}

// Buat text marquee dari notifikasi
$marquee_text = '';
if (!empty($notifications)) {
    $marquee_items = [];
    foreach ($notifications as $notif) {
        $icon = $notif['type'] == 'info' ? '📢' : ($notif['type'] == 'warning' ? '⚠️' : ($notif['type'] == 'success' ? '✅' : '🚨'));
        $marquee_items[] = $icon . ' ' . htmlspecialchars($notif['title']) . ': ' . htmlspecialchars($notif['message']);
    }
    $marquee_text = implode(' • ', $marquee_items);
} else {
    // Fallback jika tidak ada notifikasi
    $marquee_text = '🩸 Ayo donor darah hari ini di PMI Mamuju Tengah • 📅 Jadwal donor rutin setiap hari kerja • 🎯 Setiap tetes darah sangat berarti • 💪 Satu donor dapat menyelamatkan 3 nyawa!';
}

// Dummy testimoni
$testimonials = [
    ['name' => 'Budi Santoso', 'message' => 'Putri saya selamat berkat donor darah dari sistem ini. Terima kasih!', 'role' => 'Penerima Darah', 'date' => '2025-01-10'],
    ['name' => 'Siti Aminah', 'message' => 'Pelayanan sangat cepat dan mudah. Saya rutin donor sekarang!', 'role' => 'Pendonor', 'date' => '2025-01-08'],
    ['name' => 'Ahmad Fauzi', 'message' => 'Sistem sangat membantu mencari pendonor secara cepat.', 'role' => 'Pendonor', 'date' => '2025-01-05']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Pendonor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link href="../../assets/style.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      min-height: 100vh;
      margin: 0;
      color: #2c3e50;
    }

    /* Header */
    .header-main {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      color: #fff;
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    .header-main::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 150px;
      height: 150px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      transform: translate(30%, -30%);
    }

    .brand-logo {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .brand-logo img {
      height: 60px;
      width: auto;
      filter: brightness(1.1);
      transition: all 0.3s ease;
    }
    
    .brand-logo:hover img {
      transform: scale(1.05) rotate(3deg);
    }

    .brand-logo span {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .btn-logout {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 8px 15px;
      border-radius: 25px;
      text-decoration: none;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }

    .btn-logout:hover {
      background: rgba(255, 255, 255, 0.3);
      color: #fff;
      transform: translateY(-2px);
    }

    /* Marquee */
    .marquee-container {
      background: #fff3cd;
      border: 1px solid #ffeaa7;
      padding: 12px 0;
      overflow: hidden;
      white-space: nowrap;
    }

    .marquee-text {
      display: inline-block;
      padding-left: 100%;
      animation: marquee 30s linear infinite;
      color: #856404;
      font-weight: 500;
    }

    @keyframes marquee {
      0% { 
        transform: translate3d(100%, 0, 0); 
      }
      10% { 
        transform: translate3d(0%, 0, 0); 
      }
      90% { 
        transform: translate3d(-100%, 0, 0); 
      }
      100% { 
        transform: translate3d(-100%, 0, 0); 
      }
    }

    /* Main Container */
    .container-main {
      flex: 1;
      padding: 30px 20px;
      max-width: 1200px;
    }

    /* Welcome Section */
    .welcome h5 {
      color: #8B0000;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .welcome p {
      color: #666;
      margin-bottom: 0;
    }

    /* Stats Section */
    .stats-section {
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      padding: 2rem;
      margin-bottom: 2.5rem;
    }
    
    .stats-section:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .stats-title {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .stat-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border-radius: 16px;
      padding: 2rem;
      text-align: center;
      border-left: 4px solid #dc2626;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      position: relative;
    }

    .stat-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 15px 35px rgba(220, 38, 38, 0.2);
      border-left-color: #b91c1c;
    }

    .stat-icon {
      font-size: 2rem;
      color: #dc2626;
      margin-bottom: 1rem;
      transition: all 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
      transform: scale(1.2) rotate(5deg);
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 800;
      color: #dc2626;
      margin-bottom: 0.5rem;
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .stat-label {
      font-size: 0.9rem;
      color: #666;
      font-weight: 500;
    }

    /* Blood Stock */
    .blood-stock {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
    }

    .blood-item {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: #fff;
      padding: 15px 20px;
      border-radius: 25px;
      text-align: center;
      min-width: 100px;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
      transition: all 0.3s ease;
    }

    .blood-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
    }

    .blood-type {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .blood-count {
      font-size: 0.9rem;
      opacity: 0.9;
    }

    /* Dashboard Grid */
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 25px;
      margin-bottom: 40px;
    }

    .card-menu {
      background: #fff;
      border-radius: 20px;
      padding: 30px 25px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      height: 100%;
      border: none;
      position: relative;
      overflow: hidden;
    }

    .card-menu::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #8B0000, #dc3545);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .card-menu:hover::before {
      transform: scaleX(1);
    }

    .card-menu:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .card-menu h5 {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 15px 0 10px 0;
      color: #8B0000;
    }

    .card-menu p {
      color: #666;
      font-size: 0.9rem;
      margin: 0;
    }

    .card-menu .icon {
      font-size: 32px;
      width: 70px;
      height: 70px;
      margin: 0 auto 15px auto;
      border-radius: 50%;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .bg-green { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
    .bg-orange { background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%); }
    .bg-blue { background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%); }
    .bg-purple { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); }

    /* Testimonials */
    .testimonials-section {
      background: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      margin-bottom: 30px;
    }

    .testimonials-title {
      font-size: 1.3rem;
      font-weight: 600;
      color: #8B0000;
      text-align: center;
      margin-bottom: 25px;
    }

    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    .testimonial-card {
      background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
      border-radius: 15px;
      padding: 20px;
      border-left: 4px solid #8B0000;
      transition: all 0.3s ease;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .testimonial-date {
      font-size: 0.8rem;
      color: #999;
      margin-bottom: 10px;
    }

    .testimonial-quote {
      font-style: italic;
      color: #555;
      margin-bottom: 15px;
      line-height: 1.6;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .testimonial-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: #8B0000;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 1.1rem;
    }

    .testimonial-info h6 {
      margin: 0;
      color: #8B0000;
      font-weight: 600;
    }

    .testimonial-info small {
      color: #666;
    }

    /* Footer */
    .footer-main {
      background: #1a1a1a;
      color: #fff;
      text-align: center;
      padding: 20px;
      margin-top: auto;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .header-main {
        flex-direction: column;
        gap: 15px;
        padding: 20px;
      }
      
      .brand-logo {
        flex-direction: column;
        gap: 10px;
      }
      
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
      
      .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }
      
      .testimonials-grid {
        grid-template-columns: 1fr;
      }
      
      .blood-stock {
        justify-content: center;
      }
    }
  </style>
</head>
<body>

<header class="header-main">
  <div class="brand-logo">
    <img src="../assets/logo-mateng.png" alt="Logo Mamuju Tengah">
    <span>Sistem Donor Darah - Kab. Mamuju Tengah</span>
  </div>
  <a href="../logout.php" class="btn-logout">
    <i class="fas fa-sign-out-alt me-1"></i> Keluar
  </a>
</header>

<?php if (!empty($marquee_text)): ?>
<div class="marquee-container">
  <div class="marquee-text">
    <i class="fas fa-bullhorn me-2"></i>
    <?= $marquee_text ?>
  </div>
</div>
<?php endif; ?>

<main class="container container-main">
  <div class="welcome text-center mb-4">
    <h5>Selamat Datang, <?= htmlspecialchars($user['full_name']) ?>!</h5>
    <p>Pilih layanan di bawah untuk mulai berkontribusi.</p>
  </div>

  <div class="stats-section">
    <div class="stats-title">
      <i class="fas fa-chart-bar me-2"></i>Statistik Real-Time
    </div>
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-number"><?= $total_donors ?></div>
        <div class="stat-label">Pendonor Terdaftar</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-hand-holding-medical"></i></div>
        <div class="stat-number"><?= $active_requests ?></div>
        <div class="stat-label">Permintaan Aktif</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
        <div class="stat-number"><?= $total_users ?></div>
        <div class="stat-label">Total Pengguna</div>
      </div>
    </div>

    <div class="blood-stock mt-4">
      <h6 class="text-center w-100 mb-3">Stok Darah Terkini</h6>
      <?php while ($stock = mysqli_fetch_assoc($blood_stock)): ?>
        <div class="blood-item">
          <div class="blood-type"><?= $stock['type'] ?></div>
          <div class="blood-count"><?= $stock['jumlah'] ?> kantong</div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <?php if (!empty($notifications)): ?>
  <div class="testimonials-section">
    <div class="testimonials-title">
      <i class="fas fa-bell me-2"></i>Notifikasi Terbaru
    </div>
    <div class="testimonials-grid">
      <?php foreach($notifications as $notification): ?>
        <div class="testimonial-card">
          <div class="testimonial-date"><?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?></div>
          <div class="testimonial-quote">
            <strong><?= htmlspecialchars($notification['title']) ?></strong><br>
            <?= htmlspecialchars($notification['message']) ?>
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar">
              <i class="fas fa-<?= $notification['type'] == 'info' ? 'info-circle' : ($notification['type'] == 'warning' ? 'exclamation-triangle' : ($notification['type'] == 'success' ? 'check-circle' : 'exclamation-circle')) ?>"></i>
            </div>
            <div class="testimonial-info">
              <h6><?= ucfirst($notification['type']) ?></h6>
              <small>Notifikasi Resmi</small>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="dashboard-grid mt-4">
    <a href="daftar.php" class="text-decoration-none">
      <div class="card-menu">
        <div class="icon bg-green"><i class="fas fa-user-plus"></i></div>
        <h5>Daftar Pendonor</h5>
        <p>Isi data pribadi dan mulai jadi pahlawan.</p>
      </div>
    </a>
    <a href="permintaan.php" class="text-decoration-none">
      <div class="card-menu">
        <div class="icon bg-orange"><i class="fas fa-hand-holding-medical"></i></div>
        <h5>Permintaan Darah</h5>
        <p>Ajukan permintaan bantuan darah sekarang.</p>
      </div>
    </a>
    <a href="stok-darah.php" class="text-decoration-none">
      <div class="card-menu">
        <div class="icon bg-blue"><i class="fas fa-tint"></i></div>
        <h5>Cek Stok Darah</h5>
        <p>Lihat ketersediaan darah di wilayahmu.</p>
      </div>
    </a>
    <a href="jadwal-donor.php" class="text-decoration-none">
      <div class="card-menu">
        <div class="icon bg-purple"><i class="fas fa-calendar-alt"></i></div>
        <h5>Jadwal Donor</h5>
        <p>Temukan lokasi dan waktu donor terdekat.</p>
      </div>
    </a>
  </div>

  <div class="testimonials-section">
    <div class="testimonials-title">
      <i class="fas fa-heart me-2"></i>Kisah Nyata & Testimoni
    </div>
    <div class="testimonials-grid">
      <?php foreach($testimonials as $t): ?>
        <div class="testimonial-card">
          <div class="testimonial-date"><?= date('d/m/Y', strtotime($t['date'])) ?></div>
          <div class="testimonial-quote">
            <i class="fas fa-quote-left me-2 text-danger"></i><?= htmlspecialchars($t['message']) ?><i class="fas fa-quote-right ms-2 text-danger"></i>
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
            <div class="testimonial-info">
              <h6><?= htmlspecialchars($t['name']) ?></h6>
              <small><?= htmlspecialchars($t['role']) ?></small>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<footer class="footer-main">
  &copy; <?= date('Y') ?> Sistem Donor Darah • Kabupaten Mamuju Tengah
</footer>

</body>
</html>

