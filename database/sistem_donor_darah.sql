-- ========================================
-- SISTEM DONOR DARAH - MAMUJU TENGAH
-- Database Schema dan Data Awal
-- ========================================
-- Nama: Nur Hikma
-- NIM: 202312074
-- Email: hikma7091@gmail.com
-- Tugas: Ujian Akhir Semester - Pemrograman Web
-- ========================================

-- Buat database
CREATE DATABASE IF NOT EXISTS `sistem_donor_darah` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistem_donor_darah`;

-- ========================================
-- TABEL UTAMA
-- ========================================

-- Tabel users untuk pengguna sistem
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','petugas','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_users_role` (`role`),
  INDEX `idx_users_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel donor (pendonor)
CREATE TABLE IF NOT EXISTS `donors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('L','P') NOT NULL,
  `blood_type` enum('A','B','AB','O') NOT NULL,
  `rhesus` enum('+','-') NOT NULL DEFAULT '+',
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `last_donation` date DEFAULT NULL,
  `donation_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  INDEX `idx_donors_blood_type` (`blood_type`, `rhesus`),
  INDEX `idx_donors_active` (`is_active`),
  CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel blood_stock (stok darah)
CREATE TABLE IF NOT EXISTS `blood_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blood_type` enum('A','B','AB','O') NOT NULL,
  `rhesus` enum('+','-') NOT NULL DEFAULT '+',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reserved_quantity` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 10,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blood_type_rhesus` (`blood_type`,`rhesus`),
  INDEX `idx_blood_stock_quantity` (`quantity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel donations (riwayat donor)
