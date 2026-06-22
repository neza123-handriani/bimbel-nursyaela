<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/functions.php';

redirectIfNotRole('user');

$user_id = $_SESSION['user_id'];
$kelas_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check enrollment
$stmt = $pdo->prepare('
    SELECT e.* FROM enrollments e
    WHERE e.user_id = ? AND e.kelas_id = ? AND e.status = "active"
    LIMIT 1
');
$stmt->execute([$user_id, $kelas_id]);
if (!$stmt->fetch()) {
    header('Location: kelas-saya.php');
    exit;
}

// Get class details
$stmt = $pdo->prepare('
    SELECT k.*, t.nama as tutor_nama, t.spesialisasi
    FROM kelas k
    LEFT JOIN tutors t ON k.tutor_id = t.id
    WHERE k.id = ? LIMIT 1
');
$stmt->execute([$kelas_id]);
$kelas = $stmt->fetch();

// Get materials
$stmt = $pdo->prepare('SELECT * FROM materi WHERE kelas_id = ? ORDER BY created_at DESC');
$stmt->execute([$kelas_id]);
$materials = $stmt->fetchAll();

// Get schedules
$stmt = $pdo->prepare('SELECT * FROM jadwal WHERE kelas_id = ? ORDER BY tanggal, jam_mulai DESC');
$stmt->execute([$kelas_id]);
$jadwals = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo sanitizeInput($kelas['nama_kelas']); ?></title>
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
    <a href="kelas-saya.php" style="color:var(--color-primary);text-decoration:none;margin-bottom:24px;display:inline-block;">← Kembali</a>
    
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:40px;">
      <!-- Main Content -->
      <div>
        <h1 style="margin-bottom:8px;"><?php echo sanitizeInput($kelas['nama_kelas']); ?></h1>
        <p style="color:var(--color-muted);margin-bottom:24px;">Tutor: <strong><?php echo sanitizeInput($kelas['tutor_nama']); ?></strong></p>
        
        <h3 style="margin-top:32px;margin-bottom:16px;">Deskripsi Kelas</h3>
        <p><?php echo nl2br(sanitizeInput($kelas['deskripsi'])); ?></p>
        
        <h3 style="margin-top:32px;margin-bottom:16px;">📚 Materi Pembelajaran</h3>
        <?php if($materials): ?>
          <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
            <?php foreach($materials as $index => $m): ?>
              <div style="padding:16px;border-bottom:<?php echo $index < count($materials)-1 ? '1px solid var(--color-hairline)' : 'none'; ?>;">
                <div style="display:flex;align-items:center;">
                  <span style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:var(--color-primary);color:white;border-radius:50%;margin-right:12px;font-size:12px;font-weight:bold;">
                    <?php echo $index + 1; ?>
                  </span>
                  <div>
                    <p style="margin:0;font-weight:500;"><?php echo sanitizeInput($m['judul']); ?></p>
                    <p style="margin:4px 0 0 0;font-size:13px;color:var(--color-muted);">
                      <?php echo $m['file_materi'] ? '📄 Tersedia' : '📝 Deskripsi'; ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p style="color:var(--color-muted);">Materi belum tersedia</p>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div>
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);position:sticky;top:20px;">
          <h3 style="margin-bottom:16px;">📅 Jadwal Kelas</h3>
          <?php if($jadwals): ?>
            <ul style="list-style:none;padding:0;margin:0;">
              <?php foreach($jadwals as $j): ?>
                <li style="padding:12px;margin-bottom:8px;background:var(--color-surface-cream-strong);border-radius:4px;">
                  <div style="font-weight:bold;font-size:13px;"><?php echo formatDate($j['tanggal']); ?></div>
                  <div style="font-size:12px;color:var(--color-muted);margin-top:4px;">
                    ⏰ <?php echo substr($j['jam_mulai'], 0, 5); ?> - <?php echo substr($j['jam_selesai'], 0, 5); ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p style="font-size:13px;color:var(--color-muted);">Jadwal belum ditentukan</p>
          <?php endif; ?>
          
          <hr style="margin:24px 0;border:none;border-top:1px solid var(--color-hairline);">
          
          <h4 style="margin-bottom:12px;">Tentang Tutor</h4>
          <p style="margin:8px 0;font-weight:500;font-size:14px;"><?php echo sanitizeInput($kelas['tutor_nama']); ?></p>
          <p style="margin:4px 0;font-size:12px;color:var(--color-muted);">Spesialisasi: <?php echo sanitizeInput($kelas['spesialisasi']); ?></p>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>
