<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/functions.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /bimbel/auth/login.php');
    exit;
}

// Get statistics
$stmt = $pdo->prepare('SELECT COUNT(*) as total FROM users WHERE role = "user"');
$stmt->execute();
$total_users = $stmt->fetch()['total'];

$stmt = $pdo->prepare('SELECT COUNT(*) as total FROM tutors');
$stmt->execute();
$total_tutors = $stmt->fetch()['total'];

$stmt = $pdo->prepare('SELECT COUNT(*) as total FROM kelas');
$stmt->execute();
$total_kelas = $stmt->fetch()['total'];

$stmt = $pdo->prepare('SELECT SUM(total) as revenue FROM transactions WHERE status = "paid"');
$stmt->execute();
$revenue = $stmt->fetch()['revenue'] ?? 0;

$stmt = $pdo->prepare('SELECT COUNT(*) as total FROM transactions WHERE status = "pending"');
$stmt->execute();
$pending_transactions = $stmt->fetch()['total'];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/bimbel/assets/css/design-tokens.css">
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel Admin</span></div>
    <nav>
      <a href="/bimbel/auth/logout.php" style="color:var(--color-on-dark-soft)">Logout</a>
    </nav>
  </header>

  <div style="display:flex;min-height:calc(100vh - 60px);">
    <!-- Sidebar -->
    <aside style="width:250px;background:var(--color-surface-dark-soft);padding:24px;border-right:1px solid var(--color-hairline);color:var(--color-on-dark);">
      <nav style="display:flex;flex-direction:column;gap:8px;">
        <a href="dashboard.php" style="padding:12px;border-radius:4px;color:white;text-decoration:none;background:var(--color-primary);font-weight:500;">📊 Dashboard</a>
        <a href="users.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">👥 Manajemen User</a>
        <a href="tutors.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">🎓 Manajemen Tutor</a>
        <a href="kelas.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📚 Manajemen Kelas</a>
        <a href="materi.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📖 Manajemen Materi</a>
        <a href="transactions.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">💳 Transaksi</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main style="flex:1;padding:32px;">
      <h1 style="margin-bottom:32px;">Dashboard Administrator</h1>

      <!-- Stats -->
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:24px;margin-bottom:40px;">
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);border-left:4px solid var(--color-primary);">
          <div style="color:var(--color-muted);font-size:13px;margin-bottom:8px;">Total User</div>
          <div style="font-size:36px;font-weight:bold;"><?php echo $total_users; ?></div>
        </div>
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);border-left:4px solid var(--color-accent-teal);">
          <div style="color:var(--color-muted);font-size:13px;margin-bottom:8px;">Total Tutor</div>
          <div style="font-size:36px;font-weight:bold;"><?php echo $total_tutors; ?></div>
        </div>
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);border-left:4px solid var(--color-accent-amber);">
          <div style="color:var(--color-muted);font-size:13px;margin-bottom:8px;">Total Kelas</div>
          <div style="font-size:36px;font-weight:bold;"><?php echo $total_kelas; ?></div>
        </div>
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);border-left:4px solid var(--color-success);">
          <div style="color:var(--color-muted);font-size:13px;margin-bottom:8px;">Revenue</div>
          <div style="font-size:24px;font-weight:bold;"><?php echo formatRupiah($revenue); ?></div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);margin-bottom:40px;">
        <h3 style="margin-bottom:16px;">Quick Actions</h3>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <a href="users.php?action=add" class="btn-cta" style="padding:8px 16px;font-size:14px;">+ Tambah User</a>
          <a href="tutors.php?action=add" class="btn-cta" style="padding:8px 16px;font-size:14px;">+ Tambah Tutor</a>
          <a href="kelas.php?action=add" class="btn-cta" style="padding:8px 16px;font-size:14px;">+ Tambah Kelas</a>
        </div>
      </div>

      <!-- Recent Transactions -->
      <section>
        <h3 style="margin-bottom:16px;">Transaksi Pending (<?php echo $pending_transactions; ?>)</h3>
        <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
          <?php
            $stmt = $pdo->prepare('
              SELECT t.*, k.nama_kelas, u.nama, u.email
              FROM transactions t
              LEFT JOIN kelas k ON t.kelas_id = k.id
              LEFT JOIN users u ON t.user_id = u.id
              WHERE t.status = "pending"
              ORDER BY t.created_at DESC
              LIMIT 5
            ');
            $stmt->execute();
            $pending = $stmt->fetchAll();
          ?>
          <?php if($pending): ?>
            <table style="width:100%;border-collapse:collapse;">
              <thead style="background:var(--color-surface-cream-strong);">
                <tr>
                  <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Invoice</th>
                  <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">User</th>
                  <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Kelas</th>
                  <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Jumlah</th>
                  <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($pending as $t): ?>
                  <tr style="border-bottom:1px solid var(--color-hairline);">
                    <td style="padding:16px;"><code style="background:var(--color-surface-cream-strong);padding:4px 8px;border-radius:3px;font-size:12px;"><?php echo sanitizeInput($t['invoice']); ?></code></td>
                    <td style="padding:16px;"><?php echo sanitizeInput($t['nama']); ?></td>
                    <td style="padding:16px;"><?php echo sanitizeInput($t['nama_kelas']); ?></td>
                    <td style="padding:16px;"><?php echo formatRupiah($t['total']); ?></td>
                    <td style="padding:16px;"><a href="transactions.php?id=<?php echo $t['id']; ?>" style="color:var(--color-primary);text-decoration:none;">Detail</a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div style="padding:32px;text-align:center;color:var(--color-muted);">Tidak ada transaksi pending</div>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>

  <footer class="footer" style="grid-column:1/-1;">
    <div class="container">&copy; 2026 Bimbel Admin</div>
  </footer>
</body>
</html>