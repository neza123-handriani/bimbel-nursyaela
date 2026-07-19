<?php

session_start();

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/*
|--------------------------------------------------------------------------
| SIMPAN DATA
|--------------------------------------------------------------------------
*/

if(isset($_POST['simpan'])){

    $nama_kelas   = $_POST['nama_kelas'];
    $jenjang      = $_POST['jenjang'];
    $tingkat      = $_POST['tingkat'];
    $wali_kelas   = $_POST['wali_kelas'];
    $jumlah_siswa = $_POST['jumlah_siswa'];
    $status       = $_POST['status'];

    mysqli_query($conn,"
        INSERT INTO kelas
        (
            nama_kelas,
            jenjang,
            tingkat,
            wali_kelas,
            jumlah_siswa,
            status
        )
        VALUES
        (
            '$nama_kelas',
            '$jenjang',
            '$tingkat',
            '$wali_kelas',
            '$jumlah_siswa',
            '$status'
        )
    ");

    header("Location: kelas.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS DATA
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id = (int)$_GET['hapus'];

    mysqli_query(
        $conn,
        "DELETE FROM kelas WHERE id='$id'"
    );

    header("Location: kelas.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| EDIT DATA
|--------------------------------------------------------------------------
*/

$edit = [];

if(isset($_GET['edit'])){

    $id = (int)$_GET['edit'];

    $q = mysqli_query(
        $conn,
        "SELECT * FROM kelas WHERE id='$id'"
    );

    $edit = mysqli_fetch_assoc($q);
}

/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/

if(isset($_POST['update'])){

    $id            = $_POST['id'];
    $nama_kelas    = $_POST['nama_kelas'];
    $jenjang       = $_POST['jenjang'];
    $tingkat       = $_POST['tingkat'];
    $wali_kelas    = $_POST['wali_kelas'];
    $jumlah_siswa  = $_POST['jumlah_siswa'];
    $status        = $_POST['status'];

    mysqli_query($conn,"
        UPDATE kelas SET
        nama_kelas='$nama_kelas',
        jenjang='$jenjang',
        tingkat='$tingkat',
        wali_kelas='$wali_kelas',
        jumlah_siswa='$jumlah_siswa',
        status='$status'
        WHERE id='$id'
    ");

    header("Location: kelas.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

$total_kelas = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM kelas")
);

$total_aktif = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM kelas WHERE status='Aktif'"
    )
);

$total_siswa = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(jumlah_siswa) as total
         FROM kelas"
    )
);

$total_siswa = $total_siswa['total'] ?? 0;

$total_jenjang = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT DISTINCT jenjang FROM kelas"
    )
);

/*
|--------------------------------------------------------------------------
| FILTER DATA
|--------------------------------------------------------------------------
*/

$where = "WHERE 1=1";

if(isset($_GET['cari']) && $_GET['cari'] != ''){

    $cari = mysqli_real_escape_string(
        $conn,
        $_GET['cari']
    );

    $where .= "
        AND nama_kelas LIKE '%$cari%'
    ";
}

if(isset($_GET['jenjang']) && $_GET['jenjang'] != ''){

    $jenjang = mysqli_real_escape_string(
        $conn,
        $_GET['jenjang']
    );

    $where .= "
        AND jenjang='$jenjang'
    ";
}

if(isset($_GET['tingkat']) && $_GET['tingkat'] != ''){

    $tingkat = mysqli_real_escape_string(
        $conn,
        $_GET['tingkat']
    );

    $where .= "
        AND tingkat='$tingkat'
    ";
}

if(isset($_GET['wali_kelas']) && $_GET['wali_kelas'] != ''){

    $wali_kelas = mysqli_real_escape_string(
        $conn,
        $_GET['wali_kelas']
    );

    $where .= "
        AND wali_kelas='$wali_kelas'
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
        "SELECT * FROM kelas $where"
    )
);

$total_halaman = ceil(
    $total_data / $batas
);

/*
|--------------------------------------------------------------------------
| DATA KELAS
|--------------------------------------------------------------------------
*/

