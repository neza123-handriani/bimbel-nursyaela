<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Bimbel — Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/design-tokens.css">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel</span></div>
    <nav>
      <a href="login.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Sign in</a>
      <a class="btn-cta" href="register.php">Try Bimbel</a>
    </nav>
  </header>

  <main class="container">
    <section class="hero">
      <div class="left">
        <h1>Belajar dengan tutor terbaik secara online</h1>
        <p>Akses materi, jadwal, dan kelas interaktif. Sistem pembayaran terintegrasi Midtrans/Xendit.</p>
        <div style="margin-top:16px;">
          <a class="btn-cta" href="user/kelas.php">Daftar Kelas</a>
          <a href="kelas.php" style="margin-left:12px;color:var(--color-primary)">Lihat Semua</a>
        </div>
      </div>
      <div class="right" style="flex:1 1 50%;display:flex;align-items:center;justify-content:center;">
        <div style="width:320px;height:220px;background:var(--color-surface-card);border-radius:var(--r-xl);display:flex;align-items:center;justify-content:center;">Hero Mockup</div>
      </div>
    </section>

    <section>
      <div class="feature-grid">
        <div class="feature-card"> <h3>Dashboard User</h3><p>Akses materi dan jadwal.</p></div>
        <div class="feature-card"> <h3>Manajemen Tutor</h3><p>Kelola profil dan kelas tutor.</p></div>
        <div class="feature-card"> <h3>Pembayaran Aman</h3><p>Integrasi Midtrans & Xendit.</p></div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>