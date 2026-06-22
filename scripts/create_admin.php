<?php
// scripts/create_admin.php
// Usage (CLI): php create_admin.php admin@bimbel.local password123
// Or open in browser and submit form (not recommended on public servers)

if (php_sapi_name() === 'cli') {
    $email = $argv[1] ?? null;
    $password = $argv[2] ?? null;
    if (!$email) $email = readline("Admin email: ");
    if (!$password) $password = readline("Password: ");

    if (!$email || !$password) {
        fwrite(STDERR, "Email and password are required.\n");
        exit(1);
    }

    require __DIR__ . '/../inc/db.php';
    /** @var PDO $pdo */
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        fwrite(STDOUT, "A user with that email already exists.\n");
        exit(0);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (nama, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
    $stmt->execute(['Admin', $email, $hash, 'admin']);
    fwrite(STDOUT, "Admin user created: {$email}\n");
    exit(0);
}

// If accessed via web, show a small form (for convenience on local dev only)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $pdo = require __DIR__ . '/../inc/db.php';
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (nama, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute(['Admin', $email, $hash, 'admin']);
            $success = 'Admin berhasil dibuat: ' . htmlspecialchars($email);
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Admin</title>
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <main style="max-width:480px;margin:48px auto;padding:16px;">
    <h2>Create Admin (Local only)</h2>
    <?php if(!empty($error)): ?><div style="color:red"><?php echo $error;?></div><?php endif; ?>
    <?php if(!empty($success)): ?><div style="color:green"><?php echo $success;?></div><?php endif; ?>
    <form method="post">
      <div style="margin-bottom:8px;"><input name="email" type="email" placeholder="Email" class="form-control"/></div>
      <div style="margin-bottom:8px;"><input name="password" type="password" placeholder="Password" class="form-control"/></div>
      <button class="btn-cta" type="submit">Create Admin</button>
    </form>
    <p style="margin-top:12px;">Run via CLI: <code>php scripts/create_admin.php admin@bimbel.local password</code></p>
  </main>
</body>
</html>