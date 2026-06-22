<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Bimbel — Platform Pembelajaran Online</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/design-tokens.css">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel</span></div>
    <nav>
      <a href="tentang.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Tentang</a>
      <a href="kelas.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Kelas</a>
      <a href="auth/login.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Sign in</a>
      <a class="btn-cta" href="auth/register.php">Daftar Gratis</a>
    </nav>
  </header>

  <main class="container">
    <section class="hero">
      <div class="left">
        <h1>Belajar dengan tutor terbaik secara online</h1>
        <p style="font-size:18px;color:var(--color-body);line-height:1.6;">Akses ribuan kelas berkualitas, belajar dari tutor profesional, dan tingkatkan kemampuan akademik Anda dengan fleksibel kapan saja dan dimana saja.</p>
        <div style="margin-top:24px;display:flex;gap:12px;">
          <a class="btn-cta" href="kelas.php" style="padding:12px 24px;">Jelajahi Kelas</a>
          <a href="tentang.php" style="padding:12px 24px;border:2px solid var(--color-primary);border-radius:var(--r-md);text-decoration:none;color:var(--color-primary);font-weight:600;">Pelajari Lebih Lanjut</a>
        </div>
      </div>
      <div class="right" style="flex:1 1 50%;display:flex;align-items:center;justify-content:center;">
        <div style="width:100%;max-width:400px;height:300px;background:linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent-teal) 100%);border-radius:var(--r-xl);display:flex;align-items:center;justify-content:center;color:white;font-size:48px;box-shadow:0 10px 40px rgba(0,0,0,0.1);">
          📚
        </div>
      </div>
    </section>

    <section style="margin-top:100px;margin-bottom:100px;">
      <h2 style="text-align:center;margin-bottom:60px;font-family:var(--font-display);font-size:36px;">Mengapa Memilih Bimbel?</h2>
      <div class="feature-grid">
        <div class="feature-card">
          <div style="font-size:32px;margin-bottom:12px;">👨‍🏫</div>
          <h3>Tutor Profesional</h3>
          <p style="color:var(--color-muted);">Semua tutor kami telah terverifikasi dan memiliki pengalaman mengajar yang luas di berbagai bidang studi.</p>
        </div>
        <div class="feature-card">
          <div style="font-size:32px;margin-bottom:12px;">⏰</div>
          <h3>Fleksibel & Terjangkau</h3>
          <p style="color:var(--color-muted);">Pilih jadwal kelas sesuai kenyamanan Anda dengan harga yang terjangkau untuk semua kalangan.</p>
        </div>
        <div class="feature-card">
          <div style="font-size:32px;margin-bottom:12px;">💳</div>
          <h3>Pembayaran Aman</h3>
          <p style="color:var(--color-muted);">Sistem pembayaran terintegrasi dengan Midtrans dan Xendit untuk keamanan transaksi Anda.</p>
        </div>
      </div>
    </section>

    <section style="background:var(--color-surface-card);padding:60px;border-radius:var(--r-lg);text-align:center;margin-bottom:100px;">
      <h2 style="margin-bottom:16px;font-family:var(--font-display);font-size:32px;">Siap untuk Belajar?</h2>
      <p style="color:var(--color-muted);margin-bottom:24px;max-width:600px;margin-left:auto;margin-right:auto;">Bergabunglah dengan ribuan siswa lainnya yang telah meningkatkan prestasi akademik mereka bersama Bimbel.</p>
      <a class="btn-cta" href="auth/register.php" style="padding:14px 32px;font-size:16px;">Mulai Sekarang</a>
    </section>

    <section style="margin-bottom:100px;">
      <h2 style="text-align:center;margin-bottom:60px;font-family:var(--font-display);font-size:36px;">Paket Kelas Populer</h2>
      <?php
        $pdo = require __DIR__ . '/inc/db.php';
        $stmt = $pdo->prepare('SELECT * FROM kelas ORDER BY id DESC LIMIT 3');
        $stmt->execute();
        $popular = $stmt->fetchAll();
      ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:24px;">
        <?php foreach($popular as $k): ?>
          <div style="border:1px solid var(--color-hairline);border-radius:var(--r-lg);padding:24px;background:var(--color-surface-card);transition:transform 200ms ease,box-shadow 200ms ease;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
            <h3 style="margin-bottom:8px;font-size:18px;"><?php echo htmlspecialchars($k['nama_kelas']); ?></h3>
            <p style="color:var(--color-muted);font-size:14px;margin-bottom:16px;"><?php echo htmlspecialchars(substr($k['deskripsi'] ?? '', 0, 80)); ?>...</p>
            <div style="background:var(--color-surface-cream-strong);padding:12px;border-radius:4px;margin-bottom:16px;text-align:center;">
              <div style="font-size:24px;font-weight:bold;color:var(--color-primary);">Rp <?php echo number_format($k['harga'], 0, ',', '.'); ?></div>
            </div>
            <a href="kelas-detail.php?id=<?php echo $k['id']; ?>" class="btn-cta" style="display:block;text-align:center;padding:10px;">Lihat Detail</a>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:40px;margin-bottom:40px;">
        <div>
          <h4 style="color:white;margin-bottom:16px;">Bimbel</h4>
          <p style="font-size:13px;margin:0;">Platform pembelajaran online yang menghubungkan siswa dengan tutor profesional.</p>
        </div>
        <div>
          <h4 style="color:white;margin-bottom:16px;">Navigasi</h4>
          <ul style="list-style:none;padding:0;margin:0;">
            <li style="margin-bottom:8px;"><a href="/" style="color:var(--color-on-dark-soft);text-decoration:none;font-size:13px;">Home</a></li>
            <li style="margin-bottom:8px;"><a href="kelas.php" style="color:var(--color-on-dark-soft);text-decoration:none;font-size:13px;">Daftar Kelas</a></li>
            <li style="margin-bottom:8px;"><a href="tentang.php" style="color:var(--color-on-dark-soft);text-decoration:none;font-size:13px;">Tentang Kami</a></li>
          </ul>
        </div>
        <div>
          <h4 style="color:white;margin-bottom:16px;">Akun</h4>
          <ul style="list-style:none;padding:0;margin:0;">
            <li style="margin-bottom:8px;"><a href="auth/login.php" style="color:var(--color-on-dark-soft);text-decoration:none;font-size:13px;">Login</a></li>
            <li style="margin-bottom:8px;"><a href="auth/register.php" style="color:var(--color-on-dark-soft);text-decoration:none;font-size:13px;">Daftar</a></li>
            <li style="margin-bottom:8px;"><a href="auth/forgot-password.php" style="color:var(--color-on-dark-soft);text-decoration:none;font-size:13px;">Lupa Password</a></li>
          </ul>
        </div>
      </div>
      <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:24px;text-align:center;font-size:13px;">
        &copy; 2026 Bimbel. All rights reserved.
      </div>
    </div>
  </footer>
</body>
</html>