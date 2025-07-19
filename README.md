# Sistem Donor Darah - Kabupaten Mamuju Tengah

Sistem Manajemen Donor Darah yang dikembangkan untuk memfasilitasi kegiatan donor darah di Kabupaten Mamuju Tengah, termasuk pengelolaan pendonor, stok darah, permintaan darah, dan jadwal donor.

## 🩸 Tentang Proyek

Sistem Donor Darah Kabupaten Mamuju Tengah adalah aplikasi web berbasis PHP yang dirancang untuk memudahkan pengelolaan aktivitas donor darah. Sistem ini menyediakan platform lengkap untuk admin, petugas PMI, dan masyarakat umum dalam mengelola donor darah, stok darah, dan permintaan darah secara terpadu.

### 👩‍🎓 Informasi Pengembang

- **Nama**: Nur Hikma
- **NIM**: 202312074
- **Email**: hikma7091@gmail.com
- **Mata Kuliah**: Pemrograman Web
- **Tugas**: Ujian Akhir Semester

## ✨ Fitur Utama

### 👤 Untuk Pengguna (User)

- **Registrasi & Login**: Sistem autentikasi pengguna
- **Daftar Pendonor**: Mendaftarkan diri sebagai pendonor darah
- **Lihat Jadwal Donor**: Melihat jadwal kegiatan donor darah
- **Permintaan Darah**: Mengajukan permintaan darah untuk kebutuhan medis
- **Cek Stok Darah**: Melihat ketersediaan stok darah real-time
- **Riwayat Donasi**: Melihat history donasi dan status kesehatan
- **Profil**: Manage profil dan update informasi kesehatan

### 🛠️ Untuk Administrator & Petugas PMI

- **Dashboard Admin**: Overview sistem dan statistik donor darah
- **Manajemen User**: Kelola akun pengguna dan pendonor
- **Manajemen Stok Darah**: CRUD data stok darah semua golongan
- **Manajemen Pendonor**: Kelola data pendonor dan riwayat donasi
- **Manajemen Jadwal**: Buat dan kelola jadwal kegiatan donor
- **Manajemen Permintaan**: Monitor permintaan darah dari rumah sakit
- **Laporan**: Generate berbagai laporan donor dan stok darah
- **Notifikasi**: Kelola notifikasi dan pengumuman sistem

## 🏗️ Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3
- **Icons**: Font Awesome 6.5
- **Server**: Apache (XAMPP)
- **Authentication**: Session-based dengan password hashing

## 📁 Struktur Proyek (Diperbarui)

```
sistem-donor-darah/
├── admin/                     # Panel administrasi
│   ├── index.php             # Dashboard admin
│   ├── welcome.php           # Halaman utama admin
│   ├── jadwal-*.php          # Manajemen jadwal
│   ├── stok-*.php            # Manajemen stok darah
│   ├── users.php             # Manajemen pengguna
│   ├── laporan.php           # Laporan sistem
│   └── auth_check.php        # Autentikasi admin
├── user/                     # Panel pengguna
│   ├── dashboard.php         # Dashboard pengguna
│   ├── daftar.php            # Daftar pendonor
│   ├── permintaan.php        # Permintaan darah
│   ├── stok-darah.php        # Cek stok darah
│   └── jadwal-donor.php      # Jadwal donor
├── config/                   # Konfigurasi aplikasi
│   ├── config.php            # Konfigurasi utama
│   └── database.php          # Konfigurasi database
├── includes/                 # Helper functions
│   ├── functions.php         # Fungsi-fungsi helper
│   └── session.php           # Session management
├── public/                   # File yang dapat diakses publik
│   └── assets/              # Asset statis
│       ├── donordarah.png   # Logo sistem
│       ├── logo-mateng.png  # Logo Mamuju Tengah
│       └── style.css        # Stylesheet
├── logs/                     # Log files
│   ├── system.log           # Log sistem
│   ├── error.log            # Log error
│   └── access.log           # Log akses
├── database/                 # Database
│   └── sistem_donor_darah.sql # Database schema lengkap
├── docs/                     # Dokumentasi
│   ├── INSTALLATION.md      # Panduan instalasi
│   ├── DATABASE.md          # Dokumentasi database
│   ├── USAGE.md             # Panduan penggunaan
│   ├── DEPLOYMENT.md        # Panduan deployment
│   └── IMPLEMENTATION_SUMMARY.md # Ringkasan implementasi
├── index.php                 # Halaman utama
├── login.php                 # Halaman login
├── logout.php                # Proses logout
├── register.php              # Halaman registrasi
├── forgot-password.php       # Reset password
├── .gitignore               # Git ignore file
└── README.md                # Dokumentasi utama
```