$data_kelas = mysqli_query(
    $conn,
    "SELECT *
     FROM kelas
     $where
     ORDER BY id DESC
     LIMIT $mulai,$batas"
);

/*
|--------------------------------------------------------------------------
| DROPDOWN FILTER
|--------------------------------------------------------------------------
*/

$list_jenjang = mysqli_query(
    $conn,
    "SELECT DISTINCT jenjang
     FROM kelas
     ORDER BY jenjang"
);

$list_tingkat = mysqli_query(
    $conn,
    "SELECT DISTINCT tingkat
     FROM kelas
     ORDER BY tingkat"
);

$list_wali = mysqli_query(
    $conn,
    "SELECT DISTINCT wali_kelas
     FROM kelas
     ORDER BY wali_kelas"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Data Kelas</title>

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
    background:#ffffff;
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
    color:#0f172a;
}

.topbar-right{
    display:flex;
    align-items:center;
    gap:20px;
}

.notif{
    position:relative;
    font-size:22px;
    color:#334155;
    cursor:pointer;
}

.notif-badge{
    position:absolute;
    top:-8px;
    right:-10px;
    width:20px;
    height:20px;
    background:#ef4444;
    color:white;
    border-radius:50%;
    font-size:11px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.admin-box{
    display:flex;
    align-items:center;
    gap:12px;
}

.admin-box img{
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
}

.admin-info h4{
    font-size:14px;
    color:#0f172a;
}

.admin-info p{
    font-size:12px;
    color:#64748b;
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
    font-size:38px;
    color:#0f172a;
    margin-bottom:5px;
}

.page-title p{
    color:#64748b;
    font-size:14px;
}

.btn-tambah{
    background:#2563eb;
    color:white;
    padding:14px 24px;
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:10px;
    font-size:14px;
    font-weight:500;
}

.btn-tambah:hover{
    background:#1d4ed8;
}

/*
|--------------------------------------------------------------------------
| CARD STATISTIK
|--------------------------------------------------------------------------
*/

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:25px;
}

.card{
    background:white;
    border-radius:16px;
    padding:20px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
}

.card-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.card-title{
    color:#64748b;
    font-size:14px;
}

.card-value{
    font-size:32px;
    font-weight:700;
    color:#0f172a;
    margin-top:10px;
}

.card-icon{
    width:55px;
    height:55px;
    border-radius:14px;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:22px;
}

.bg-blue{
    background:#dbeafe;
    color:#2563eb;
}

.bg-green{
    background:#dcfce7;
    color:#16a34a;
}

.bg-orange{
    background:#fed7aa;
    color:#ea580c;
}

.bg-purple{
    background:#ede9fe;
    color:#7c3aed;
}

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

.filter-box{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    margin-bottom:25px;
}

.filter-form{
    display:grid;
    grid-template-columns:2fr 1fr 1fr 1fr auto;
    gap:15px;
}

.filter-form input,
.filter-form select{
    height:48px;
    padding:0 15px;
    border:1px solid #e2e8f0;
    border-radius:10px;
    outline:none;
    font-size:14px;
}

.filter-form input:focus,
.filter-form select:focus{
    border-color:#2563eb;
}

.btn-filter{
    background:#2563eb;
    color:white;
    border:none;
    padding:0 20px;
    border-radius:10px;
    cursor:pointer;
    font-size:14px;
    font-weight:500;
}

.btn-filter:hover{
    background:#1d4ed8;
}

/*
|--------------------------------------------------------------------------
| TABEL KELAS
|--------------------------------------------------------------------------
*/

.table-box{
    background:white;
    border-radius:16px;
    padding:20px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    overflow-x:auto;
}

.table-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.table-header h3{
    color:#0f172a;
    font-size:18px;
}

.table-header p{
    color:#64748b;
    font-size:13px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table thead{
    background:#f8fafc;
}

table th{
    padding:15px;
    text-align:left;
    color:#334155;
    font-size:14px;
}

table td{
    padding:15px;
    border-bottom:1px solid #e2e8f0;
    font-size:14px;
}

table tbody tr:hover{
    background:#f8fafc;
}

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:500;
}

