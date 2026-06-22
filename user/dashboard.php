<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: /bimbel/auth/login.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <header class="top-nav"><div class="brand">Bimbel</div><nav><a href="/bimbel/auth/logout.php">Logout</a></nav></header>
  <main style="max-width:1200px;margin:32px auto;padding:16px;">
    <h1>Dashboard User</h1>
    <p>Halo, ini dashboard siswa. Akses kelas, materi, jadwal, dan pembayaran.</p>
  </main>
</body>
</html>