## 🚀 Quick Start

1. **Clone/Download** proyek ini
2. **Setup** XAMPP dan jalankan Apache + MySQL
3. **Import** database dari `database/sistem_donor_darah.sql`
4. **Konfigurasi** database di `koneksi.php` (jika diperlukan)
5. **Akses** aplikasi melalui `http://localhost/sistem-donor-darah`

## 📚 Dokumentasi Lengkap

  # Instalasi Sistem Donor Darah

Proses instalasi untuk Sistem Donor Darah Kabupaten Mamuju Tengah:

## Persyaratan Sistem

- **PHP**: 7.4 atau lebih tinggi
- **MySQL**: 5.7 atau lebih tinggi
- **Apache**: 2.4+
- **XAMPP**: Disarankan untuk kemudahan setup

## Langkah-Langkah Instalasi

1. **Kloning Repository**

   Clone atau download proyek ini ke direktori server lokal Anda.

   ```bash
   git clone https://github.com/username/UAS_PWEB.git
   ```

2. **Instalasi XAMPP**
   
   Unduh dan instal XAMPP dari situs resmi. Jalankan Apache dan MySQL dari XAMPP Control Panel.

3. **Setup Database**

   - Buka `phpMyAdmin` melalui `http://localhost/phpmyadmin`
   - Buat database baru dengan nama `sistem_donor_darah`
   - Import file SQL yang terletak di `database/sistem_donor_darah.sql`
     
4. **Konfigurasi Koneksi Database**

   Buka `config/database.php` dan pastikan detail koneksi sesuai dengan setup Anda:

   ```php
   define('DB_HOST', 'localhost');  // Host database Anda
   define('DB_USER', 'root');       // Username database
   define('DB_PASS', '');           // Password database
   define('DB_NAME', 'sistem_donor_darah'); // Nama database
   ```

5. **Set Permission** (Opsional)

   Pastikan direktori proyek dapat diakses oleh web server (Apache) dengan cukup permission.

6. **Menjalankan Aplikasi**

   Akses aplikasi melalui browser:

   ```
   http://localhost/sistem-donor-darah
   ```

## Troubleshooting

- **Fatal error: Class 'mysqli' not found**: Pastikan ext-mysqli diaktifkan di `php.ini`.
- **403 Forbidden Error**: Periksa permission folder atau konfigurasi Apache.
- **500 Internal Server Error**: Aktifkan `display_errors` di `php.ini` untuk debugging.

---

Proyek siap digunakan! Jika ada pertanyaan silakan cek dokumentasi penggunaan atau hubungi pengembang.

---
  # Dokumentasi Database - Sistem Donor Darah Kabupaten Mamuju Tengah

## 🗄️ Overview Database

Database `sistem_donor_darah` didesain khusus untuk mengelola seluruh aspek donor darah mulai dari registrasi pendonor, manajemen stok darah, permintaan darah, hingga laporan aktivitas.

### 📊 Struktur Database

Database terdiri dari **8 tabel utama** yang saling berelasi:

