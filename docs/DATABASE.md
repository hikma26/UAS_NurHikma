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

**© 2024 Sistem Donor Darah Kabupaten Mamuju Tengah**
*Database Documentation - Version 1.0*
