<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

/* =========================
   PROSES TERIMA / TOLAK
========================= */

if(isset($_GET['terima'])){

    $id = $_GET['terima'];

    mysqli_query(
        $conn,
        "UPDATE daftar_siswa
         SET status='diterima'
         WHERE id='$id'"
    );

    header("Location: pendaftaran.php");
    exit;
}

if(isset($_GET['tolak'])){

    $id = $_GET['tolak'];

    mysqli_query(
        $conn,
        "UPDATE daftar_siswa
         SET status='ditolak'
         WHERE id='$id'"
    );

    header("Location: pendaftaran.php");
    exit;
}

/* =========================
   STATISTIK
========================= */

$pending = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM daftar_siswa
 WHERE status='pending'"
));

$diterima = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM daftar_siswa
 WHERE status='diterima'"
));

$ditolak = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM daftar_siswa
 WHERE status='ditolak'"
));

$total = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM daftar_siswa"
));

/* =========================
   SEARCH
========================= */

$cari = "";

if(isset($_GET['cari'])){
    $cari = $_GET['cari'];
}

$data = mysqli_query(
$conn,
"SELECT *
 FROM daftar_siswa
 WHERE nama_lengkap LIKE '%$cari%'
 ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Pendaftaran</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI';
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

    .title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card h2 {
        font-size: 34px;
    }

    .pending {
        color: #f59e0b;
    }

    .success {
        color: #10b981;
    }

    .danger {
        color: #ef4444;
    }

    .primary {
        color: #2563eb;
    }

    /* SEARCH */

    .search-box {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        margin-bottom: 20px;
    }

    .search-box form {
        display: flex;
        gap: 10px;
    }

    .search-box input {
        flex: 1;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 10px;
    }

    .btn {
        padding: 12px 20px;
        background: #2563eb;
        color: white;
        border: none;
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

    .badge {
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge.pending {
        background: #fff7ed;
        color: #f59e0b;
    }

    .badge.success {
        background: #ecfdf5;
        color: #10b981;
    }

    .badge.danger {
        background: #fef2f2;
        color: #ef4444;
    }

    .action {
        display: flex;
        gap: 8px;
    }

    .icon-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        color: white;
    }

    .view {
        background: #3b82f6;
    }

    .accept {
        background: #10b981;
    }

    .reject {
        background: #ef4444;
    }

    .top-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .add-btn {
        padding: 12px 20px;
        background: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 10px;
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

        <div class="main">

            <div class="topbar">

                <h2>Data Pendaftaran Siswa</h2>

                <div>
                    <?= $_SESSION['nama']; ?>
                </div>

            </div>

            <div class="content">

                <div class="top-title">

                    <div class="title">
                        Data Pendaftaran
                    </div>

                    <a href="../pendaftaran.php" class="add-btn">
                        <i class="fa fa-plus"></i>
                        Tambah Pendaftaran
                    </a>

                </div>

                <!-- CARD -->

                <div class="cards">

                    <div class="card">
                        <div>
                            <p>Menunggu</p>
                            <h2 class="pending">
                                <?= $pending ?>
                            </h2>
                        </div>
                        <i class="fa fa-clock fa-2x pending"></i>
                    </div>

                    <div class="card">
                        <div>
                            <p>Diterima</p>
                            <h2 class="success">
                                <?= $diterima ?>
                            </h2>
                        </div>
                        <i class="fa fa-check-circle fa-2x success"></i>
                    </div>

                    <div class="card">
                        <div>
                            <p>Ditolak</p>
                            <h2 class="danger">
                                <?= $ditolak ?>
                            </h2>
                        </div>
                        <i class="fa fa-times-circle fa-2x danger"></i>
                    </div>

                    <div class="card">
                        <div>
                            <p>Total</p>
                            <h2 class="primary">
                                <?= $total ?>
                            </h2>
                        </div>
                        <i class="fa fa-users fa-2x primary"></i>
                    </div>

                </div>

                <!-- SEARCH -->

                <div class="search-box">

                    <form>

                        <input type="text" name="cari" placeholder="Cari nama siswa..." value="<?= $cari ?>">

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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Aksi</th>

                        </tr>

                        <?php
$no=1;

while($row =
mysqli_fetch_assoc($data)):
?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td><?= $row['nama_lengkap'] ?></td>

                            <td><?= $row['email'] ?></td>

                            <td><?= $row['program'] ?></td>

                            <td>

                                <?php
if($row['status']=="pending"){
echo "<span class='badge pending'>Menunggu</span>";
}

elseif($row['status']=="diterima"){
echo "<span class='badge success'>Diterima</span>";
}

else{
echo "<span class='badge danger'>Ditolak</span>";
}
?>

                            </td>

                            <td>

                                <div class="action">

                                    <button class="icon-btn view">
                                        <i class="fa fa-eye"></i>
                                    </button>

                                    <a href="?terima=<?= $row['id'] ?>">
                                        <button class="icon-btn accept">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </a>

                                    <a href="?tolak=<?= $row['id'] ?>">
                                        <button class="icon-btn reject">
                                            <i class="fa fa-times"></i>
                                        </button>
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