1. **users** - Pengguna sistem (admin, petugas, user)
2. **donors** - Data pendonor darah
3. **blood_stock** - Stok darah berdasarkan golongan
4. **blood_requests** - Permintaan darah dari rumah sakit
5. **donations** - Riwayat aktivitas donor
6. **events** - Jadwal kegiatan donor darah
7. **event_registrations** - Pendaftaran peserta event
8. **notifications** - Notifikasi dan pengumuman sistem

---

## 🔍 Detail Tabel

### 1. Tabel `users`
**Deskripsi**: Menyimpan data pengguna sistem dengan role-based access

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik pengguna |
| username | varchar(50) | NO | UNI | | Nama pengguna |
| password | varchar(255) | NO | | | Password ter-hash |
| email | varchar(100) | NO | UNI | | Email pengguna |
| full_name | varchar(100) | NO | | | Nama lengkap |
| phone | varchar(20) | YES | | NULL | Nomor telepon |
| role | enum('admin','petugas','user') | NO | | 'user' | Role pengguna |
| is_active | tinyint(1) | NO | | 1 | Status aktif |
| created_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu diupdate |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`username`)
- UNIQUE KEY (`email`)

---

### 2. Tabel `donors`
**Deskripsi**: Data lengkap pendonor darah

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik pendonor |
| name | varchar(100) | NO | | | Nama lengkap |
| email | varchar(100) | NO | | | Email pendonor |
| phone | varchar(20) | NO | | | Nomor telepon |
| address | text | NO | | | Alamat lengkap |
| birth_date | date | NO | | | Tanggal lahir |
| gender | enum('L','P') | NO | | | Jenis kelamin |
| blood_type | enum('A','B','AB','O') | NO | | | Golongan darah |
| rhesus | enum('+','-') | NO | | | Rhesus |
| weight | decimal(5,2) | NO | | | Berat badan (kg) |
| height | decimal(5,2) | NO | | | Tinggi badan (cm) |
| occupation | varchar(100) | YES | | NULL | Pekerjaan |
| emergency_contact | varchar(100) | YES | | NULL | Kontak darurat |
| medical_history | text | YES | | NULL | Riwayat kesehatan |
| donation_count | int(11) | NO | | 0 | Jumlah donasi |
| last_donation | date | YES | | NULL | Tanggal donor terakhir |
| is_active | tinyint(1) | NO | | 1 | Status aktif |
| created_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu diupdate |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`blood_type`, `rhesus`)
- INDEX (`email`)
- INDEX (`last_donation`)

---

### 3. Tabel `blood_stock`
**Deskripsi**: Stok darah per golongan darah

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik stok |
| blood_type | enum('A','B','AB','O') | NO | | | Golongan darah |
| rhesus | enum('+','-') | NO | | | Rhesus |
| quantity | int(11) | NO | | 0 | Jumlah stok (ml) |
| reserved_quantity | int(11) | NO | | 0 | Stok yang direservasi |
| min_stock | int(11) | NO | | 10 | Minimum stok |
| expires_at | date | YES | | NULL | Tanggal kadaluarsa |
| last_updated | timestamp | NO | | CURRENT_TIMESTAMP | Terakhir diupdate |
| updated_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu diupdate |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`blood_type`, `rhesus`)
- INDEX (`quantity`)

---

### 4. Tabel `blood_requests`
**Deskripsi**: Permintaan darah dari rumah sakit

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik permintaan |
| patient_name | varchar(100) | NO | | | Nama pasien |
| hospital | varchar(100) | NO | | | Nama rumah sakit |
| blood_type | enum('A','B','AB','O') | NO | | | Golongan darah |
| rhesus | enum('+','-') | NO | | | Rhesus |
| quantity_needed | int(11) | NO | | | Jumlah yang dibutuhkan |
| urgency | enum('normal','urgent','emergency') | NO | | 'normal' | Tingkat urgensi |
| request_date | date | NO | | | Tanggal permintaan |
| needed_date | date | NO | | | Tanggal dibutuhkan |
| contact_person | varchar(100) | NO | | | Penanggung jawab |
| contact_phone | varchar(20) | NO | | | Nomor telepon kontak |
| notes | text | YES | | NULL | Catatan tambahan |
| status | enum('pending','approved','rejected','completed') | NO | | 'pending' | Status permintaan |
| processed_by | int(11) | YES | | NULL | Diproses oleh |
| processed_at | timestamp | YES | | NULL | Waktu diproses |
| created_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu dibuat |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`blood_type`, `rhesus`)
- INDEX (`status`)
- INDEX (`urgency`)
- FOREIGN KEY (`processed_by`) REFERENCES `users(id)`

