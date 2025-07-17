# Setup Email untuk Production

## 🚫 Masalah Development Mode

Ketika menggunakan XAMPP di localhost, PHP tidak dapat mengirim email karena tidak ada mail server yang dikonfigurasi. Error yang muncul:

```
Warning: mail(): Failed to connect to mailserver at "localhost" port 25
```

## 🔧 Solusi Development Mode

Sistem telah dikonfigurasi untuk mengatasi masalah ini:

### 1. **Auto-Detection Development Mode**
- Sistem otomatis mendeteksi jika berjalan di localhost
- Jika di localhost, email disimpan ke file log
- Link reset password ditampilkan langsung di halaman

### 2. **File Log Email**
- Email disimpan di `email_log.txt`
- Dapat diakses melalui `view_email_log.php`
- Berisi timestamp, email tujuan, dan link reset

### 3. **Link Reset Langsung**
- Setelah request reset password, link ditampilkan langsung
- Tidak perlu cek email saat development
- Link berfungsi normal untuk reset password

## 📧 Setup Email Production

### Opsi 1: PHP Mail dengan SMTP

Edit `php.ini` di server production:

```ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = noreply@yourdomain.com
```

### Opsi 2: PHPMailer dengan Gmail

Install PHPMailer:

```bash
composer require phpmailer/phpmailer
```

Update `mail_config.php`:

```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function sendResetPasswordEmail($email, $token, $full_name) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';
        $mail->Password   = 'your-app-password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('noreply@yourdomain.com', 'Sistem Donor Darah');
        $mail->addAddress($email, $full_name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Password - Sistem Donor Darah';
        $mail->Body    = $message; // HTML content
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
```

### Opsi 3: Menggunakan Mail Service

**Mailgun:**
```php
// Install: composer require mailgun/mailgun-php
$mailgun = Mailgun::create('key-your-mailgun-key');
$domain = "your-domain.com";

$mailgun->messages()->send($domain, [
    'from'    => 'noreply@your-domain.com',
    'to'      => $email,
    'subject' => 'Reset Password',
    'html'    => $message
]);
```

**SendGrid:**
```php
// Install: composer require sendgrid/sendgrid
$email = new \SendGrid\Mail\Mail();
$email->setFrom("noreply@yourdomain.com", "Sistem Donor Darah");
$email->setSubject("Reset Password");
$email->addTo($to_email, $full_name);
$email->addContent("text/html", $message);

$sendgrid = new \SendGrid('your-sendgrid-api-key');
$response = $sendgrid->send($email);
```

## 🛠️ Konfigurasi Server

### Apache/Nginx

Pastikan PHP dapat mengakses port SMTP:

```apache
# .htaccess
php_value sendmail_path "/usr/sbin/sendmail -t -i"
```

### Firewall

Buka port untuk SMTP:

```bash
# Port 25 (SMTP)
sudo ufw allow 25

# Port 587 (SMTP dengan TLS)
sudo ufw allow 587

# Port 465 (SMTPS)
sudo ufw allow 465
```

### DNS Records

Tambahkan SPF record untuk menghindari spam:

```dns
TXT record: v=spf1 include:_spf.google.com ~all
```

## 🔄 Cara Mengubah Mode

### Development ke Production

1. **Update `isDevelopmentMode()` di mail_config.php:**

```php
function isDevelopmentMode() {
    // Return false untuk production
    return false;
    
    // Atau gunakan environment variable
    return $_ENV['APP_ENV'] === 'development';
}
```

2. **Setup SMTP credentials**
3. **Test email functionality**

### Environment Variables

Buat file `.env`:

```env
APP_ENV=production
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Sistem Donor Darah"
```

## 🧪 Testing Email

### Development Test

1. Akses `forgot-password.php`
2. Masukkan email terdaftar
3. Cek link yang ditampilkan
4. Akses `view_email_log.php` untuk melihat log

### Production Test

1. Setup SMTP credentials
2. Test dengan email sungguhan
3. Cek spam folder
4. Verifikasi link dalam email

## 🔐 Keamanan Email

### Best Practices

1. **SSL/TLS Encryption**
2. **App Passwords** (bukan password biasa)
3. **Rate Limiting** untuk reset password
4. **Domain Verification**
5. **SPF/DKIM Records**

### Rate Limiting

Tambahkan di `forgot-password.php`:

```php
// Check rate limit (max 3 requests per hour)
$rate_limit_query = "SELECT COUNT(*) as count FROM password_resets 
                    WHERE email = '$email' AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
$rate_result = mysqli_query($conn, $rate_limit_query);
$rate_data = mysqli_fetch_assoc($rate_result);

if ($rate_data['count'] >= 3) {
    $error = "Terlalu banyak permintaan reset password. Coba lagi dalam 1 jam.";
    return;
}
```

## 📱 Email Template

### Responsive HTML

Template email sudah responsive dengan:
- HTML5 structure
- Inline CSS untuk kompatibilitas
- Mobile-friendly layout
- Branded design

### Plain Text Alternative

Untuk compatibility yang lebih baik:

```php
$mail->AltBody = "Reset password link: " . $reset_link;
```

## 🚀 Deployment Checklist

- [ ] SMTP server dikonfigurasi
- [ ] Environment variables diset
- [ ] DNS records ditambahkan
- [ ] Firewall ports dibuka
- [ ] Rate limiting diaktifkan
- [ ] Email templates ditest
- [ ] SSL certificates valid
- [ ] Domain verified di email service

---

## 📝 Catatan Penting

1. **Development Mode**: Email log disimpan di file, link ditampilkan langsung
2. **Production Mode**: Email dikirim melalui SMTP/email service
3. **Keamanan**: Gunakan app passwords dan encryption
4. **Testing**: Selalu test email functionality sebelum deploy
5. **Monitoring**: Monitor email delivery dan bounce rates

© 2024 Sistem Donor Darah Mamuju Tengah
