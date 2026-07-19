<?php

session_start();

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/*
|--------------------------------------------------------------------------
| SIMPAN JADWAL
|--------------------------------------------------------------------------
*/

if(isset($_POST['simpan'])){

    $kelas         = $_POST['kelas'];
    $mapel         = $_POST['mapel'];
    $guru          = $_POST['guru'];
    $hari          = $_POST['hari'];
    $jam_mulai     = $_POST['jam_mulai'];
    $jam_selesai   = $_POST['jam_selesai'];
    $ruang         = $_POST['ruang'];
    $jumlah_siswa  = $_POST['jumlah_siswa'];
    $status        = $_POST['status'];

    mysqli_query($conn,"
        INSERT INTO jadwal
        (
            kelas,
            mapel,
            guru,
            hari,
            jam_mulai,
            jam_selesai,
            ruang,
            jumlah_siswa,
            status
        )
        VALUES
        (
            '$kelas',
            '$mapel',
            '$guru',
            '$hari',
            '$jam_mulai',
            '$jam_selesai',
            '$ruang',
            '$jumlah_siswa',
            '$status'
        )
    ");

    header("Location: jadwal.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id = (int)$_GET['hapus'];

    mysqli_query(
        $conn,
        "DELETE FROM jadwal WHERE id='$id'"
    );

    header("Location: jadwal.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

$edit = [];

if(isset($_GET['edit'])){

    $id = (int)$_GET['edit'];

    $q = mysqli_query(
        $conn,
        "SELECT * FROM jadwal WHERE id='$id'"
    );

    $edit = mysqli_fetch_assoc($q);
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if(isset($_POST['update'])){

    $id            = $_POST['id'];
    $kelas         = $_POST['kelas'];
    $mapel         = $_POST['mapel'];
    $guru          = $_POST['guru'];
    $hari          = $_POST['hari'];
    $jam_mulai     = $_POST['jam_mulai'];
    $jam_selesai   = $_POST['jam_selesai'];
    $ruang         = $_POST['ruang'];
    $jumlah_siswa  = $_POST['jumlah_siswa'];
    $status        = $_POST['status'];

    mysqli_query($conn,"
        UPDATE jadwal SET
        kelas='$kelas',
        mapel='$mapel',
        guru='$guru',
        hari='$hari',
        jam_mulai='$jam_mulai',
        jam_selesai='$jam_selesai',
        ruang='$ruang',
        jumlah_siswa='$jumlah_siswa',
        status='$status'
        WHERE id='$id'
    ");

    header("Location: jadwal.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| STATISTIK JADWAL
|--------------------------------------------------------------------------
*/

$total_jadwal = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM jadwal"
    )
);

$total_kelas = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT DISTINCT kelas
         FROM jadwal"
    )
);

$total_mapel = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT DISTINCT mapel
         FROM jadwal"
    )
);

$total_guru = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT DISTINCT guru
         FROM jadwal"
    )
);

/*
|--------------------------------------------------------------------------
| FILTER JADWAL
|--------------------------------------------------------------------------
*/

$where = "WHERE 1=1";

/*
|--------------------------------------------------------------------------
| CARI
|--------------------------------------------------------------------------
*/

if(isset($_GET['cari']) && $_GET['cari'] != ''){

    $cari = mysqli_real_escape_string(
        $conn,
        $_GET['cari']
    );

    $where .= "
        AND (
            kelas LIKE '%$cari%'
            OR guru LIKE '%$cari%'
            OR mapel LIKE '%$cari%'
        )
    ";
}

/*
|--------------------------------------------------------------------------
| FILTER KELAS
|--------------------------------------------------------------------------
*/

if(isset($_GET['kelas']) && $_GET['kelas'] != ''){

    $kelas = mysqli_real_escape_string(
        $conn,
        $_GET['kelas']
    );

    $where .= "
        AND kelas='$kelas'
    ";
}

/*
|--------------------------------------------------------------------------
| FILTER GURU
|--------------------------------------------------------------------------
*/

if(isset($_GET['guru']) && $_GET['guru'] != ''){

    $guru = mysqli_real_escape_string(
        $conn,
        $_GET['guru']
    );

    $where .= "
        AND guru='$guru'
    ";
}

/*
|--------------------------------------------------------------------------
| FILTER HARI
|--------------------------------------------------------------------------
*/