---

### 5. Tabel `donations`
**Deskripsi**: Riwayat aktivitas donor darah

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik donasi |
| donor_id | int(11) | NO | MUL | | ID pendonor |
| donation_date | date | NO | | | Tanggal donor |
| blood_type | enum('A','B','AB','O') | NO | | | Golongan darah |
| rhesus | enum('+','-') | NO | | | Rhesus |
| quantity | int(11) | NO | | 350 | Jumlah darah (ml) |
| hemoglobin | decimal(4,2) | YES | | NULL | Kadar hemoglobin |
| blood_pressure | varchar(20) | YES | | NULL | Tekanan darah |
| weight | decimal(5,2) | YES | | NULL | Berat badan saat donor |
| temperature | decimal(4,2) | YES | | NULL | Suhu tubuh |
| health_status | text | YES | | NULL | Status kesehatan |
| notes | text | YES | | NULL | Catatan |
| status | enum('pending','approved','rejected','completed') | NO | | 'pending' | Status donasi |
| processed_by | int(11) | YES | | NULL | Diproses oleh |
| created_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu diupdate |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`donor_id`)
- INDEX (`donation_date`)
- INDEX (`status`)
- FOREIGN KEY (`donor_id`) REFERENCES `donors(id)`
- FOREIGN KEY (`processed_by`) REFERENCES `users(id)`

---

### 6. Tabel `events`
**Deskripsi**: Jadwal kegiatan donor darah

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik event |
| title | varchar(200) | NO | | | Judul event |
| description | text | YES | | NULL | Deskripsi event |
| event_date | date | NO | | | Tanggal event |
| start_time | time | NO | | | Waktu mulai |
| end_time | time | NO | | | Waktu selesai |
| location | varchar(200) | NO | | | Lokasi event |
| target_donors | int(11) | NO | | 50 | Target pendonor |
| registered_count | int(11) | NO | | 0 | Jumlah terdaftar |
| organizer | varchar(100) | NO | | | Penyelenggara |
| contact_info | varchar(100) | NO | | | Kontak penyelenggara |
| status | enum('planned','active','completed','cancelled') | NO | | 'planned' | Status event |
| created_by | int(11) | YES | | NULL | Dibuat oleh |
| created_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu diupdate |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`event_date`)
- INDEX (`status`)
- FOREIGN KEY (`created_by`) REFERENCES `users(id)`

---

### 7. Tabel `event_registrations`
**Deskripsi**: Pendaftaran peserta event donor darah

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik registrasi |
| event_id | int(11) | NO | MUL | | ID event |
| donor_id | int(11) | NO | MUL | | ID pendonor |
| registration_date | timestamp | NO | | CURRENT_TIMESTAMP | Tanggal daftar |
| status | enum('registered','confirmed','attended','cancelled') | NO | | 'registered' | Status registrasi |
| notes | text | YES | | NULL | Catatan |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`event_id`, `donor_id`)
- FOREIGN KEY (`event_id`) REFERENCES `events(id)`
- FOREIGN KEY (`donor_id`) REFERENCES `donors(id)`

---

### 8. Tabel `notifications`
**Deskripsi**: Notifikasi dan pengumuman sistem

