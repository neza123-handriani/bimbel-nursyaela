<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $errors[] = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT id, password, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            // redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: /bimbel/admin/dashboard.php');
            } else {
                header('Location: /bimbel/user/dashboard.php');
            }
            exit;
        } else {
            $errors[] = 'Kredensial salah.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
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
      <h2 style="margin-bottom:8px;">Login</h2>
      <p style="color:var(--color-muted);margin-bottom:24px;">Masukkan email dan password Anda</p>
      
      <?php if($errors): ?>
        <div style="background:var(--color-error);color:white;padding:12px;border-radius:4px;margin-bottom:16px;">
          <?php foreach($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?>
        </div>
      <?php endif; ?>
      
      <form method="post">
        <div style="margin-bottom:16px;">
          <label style="display:block;margin-bottom:8px;font-weight:500;">Email</label>
          <input name="email" type="email" placeholder="Masukkan email" class="form-control" required/>
        </div>
        <div style="margin-bottom:24px;">
          <label style="display:block;margin-bottom:8px;font-weight:500;">Password</label>
          <input name="password" type="password" placeholder="Masukkan password" class="form-control" required/>
        </div>
        <button class="btn-cta" type="submit" style="width:100%;padding:12px;font-weight:600;">Login</button>
      </form>

      <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--color-hairline);">
        <p style="margin:8px 0;font-size:13px;color:var(--color-muted);">
          <a href="forgot-password.php" style="color:var(--color-primary);text-decoration:none;">Lupa password?</a>
        </p>
        <p style="margin:8px 0;font-size:13px;color:var(--color-muted);">
          Belum punya akun? <a href="register.php" style="color:var(--color-primary);text-decoration:none;">Daftar di sini</a>
        </p>
      </div>
    </div>

    <p style="text-align:center;margin-top:24px;font-size:13px;color:var(--color-muted);">
      Demo: admin@bimbel.local / admin123
    </p>
  </main>

  <footer class="footer" style="margin-top:80px;">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>