.badge-aktif{
    background:#dcfce7;
    color:#16a34a;
}

.badge-nonaktif{
    background:#fee2e2;
    color:#dc2626;
}

.jumlah-siswa{
    font-weight:600;
    color:#2563eb;
}

.aksi{
    display:flex;
    gap:8px;
}

.btn-aksi{
    width:35px;
    height:35px;
    border-radius:8px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.btn-detail{
    background:#dbeafe;
    color:#2563eb;
}

.btn-edit{
    background:#fef3c7;
    color:#d97706;
}

.btn-hapus{
    background:#fee2e2;
    color:#dc2626;
}

/*
|--------------------------------------------------------------------------
| FORM KELAS
|--------------------------------------------------------------------------
*/

.form-box{
    background:white;
    padding:25px;
    border-radius:16px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    margin-bottom:25px;
}

.form-box h3{
    margin-bottom:20px;
    color:#0f172a;
}

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
    margin-bottom:15px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    margin-bottom:8px;
    font-size:14px;
    font-weight:500;
    color:#334155;
}

.form-group input,
.form-group select{
    height:48px;
    padding:0 15px;
    border:1px solid #dbe2ea;
    border-radius:10px;
    outline:none;
}

.form-group input:focus,
.form-group select:focus{
    border-color:#2563eb;
}

.btn-simpan{
    background:#2563eb;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
}

.btn-update{
    background:#16a34a;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
}

.btn-batal{
    background:#e2e8f0;
    color:#0f172a;
    padding:12px 20px;
    border-radius:10px;
    margin-left:10px;
}

</style>
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">

    <div class="logo">

        <h2>BIMBEL ONLINE</h2>

        <p>Sistem Bimbingan Belajar</p>

    </div>

    <div class="menu">

        <a href="index.php">
            <i class="fa fa-house"></i>
            Dashboard
        </a>

        <a href="pendaftaran.php">
            <i class="fa fa-file-circle-plus"></i>
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

        <a href="kelas.php" class="active">
            <i class="fa fa-book-open"></i>
            Kelas
        </a>

        <a href="jadwal.php">
            <i class="fa fa-calendar-days"></i>
            Jadwal
        </a>

        <a href="materi.php">
            <i class="fa fa-book"></i>
            Materi
        </a>

        <a href="tugas.php">
            <i class="fa fa-file-lines"></i>
            Tugas
        </a>

        <a href="nilai.php">
            <i class="fa fa-square-poll-vertical"></i>
            Nilai
        </a>

        <a href="pembayaran.php">
            <i class="fa fa-money-bill-wave"></i>
            Pembayaran
        </a>

        <a href="notifikasi.php">
            <i class="fa fa-bell"></i>
            Notifikasi
        </a>

        <a href="laporan.php">
            <i class="fa fa-chart-line"></i>
            Laporan
        </a>

        <a href="pengaturan.php">
            <i class="fa fa-gear"></i>
            Pengaturan
        </a>

        <a href="../logout.php">
            <i class="fa fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</div>

    <!-- MAIN -->
    <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">

    <div class="topbar-left">

        <i class="fa fa-bars menu-toggle"></i>

        <div class="topbar-title">
            Data Kelas
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

            <div class="admin-info">

                <h4>
                    Admin Bimbel
                </h4>

                <p>
                    Administrator
                </p>

            </div>

            <i class="fa fa-chevron-down"></i>

        </div>

    </div>

</div>

    <!-- CONTENT -->
    <div class="content">

    <div class="page-header">

        <div class="page-title">

            <h1>Data Kelas</h1>

            <p>
                Kelola seluruh kelas yang tersedia di bimbingan belajar.
            </p>

        </div>

        <a href="?tambah=1" class="btn-tambah">

            <i class="fa fa-plus"></i>

            Tambah Kelas

        </a>

    </div>

    <!-- CARD STATISTIK -->
    <div class="cards">

    <div class="card">

        <div class="card-top">

            <div>

                <div class="card-title">
                    Total Kelas
                </div>

                <div class="card-value">
                    <?= $total_kelas ?>
