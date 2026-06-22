<?php
session_start();
$pdo = require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/functions.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /bimbel/auth/login.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? '';
$message = '';
$errors = [];

// Get all tutors
$stmt = $pdo->prepare('SELECT * FROM tutors ORDER BY nama');
$stmt->execute();
$tutors = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $spesialisasi = trim($_POST['spesialisasi'] ?? '');
        
        if (!$nama || !$email) {
            $errors[] = 'Nama dan email wajib diisi.';
        } else {
            // Check if email exists
            $stmt = $pdo->prepare('SELECT id FROM tutors WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Email tutor sudah terdaftar.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO tutors (nama, email, spesialisasi) VALUES (?, ?, ?)');
                $stmt->execute([$nama, $email, $spesialisasi]);
                $message = 'Tutor berhasil ditambahkan.';
                $action = 'list';
            }
        }
    } elseif ($action === 'delete' && $id) {
        // Check if tutor has classes
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM kelas WHERE tutor_id = ?');
        $stmt->execute([$id]);
        if ($stmt->fetch()['count'] > 0) {
            $errors[] = 'Tidak dapat menghapus tutor yang memiliki kelas.';
        } else {
            $stmt = $pdo->prepare('DELETE FROM tutors WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Tutor berhasil dihapus.';
            $action = 'list';
        }
    }
}

if ($action !== 'add') {
    $action = 'list';
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manajemen Tutor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/bimbel/assets/css/design-tokens.css">
  <link rel="stylesheet" href="/bimbel/assets/css/styles.css">
</head>
<body>
  <header class="top-nav">
    <div class="brand"><span style="width:20px;height:20px;background:var(--color-ink);border-radius:3px;display:inline-block"></span><span>Bimbel Admin</span></div>
    <nav>
      <a href="/bimbel/auth/logout.php" style="color:var(--color-on-dark-soft)">Logout</a>
    </nav>
  </header>

  <div style="display:flex;min-height:calc(100vh - 60px);">
    <!-- Sidebar -->
    <aside style="width:250px;background:var(--color-surface-dark-soft);padding:24px;border-right:1px solid var(--color-hairline);color:var(--color-on-dark);">
      <nav style="display:flex;flex-direction:column;gap:8px;">
        <a href="dashboard.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📊 Dashboard</a>
        <a href="users.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">👥 Manajemen User</a>
        <a href="tutors.php" style="padding:12px;border-radius:4px;color:white;text-decoration:none;background:var(--color-primary);">🎓 Manajemen Tutor</a>
        <a href="kelas.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📚 Manajemen Kelas</a>
        <a href="materi.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📖 Manajemen Materi</a>
        <a href="transactions.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">💳 Transaksi</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main style="flex:1;padding:32px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <h1>Manajemen Tutor</h1>
        <a href="tutors.php?action=add" class="btn-cta" style="padding:8px 16px;">+ Tambah Tutor</a>
      </div>

      <?php if($message): ?>
        <div style="background:var(--color-success);color:white;padding:16px;border-radius:4px;margin-bottom:24px;">
          ✓ <?php echo $message; ?>
        </div>
      <?php endif; ?>

      <?php if($errors): ?>
        <div style="background:var(--color-error);color:white;padding:16px;border-radius:4px;margin-bottom:24px;">
          <?php foreach($errors as $e) echo '<div>' . $e . '</div>'; ?>
        </div>
      <?php endif; ?>

      <?php if($action === 'add'): ?>
        <!-- Add Form -->
        <div style="background:var(--color-surface-card);padding:24px;border-radius:var(--r-lg);max-width:600px;">
          <h2 style="margin-bottom:24px;">Tambah Tutor Baru</h2>
          <form method="post">
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Nama</label>
              <input name="nama" type="text" placeholder="Nama tutor" class="form-control" required>
            </div>
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Email</label>
              <input name="email" type="email" placeholder="Email" class="form-control" required>
            </div>
            <div style="margin-bottom:24px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Spesialisasi</label>
              <input name="spesialisasi" type="text" placeholder="Contoh: Matematika, Bahasa Inggris" class="form-control">
            </div>
            <div style="display:flex;gap:12px;">
              <button type="submit" class="btn-cta" style="padding:10px 20px;">Simpan</button>
              <a href="tutors.php" style="padding:10px 20px;border:1px solid var(--color-hairline);border-radius:4px;text-decoration:none;color:var(--color-body);">Batal</a>
            </div>
          </form>
        </div>
      <?php else: ?>
        <!-- Tutors List -->
        <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
          <table style="width:100%;border-collapse:collapse;">
            <thead style="background:var(--color-surface-cream-strong);">
              <tr>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Nama</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Email</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Spesialisasi</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($tutors as $t): ?>
                <tr style="border-bottom:1px solid var(--color-hairline);">
                  <td style="padding:16px;"><?php echo sanitizeInput($t['nama']); ?></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($t['email']); ?></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($t['spesialisasi']); ?></td>
                  <td style="padding:16px;">
                    <a href="tutors.php?action=delete&id=<?php echo $t['id']; ?>" onclick="return confirm('Hapus tutor ini?')" style="color:var(--color-error);text-decoration:none;">Hapus</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
