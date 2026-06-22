<?php
session_start();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Tentang Kami</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/design-tokens.css">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel</span></div>
    <nav>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="user/dashboard.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Dashboard</a>
        <a href="auth/logout.php" style="color:var(--color-ink);text-decoration:none">Logout</a>
      <?php else: ?>
        <a href="auth/login.php" style="margin-right:12px;color:var(--color-ink);text-decoration:none">Sign in</a>
        <a class="btn-cta" href="auth/register.php">Daftar</a>
      <?php endif; ?>
    </nav>
  </header>

  <main class="container" style="margin-top:60px;margin-bottom:60px;">
    <h1 style="margin-bottom:32px;">Tentang Bimbel</h1>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:40px;">
      <div>
        <h2 style="margin-top:0;margin-bottom:16px;">Kami adalah solusi pembelajaran online terpercaya</h2>
        <p style="color:var(--color-muted);line-height:1.6;">
          Bimbel.id adalah platform pembelajaran online yang menghubungkan siswa dengan tutor profesional. 
          Kami menyediakan kelas interaktif, materi berkualitas tinggi, dan sistem pembayaran yang aman.
        </p>
        
        <h3 style="margin-top:32px;margin-bottom:16px;">Visi Kami</h3>
        <p style="color:var(--color-muted);line-height:1.6;">
          Menjadi platform pembelajaran online terdepan di Indonesia yang memberdayakan siswa untuk mencapai 
          potensi akademik maksimal mereka melalui akses ke tutor berkualitas dan materi pembelajaran yang 
          disesuaikan dengan kebutuhan individu.
        </p>
        
        <h3 style="margin-top:32px;margin-bottom:16px;">Misi Kami</h3>
        <ul style="color:var(--color-muted);line-height:1.8;margin-left:20px;">
          <li>Menyediakan akses pendidikan berkualitas tinggi yang terjangkau bagi semua orang</li>
          <li>Menghubungkan siswa dengan tutor berpengalaman dan terverifikasi</li>
          <li>Menggunakan teknologi terkini untuk meningkatkan pengalaman belajar</li>
          <li>Membangun komunitas pembelajaran yang suportif dan inklusif</li>
        </ul>

        <h3 style="margin-top:32px;margin-bottom:16px;">Mengapa Memilih Kami?</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div style="background:var(--color-surface-card);padding:16px;border-radius:var(--r-md);">
            <h4 style="margin-top:0;">✓ Tutor Profesional</h4>
            <p style="font-size:13px;color:var(--color-muted);">Semua tutor diverifikasi dan berpengalaman</p>
          </div>
          <div style="background:var(--color-surface-card);padding:16px;border-radius:var(--r-md);">
            <h4 style="margin-top:0;">✓ Pembelajaran Fleksibel</h4>
            <p style="font-size:13px;color:var(--color-muted);">Belajar kapan saja dan dimana saja</p>
          </div>
          <div style="background:var(--color-surface-card);padding:16px;border-radius:var(--r-md);">
            <h4 style="margin-top:0;">✓ Harga Terjangkau</h4>
            <p style="font-size:13px;color:var(--color-muted);">Paket pembelajaran dengan harga kompetitif</p>
          </div>
          <div style="background:var(--color-surface-card);padding:16px;border-radius:var(--r-md);">
            <h4 style="margin-top:0;">✓ Pembayaran Aman</h4>
            <p style="font-size:13px;color:var(--color-muted);">Integrasi dengan Midtrans dan Xendit</p>
          </div>
        </div>
      </div>

      <aside style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);height:fit-content;position:sticky;top:20px;">
        <h3 style="margin-top:0;">Statistik Kami</h3>
        <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--color-hairline);">
          <div style="font-size:32px;font-weight:bold;color:var(--color-primary);">1000+</div>
          <div style="font-size:13px;color:var(--color-muted);">Siswa Aktif</div>
        </div>
        <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--color-hairline);">
          <div style="font-size:32px;font-weight:bold;color:var(--color-primary);">50+</div>
          <div style="font-size:13px;color:var(--color-muted);">Tutor Profesional</div>
        </div>
        <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--color-hairline);">
          <div style="font-size:32px;font-weight:bold;color:var(--color-primary);">100+</div>
          <div style="font-size:13px;color:var(--color-muted);">Kelas Tersedia</div>
        </div>
        <div>
          <div style="font-size:32px;font-weight:bold;color:var(--color-primary);">4.8★</div>
          <div style="font-size:13px;color:var(--color-muted);">Rating Rata-rata</div>
        </div>
        
        <a href="kelas.php" class="btn-cta" style="display:block;width:100%;text-align:center;margin-top:24px;padding:12px;">Jelajahi Kelas</a>
      </aside>
    </div>
  </main>

  <footer class="footer">
    <div class="container">&copy; 2026 Bimbel — All rights reserved</div>
  </footer>
</body>
</html>
