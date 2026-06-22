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

// Get all users
$stmt = $pdo->prepare('SELECT * FROM users ORDER BY created_at DESC');
$stmt->execute();
$users = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        
        if (!$nama || !$email || !$password) {
            $errors[] = 'Semua field wajib diisi.';
        } else {
            // Check if email exists
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Email sudah terdaftar.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (nama, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
                $stmt->execute([$nama, $email, $hash, $role]);
                $message = 'User berhasil ditambahkan.';
                $action = 'list';
            }
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND role != "admin"');
        $stmt->execute([$id]);
        $message = 'User berhasil dihapus.';
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
  <title>Manajemen User</title>
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
        <a href="users.php" style="padding:12px;border-radius:4px;color:white;text-decoration:none;background:var(--color-primary);">👥 Manajemen User</a>
        <a href="tutors.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">🎓 Manajemen Tutor</a>
        <a href="kelas.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📚 Manajemen Kelas</a>
        <a href="materi.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">📖 Manajemen Materi</a>
        <a href="transactions.php" style="padding:12px;border-radius:4px;color:var(--color-on-dark);text-decoration:none;">💳 Transaksi</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main style="flex:1;padding:32px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <h1>Manajemen User</h1>
        <a href="users.php?action=add" class="btn-cta" style="padding:8px 16px;">+ Tambah User</a>
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
          <h2 style="margin-bottom:24px;">Tambah User Baru</h2>
          <form method="post">
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Nama</label>
              <input name="nama" type="text" placeholder="Nama lengkap" class="form-control" required>
            </div>
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Email</label>
              <input name="email" type="email" placeholder="Email" class="form-control" required>
            </div>
            <div style="margin-bottom:16px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Password</label>
              <input name="password" type="password" placeholder="Password" class="form-control" required>
            </div>
            <div style="margin-bottom:24px;">
              <label style="display:block;margin-bottom:8px;font-weight:500;">Role</label>
              <select name="role" class="form-control">
                <option value="user">User (Siswa)</option>
                <option value="tutor">Tutor</option>
              </select>
            </div>
            <div style="display:flex;gap:12px;">
              <button type="submit" class="btn-cta" style="padding:10px 20px;">Simpan</button>
              <a href="users.php" style="padding:10px 20px;border:1px solid var(--color-hairline);border-radius:4px;text-decoration:none;color:var(--color-body);">Batal</a>
            </div>
          </form>
        </div>
      <?php else: ?>
        <!-- Users List -->
        <div style="background:var(--color-surface-card);border-radius:var(--r-lg);overflow:hidden;">
          <table style="width:100%;border-collapse:collapse;">
            <thead style="background:var(--color-surface-cream-strong);">
              <tr>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Nama</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Email</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Role</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Bergabung</th>
                <th style="padding:16px;text-align:left;border-bottom:1px solid var(--color-hairline);">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($users as $u): ?>
                <tr style="border-bottom:1px solid var(--color-hairline);">
                  <td style="padding:16px;"><?php echo sanitizeInput($u['nama']); ?></td>
                  <td style="padding:16px;"><?php echo sanitizeInput($u['email']); ?></td>
                  <td style="padding:16px;"><span style="background:var(--color-primary);color:white;padding:4px 8px;border-radius:3px;font-size:12px;"><?php echo strtoupper($u['role']); ?></span></td>
                  <td style="padding:16px;"><?php echo formatDate($u['created_at']); ?></td>
                  <td style="padding:16px;">
                    <?php if($u['role'] !== 'admin'): ?>
                      <a href="users.php?action=delete&id=<?php echo $u['id']; ?>" onclick="return confirm('Hapus user ini?')" style="color:var(--color-error);text-decoration:none;">Hapus</a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
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