if(isset($_GET['hari']) && $_GET['hari'] != ''){

    $hari = mysqli_real_escape_string(
        $conn,
        $_GET['hari']
    );

    $where .= "
        AND hari='$hari'
    ";
}

/*
|--------------------------------------------------------------------------
| FILTER MAPEL
|--------------------------------------------------------------------------
*/

if(isset($_GET['mapel']) && $_GET['mapel'] != ''){

    $mapel = mysqli_real_escape_string(
        $conn,
        $_GET['mapel']
    );

    $where .= "
        AND mapel='$mapel'
    ";
}

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$batas = 10;

$halaman = isset($_GET['hal'])
    ? (int)$_GET['hal']
    : 1;

$mulai = ($halaman - 1) * $batas;

$total_data = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT *
         FROM jadwal
         $where"
    )
);

$total_halaman = ceil(
    $total_data / $batas
);

/*
|--------------------------------------------------------------------------
| DATA JADWAL
|--------------------------------------------------------------------------
*/

$data_jadwal = mysqli_query(
    $conn,
    "SELECT *
     FROM jadwal
     $where
     ORDER BY id DESC
     LIMIT $mulai,$batas"
);

/*
|--------------------------------------------------------------------------
| DROPDOWN FILTER
|--------------------------------------------------------------------------
*/

$list_kelas = mysqli_query(
    $conn,
    "SELECT DISTINCT kelas
     FROM jadwal
     ORDER BY kelas"
);

$list_guru = mysqli_query(
    $conn,
    "SELECT DISTINCT guru
     FROM jadwal
     ORDER BY guru"
);

$list_mapel = mysqli_query(
    $conn,
    "SELECT DISTINCT mapel
     FROM jadwal
     ORDER BY mapel"
);

/*
|--------------------------------------------------------------------------
| DATA HARI
|--------------------------------------------------------------------------
*/

$hari_list = [
    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jumat',
    'Sabtu',
    'Minggu'
];
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Data Jadwal</title>

    <!-- FONT AWESOME -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- GOOGLE FONT -->

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f5f7fb;
}

a{
    text-decoration:none;
}

ul{
    list-style:none;
}

/*
|--------------------------------------------------------------------------
| LAYOUT
|--------------------------------------------------------------------------
*/

.wrapper{
    display:flex;
}

.sidebar{
    width:260px;
    height:100vh;
    background:#071d49;
    position:fixed;
    left:0;
    top:0;
    overflow-y:auto;
}

.main{
    margin-left:260px;
    width:calc(100% - 260px);
    min-height:100vh;
}

/*
|--------------------------------------------------------------------------
| SIDEBAR
|--------------------------------------------------------------------------
*/

.logo{
    padding:25px;
    border-bottom:1px solid rgba(255,255,255,.1);
}

.logo h2{
    color:white;
    font-size:24px;
    font-weight:700;
}

.logo p{
    color:#94a3b8;
    font-size:13px;
}

.menu{
    padding:15px 10px;
}

.menu a{
    display:flex;
    align-items:center;
    gap:12px;
    color:#cbd5e1;
    padding:14px 15px;
    border-radius:12px;
    margin-bottom:5px;
    transition:.3s;
}

.menu a:hover{
    background:#2563eb;
    color:white;
}

.menu .active{
    background:#2563eb;
    color:white;
}

/*
|--------------------------------------------------------------------------
| TOPBAR
|--------------------------------------------------------------------------
*/

.topbar{
    height:85px;
    background:#fff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.topbar-left{
    display:flex;
    align-items:center;
    gap:20px;
}

.menu-toggle{
    font-size:24px;
    color:#334155;
}

.topbar-title{
    font-size:20px;
    font-weight:600;
}

.topbar-right{
    display:flex;
    align-items:center;
    gap:20px;
}

.notif{
    position:relative;
}

.notif i{
    font-size:22px;
}

