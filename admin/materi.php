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

// Get all kelas
$stmt = $pdo->prepare('SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas');
$stmt->execute();
$kelas_list = $stmt->fetchAll();

// Get all materials
$stmt = $pdo->prepare('
    SELECT m.*, k.nama_kelas
    FROM materi m
    LEFT JOIN kelas k ON m.kelas_id = k.id
    ORDER BY k.nama_kelas, m.judul
');
$stmt->execute();
$materials = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $kelas_id = $_POST['kelas_id'] ?? '';
        $judul = trim($_POST['judul'] ?? '');
        
        if (!$kelas_id || !$judul) {
            $errors[] = 'Kelas dan judul materi wajib diisi.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO materi (kelas_id, judul, created_at) VALUES (?, ?, NOW())');
            $stmt->execute([$kelas_id, $judul]);
            $message = 'Materi berhasil ditambahkan.';
            $action = 'list';
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $pdo->prepare('DELETE FROM materi WHERE id = ?');
        $stmt->execute([$id]);
        $message = 'Materi berhasil dihapus.';
        $action = 'list';
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
  <title>Manajemen Materi</title>
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
        <a href="tutors.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">🎓 Manajemen Tutor</a>
        <a href="kelas.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📚 Manajemen Kelas</a>
        <a href="materi.php" style="padding:12px;border-radius:4px;color:white;text-decoration:none;background:var(--color-primary);">📖 Manajemen Materi</a>
        <a href="transactions.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">💳 Transaksi</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main style="flex:1;padding:32px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <h1>Manajemen Materi</h1>
        <a href="materi.php?action=add" class="btn-cta" style="padding:8px 16px;">+ Tambah Materi</a>
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
          <h2 style="margin-bottom:24px;">Tambah Materi Baru</h2>
          <form method="post">
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Kelas</label>
              <select name="kelas_id" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($kelas_list as $k): ?>
                  <option value="<?php echo $k['id']; ?>"><?php echo sanitizeInput($k['nama_kelas']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div style="margin-bottom:24px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Judul Materi</label>
              <input name="judul" type="text" placeholder="Judul materi pembelajaran" class="form-control" required>
            </div>
            <div style="display:flex;gap:12px;">
              <button type="submit" class="btn-cta" style="padding:10px 20px;">Simpan</button>
              <a href="materi.php" style="padding:10px 20px;border:1px solid var(--color-hairline);border-radius:4px;text-decoration:none;color:var(--color-body);">Batal</a>
            </div>
          </form>
        </div>
      <?php else: ?>
        <!-- Materials List -->
        <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
          <table style="width:100%;border-collapse:collapse;">
            <thead style="background:var(--color-surface-cream-strong);">
              <tr>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Kelas</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Judul Materi</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Ditambahkan</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($materials as $m): ?>
                <tr style="border-bottom:1px solid var(--color-hairline);">
                  <td style="padding:16px;"><?php echo sanitizeInput($m['nama_kelas']); ?></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($m['judul']); ?></td>
                  <td style="padding:16px;"><?php echo formatDate($m['created_at']); ?></td>
                  <td style="padding:16px;">
                    <a href="materi.php?action=delete&id=<?php echo $m['id']; ?>" onclick="return confirm('Hapus materi ini?')" style="color:var(--color-error);text-decoration:none;">Hapus</a>
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