| Field | Type | Null | Key | Default | Description |
|-------|------|------|-----|---------|-------------|
| id | int(11) | NO | PRI | AUTO_INCREMENT | ID unik notifikasi |
| title | varchar(255) | NO | | | Judul notifikasi |
| message | text | NO | | | Pesan notifikasi |
| type | enum('info','warning','success','danger') | NO | | 'info' | Jenis notifikasi |
| is_active | tinyint(1) | NO | | 1 | Status aktif |
| created_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | timestamp | NO | | CURRENT_TIMESTAMP | Waktu diupdate |
| expires_at | timestamp | YES | | NULL | Waktu kadaluarsa |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`is_active`)
- INDEX (`expires_at`)

---

## 🔗 Relasi Database

### Primary Relationships:
1. **users** → **donations** (1:N) - Petugas memproses donasi
2. **users** → **events** (1:N) - Admin membuat event
3. **users** → **blood_requests** (1:N) - Petugas memproses permintaan
4. **donors** → **donations** (1:N) - Pendonor melakukan donasi
5. **donors** → **event_registrations** (1:N) - Pendonor daftar event
6. **events** → **event_registrations** (1:N) - Event memiliki peserta
7. **blood_stock** → **blood_requests** (1:N) - Stok untuk permintaan

### Business Rules:
- Pendonor harus menunggu minimal 90 hari sebelum donor lagi
- Stok darah otomatis bertambah setelah donasi disetujui
- Permintaan darah mengurangi stok yang tersedia
- Event dapat dibatalkan jika pendaftar kurang dari minimum

---
# Entity Relationship Diagram (ERD)  
**Sistem Informasi Donor Darah Kabupaten Mamuju Tengah**

## 📌 Tujuan ERD
Entity Relationship Diagram (ERD) digunakan untuk menggambarkan struktur data dan hubungan antar entitas dalam sistem donor darah. Diagram ini menjadi dasar dalam merancang database relasional yang efisien dan terstruktur.

## 📊 Deskripsi Entitas Utama

Berikut ini adalah entitas yang terlibat dalam sistem:

1. **Users**
   - Menyimpan informasi akun pengguna dan admin.
   - Atribut: `id`, `name`, `email`, `password`, `role_id`, `created_at`.

2. **Roles**
   - Menyimpan peran (user/admin).
   - Atribut: `id`, `role_name`.

3. **Donors**
   - Data pendonor yang telah mendaftar.
   - Atribut: `id`, `user_id`, `full_name`, `gender`, `birth_date`, `blood_type_id`, `location_id`, `phone`, `status`, `created_at`.

4. **Requests**
   - Permintaan darah oleh rumah sakit atau individu.
   - Atribut: `id`, `requested_by`, `blood_type_id`, `location_id`, `quantity`, `status_id`, `created_at`.

5. **Matches**
   - Mencatat pencocokan antara pendonor dan permintaan.
   - Atribut: `id`, `donor_id`, `request_id`, `matched_at`.

6. **Blood_Types**
   - Menyimpan data golongan darah (A, B, AB, O).
   - Atribut: `id`, `type`.

7. **Locations**
   - Data lokasi/kecamatan/kabupaten pengguna dan pendonor.
   - Atribut: `id`, `location_name`.

8. **Statuses**
   - Status dari permintaan darah.
   - Atribut: `id`, `status_name`.

9. **Logs**
   - Catatan aktivitas penting dalam sistem.
   - Atribut: `id`, `user_id`, `activity`, `timestamp`.

10. **Settings**
    - Konfigurasi sistem.
    - Atribut: `id`, `setting_name`, `setting_value`.

## 🔗 Relasi Utama
- `Users` → `Roles`: many-to-one.
- `Donors` → `Users`: many-to-one.
- `Donors` → `Blood_Types`: many-to-one.
- `Donors` → `Locations`: many-to-one.
- `Requests` → `Blood_Types`, `Locations`, `Statuses`: many-to-one.
- `Matches` → `Donors`, `Requests`: many-to-one.
- `Logs` → `Users`: many-to-one.

