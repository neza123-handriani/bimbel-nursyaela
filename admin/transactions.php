<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/functions.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /bimbel/auth/login.php');
    exit;
}

$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? 'list';
$message = '';

// Get all transactions
$stmt = $pdo->prepare('
    SELECT t.*, k.nama_kelas, u.nama, u.email
    FROM transactions t
    LEFT JOIN kelas k ON t.kelas_id = k.id
    LEFT JOIN users u ON t.user_id = u.id
    ORDER BY t.created_at DESC
');
$stmt->execute();
$transactions = $stmt->fetchAll();

// Update transaction status
if ($_POST['status'] ?? '' && $id) {
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare('UPDATE transactions SET status = ? WHERE id = ?');
    $stmt->execute([$new_status, $id]);
    $message = 'Status transaksi berhasil diperbarui.';
    $action = 'list';
}

$selected_transaction = null;
if ($id && $action !== 'list') {
    $stmt = $pdo->prepare('SELECT * FROM transactions WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $selected_transaction = $stmt->fetch();
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manajemen Transaksi</title>
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
        <a href="dashboard.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📊 Dashboard</a>
        <a href="users.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">👥 Manajemen User</a>
        <a href="tutors.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">🎓 Manajemen Tutor</a>
        <a href="kelas.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📚 Manajemen Kelas</a>
        <a href="materi.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📖 Manajemen Materi</a>
        <a href="transactions.php" style="padding:12px;border-radius:4px;color:white;text-decoration:none;background:var(--color-primary);">💳 Transaksi</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main style="flex:1;padding:32px;">
      <h1 style="margin-bottom:32px;">Manajemen Transaksi</h1>

      <?php if($message): ?>
        <div style="background:var(--color-success);color:white;padding:16px;border-radius:4px;margin-bottom:24px;">
          ✓ <?php echo $message; ?>
        </div>
      <?php endif; ?>

      <?php if($action !== 'list' && $selected_transaction): ?>
        <!-- Transaction Detail -->
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);max-width:600px;">
          <h2 style="margin-bottom:24px;">Detail Transaksi</h2>
          <div style="margin-bottom:16px;">
            <label style="display:block;color:var(--color-muted);font-size:13px;">Invoice</label>
            <div style="font-weight:bold;"><?php echo sanitizeInput($selected_transaction['invoice']); ?></div>
          </div>
          <div style="margin-bottom:16px;">
            <label style="display:block;color:var(--color-muted);font-size:13px;">Total</label>
            <div style="font-weight:bold;font-size:24px;"><?php echo formatRupiah($selected_transaction['total']); ?></div>
          </div>
          <div style="margin-bottom:16px;">
            <label style="display:block;color:var(--color-muted);font-size:13px;">Metode Pembayaran</label>
            <div><?php echo strtoupper($selected_transaction['payment_method']); ?></div>
          </div>
          <div style="margin-bottom:24px;">
            <label style="display:block;color:var(--color-muted);font-size:13px;">Status</label>
            <form method="post" style="display:flex;gap:12px;">
              <select name="status" class="form-control" style="max-width:200px;">
                <option value="pending" <?php echo $selected_transaction['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="paid" <?php echo $selected_transaction['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                <option value="failed" <?php echo $selected_transaction['status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                <option value="expired" <?php echo $selected_transaction['status'] === 'expired' ? 'selected' : ''; ?>>Expired</option>
              </select>
              <button type="submit" class="btn-cta" style="padding:8px 16px;">Update</button>
            </form>
          </div>
          <a href="transactions.php" style="color:var(--color-primary);text-decoration:none;">← Kembali</a>
        </div>
      <?php else: ?>
        <!-- Transactions List -->
        <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
          <table style="width:100%;border-collapse:collapse;">
            <thead style="background:var(--color-surface-cream-strong);">
              <tr>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Invoice</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">User</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Kelas</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Total</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Status</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Tanggal</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($transactions as $t): ?>
                <tr style="border-bottom:1px solid var(--color-hairline);">
                  <td style="padding:16px;"><code style="background:var(--color-surface-cream-strong);padding:4px 8px;border-radius:3px;font-size:11px;"><?php echo sanitizeInput($t['invoice']); ?></code></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($t['nama']); ?></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($t['nama_kelas'] ?? '-'); ?></td>
                  <td style="padding:16px;font-weight:bold;"><?php echo formatRupiah($t['total']); ?></td>
                  <td style="padding:16px;">
                    <span style="background:<?php 
                      if ($t['status'] === 'paid') echo 'var(--color-success)';
                      elseif ($t['status'] === 'pending') echo 'var(--color-warning)';
                      else echo 'var(--color-error)';
                    ?>;color:white;padding:4px 8px;border-radius:3px;font-size:12px;">
                      <?php echo strtoupper($t['status']); ?>
                    </span>
                  </td>
                  <td style="padding:16px;font-size:13px;"><?php echo formatDate($t['created_at']); ?></td>
                  <td style="padding:16px;">
                    <a href="transactions.php?action=detail&id=<?php echo $t['id']; ?>" style="color:var(--color-primary);text-decoration:none;">Detail</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
