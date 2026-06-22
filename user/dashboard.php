<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/functions.php';

if (empty($_SESSION['user_id'])) {
    header('Location: /bimbel/auth/login.php');
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header('Location: /bimbel/admin/dashboard.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get enrolled classes
$stmt = $pdo->prepare('
    SELECT k.*, t.nama as tutor_nama, e.status as enrollment_status
    FROM enrollments e
    JOIN kelas k ON e.kelas_id = k.id
    LEFT JOIN tutors t ON k.tutor_id = t.id
    WHERE e.user_id = ? AND e.status = "active"
    ORDER BY e.created_at DESC
');
$stmt->execute([$user_id]);
$classes = $stmt->fetchAll();

// Get pending transactions
$stmt = $pdo->prepare('
    SELECT t.*, k.nama_kelas
    FROM transactions t
    LEFT JOIN kelas k ON t.kelas_id = k.id
    WHERE t.user_id = ? AND t.status IN ("pending", "paid")
    ORDER BY t.created_at DESC
    LIMIT 5
');
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/bimbel/assets/css/design-tokens.css">
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel User</span></div>
    <nav>
      <span style="margin-right:12px;color:var(--color-ink);"><?php echo sanitizeInput($user['nama']); ?></span>
      <a href="/bimbel/auth/logout.php" style="color:var(--color-ink);text-decoration:none">Logout</a>
    </nav>
  </header>

  <main style="max-width:1200px;margin:32px auto;padding:16px;">
    <?php if($_GET['payment'] ?? '' === 'success'): ?>
      <div style="background:var(--color-success);color:white;padding:16px;border-radius:4px;margin-bottom:24px;">
        ✓ Pembayaran berhasil! Anda sekarang dapat mengakses kelas.
      </div>
    <?php endif; ?>

    <h1 style="margin-bottom:32px;">Dashboard</h1>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:24px;margin-bottom:40px;">
      <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);border-left:4px solid var(--color-primary);">
        <div style="color:var(--color-muted);font-size:13px;margin-bottom:8px;">Kelas Aktif</div>
        <div style="font-size:32px;font-weight:bold;"><?php echo count($classes); ?></div>
      </div>
      <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);border-left:4px solid var(--color-accent-teal);">
        <div style="color:var(--color-muted);font-size:13px;margin-bottom:8px;">Transaksi</div>
        <div style="font-size:32px;font-weight:bold;"><?php echo count($transactions); ?></div>
      </div>
      <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);border-left:4px solid var(--color-accent-amber);">
        <div style="color:var(--color-muted);font-size:13px;margin-bottom:8px;">Member Sejak</div>
        <div style="font-size:14px;font-weight:bold;"><?php echo formatDate($user['created_at']); ?></div>
      </div>
    </div>

    <!-- Enrolled Classes -->
    <section style="margin-bottom:40px;">
      <h2 style="margin-bottom:24px;">Kelas Saya</h2>
      <?php if($classes): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:24px;">
          <?php foreach($classes as $c): ?>
            <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;border:1px solid var(--color-hairline);">
              <div style="padding:24px;border-bottom:1px solid var(--color-hairline);">
                <h3 style="margin:0 0 8px 0;font-size:16px;"><?php echo sanitizeInput($c['nama_kelas']); ?></h3>
                <p style="margin:0;color:var(--color-muted);font-size:13px;">Tutor: <?php echo sanitizeInput($c['tutor_nama'] ?? '-'); ?></p>
              </div>
              <div style="padding:16px;">
                <a href="kelas-detail.php?id=<?php echo $c['id']; ?>" style="color:var(--color-primary);text-decoration:none;">Akses Materi →</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div style="background:var(--color-surface-card);padding:32px;border-radius:var(--r-lg);text-align:center;">
          <p style="color:var(--color-muted);margin-bottom:16px;">Anda belum mendaftar kelas apapun</p>
          <a href="/bimbel/kelas.php" class="btn-cta" style="display:inline-block;">Lihat Daftar Kelas</a>
        </div>
      <?php endif; ?>
    </section>

    <!-- Transactions -->
    <section>
      <h2 style="margin-bottom:24px;">Riwayat Transaksi</h2>
      <?php if($transactions): ?>
        <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
          <table style="width:100%;border-collapse:collapse;">
            <thead style="background:var(--color-surface-cream-strong);">
              <tr>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Invoice</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Kelas</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Total</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Status</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($transactions as $t): ?>
                <tr style="border-bottom:1px solid var(--color-hairline);">
                  <td style="padding:16px;"><code style="background:var(--color-surface-cream-strong);padding:4px 8px;border-radius:3px;font-size:12px;"><?php echo sanitizeInput($t['invoice']); ?></code></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($t['nama_kelas'] ?? '-'); ?></td>
                  <td style="padding:16px;font-weight:bold;"><?php echo formatRupiah($t['total']); ?></td>
                  <td style="padding:16px;">
                    <span style="background:<?php echo $t['status'] === 'paid' ? 'var(--color-success)' : 'var(--color-warning)'; ?>;color:white;padding:4px 8px;border-radius:3px;font-size:12px;">
                      <?php echo strtoupper($t['status']); ?>
                    </span>
                  </td>
                  <td style="padding:16px;"><?php echo formatDate($t['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div style="background:var(--color-surface-card);padding:32px;border-radius:var(--r-lg);text-align:center;color:var(--color-muted);">
          Belum ada transaksi
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="footer" style="margin-top:60px;">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>