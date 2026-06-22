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

// Get tutors
$stmt = $pdo->prepare('SELECT * FROM tutors ORDER BY nama');
$stmt->execute();
$tutors = $stmt->fetchAll();

// Get all classes
$stmt = $pdo->prepare('
    SELECT k.*, t.nama as tutor_nama
    FROM kelas k
    LEFT JOIN tutors t ON k.tutor_id = t.id
    ORDER BY k.nama_kelas
');
$stmt->execute();
$kelas_list = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $nama_kelas = trim($_POST['nama_kelas'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $harga = floatval($_POST['harga'] ?? 0);
        $tutor_id = $_POST['tutor_id'] ?? null;
        
        if (!$nama_kelas || $harga <= 0) {
            $errors[] = 'Nama kelas dan harga wajib diisi dengan benar.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO kelas (nama_kelas, deskripsi, harga, tutor_id) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nama_kelas, $deskripsi, $harga, $tutor_id ?: null]);
            $message = 'Kelas berhasil ditambahkan.';
            $action = 'list';
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $pdo->prepare('DELETE FROM kelas WHERE id = ?');
        $stmt->execute([$id]);
        $message = 'Kelas berhasil dihapus.';
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
  <title>Manajemen Kelas</title>
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
        <a href="kelas.php" style="padding:12px;border-radius:4px;color:white;text-decoration:none;background:var(--color-primary);">📚 Manajemen Kelas</a>
        <a href="materi.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📖 Manajemen Materi</a>
        <a href="transactions.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">💳 Transaksi</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main style="flex:1;padding:32px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <h1>Manajemen Kelas</h1>
        <a href="kelas.php?action=add" class="btn-cta" style="padding:8px 16px;">+ Tambah Kelas</a>
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
          <h2 style="margin-bottom:24px;">Tambah Kelas Baru</h2>
          <form method="post">
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Nama Kelas</label>
              <input name="nama_kelas" type="text" placeholder="Nama kelas" class="form-control" required>
            </div>
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Deskripsi</label>
              <textarea name="deskripsi" placeholder="Deskripsi kelas" class="form-control" rows="4"></textarea>
            </div>
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Harga (Rp)</label>
              <input name="harga" type="number" placeholder="250000" step="1000" min="0" class="form-control" required>
            </div>
            <div style="margin-bottom:24px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Tutor</label>
              <select name="tutor_id" class="form-control">
                <option value="">-- Pilih Tutor --</option>
                <?php foreach($tutors as $t): ?>
                  <option value="<?php echo $t['id']; ?>"><?php echo sanitizeInput($t['nama']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div style="display:flex;gap:12px;">
              <button type="submit" class="btn-cta" style="padding:10px 20px;">Simpan</button>
              <a href="kelas.php" style="padding:10px 20px;border:1px solid var(--color-hairline);border-radius:4px;text-decoration:none;color:var(--color-body);">Batal</a>
            </div>
          </form>
        </div>
      <?php else: ?>
        <!-- Classes List -->
        <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
          <table style="width:100%;border-collapse:collapse;">
            <thead style="background:var(--color-surface-cream-strong);">
              <tr>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Nama Kelas</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Tutor</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Harga</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($kelas_list as $k): ?>
                <tr style="border-bottom:1px solid var(--color-hairline);">
                  <td style="padding:16px;"><?php echo sanitizeInput($k['nama_kelas']); ?></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($k['tutor_nama'] ?? '-'); ?></td>
                  <td style="padding:16px;"><?php echo formatRupiah($k['harga']); ?></td>
                  <td style="padding:16px;">
                    <a href="kelas.php?action=delete&id=<?php echo $k['id']; ?>" onclick="return confirm('Hapus kelas ini?')" style="color:var(--color-error);text-decoration:none;">Hapus</a>
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
