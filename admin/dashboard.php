<?php
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /bimbel/auth/login.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <header class="top-nav"><div class="brand">Bimbel Admin</div><nav><a href="/bimbel/auth/logout.php" style="color:var(--color-on-dark-soft)">Logout</a></nav></header>
  <main style="max-width:1200px;margin:32px auto;padding:16px;">
    <h1>Admin Dashboard</h1>
    <p>Placeholder untuk manajemen user, tutor, kelas, transaksi.</p>
  </main>
</body>
</html>