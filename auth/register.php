<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$nama || !$email || !$password) {
        $errors[] = 'Semua field wajib diisi.';
    } else {
        // check existing
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (nama, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute([$nama, $email, $hash, 'user']);
            header('Location: /bimbel/auth/login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register</title>
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <main style="max-width:480px;margin:48px auto;padding:16px;">
    <h2>Daftar</h2>
    <?php if($errors): ?>
      <div style="color:red;">
        <?php foreach($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <div style="margin-bottom:8px;"><input name="nama" placeholder="Nama" class="form-control"/></div>
      <div style="margin-bottom:8px;"><input name="email" type="email" placeholder="Email" class="form-control"/></div>
      <div style="margin-bottom:8px;"><input name="password" type="password" placeholder="Password" class="form-control"/></div>
      <button class="btn-cta" type="submit">Register</button>
    </form>
    <p style="margin-top:12px;">Sudah punya akun? <a href="login.php">Login</a></p>
  </main>
</body>
</html>