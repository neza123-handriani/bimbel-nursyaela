<?php
session_start();
$pdo = require __DIR__ . '/inc/db.php';
require __DIR__ . '/inc/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get class details with tutor
$stmt = $pdo->prepare('
    SELECT k.*, t.nama as tutor_nama, t.email as tutor_email, t.spesialisasi
    FROM kelas k
    LEFT JOIN tutors t ON k.tutor_id = t.id
    WHERE k.id = ? LIMIT 1
');
$stmt->execute([$id]);
$kelas = $stmt->fetch();

if (!$kelas) {
    header('Location: kelas.php');
    exit;
}

// Get schedules
$stmt = $pdo->prepare('SELECT * FROM jadwal WHERE kelas_id = ? ORDER BY tanggal, jam_mulai');
$stmt->execute([$id]);
$jadwals = $stmt->fetchAll();

// Get materials
$stmt = $pdo->prepare('SELECT * FROM materi WHERE kelas_id = ? ORDER BY created_at DESC');
$stmt->execute([$id]);
$materials = $stmt->fetchAll();

// Check if user is enrolled
$is_enrolled = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('SELECT id FROM enrollments WHERE user_id = ? AND kelas_id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id'], $id]);
    $is_enrolled = $stmt->fetch() ? true : false;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo sanitizeInput($kelas['nama_kelas']); ?></title>
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

  <main class="container" style="margin-top:40px;margin-bottom:60px;">
    <div style="margin-bottom:32px;">
      <a href="kelas.php" style="color:var(--color-primary);text-decoration:none;">← Kembali ke Daftar Kelas</a>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:40px;">
      <!-- Content -->
      <div>
        <h1 style="margin-bottom:8px;"><?php echo sanitizeInput($kelas['nama_kelas']); ?></h1>
        <p style="color:var(--color-muted);font-size:14px;margin-bottom:24px;">Oleh <strong><?php echo sanitizeInput($kelas['tutor_nama'] ?? 'N/A'); ?></strong></p>
        
        <h3 style="margin-top:32px;margin-bottom:16px;">Deskripsi</h3>
        <p><?php echo nl2br(sanitizeInput($kelas['deskripsi'] ?? '')); ?></p>
        
        <h3 style="margin-top:32px;margin-bottom:16px;">Jadwal Kelas</h3>
        <?php if($jadwals): ?>
          <ul style="list-style:none;padding:0;">
            <?php foreach($jadwals as $j): ?>
              <li style="padding:12px;margin-bottom:8px;background:var(--color-surface-card);border-radius:4px;">
                <strong><?php echo formatDate($j['tanggal']); ?></strong> - 
                <?php echo substr($j['jam_mulai'], 0, 5); ?> s/d <?php echo substr($j['jam_selesai'], 0, 5); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p style="color:var(--color-muted);">Jadwal belum tersedia</p>
        <?php endif; ?>
        
        <h3 style="margin-top:32px;margin-bottom:16px;">Materi Pembelajaran</h3>
        <?php if($materials): ?>
          <ul style="list-style:none;padding:0;">
            <?php foreach($materials as $m): ?>
              <li style="padding:12px;margin-bottom:8px;background:var(--color-surface-card);border-radius:4px;border-left:4px solid var(--color-primary);">
                <?php echo sanitizeInput($m['judul']); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p style="color:var(--color-muted);">Materi belum tersedia</p>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div>
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);position:sticky;top:20px;">
          <div style="background:var(--color-surface-cream-strong);padding:12px;border-radius:4px;margin-bottom:24px;text-align:center;">
            <div style="font-size:28px;font-weight:bold;color:var(--color-primary);"><?php echo formatRupiah($kelas['harga']); ?></div>
          </div>
          
          <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($is_enrolled): ?>
              <div style="padding:12px;background:var(--color-success);color:white;border-radius:4px;text-align:center;">
                ✓ Anda sudah terdaftar
              </div>
              <a href="user/kelas-saya.php" class="btn-cta" style="display:block;width:100%;text-align:center;margin-top:12px;padding:12px;">Akses Kelas</a>
            <?php else: ?>
              <a href="checkout.php?kelas_id=<?php echo $id; ?>" class="btn-cta" style="display:block;width:100%;text-align:center;padding:12px;">Beli Sekarang</a>
            <?php endif; ?>
          <?php else: ?>
            <a href="auth/login.php" class="btn-cta" style="display:block;width:100%;text-align:center;padding:12px;">Login untuk Daftar</a>
          <?php endif; ?>
          
          <hr style="margin:24px 0;border:none;border-top:1px solid var(--color-hairline);">
          
          <h4 style="margin-bottom:12px;">Tentang Tutor</h4>
          <p style="margin-bottom:8px;font-weight:500;"><?php echo sanitizeInput($kelas['tutor_nama'] ?? '-'); ?></p>
          <p style="color:var(--color-muted);font-size:13px;margin-bottom:12px;"><?php echo sanitizeInput($kelas['spesialisasi'] ?? '-'); ?></p>
          <p style="font-size:13px;color:var(--color-muted);">Email: <?php echo sanitizeInput($kelas['tutor_email'] ?? '-'); ?></p>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>
