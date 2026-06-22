# Bimbel - Platform Pembelajaran Online

Sistem informasi bimbingan belajar online dengan integrasi pembayaran Midtrans dan Xendit.

## Setup Cepat

### 1. Persiapan Database

**Via MySQL CLI:**
```bash
mysql -u root -p < sql/create_tables.sql
mysql -u root -p < sql/seed_data.sql
```

**Via phpMyAdmin:**
- Buka `http://localhost/phpmyadmin`
- Import file `sql/create_tables.sql`
- Import file `sql/seed_data.sql`

### 2. Konfigurasi Database

Edit `config/database.php` sesuaikan dengan kredensial MySQL:
```php
return [
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'bimbel_db',
    'username' => 'root',        // Ubah sesuai username MySQL
    'password' => '',             // Ubah sesuai password MySQL
    'charset' => 'utf8mb4'
];
```

### 3. Jalankan di XAMPP

- Letakkan folder di `C:\xampp\htdocs\bimbel`
- Buka `http://localhost/bimbel/`

## Akun Default

- **Admin:**
  - Email: `admin@bimbel.local`
  - Password: `admin123`
  - URL: `http://localhost/bimbel/admin/dashboard.php`

- **Demo User (Register dulu atau gunakan form login):**
  - Email: `user@bimbel.local`
  - Password: `user123`

## Struktur Halaman

### Public Pages
- `/` - Landing page
- `/kelas.php` - Daftar kelas
- `/kelas-detail.php?id=X` - Detail kelas
- `/tentang.php` - Tentang kami
- `/auth/login.php` - Login
- `/auth/register.php` - Register
- `/auth/forgot-password.php` - Lupa password

### User Pages (Memerlukan login sebagai user)
- `/user/dashboard.php` - Dashboard user
- `/user/kelas-saya.php` - Kelas saya
- `/user/kelas-detail.php?id=X` - Detail kelas user
- `/checkout.php?kelas_id=X` - Checkout kelas
- `/payment-confirm.php?invoice=X` - Konfirmasi pembayaran

### Admin Pages (Memerlukan login sebagai admin)
- `/admin/dashboard.php` - Dashboard admin
- `/admin/users.php` - Manajemen user
- `/admin/tutors.php` - Manajemen tutor
- `/admin/kelas.php` - Manajemen kelas
- `/admin/materi.php` - Manajemen materi
- `/admin/transactions.php` - Manajemen transaksi

## Fitur Utama

✓ **Authentication**
- Login, Register, Logout
- Forgot Password
- Role-based Access (Admin, Tutor, User/Siswa)

✓ **Admin Dashboard**
- Manajemen user, tutor, kelas, materi
- Monitoring transaksi
- Statistik dashboard

✓ **User Dashboard**
- Daftar dan akses kelas
- Lihat jadwal dan materi
- Riwayat transaksi

✓ **Sistem Pembayaran**
- Integrasi gateway pembayaran
- Generate invoice
- Tracking status pembayaran

✓ **Manajemen Kelas**
- CRUD kelas
- Jadwal kelas
- Materi pembelajaran

## Teknologi Stack

- **Backend:** PHP Native (PDO)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Payment Gateway:** Midtrans, Xendit
- **Design System:** Custom Design Tokens

## File Penting

```
bimbel/
├── config/
│   └── database.php          # Konfigurasi database
├── inc/
│   ├── db.php               # Koneksi PDO
│   └── functions.php        # Helper functions
├── assets/
│   └── css/
│       ├── design-tokens.css # Design system
│       └── styles.css       # Main styles
├── auth/
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   └── forgot-password.php
├── admin/
│   ├── dashboard.php
│   ├── users.php
│   ├── tutors.php
│   ├── kelas.php
│   ├── materi.php
│   └── transactions.php
├── user/
│   ├── dashboard.php
│   ├── kelas-saya.php
│   └── kelas-detail.php
├── sql/
│   ├── create_tables.sql    # Schema database
│   └── seed_data.sql        # Data seed
├── index.php                 # Landing page
├── kelas.php                 # Daftar kelas
├── kelas-detail.php          # Detail kelas
├── tentang.php               # Tentang kami
├── checkout.php              # Checkout kelas
└── payment-confirm.php       # Konfirmasi pembayaran
```

## Development Notes

- Semua password di-hash menggunakan `password_hash()` dengan algoritma bcrypt
- File ini menggunakan PDO untuk query database yang aman dari SQL injection
- Session management untuk authentication
- Input sanitization dengan `htmlspecialchars()`

## Testing

1. **Akses Home:**
   - Buka `http://localhost/bimbel/`

2. **Daftar User Baru:**
   - Klik "Daftar" → isi form → login

3. **Admin Login:**
   - Gunakan email `admin@bimbel.local` password `admin123`
   - Kelola users, tutors, kelas, transaksi dari dashboard

4. **User Flow:**
   - Login → lihat daftar kelas → checkout → pembayaran → akses kelas

## Support

Untuk pertanyaan atau masalah, hubungi tim development.

---
**Version:** 1.0  
**Last Updated:** June 2026

