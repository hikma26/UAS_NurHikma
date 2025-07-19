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

- 📖 [Panduan Instalasi](docs/INSTALLATION.md)
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

- 🗄️ [Dokumentasi Database](docs/DATABASE.md)
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