CREATE TABLE IF NOT EXISTS `donations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `donation_date` date NOT NULL,
  `blood_type` enum('A','B','AB','O') NOT NULL,
  `rhesus` enum('+','-') NOT NULL DEFAULT '+',
  `quantity` int(11) NOT NULL DEFAULT 350,
  `hemoglobin` decimal(4,2) DEFAULT NULL,
  `blood_pressure` varchar(20) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `temperature` decimal(4,2) DEFAULT NULL,
  `medical_check` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `donor_id` (`donor_id`),
  KEY `processed_by` (`processed_by`),
  INDEX `idx_donations_date` (`donation_date`),
  INDEX `idx_donations_status` (`status`),
  CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel blood_requests (permintaan darah)
CREATE TABLE IF NOT EXISTS `blood_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(100) NOT NULL,
  `hospital` varchar(100) NOT NULL,
  `blood_type` enum('A','B','AB','O') NOT NULL,
  `rhesus` enum('+','-') NOT NULL DEFAULT '+',
  `quantity_needed` int(11) NOT NULL,
  `urgency` enum('normal','urgent','emergency') NOT NULL DEFAULT 'normal',
  `request_date` date NOT NULL,
  `needed_date` date NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed','cancelled') NOT NULL DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `approved_by` (`approved_by`),
  INDEX `idx_requests_date` (`request_date`),
  INDEX `idx_requests_status` (`status`),
  INDEX `idx_requests_urgency` (`urgency`),
  CONSTRAINT `blood_requests_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel events (event donor darah)
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(200) NOT NULL,
  `target_donors` int(11) DEFAULT NULL,
  `registered_donors` int(11) DEFAULT 0,
  `status` enum('planned','active','completed','cancelled') NOT NULL DEFAULT 'planned',
  `organizer` varchar(100) DEFAULT NULL,
  `contact_info` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  INDEX `idx_events_date` (`event_date`),
  INDEX `idx_events_status` (`status`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel event_registrations (pendaftaran event)
CREATE TABLE IF NOT EXISTS `event_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `registration_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `status` enum('registered','confirmed','attended','cancelled') NOT NULL DEFAULT 'registered',
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_donor_unique` (`event_id`,`donor_id`),
  KEY `donor_id` (`donor_id`),
  CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','success','danger') DEFAULT 'info',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_notifications_active` (`is_active`),
  INDEX `idx_notifications_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- DATA AWAL SISTEM
-- ========================================

-- Insert admin dan petugas (password: password)
INSERT INTO `users` (`username`, `password`, `email`, `full_name`, `role`, `is_active`) VALUES
('admin', '$2a$12$s9jAA02c/Kt1ZmSK5OZ6V.v8B.WUOBpuSh.hdzP/1dxIkJLXR62jm', 'admin@donor.com', 'Administrator Sistem', 'admin', 1),
('petugas', '$2a$12$s9jAA02c/Kt1ZmSK5OZ6V.v8B.WUOBpuSh.hdzP/1dxIkJLXR62jm', 'petugas@donor.com', 'Petugas PMI', 'user', 1);

-- Insert stok darah awal
INSERT INTO `blood_stock` (`blood_type`, `rhesus`, `quantity`, `min_stock`) VALUES
('A', '+', 25, 10),
('A', '-', 5, 5),
('B', '+', 20, 10),
('B', '-', 3, 5),
('AB', '+', 15, 8),
('AB', '-', 2, 3),
('O', '+', 30, 15),
('O', '-', 8, 8);

-- Insert notifikasi sistem
INSERT INTO `notifications` (`title`, `message`, `type`, `is_active`) VALUES
('Selamat Datang', 'Selamat datang di Sistem Donor Darah Kabupaten Mamuju Tengah. Mari berpartisipasi dalam kegiatan donor darah untuk membantu sesama!', 'info', 1),
('Stok Darah Menipis', 'Stok darah golongan AB- dan O- sedang menipis. Diperlukan segera donor darah untuk golongan tersebut.', 'warning', 1),
('Event Donor Darah', 'Event donor darah massal akan dilaksanakan di Gedung Serbaguna Mamuju Tengah. Daftarkan diri Anda sekarang!', 'success', 1);

-- Insert contoh event donor darah
INSERT INTO `events` (`title`, `description`, `event_date`, `start_time`, `end_time`, `location`, `target_donors`, `organizer`, `contact_info`, `created_by`) VALUES
('Donor Darah Massal - Hari Kesehatan Nasional', 'Event donor darah massal dalam rangka memperingati Hari Kesehatan Nasional 2024. Gratis pemeriksaan kesehatan untuk semua peserta.', '2024-12-20', '08:00:00', '14:00:00', 'Gedung Serbaguna Mamuju Tengah', 100, 'PMI Mamuju Tengah', '0812-3456-7890', 1),
('Donor Darah Rutin - Bulanan', 'Kegiatan donor darah rutin yang dilaksanakan setiap bulan untuk memenuhi kebutuhan stok darah di rumah sakit.', '2024-12-15', '09:00:00', '15:00:00', 'Kantor PMI Mamuju Tengah', 50, 'PMI Mamuju Tengah', '0812-3456-7890', 1);

-- Insert contoh data donor
INSERT INTO `donors` (`name`, `email`, `phone`, `address`, `birth_date`, `gender`, `blood_type`, `rhesus`, `weight`, `height`, `donation_count`, `last_donation`) VALUES
('Ahmad Hidayat', 'ahmad.hidayat@gmail.com', '081234567890', 'Jl. Sudirman No. 123, Mamuju Tengah', '1990-03-15', 'L', 'A', '+', 75.5, 175.0, 5, '2024-09-15'),
('Siti Nurhaliza', 'siti.nurhaliza@gmail.com', '081234567891', 'Jl. Diponegoro No. 45, Mamuju Tengah', '1992-07-22', 'P', 'B', '+', 58.0, 162.0, 3, '2024-08-20'),
('Budi Santoso', 'budi.santoso@gmail.com', '081234567892', 'Jl. Kartini No. 67, Mamuju Tengah', '1988-11-10', 'L', 'O', '+', 80.0, 180.0, 8, '2024-10-05'),
('Dewi Sartika', 'dewi.sartika@gmail.com', '081234567893', 'Jl. Cut Nyak Dien No. 89, Mamuju Tengah', '1995-01-30', 'P', 'AB', '+', 52.0, 158.0, 2, '2024-07-12');

-- Insert contoh permintaan darah
INSERT INTO `blood_requests` (`patient_name`, `hospital`, `blood_type`, `rhesus`, `quantity_needed`, `urgency`, `request_date`, `needed_date`, `contact_person`, `contact_phone`, `notes`, `status`) VALUES
('Pak Surya', 'RSUD Mamuju Tengah', 'A', '+', 2, 'urgent', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'Dr. Siti Rahmawati', '081234567894', 'Untuk operasi darurat, kondisi pasien stabil', 'pending'),
('Ibu Aminah', 'RS Bhayangkara Mamuju', 'O', '+', 1, 'normal', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'Ns. Budi Wijaya', '081234567895', 'Untuk transfusi rutin, pasien thalassemia', 'pending'),
('Anak Rudi', 'RS Ibu dan Anak Mamuju', 'B', '+', 1, 'emergency', CURDATE(), CURDATE(), 'Dr. Ahmad Fauzi', '081234567896', 'Kecelakaan lalu lintas, perlu segera', 'approved');

-- Insert contoh riwayat donasi
INSERT INTO `donations` (`donor_id`, `donation_date`, `blood_type`, `rhesus`, `quantity`, `hemoglobin`, `blood_pressure`, `weight`, `temperature`, `status`, `processed_by`) VALUES
(1, '2024-09-15', 'A', '+', 350, 14.5, '120/80', 75.5, 36.5, 'completed', 1),
(2, '2024-08-20', 'B', '+', 350, 13.8, '110/70', 58.0, 36.2, 'completed', 1),
(3, '2024-10-05', 'O', '+', 350, 15.2, '130/85', 80.0, 36.8, 'completed', 1);

-- ========================================
-- INDEXES UNTUK OPTIMASI
-- ========================================

-- Tambahan index untuk performa yang lebih baik
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_donors_name ON donors(name);
CREATE INDEX idx_blood_requests_hospital ON blood_requests(hospital);
CREATE INDEX idx_events_location ON events(location);

-- ========================================
-- VIEWS UNTUK KEMUDAHAN QUERY
-- ========================================

-- View untuk statistik darah
CREATE VIEW view_blood_statistics AS
SELECT 
    CONCAT(blood_type, rhesus) as blood_type_full,
    blood_type,
    rhesus,
    quantity,
    reserved_quantity,
    (quantity - reserved_quantity) as available_quantity,
    min_stock,
    CASE 
        WHEN (quantity - reserved_quantity) <= min_stock THEN 'Low Stock'
        WHEN (quantity - reserved_quantity) <= (min_stock * 2) THEN 'Medium Stock'
        ELSE 'High Stock'
    END as stock_status
FROM blood_stock
ORDER BY blood_type, rhesus;

-- View untuk donor aktif
CREATE VIEW view_active_donors AS
SELECT 
    d.id,
    d.name,
    d.email,
    d.phone,
    CONCAT(d.blood_type, d.rhesus) as blood_type_full,
    d.donation_count,
    d.last_donation,
    DATEDIFF(CURDATE(), d.last_donation) as days_since_last_donation,
    CASE 
        WHEN d.last_donation IS NULL THEN 'Eligible'
        WHEN DATEDIFF(CURDATE(), d.last_donation) >= 90 THEN 'Eligible'
        ELSE 'Not Eligible'
    END as donation_eligibility
FROM donors d
WHERE d.is_active = 1
ORDER BY d.last_donation DESC;

-- ========================================
-- STORED PROCEDURES
-- ========================================

-- Procedure untuk update stok darah setelah donasi
DELIMITER //
CREATE PROCEDURE UpdateBloodStock(
    IN p_blood_type ENUM('A','B','AB','O'),
    IN p_rhesus ENUM('+','-'),
    IN p_quantity INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    UPDATE blood_stock 
    SET quantity = quantity + p_quantity,
        updated_at = CURRENT_TIMESTAMP
    WHERE blood_type = p_blood_type AND rhesus = p_rhesus;
    
    IF ROW_COUNT() = 0 THEN
        INSERT INTO blood_stock (blood_type, rhesus, quantity, min_stock) 
        VALUES (p_blood_type, p_rhesus, p_quantity, 10);
    END IF;
    
    COMMIT;
END //
DELIMITER ;

-- ========================================
-- TRIGGERS
-- ========================================

-- Trigger untuk update jumlah donor setelah donasi approved
DELIMITER //
CREATE TRIGGER after_donation_approved
AFTER UPDATE ON donations
FOR EACH ROW
BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        UPDATE donors 
        SET donation_count = donation_count + 1,
            last_donation = NEW.donation_date
        WHERE id = NEW.donor_id;
        
        CALL UpdateBloodStock(NEW.blood_type, NEW.rhesus, NEW.quantity);
    END IF;
END //
DELIMITER ;

-- ========================================
-- FINAL NOTES
-- ========================================

-- Password default untuk admin dan petugas: password
-- Untuk menambah user baru, gunakan password_hash('password_baru', PASSWORD_DEFAULT) di PHP
-- Database ini sudah dioptimasi dengan index dan view untuk performa yang baik
-- Sistem mendukung role-based access control (admin, petugas, user)
-- Stok darah akan otomatis terupdate ketika ada donasi yang completed

-- ========================================
-- END OF FILE
-- ========================================