## 🖼️ Visual ERD
Silakan lihat diagram ERD pada gambar berikut:  
<img width="984" height="1496" alt="image" src="https://github.com/user-attachments/assets/7ae93fa1-c5f3-499c-b3ff-c5deb5ee0699" />

*(Gambar ini merupakan hasil desain ERD sistem secara keseluruhan)*

## ✅ Kesimpulan
ERD ini menjadi kerangka kerja penting dalam pengembangan sistem informasi donor darah. Struktur yang baik akan memudahkan implementasi fitur serta menjaga integritas data dalam sistem.

# 📋 Fitur CRUD Admin – Sistem Informasi Donor Darah

## 🔐 Fitur CRUD Admin

Admin memiliki akses penuh untuk melakukan operasi **Create, Read, Update, Delete (CRUD)** terhadap data berikut:

- 👥 **Data Pengguna (users)**
- 💉 **Data Pendonor (donors)**
- 🩸 **Data Permintaan Darah (requests)**
- 🗺️ **Data Lokasi (locations)**
- 🧬 **Data Golongan Darah (blood_types)**
- 🔄 **Status Permintaan (statuses)**
- 🤝 **Pencocokan Donor (matches)**
- 📜 **Log Aktivitas (logs)**
- ⚙️ **Pengaturan Sistem (settings)**

---

## 📃 Fitur Tampilan Setiap Modul

Setiap entitas di atas memiliki antarmuka pengelolaan data yang terdiri dari:

- ✅ **Tambah Data Baru**  
  Formulir input untuk menambahkan entitas baru ke dalam sistem.

- ✏️ **Edit Data**  
  Halaman untuk memperbarui data yang sudah ada.

- ❌ **Hapus Data**  
  Fitur untuk menghapus data yang tidak diperlukan lagi.

- 📑 **Daftar Data (Tabel)**  
  Tabel data yang rapi, responsif, dan interaktif, dengan dukungan:
  - **Pencarian Data**
  - **Pagination**
  - **Desain Bootstrap untuk tampilan modern dan profesional**

---

> Semua modul CRUD ini dirancang agar mudah digunakan oleh admin dan mendukung pengembangan sistem donor darah yang efisien dan terstruktur.


## 📊 Views dan Stored Procedures

### View: `view_blood_statistics`
```sql
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
```

### View: `view_active_donors`
```sql
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
```

### Stored Procedure: `UpdateBloodStock`
```sql
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
```

---

## 🛠️ Maintenance

### Optimasi Database:
```sql
-- Optimasi tabel bulanan
OPTIMIZE TABLE users, donors, blood_stock, donations, 
                blood_requests, events, event_registrations, notifications;

-- Tambahan index untuk performa
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_donors_name ON donors(name);
CREATE INDEX idx_blood_requests_hospital ON blood_requests(hospital);
CREATE INDEX idx_events_location ON events(location);
```

