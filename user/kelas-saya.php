<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/functions.php';

redirectIfNotRole('user');

$user_id = $_SESSION['user_id'];

// Get enrolled classes
$stmt = $pdo->prepare('
    SELECT k.*, t.nama as tutor_nama, e.status as enrollment_status
    FROM enrollments e
    JOIN kelas k ON e.kelas_id = k.id
    LEFT JOIN tutors t ON k.tutor_id = t.id
    WHERE e.user_id = ? AND e.status = "active"
    ORDER BY k.nama_kelas
');
$stmt->execute([$user_id]);
$classes = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Kelas Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/bimbel/assets/css/design-tokens.css">
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel</span></div>
    <nav>
      <a href="dashboard.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Dashboard</a>
      <a href="/bimbel/auth/logout.php" style="color:var(--color-ink);text-decoration:none">Logout</a>
    </nav>
  </header>

  <main class="container" style="margin-top:40px;margin-bottom:60px;">
    <h1 style="margin-bottom:32px;">Kelas Saya</h1>
    
    <?php if($classes): ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(350px, 1fr));gap:24px;">
        <?php foreach($classes as $c): ?>
          <div style="background:var(--color-surface-card);border-radius:var(--r-lg);padding:24px;border:1px solid var(--color-hairline);">
            <h3 style="margin-bottom:8px;"><?php echo sanitizeInput($c['nama_kelas']); ?></h3>
            <p style="color:var(--color-muted);font-size:13px;margin-bottom:16px;">Tutor: <?php echo sanitizeInput($c['tutor_nama']); ?></p>
            
            <p style="font-size:13px;margin-bottom:16px;"><?php echo sanitizeInput(substr($c['deskripsi'] ?? '', 0, 100)); ?>...</p>
            
            <div style="background:var(--color-surface-cream-strong);padding:12px;border-radius:4px;margin-bottom:16px;text-align:center;">
              <div style="font-size:24px;font-weight:bold;color:var(--color-primary);"><?php echo formatRupiah($c['harga']); ?></div>
            </div>
            
            <a href="kelas-detail.php?id=<?php echo $c['id']; ?>" class="btn-cta" style="display:block;text-align:center;padding:10px;">Akses Kelas</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="background:var(--color-surface-card);padding:40px;border-radius:var(--r-lg);text-align:center;">
        <p style="color:var(--color-muted);font-size:16px;margin-bottom:24px;">Anda belum mendaftar kelas apapun</p>
        <a href="/bimbel/kelas.php" class="btn-cta" style="display:inline-block;">Jelajahi Kelas</a>
      </div>
    <?php endif; ?>
  </main>

  <footer class="footer">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>
