# 🗃️ Database Setup Guide

## ❌ **Masalah yang Ditemukan**
```
Unknown database 'sistem_donor_darah'
```

Database belum dibuat atau diimport ke MySQL server.

## 🔧 **Solusi - Setup Database Manual**

### **Metode 1: Menggunakan phpMyAdmin (Recommended)**

1. **Buka phpMyAdmin**
   - Akses: `http://localhost/phpmyadmin`
   - Login dengan username: `root` (tanpa password)

2. **Import Database**
   - Klik tab "**Import**"
   - Klik "**Choose file**"
   - Pilih file: `D:\Programs\Xampp\htdocs\sistem-donor-darah\setup_manual.sql`
   - Klik "**Go**"

3. **Verifikasi**
   - Database `sistem_donor_darah` akan terbuat
   - Tabel-tabel akan terinstall otomatis
   - Data sample akan terisi

### **Metode 2: Menggunakan MySQL Command Line**

1. **Buka Command Prompt**
   - Tekan `Win + R`, ketik `cmd`

2. **Masuk ke MySQL**
   ```cmd
   cd "C:\xampp\mysql\bin"
   mysql -u root -p
   ```

3. **Jalankan Script**
   ```sql
   source D:\Programs\Xampp\htdocs\sistem-donor-darah\setup_manual.sql
   ```

4. **Verifikasi**
   ```sql
   USE sistem_donor_darah;
   SHOW TABLES;
   ```

### **Metode 3: Copy-Paste Manual**

1. **Buka phpMyAdmin**
2. **Klik tab "SQL"**
3. **Copy seluruh isi file `setup_manual.sql`**
4. **Paste ke SQL editor**
5. **Klik "Go"**

## ✅ **Verifikasi Setup**

Setelah setup selesai, pastikan:

### **1. Database Terbuat**
- Database: `sistem_donor_darah`
- Character set: `utf8mb4`
- Collation: `utf8mb4_general_ci`

### **2. Tabel Terinstall**
- ✅ `users` (pengguna sistem)
- ✅ `donors` (data pendonor)
- ✅ `blood_stock` (stok darah)
- ✅ `donations` (riwayat donor)
- ✅ `blood_requests` (permintaan darah)
- ✅ `events` (jadwal event)
- ✅ `notifications` (notifikasi)

### **3. Data Sample**
- ✅ **Admin user**: admin@donor.com / admin123
- ✅ **Petugas user**: petugas@donor.com / petugas123  
- ✅ **Regular user**: user@donor.com / user123
- ✅ **Blood stock**: 8 golongan darah
- ✅ **Sample event**: Event donor darah
- ✅ **Notifications**: Notifikasi sistem

## 🧪 **Testing Database**

Setelah setup, test koneksi:

1. **Akses login page**
   ```
   http://localhost/sistem-donor-darah/login.php
   ```

2. **Login sebagai admin**
   - Email: `admin@donor.com`
   - Password: `admin123`

3. **Verifikasi tidak ada error database**

## 📋 **Default Login Credentials**

| Role | Email | Password | Access |
|------|-------|----------|---------|
| **Admin** | admin@donor.com | admin123 | Full system access |
| **Petugas** | petugas@donor.com | petugas123 | PMI staff access |
| **User** | user@donor.com | user123 | Donor access |

## 🔧 **Troubleshooting**

### **Problem: "Access denied for user 'root'"**
**Solution**: 
- Pastikan MySQL service berjalan
- Cek username/password MySQL
- Restart XAMPP MySQL service

### **Problem: "Can't connect to MySQL server"**
**Solution**:
- Start MySQL service di XAMPP Control Panel
- Cek port 3306 tidak diblok
- Restart XAMPP

### **Problem: "Table doesn't exist"**
**Solution**:
- Pastikan database sudah diimport
- Cek nama tabel di phpMyAdmin
- Re-import setup_manual.sql

## 📊 **Database Schema Overview**

```
sistem_donor_darah/
├── users (pengguna sistem)
├── donors (data pendonor)
├── blood_stock (stok darah)
├── donations (riwayat donor)
├── blood_requests (permintaan darah)
├── events (jadwal event)
└── notifications (notifikasi)
```

## 🎯 **Next Steps**

Setelah database setup:

1. ✅ **Test login system**
2. ✅ **Verify admin access**
3. ✅ **Check blood stock data**
4. ✅ **Test user registration**
5. ✅ **Verify all features**

---

## 🆘 **Bantuan Lebih Lanjut**

Jika masih bermasalah:

1. **Cek MySQL service status**
2. **Verifikasi XAMPP installation**
3. **Check PHP mysqli extension**
4. **Review error logs**

**Contact**: hikma7091@gmail.com

---

**Database Setup Guide**  
**Sistem Donor Darah Kabupaten Mamuju Tengah**  
**Version 1.0 - 2025**
