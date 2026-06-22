<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (!$email) {
        $errors[] = 'Email wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            // In production, send reset email
            $message = 'Link reset password telah dikirim ke email Anda.';
        } else {
            $errors[] = 'Email tidak ditemukan.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Lupa Password</title>
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <main style="max-width:480px;margin:48px auto;padding:16px;">
    <h2>Lupa Password</h2>
    <?php if($message): ?>
      <div style="color:green;margin-bottom:16px;"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if($errors): ?>
      <div style="color:red;margin-bottom:16px;">
        <?php foreach($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <div style="margin-bottom:8px;"><input name="email" type="email" placeholder="Masukkan email Anda" class="form-control"/></div>
      <button class="btn-cta" type="submit">Kirim Link Reset</button>
    </form>
    <p style="margin-top:12px;"><a href="login.php">Kembali ke login</a></p>
  </main>
</body>
</html>
