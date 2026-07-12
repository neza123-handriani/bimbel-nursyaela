<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

/* ==========================
   STATISTIK
========================== */

$totalSiswa = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM siswa")
);

$laki = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM siswa WHERE jenis_kelamin='L'"
    )
);

$perempuan = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM siswa WHERE jenis_kelamin='P'"
    )
);

$kelasAktif = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT DISTINCT kelas FROM siswa"
    )
);

/* ==========================
   FILTER
========================== */

$cari = $_GET['cari'] ?? '';

$data = mysqli_query(
    $conn,
    "SELECT * FROM siswa
     WHERE nama_lengkap LIKE '%$cari%'
     ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: #f5f7fb;
    }

    .wrapper {
        display: flex;
    }

    .sidebar {
        width: 260px;
        background: #071d49;
        height: 100vh;
        position: fixed;
        color: white;
    }

    .logo {
        padding: 25px;
        font-size: 25px;
        font-weight: bold;
        border-bottom: 1px solid rgba(255, 255, 255, .1);
    }

    .menu a {
        display: block;
        padding: 15px 25px;
        color: white;
        text-decoration: none;
        transition: .3s;
    }

    .menu a:hover {
        background: #2563eb;
    }

    /* CONTENT */

    .main {
        margin-left: 260px;
        width: calc(100% - 260px);
    }

    .topbar {
        background: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
    }

    .content {
        padding: 25px;
    }

    .page-title {
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* CARD */

    .cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
    }

    .card h2 {
        font-size: 35px;
        margin-top: 10px;
        color: #0f172a;
    }

    .card p {
        color: #64748b;
    }

    /* FILTER */

    .filter-box {
        background: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
    }

    .filter-box form {
        display: flex;
        gap: 10px;
    }

    .filter-box input {
        flex: 1;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 10px;
    }

    .btn {
        background: #2563eb;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
    }

    /* TABLE */

    .table-box {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        background: #f8fafc;
        padding: 15px;
        text-align: left;
    }

    table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-active {
        background: #dcfce7;
        color: #16a34a;
    }

    .action {
        display: flex;
        gap: 8px;
    }

    .action a {
        width: 35px;
        height: 35px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 8px;
        text-decoration: none;
    }

    .view {
        background: #dbeafe;
        color: #2563eb;
    }

    .edit {
        background: #fef3c7;
        color: #ca8a04;
    }

    .delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .header-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .add-btn {
        background: #2563eb;
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
    }
    </style>
</head>

<body>

    <div class="wrapper">

        <div class="sidebar">

            <div class="logo">
                BIMBEL ONLINE
            </div>

            <div class="menu">

                <a href="index.php">
                    <i class="fa fa-home"></i>
                    Dashboard
                </a>

                <a href="pendaftaran.php">
                    <i class="fa fa-user-plus"></i>
                    Data Pendaftaran
                </a>

                <a href="siswa.php">
                    <i class="fa fa-users"></i>
                    Data Siswa
                </a>

                <a href="guru.php">
                    <i class="fa fa-chalkboard-user"></i>
                    Data Guru
                </a>

                <a href="kelas.php">
                    <i class="fa fa-school"></i>
                    Kelas
                </a>

                <a href="jadwal.php">
                    <i class="fa fa-calendar"></i>
                    Jadwal
                </a>

                <a href="materi.php">
                    <i class="fa fa-book"></i>
                    Materi
                </a>

                <a href="tugas.php">
                    <i class="fa fa-file"></i>
                    Tugas
                </a>

                <a href="nilai.php">
                    <i class="fa fa-star"></i>
                    Nilai
                </a>

                <a href="pembayaran.php">
                    <i class="fa fa-money-bill"></i>
                    Pembayaran
                </a>

                <a href="laporan.php">
                    <i class="fa fa-chart-line"></i>
                    Laporan
                </a>

                <a href="../logout.php">
                    <i class="fa fa-sign-out"></i>
                    Logout
                </a>

            </div>

        </div>

        <!-- MAIN -->

        <div class="main">

            <div class="topbar">

                <h2>Data Siswa</h2>

                <div>
                    <?= $_SESSION['nama']; ?>
                </div>

            </div>

            <div class="content">

                <div class="header-action">

                    <div>

                        <div class="page-title">
                            Data Siswa
                        </div>

                        <p>Kelola seluruh data siswa yang sudah terdaftar.</p>

                    </div>

                    <a href="tambah_siswa.php" class="add-btn">
                        <i class="fa fa-plus"></i> Tambah Siswa
                    </a>

                </div>

                <!-- STATISTIK -->

                <div class="cards">

                    <div class="card">
                        <p>Total Siswa</p>
                        <h2><?= $totalSiswa ?></h2>
                    </div>

                    <div class="card">
                        <p>Siswa Laki-Laki</p>
                        <h2><?= $laki ?></h2>
                    </div>

                    <div class="card">
                        <p>Siswa Perempuan</p>
                        <h2><?= $perempuan ?></h2>
                    </div>

                    <div class="card">
                        <p>Kelas Aktif</p>
                        <h2><?= $kelasAktif ?></h2>
                    </div>

                </div>

                <!-- FILTER -->

                <div class="filter-box">

                    <form>

                        <input type="text" name="cari" placeholder="Cari siswa..." value="<?= $cari ?>">

                        <button class="btn">
                            Cari
                        </button>

                    </form>

                </div>

                <!-- TABLE -->

                <div class="table-box">

                    <table>

                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                        <?php
$no=1;
while($row=mysqli_fetch_assoc($data)):
?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>

                                <?php if(!empty($row['foto'])): ?>

                                <img src="../uploads/<?= $row['foto']; ?>" class="avatar">

                                <?php else: ?>

                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama_lengkap']); ?>"
                                    class="avatar">

                                <?php endif; ?>

                            </td>

                            <td><?= $row['nisn'] ?? '-' ?></td>

                            <td><?= $row['nama_lengkap'] ?></td>

                            <td><?= $row['kelas'] ?? '-' ?></td>

                            <td><?= $row['program'] ?></td>

                            <td>
                                <span class="badge badge-active">
                                    <?= $row['status'] ?>
                                </span>
                            </td>

                            <td>

                                <div class="action">

                                    <a href="detail_siswa.php?id=<?= $row['id'] ?>" class="view">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="edit_siswa.php?id=<?= $row['id'] ?>" class="edit">
                                        <i class="fa fa-pen"></i>
                                    </a>

                                    <a href="hapus_siswa.php?id=<?= $row['id'] ?>" class="delete"
                                        onclick="return confirm('Hapus data siswa?')">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </div>

                            </td>

                        </tr>

                        <?php endwhile; ?>

                    </table>

                </div>

            </div>
        </div>

    </div>

</body>

</html>