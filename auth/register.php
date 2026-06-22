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
            header('Location: /bimbel/auth/login.php?success=1');
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/bimbel/assets/css/design-tokens.css">
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel</span></div>
    <nav>
      <a href="/bimbel/" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Home</a>
    </nav>
  </header>

  <main style="max-width:480px;margin:60px auto;padding:16px;">
    <div style="background:var(--color-surface-card);padding:32px;border-radius:var(--r-lg);">
      <h2 style="margin-bottom:8px;">Daftar Akun</h2>
      <p style="color:var(--color-muted);margin-bottom:24px;">Buat akun baru untuk mengakses kelas</p>
      
      <?php if($errors): ?>
        <div style="background:var(--color-error);color:white;padding:12px;border-radius:4px;margin-bottom:16px;">
          <?php foreach($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?>
        </div>
      <?php endif; ?>
      
      <form method="post">
        <div style="margin-bottom:16px;">
          <label style="display:block;margin-bottom:8px;font-weight:500;">Nama Lengkap</label>
          <input name="nama" type="text" placeholder="Masukkan nama Anda" class="form-control" required/>
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block;margin-bottom:8px;font-weight:500;">Email</label>
          <input name="email" type="email" placeholder="Masukkan email" class="form-control" required/>
        </div>
        <div style="margin-bottom:24px;">
          <label style="display:block;margin-bottom:8px;font-weight:500;">Password</label>
          <input name="password" type="password" placeholder="Minimal 6 karakter" class="form-control" required/>
        </div>
        <button class="btn-cta" type="submit" style="width:100%;padding:12px;font-weight:600;">Daftar</button>
      </form>

      <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--color-hairline);">
        <p style="margin:0;font-size:13px;color:var(--color-muted);">
          Sudah punya akun? <a href="login.php" style="color:var(--color-primary);text-decoration:none;">Login di sini</a>
        </p>
      </div>
    </div>
  </main>

  <footer class="footer" style="margin-top:80px;">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>