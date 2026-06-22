-- Seed data untuk Bimbel
USE bimbel_db;

-- Insert admin user (password: admin123)
INSERT INTO users (nama, email, password, role, created_at) VALUES
('Admin Bimbel', 'admin@bimbel.local', '$2y$10$M.J3QqLsq9hIASwBXJZKL.XF9Ks9qZwqzVkKm7qQ4kVtRnH8q4R2m', 'admin', NOW());

-- Insert tutors
INSERT INTO tutors (nama, email, spesialisasi) VALUES
('Budi Santoso', 'budi@tutor.local', 'Matematika'),
('Siti Nurhaliza', 'siti@tutor.local', 'Bahasa Inggris'),
('Roni Wijaya', 'roni@tutor.local', 'IPA'),
('Eka Putri', 'eka@tutor.local', 'Bahasa Indonesia');

-- Insert classes
INSERT INTO kelas (nama_kelas, deskripsi, harga, tutor_id) VALUES
('Matematika Dasar Kelas 7', 'Pembelajaran matematika dasar untuk kelas 7 SMP mencakup aljabar, geometri, dan trigonometri', 250000.00, 1),
('Bahasa Inggris Intermediate', 'Kursus bahasa inggris level intermediate untuk meningkatkan skill komunikasi', 300000.00, 2),
('IPA Terpadu Kelas 8', 'Pembelajaran IPA terpadu mencakup fisika, kimia, dan biologi untuk kelas 8 SMP', 275000.00, 3),
('Bahasa Indonesia untuk UNBK', 'Persiapan menghadapi UNBK dengan fokus pada teks dan analisis sastra', 320000.00, 4),
('Matematika Persiapan UN', 'Persiapan UN matematika dengan trik-trik cepat dan soal-soal pilihan', 350000.00, 1);

-- Insert schedules
INSERT INTO jadwal (kelas_id, tanggal, jam_mulai, jam_selesai) VALUES
(1, '2026-06-24', '15:00:00', '16:30:00'),
(1, '2026-06-26', '15:00:00', '16:30:00'),
(2, '2026-06-25', '16:00:00', '17:30:00'),
(2, '2026-06-27', '16:00:00', '17:30:00'),
(3, '2026-06-24', '17:00:00', '18:30:00'),
(3, '2026-06-28', '17:00:00', '18:30:00'),
(4, '2026-06-25', '18:00:00', '19:30:00'),
(5, '2026-06-26', '18:00:00', '19:30:00');

-- Insert materials
INSERT INTO materi (kelas_id, judul) VALUES
(1, 'Bab 1 - Bilangan Bulat dan Operasinya'),
(1, 'Bab 2 - Pecahan dan Desimal'),
(1, 'Bab 3 - Persamaan Linear Satu Variabel'),
(2, 'Unit 1 - Present Tense'),
(2, 'Unit 2 - Past Tense'),
(2, 'Unit 3 - Future Tense'),
(3, 'Bab 1 - Energi dan Usaha'),
(3, 'Bab 2 - Gelombang'),
(4, 'Tema 1 - Teks Narasi'),
(4, 'Tema 2 - Teks Deskripsi'),
(5, 'Paket 1 - Aljabar Dasar'),
(5, 'Paket 2 - Geometri dan Trigonometri');