</div>

</div>

<div class="card-icon bg-blue">

    <i class="fa fa-book-open"></i>

</div>

</div>

</div>

<div class="card">

    <div class="card-top">

        <div>

            <div class="card-title">
                Kelas Aktif
            </div>

            <div class="card-value">
                <?= $total_aktif ?>
            </div>

        </div>

        <div class="card-icon bg-green">

            <i class="fa fa-circle-check"></i>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-top">

        <div>

            <div class="card-title">
                Total Jenjang
            </div>

            <div class="card-value">
                <?= $total_jenjang ?>
            </div>

        </div>

        <div class="card-icon bg-orange">

            <i class="fa fa-layer-group"></i>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-top">

        <div>

            <div class="card-title">
                Total Siswa
            </div>

            <div class="card-value">
                <?= $total_siswa ?>
            </div>

        </div>

        <div class="card-icon bg-purple">

            <i class="fa fa-users"></i>

        </div>

    </div>

</div>

</div>

<!-- FILTER KELAS -->
<div class="filter-box">

    <form method="GET" class="filter-form">

        <input type="text" name="cari" placeholder="Cari nama kelas..." value="<?= $_GET['cari'] ?? '' ?>">

        <select name="jenjang">

            <option value="">
                Semua Jenjang
            </option>

            <?php
            mysqli_data_seek($list_jenjang,0);
            while($j=mysqli_fetch_assoc($list_jenjang)):
            ?>

            <option value="<?= $j['jenjang'] ?>" <?= (($_GET['jenjang'] ?? '')==$j['jenjang'])
                ? 'selected' : '' ?>>

                <?= $j['jenjang'] ?>

            </option>

            <?php endwhile; ?>

        </select>

        <select name="tingkat">

            <option value="">
                Semua Tingkat
            </option>

            <?php
            mysqli_data_seek($list_tingkat,0);
            while($t=mysqli_fetch_assoc($list_tingkat)):
            ?>

            <option value="<?= $t['tingkat'] ?>" <?= (($_GET['tingkat'] ?? '')==$t['tingkat'])
                ? 'selected' : '' ?>>

                Kelas <?= $t['tingkat'] ?>

            </option>

            <?php endwhile; ?>

        </select>

        <select name="wali_kelas">

            <option value="">
                Semua Wali Kelas
            </option>

            <?php
            mysqli_data_seek($list_wali,0);
            while($w=mysqli_fetch_assoc($list_wali)):
            ?>

            <option value="<?= $w['wali_kelas'] ?>" <?= (($_GET['wali_kelas'] ?? '')==$w['wali_kelas'])
                ? 'selected' : '' ?>>

                <?= $w['wali_kelas'] ?>

            </option>

            <?php endwhile; ?>

        </select>

        <button type="submit" class="btn-filter">

            <i class="fa fa-search"></i>
            Cari

        </button>

    </form>

</div>

<!-- TABEL KELAS -->
<div class="table-box">

    <div class="table-header">

        <div>

            <h3>Daftar Kelas</h3>

            <p>
                Total <?= $total_data ?> kelas ditemukan
            </p>

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th>No</th>
                <th>Nama Kelas</th>
                <th>Jenjang</th>
                <th>Tingkat</th>
                <th>Wali Kelas</th>
                <th>Jumlah Siswa</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

            <?php

            $no = $mulai + 1;

            while($k = mysqli_fetch_assoc($data_kelas)):

            ?>

            <tr>

                <td><?= $no++ ?></td>

                <td>

                    <strong>
                        <?= $k['nama_kelas'] ?>
                    </strong>

                </td>

                <td>
                    <?= $k['jenjang'] ?>
                </td>

                <td>
                    Kelas <?= $k['tingkat'] ?>
                </td>

                <td>
                    <?= $k['wali_kelas'] ?>
                </td>

                <td>

                    <span class="jumlah-siswa">

                        <?= $k['jumlah_siswa'] ?>

                    </span>

                    Siswa

                </td>

                <td>

                    <?php if($k['status']=='Aktif'): ?>

                    <span class="badge badge-aktif">
                        Aktif
                    </span>

                    <?php else: ?>

                    <span class="badge badge-nonaktif">
                        Nonaktif
                    </span>

                    <?php endif; ?>

                </td>

                <td>

                    <div class="aksi">

                        <a href="?detail=<?= $k['id'] ?>" class="btn-aksi btn-detail">

                            <i class="fa fa-eye"></i>

                        </a>

                        <a href="?edit=<?= $k['id'] ?>" class="btn-aksi btn-edit">

                            <i class="fa fa-pen"></i>

                        </a>

                        <a href="?hapus=<?= $k['id'] ?>" class="btn-aksi btn-hapus"
                            onclick="return confirm('Yakin hapus data kelas?')">

                            <i class="fa fa-trash"></i>

                        </a>

                    </div>

                </td>

            </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

