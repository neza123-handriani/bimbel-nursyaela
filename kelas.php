<?php
session_start();
$pdo = require __DIR__ . '/inc/db.php';
require __DIR__ . '/inc/functions.php';

// Get all classes with tutors
$stmt = $pdo->prepare('
    SELECT k.*, t.nama as tutor_nama, COUNT(e.id) as jumlah_siswa
    FROM kelas k
    LEFT JOIN tutors t ON k.tutor_id = t.id
    LEFT JOIN enrollments e ON k.id = e.kelas_id
    GROUP BY k.id
    ORDER BY k.id DESC
');
$stmt->execute();
$kelas_list = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daftar Kelas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/design-tokens.css">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel</span></div>
    <nav>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="user/dashboard.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Dashboard</a>
        <a href="auth/logout.php" style="color:var(--color-ink);text-decoration:none">Logout</a>
      <?php else: ?>
        <a href="auth/login.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Sign in</a>
        <a class="btn-cta" href="auth/register.php">Daftar</a>
      <?php endif; ?>
    </nav>
  </header>

  <main class="container" style="margin-top:40px;">
    <h1 style="margin-bottom:32px;">Daftar Kelas Tersedia</h1>
    
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:24px;">
      <?php foreach($kelas_list as $k): ?>
        <div style="border:1px solid var(--color-hairline);border-radius:var(--r-lg);padding:24px;background:var(--color-surface-card);">
          <h3 style="margin-bottom:8px;font-size:18px;"><?php echo sanitizeInput($k['nama_kelas']); ?></h3>
          <p style="color:var(--color-muted);font-size:14px;margin-bottom:8px;">Tutor: <strong><?php echo sanitizeInput($k['tutor_nama'] ?? 'N/A'); ?></strong></p>
          <p style="color:var(--color-muted);font-size:13px;margin-bottom:12px;"><?php echo sanitizeInput(substr($k['deskripsi'] ?? '', 0, 100)); ?>...</p>
          
          <div style="background:var(--color-surface-cream-strong);padding:8px;border-radius:4px;margin-bottom:12px;font-weight:500;">
            <?php echo formatRupiah($k['harga']); ?>
          </div>
          
          <p style="color:var(--color-muted);font-size:13px;margin-bottom:12px;">Siswa terdaftar: <strong><?php echo $k['jumlah_siswa']; ?></strong></p>
          
          <div>
            <a href="kelas-detail.php?id=<?php echo $k['id']; ?>" class="btn-cta" style="display:inline-block;padding:8px 16px;font-size:14px;">Lihat Detail</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <footer class="footer" style="margin-top:80px;">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>
