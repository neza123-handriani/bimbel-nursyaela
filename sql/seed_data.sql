USE bimbel_db;

-- Minimal seed data for bimbel_db
USE bimbel_db;

-- NOTE: For passwords use PHP's password_hash. Create admin via a PHP script or register flow.
-- Sample tutors
INSERT INTO tutors (nama, email, spesialisasi, foto) VALUES
('Budi Santoso','budi@tutor.local','Matematika', NULL),
('Siti Aminah','siti@tutor.local','Bahasa Indonesia', NULL);

-- Sample classes
INSERT INTO kelas (nama_kelas, deskripsi, harga, tutor_id) VALUES
('Kelas Matematika Dasar','Mempelajari aljabar dasar dan aritmatika',150000.00,1),
('Kelas Bahasa Indonesia','Pemahaman teks, tata bahasa, dan penulisan',120000.00,2);

-- Sample users (create password using register form)
INSERT INTO users (nama, email, password, role, created_at) VALUES
('Demo User','user@bimbel.local','', 'user', NOW());