</div>

<!-- PAGINATION -->
<div style="
margin-top:20px;
display:flex;
justify-content:center;
gap:8px;
">

    <?php for($i=1;$i<=$total_halaman;$i++): ?>

    <a href="?hal=<?= $i ?>" style="
padding:10px 15px;
border-radius:10px;
text-decoration:none;

<?= ($halaman==$i)
? 'background:#2563eb;color:white;'
: 'background:white;color:#0f172a;'
?>
">

        <?= $i ?>

    </a>

    <?php endfor; ?>

</div>

<!-- FORM KELAS -->
<?php if(isset($_GET['tambah']) || isset($_GET['edit'])): ?>

<div class="form-box">

    <h3>

        <?= isset($_GET['edit'])
        ? 'Edit Data Kelas'
        : 'Tambah Data Kelas'; ?>

    </h3>

    <form method="POST">

        <?php if(isset($_GET['edit'])): ?>

        <input type="hidden" name="id" value="<?= $edit['id'] ?>">

        <?php endif; ?>

        <div class="form-row">

            <div class="form-group">

                <label>Nama Kelas</label>

                <input type="text" name="nama_kelas" required value="<?= $edit['nama_kelas'] ?? '' ?>">

            </div>

            <div class="form-group">

                <label>Jenjang</label>

                <select name="jenjang" required>

                    <option value="">
                        Pilih Jenjang
                    </option>

                    <option value="SD" <?= (($edit['jenjang'] ?? '')=='SD')
                    ? 'selected' : '' ?>>
                        SD
                    </option>

                    <option value="SMP" <?= (($edit['jenjang'] ?? '')=='SMP')
                    ? 'selected' : '' ?>>
                        SMP
                    </option>

                    <option value="SMA" <?= (($edit['jenjang'] ?? '')=='SMA')
                    ? 'selected' : '' ?>>
                        SMA
                    </option>

                </select>

            </div>

        </div>

        <div class="form-row">

            <div class="form-group">

                <label>Tingkat</label>

                <input type="number" name="tingkat" required value="<?= $edit['tingkat'] ?? '' ?>">

            </div>

            <div class="form-group">

                <label>Wali Kelas</label>

                <input type="text" name="wali_kelas" required value="<?= $edit['wali_kelas'] ?? '' ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group">

                <label>Jumlah Siswa</label>

                <input type="number" name="jumlah_siswa" required value="<?= $edit['jumlah_siswa'] ?? 0 ?>">

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status">

                    <option value="Aktif" <?= (($edit['status'] ?? '')=='Aktif')
                    ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option value="Nonaktif" <?= (($edit['status'] ?? '')=='Nonaktif')
                    ? 'selected' : '' ?>>
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>

        <br>

        <?php if(isset($_GET['edit'])): ?>

        <button type="submit" name="update" class="btn-update">

            Update Data

        </button>

        <?php else: ?>

        <button type="submit" name="simpan" class="btn-simpan">

            Simpan Data

        </button>

        <?php endif; ?>

        <a href="kelas.php" class="btn-batal">

            Batal

        </a>

    </form>

</div>

<?php endif; ?>

</div>

</div>

</div>

</body>

</html>