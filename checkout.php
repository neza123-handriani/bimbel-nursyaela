<?php
session_start();
$pdo = require __DIR__ . '/inc/db.php';
require __DIR__ . '/inc/functions.php';

checkAuth();

$user_id = $_SESSION['user_id'];
$kelas_id = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : 0;

// Get class info
$stmt = $pdo->prepare('SELECT * FROM kelas WHERE id = ? LIMIT 1');
$stmt->execute([$kelas_id]);
$kelas = $stmt->fetch();

if (!$kelas) {
    header('Location: kelas.php');
    exit;
}

// Check if already enrolled
$stmt = $pdo->prepare('SELECT id FROM enrollments WHERE user_id = ? AND kelas_id = ? LIMIT 1');
$stmt->execute([$user_id, $kelas_id]);
if ($stmt->fetch()) {
    header('Location: user/kelas-saya.php');
    exit;
}

// Get user info
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'midtrans';
    $invoice = generateInvoice();
    
    // Create transaction
    $stmt = $pdo->prepare('INSERT INTO transactions (invoice, user_id, kelas_id, total, status, payment_method, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
    $stmt->execute([$invoice, $user_id, $kelas_id, $kelas['harga'], 'pending', $payment_method]);
    $transaction_id = $pdo->lastInsertId();
    
    // Create enrollment (pending)
    $stmt = $pdo->prepare('INSERT INTO enrollments (user_id, kelas_id, status, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$user_id, $kelas_id, 'pending']);
    
    // Redirect to payment simulation
    header('Location: payment-confirm.php?invoice=' . $invoice);
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Checkout</title>
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
      <h1 style="margin-bottom:32px;">Checkout</h1>
      
      <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);margin-bottom:24px;">
        <h3 style="margin-bottom:16px;">Ringkasan Pesanan</h3>
        
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--color-hairline);">
          <span><?php echo sanitizeInput($kelas['nama_kelas']); ?></span>
          <strong><?php echo formatRupiah($kelas['harga']); ?></strong>
        </div>
        
        <div style="padding:12px 0;border-bottom:1px solid var(--color-hairline);">
          <strong>Data Pembeli</strong>
          <p style="margin:8px 0;color:var(--color-muted);"><?php echo sanitizeInput($user['nama']); ?></p>
          <p style="margin:8px 0;color:var(--color-muted);"><?php echo sanitizeInput($user['email']); ?></p>
        </div>
        
        <div style="padding:12px 0;background:var(--color-surface-cream-strong);padding:16px;border-radius:4px;text-align:center;margin-top:16px;">
          <div style="font-size:12px;color:var(--color-muted);margin-bottom:4px;">Total Pembayaran</div>
          <div style="font-size:32px;font-weight:bold;color:var(--color-primary);"><?php echo formatRupiah($kelas['harga']); ?></div>
        </div>
      </div>
      
      <form method="post" style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);">
        <h3 style="margin-bottom:16px;">Metode Pembayaran</h3>
        
        <div style="margin-bottom:16px;">
          <label style="display:flex;align-items:center;padding:12px;border:2px solid var(--color-primary);border-radius:4px;cursor:pointer;">
            <input type="radio" name="payment_method" value="midtrans" checked>
            <span style="margin-left:12px;">
              <strong>Midtrans</strong>
              <p style="font-size:13px;color:var(--color-muted);margin:4px 0 0 0;">Transfer Bank, E-Wallet, BNPL</p>
            </span>
          </label>
        </div>
        
        <div style="margin-bottom:24px;">
          <label style="display:flex;align-items:center;padding:12px;border:2px solid var(--color-hairline);border-radius:4px;cursor:pointer;">
            <input type="radio" name="payment_method" value="xendit">
            <span style="margin-left:12px;">
              <strong>Xendit</strong>
              <p style="font-size:13px;color:var(--color-muted);margin:4px 0 0 0;">Virtual Account, E-Money</p>
            </span>
          </label>
        </div>
        
        <button type="submit" class="btn-cta" style="width:100%;padding:12px;">Lanjut ke Pembayaran</button>
        <a href="kelas-detail.php?id=<?php echo $kelas_id; ?>" style="display:block;text-align:center;margin-top:12px;color:var(--color-primary);text-decoration:none;">Batal</a>
      </form>
    </div>
  </main>

  <footer class="footer">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>
