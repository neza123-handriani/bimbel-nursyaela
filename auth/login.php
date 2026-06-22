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
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <main style="max-width:480px;margin:48px auto;padding:16px;">
    <h2>Login</h2>
    <?php if($errors): ?>
      <div style="color:red;">
        <?php foreach($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <div style="margin-bottom:8px;"><input name="email" type="email" placeholder="Email" class="form-control"/></div>
      <div style="margin-bottom:8px;"><input name="password" type="password" placeholder="Password" class="form-control"/></div>
      <button class="btn-cta" type="submit">Login</button>
    </form>
    <p style="margin-top:12px;">Belum punya akun? <a href="register.php">Daftar</a></p>
  </main>
</body>
</html>