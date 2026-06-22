<?php
session_start();
$pdo = require __DIR__ . '/inc/db.php';
require __DIR__ . '/inc/functions.php';

checkAuth();

$invoice = $_GET['invoice'] ?? '';

// Get transaction
$stmt = $pdo->prepare('
    SELECT t.*, k.nama_kelas, k.harga
    FROM transactions t
    LEFT JOIN kelas k ON t.kelas_id = k.id
    WHERE t.invoice = ? AND t.user_id = ? LIMIT 1
');
$stmt->execute([$invoice, $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header('Location: kelas.php');
    exit;
}

// Simulate payment confirmation (in production, integrate with actual payment gateway)
if ($_POST['action'] ?? '' === 'confirm') {
    // Update transaction status
    $stmt = $pdo->prepare('UPDATE transactions SET status = ? WHERE id = ?');
    $stmt->execute(['paid', $transaction['id']]);
    
    // Update enrollment status
    $stmt = $pdo->prepare('UPDATE enrollments SET status = ? WHERE user_id = ? AND kelas_id = ?');
    $stmt->execute(['active', $_SESSION['user_id'], $transaction['kelas_id']]);
    
    // Create payment record
    $stmt = $pdo->prepare('INSERT INTO payments (transaction_id, gateway_reference, payment_status, paid_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$transaction['id'], 'SIM-' . time(), 'success']);
    
    header('Location: user/dashboard.php?payment=success');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Konfirmasi Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/design-tokens.css">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel</span></div>
    <nav>
      <a href="user/dashboard.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Dashboard</a>
      <a href="auth/logout.php" style="color:var(--color-ink);text-decoration:none">Logout</a>
    </nav>
  </header>

  <main class="container" style="margin-top:40px;margin-bottom:60px;">
    <div style="max-width:600px;margin:0 auto;">
      <div style="background:var(--color-surface-card);padding:40px;border-radius:var(--r-lg);text-align:center;">
        <div style="font-size:64px;margin-bottom:16px;">💳</div>
        <h1 style="margin-bottom:8px;">Konfirmasi Pembayaran</h1>
        <p style="color:var(--color-muted);margin-bottom:32px;">Invoice: <strong><?php echo sanitizeInput($invoice); ?></strong></p>
        
        <div style="background:var(--color-surface-cream-strong);padding:20px;border-radius:4px;margin-bottom:32px;text-align:left;">
          <p style="margin:8px 0;display:flex;justify-content:space-between;">
            <span>Kelas:</span>
            <strong><?php echo sanitizeInput($transaction['nama_kelas']); ?></strong>
          </p>
          <p style="margin:8px 0;display:flex;justify-content:space-between;">
            <span>Metode:</span>
            <strong><?php echo strtoupper($transaction['payment_method']); ?></strong>
          </p>
          <p style="margin:8px 0;display:flex;justify-content:space-between;font-size:18px;border-top:1px solid var(--color-hairline);padding-top:12px;margin-top:12px;">
            <span>Total:</span>
            <strong style="color:var(--color-primary);"><?php echo formatRupiah($transaction['total']); ?></strong>
          </p>
        </div>
        
        <form method="post">
          <input type="hidden" name="action" value="confirm">
          <button type="submit" class="btn-cta" style="width:100%;padding:12px;margin-bottom:12px;">✓ Pembayaran Berhasil</button>
        </form>
        
        <p style="color:var(--color-muted);font-size:13px;">
          <em>Catatan: Ini adalah simulasi pembayaran untuk development. Di production, ini akan terhubung dengan gateway pembayaran sebenarnya.</em>
        </p>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>
