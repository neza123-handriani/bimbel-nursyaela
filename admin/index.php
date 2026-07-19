<?php session_start(); 

if(!isset($_SESSION['role'])){ header("Location: ../login.php"); 
exit; } 

include "../config/koneksi.php"; 

$jml_siswa = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM siswa")); 
$jml_guru = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM guru")); 
$jml_kelas = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM kelas")); 
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Segoe UI;
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

    .main {
        margin-left: 260px;
        width: calc(100% - 260px);
    }

    .topbar {
        background: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
    }

    .content {
        padding: 25px;
    }

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
        box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
    }

    .card h2 {
        font-size: 35px;
        color: #2563eb;
    }

    .card p {
        color: #64748b;
    }

    .table-box {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .badge {
        background: #fef3c7;
        color: #92400e;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="logo"> BIMBEL ONLINE </div>
            <div class="menu"> <a href="index.php"> <i class="fa fa-home"></i> Dashboard </a> <a href="pendaftaran.php">
                    <i class="fa fa-user-plus"></i> Data Pendaftaran </a> <a href="siswa.php"> <i
                        class="fa fa-users"></i> Data Siswa </a> <a href="guru.php"> <i
                        class="fa fa-chalkboard-user"></i> Data Guru </a> <a href="kelas.php"> <i
                        class="fa fa-school"></i> Kelas </a> <a href="jadwal.php"> <i class="fa fa-calendar"></i> Jadwal
                </a> <a href="materi.php"> <i class="fa fa-book"></i> Materi </a> <a href="tugas.php"> <i
                        class="fa fa-file"></i> Tugas </a> <a href="nilai.php"> <i class="fa fa-star"></i> Nilai </a> <a
                    href="pembayaran.php"> <i class="fa fa-money-bill"></i> Pembayaran </a> <a href="laporan.php"> <i
                        class="fa fa-chart-line"></i> Laporan </a> <a href="../logout.php"> <i
                        class="fa fa-sign-out"></i> Logout </a> </div>
        </div>
        <div class="main">
            <div class="topbar">
                <h2>Dashboard Admin</h2>
                <div> Admin </div>
            </div>
            <div class="content">
                <div class="cards">
                    <div class="card">
                        <h2><?= $jml_siswa ?></h2>
                        <p>Jumlah Siswa</p>
                    </div>
                    <div class="card">
                        <h2><?= $jml_guru ?></h2>
                        <p>Jumlah Guru</p>
                    </div>
                    <div class="card">
                        <h2><?= $jml_kelas ?></h2>
                        <p>Jumlah Kelas</p>
                    </div>
                    <div class="card">
                        <h2>Rp24JT</h2>
                        <p>Pembayaran</p>
                    </div>
                </div>
                <div class="table-box">
                    <h3>Pendaftaran Terbaru</h3> <br>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Program</th>
                            <th>Status</th>
                        </tr>
                        <?php $data = mysqli_query( $conn, "SELECT * FROM siswa ORDER BY id DESC LIMIT 10" ); 
                        $no=1; while($d=mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $d['nama_lengkap'] ?></td>
                            <td><?= $d['program'] ?></td>
                            <td> <span class="badge"> Pending </span> </td>
                        </tr> <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>