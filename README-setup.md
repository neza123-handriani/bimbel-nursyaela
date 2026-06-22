Setup singkat untuk proyek `bimbel`

1) Import database

- Buka phpMyAdmin atau gunakan `mysql` CLI, lalu jalankan:

```sql
SOURCE sql/create_tables.sql;
```

atau buat database `bimbel_db` lalu import file `sql/create_tables.sql`.

2) Konfigurasi koneksi

- Edit file `config/database.php` sesuai kredensial MySQL lokal (user/password).
- Contoh penggunaan PDO tersedia di file `config/database.php`.

3) Jalankan di XAMPP

- Letakkan folder ini di `htdocs` (sudah di `c:/xampp/htdocs/bimbel`).
- Buka `http://localhost/bimbel/`.

4) Langkah lanjut (opsional)

- Buat halaman dasar: `index.php`, `auth/login.php`, `auth/register.php`.
- Implementasi webhook pembayaran di `payment/callback.php`.
- Buat seed data untuk `tutors`, `kelas` dan `users`.
5) Membuat admin cepat

- Ada skrip helper `scripts/create_admin.php` yang menambahkan user dengan role `admin` menggunakan `password_hash`.
- Jalankan dari CLI (direkomendasikan):

```bash
php scripts/create_admin.php admin@bimbel.local yourpassword
```

- Atau buka `http://localhost/bimbel/scripts/create_admin.php` dan gunakan form (hanya untuk development lokal).