.notif-badge{
    width:20px;
    height:20px;
    border-radius:50%;
    background:red;
    color:white;
    position:absolute;
    top:-8px;
    right:-10px;
    font-size:11px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.admin-box{
    display:flex;
    align-items:center;
    gap:10px;
}

.admin-box img{
    width:45px;
    height:45px;
    border-radius:50%;
}

/*
|--------------------------------------------------------------------------
| CONTENT
|--------------------------------------------------------------------------
*/

.content{
    padding:25px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.page-title h1{
    font-size:36px;
    color:#0f172a;
}

.page-title p{
    color:#64748b;
    margin-top:5px;
}

.btn-tambah{
    background:#2563eb;
    color:white;
    padding:14px 22px;
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:10px;
}

.menu .active{
    background:#2563eb;
    color:#fff;
    box-shadow:0 4px 15px rgba(37,99,235,.35);
}

.menu .active i{
    color:white;
}

.admin-box{
    cursor:pointer;
}

.admin-box img{
    object-fit:cover;
    border:2px solid #e2e8f0;
}

.topbar-title{
    color:#0f172a;
}

.notif{
    cursor:pointer;
}

.notif i{
    color:#334155;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.page-title h1{
    font-size:34px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:5px;
}

.page-title p{
    font-size:14px;
    color:#64748b;
}

.btn-tambah{
    background:#2563eb;
    color:white;
    padding:14px 22px;
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:10px;
    font-size:14px;
    font-weight:500;
    transition:.3s;
}

.btn-tambah:hover{
    background:#1d4ed8;
}

</style>

</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
     <div class="sidebar">

    <!-- LOGO -->

    <div class="logo">

        <h2>BIMBEL ONLINE</h2>

        <p>Sistem Bimbingan Belajar</p>

    </div>

    <!-- MENU -->

    <div class="menu">

        <a href="index.php">
            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        <a href="pendaftaran.php">
            <i class="fa-regular fa-file-lines"></i>
            Data Pendaftaran
        </a>

        <a href="siswa.php">
            <i class="fa-solid fa-users"></i>
            Data Siswa
        </a>

        <a href="guru.php">
            <i class="fa-solid fa-user-tie"></i>
            Data Guru
        </a>

        <a href="kelas.php">
            <i class="fa-solid fa-book-open"></i>
            Kelas
        </a>

        <!-- MENU AKTIF -->

        <a href="jadwal.php" class="active">
            <i class="fa-regular fa-calendar-days"></i>
            Jadwal
        </a>

        <a href="materi.php">
            <i class="fa-solid fa-book"></i>
            Materi
        </a>

        <a href="tugas.php">
            <i class="fa-regular fa-file"></i>
            Tugas
        </a>

        <a href="nilai.php">
            <i class="fa-solid fa-square-poll-vertical"></i>
            Nilai
        </a>

        <a href="pembayaran.php">
            <i class="fa-solid fa-wallet"></i>
            Pembayaran
        </a>

        <a href="notifikasi.php">
            <i class="fa-regular fa-bell"></i>
            Notifikasi
        </a>

        <a href="laporan.php">
            <i class="fa-solid fa-chart-line"></i>
            Laporan
        </a>

        <a href="pengaturan.php">
            <i class="fa-solid fa-gear"></i>
            Pengaturan
        </a>

        <br>

        <a href="../logout.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</div>

    <!-- MAIN -->
     <div class="main">

    <!-- TOPBAR -->

    <div class="topbar">

        <div class="topbar-left">

            <i class="fa-solid fa-bars menu-toggle"></i>

            <div class="topbar-title">
                Data Jadwal
            </div>

        </div>

        <div class="topbar-right">

            <div class="notif">

                <i class="fa-regular fa-bell"></i>

                <div class="notif-badge">
                    5
                </div>

            </div>

            <div class="admin-box">

                <img src="https://ui-avatars.com/api/?name=Admin">

                <div>

                    <div
                    style="
                    font-weight:600;
                    color:#0f172a;
                    ">
                        Admin Bimbel
                    </div>

                    <div
                    style="
                    font-size:12px;
                    color:#64748b;
                    ">
                        Administrator
                    </div>

                </div>

                <i
                class="fa-solid fa-chevron-down"
                style="color:#64748b;">
                </i>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
     <div class="content">

    <!-- HEADER HALAMAN -->
     <div class="page-header">

    <div class="page-title">

        <h1>Data Jadwal</h1>

        <p>
            Kelola jadwal pembelajaran, atur kelas, guru dan mata pelajaran.
        </p>

    </div>

    <a href="?tambah=1" class="btn-tambah">

        <i class="fa-solid fa-plus"></i>

        Tambah Jadwal

    </a>

</div>

<!-- CARD STATISTIK -->

</div>

</div>

</div>

</body>

</html>