### Cleanup Data:
```sql
-- Hapus notifikasi yang sudah expired
DELETE FROM notifications 
WHERE expires_at IS NOT NULL AND expires_at < NOW();

-- Hapus event lama yang sudah completed
DELETE FROM events 
WHERE status = 'completed' AND event_date < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

---

## 🔐 Security

### User Management:
- Password di-hash menggunakan bcrypt
- Role-based access control (admin, petugas, user)
- Session management dengan timeout

### Data Protection:
- Prepared statements untuk mencegah SQL injection
- Input validation pada semua form
- Sanitasi output untuk mencegah XSS

### Backup Strategy:
- Daily backup database
- Weekly backup files
- Monthly archive ke external storage

---
*Database Documentation - Version 1.0*

- 📝 [Panduan Penggunaan](docs/USAGE.md)
- 🚀 [Panduan Deployment](docs/DEPLOYMENT.md)

## 👥 Default Akun

### Administrator

- **Email**: `admin@donor.com`
- **Password**: `password`

### User Demo

- **Email**: `user@donor.com`
- **Password**: `password`

## 🎯 Fitur Utama Sistem

### 🩸 Manajemen Stok Darah

- **8 Golongan Darah**: A+, A-, B+, B-, AB+, AB-, O+, O-
- **Real-time Stock**: Update stok secara real-time
- **Minimum Stock Alert**: Notifikasi stok menipis
- **Stock History**: Riwayat perubahan stok

### 👥 Manajemen Pendonor

- **Profil Lengkap**: Data lengkap pendonor
- **Riwayat Donasi**: History donasi pendonor
- **Eligibility Check**: Cek kelayakan donor
- **Medical History**: Riwayat kesehatan

### 📅 Manajemen Jadwal

- **Event Donor**: Jadwal kegiatan donor darah
- **Lokasi**: Informasi lokasi donor
- **Capacity**: Kapasitas peserta
- **Status**: Status kegiatan (planned, active, completed)

### 🏥 Permintaan Darah

- **Urgency Level**: Normal, urgent, emergency
- **Hospital Info**: Informasi rumah sakit
- **Patient Data**: Data pasien
- **Approval System**: Sistem persetujuan

## 🔧 Persyaratan Sistem

- **PHP**: 7.4 atau lebih tinggi
- **MySQL**: 8.0 atau lebih tinggi
- **Apache**: 2.4+
- **Browser**: Chrome, Firefox, Safari, Edge (modern browsers)
- **Memory**: Minimal 512MB RAM
- **Storage**: Minimal 200MB disk space

## 🛡️ Keamanan

- **Password Hashing**: bcrypt untuk keamanan password
- **Session Management**: Secure session handling
- **SQL Injection Protection**: Prepared statements
- **XSS Prevention**: Input sanitization
- **Role-based Access**: Control akses berdasarkan role

## 📊 Database

### Tabel Utama:

1. **users** - Data pengguna sistem
2. **donors** - Data pendonor
3. **blood_stock** - Stok darah
4. **blood_requests** - Permintaan darah
5. **donations** - Riwayat donasi
6. **events** - Jadwal kegiatan donor
7. **notifications** - Notifikasi sistem

### Fitur Database:

- **Views**: Query optimization
- **Indexes**: Performance optimization
- **Stored Procedures**: Business logic
- **Triggers**: Auto-update functionality

## 📱 Responsive Design

Sistem ini fully responsive dan dapat diakses melalui:

- **Desktop**: Full-featured interface
- **Tablet**: Optimized layout
- **Mobile**: Touch-friendly interface

## 🚀 Deployment

Sistem dapat di-deploy ke:

- **Shared Hosting**: cPanel compatible
- **VPS**: Ubuntu/CentOS
- **Cloud**: AWS, Google Cloud, Azure
- **Local Server**: XAMPP, WAMP, LAMP

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademis (UAS Pemrograman Web).

## 👩‍💻 Pengembang

**Nur Hikma**

- **NIM**: 202312074
- **Email**: hikma7091@gmail.com
- **Tugas**: Ujian Akhir Semester - Pemrograman Web

---

## 🌟 Highlights

✅ **Modern UI/UX**: Interface yang intuitif dan user-friendly  
✅ **Real-time Data**: Stok darah dan status real-time  
✅ **Responsive**: Dapat diakses dari berbagai device  
✅ **Secure**: Implementasi keamanan yang baik  
✅ **Scalable**: Arsitektur yang dapat dikembangkan  
✅ **Well-documented**: Dokumentasi lengkap

---

**⚡ Quick Links:**

- [🚀 Instalasi](docs/INSTALLATION.md)
- [📊 Database](docs/DATABASE.md)
- [📖 Penggunaan](docs/USAGE.md)
- [🚀 Deployment](docs/DEPLOYMENT.md)

**© 2024 Nur Hikma - Sistem Donor Darah Kabupaten Mamuju Tengah 🩸**
