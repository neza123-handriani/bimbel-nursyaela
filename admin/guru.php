<?php
session_start();

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/*
|--------------------------------------------------------------------------
| SIMPAN DATA GURU
|--------------------------------------------------------------------------
*/

if(isset($_POST['simpan'])){

    $nama_guru = mysqli_real_escape_string(
        $conn,
        $_POST['nama_guru']
    );

    $nip = mysqli_real_escape_string(
        $conn,
        $_POST['nip']
    );

    $mapel = mysqli_real_escape_string(
        $conn,
        $_POST['mapel']
    );

    $alamat = mysqli_real_escape_string(
        $conn,
        $_POST['alamat']
    );

    $telepon = mysqli_real_escape_string(
        $conn,
        $_POST['telepon']
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $foto = '';

    if(!empty($_FILES['foto']['name'])){

        if(!is_dir('../uploads')){
            mkdir('../uploads');
        }

        $foto = time().'_'.$_FILES['foto']['name'];

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            '../uploads/'.$foto
        );
    }

    mysqli_query(
        $conn,
        "INSERT INTO guru
        (
            nama_guru,
            nip,
            mapel,
            alamat,
            telepon,
            email,
            status,
            foto
        )
        VALUES
        (
            '$nama_guru',
            '$nip',
            '$mapel',
            '$alamat',
            '$telepon',
            '$email',
            '$status',
            '$foto'
        )"
    );

    header("Location: guru.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE DATA GURU
|--------------------------------------------------------------------------
*/

if(isset($_POST['update'])){

    $id = (int)$_POST['id'];

    $nama_guru = mysqli_real_escape_string(
        $conn,
        $_POST['nama_guru']
    );

    $nip = mysqli_real_escape_string(
        $conn,
        $_POST['nip']
    );

    $mapel = mysqli_real_escape_string(
        $conn,
        $_POST['mapel']
    );

    $alamat = mysqli_real_escape_string(
        $conn,
        $_POST['alamat']
    );

    $telepon = mysqli_real_escape_string(
        $conn,
        $_POST['telepon']
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $update_foto = "";

    if(!empty($_FILES['foto']['name'])){

        $guru_lama = mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT foto
                 FROM guru
                 WHERE id='$id'"
            )
        );

        if(!empty($guru_lama['foto'])){
            @unlink(
                "../uploads/".
                $guru_lama['foto']
            );
        }

        $foto = time().'_'.$_FILES['foto']['name'];

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            '../uploads/'.$foto
        );

        $update_foto = ",
            foto='$foto'
        ";
    }

    mysqli_query(
        $conn,
        "UPDATE guru SET

        nama_guru='$nama_guru',
        nip='$nip',
        mapel='$mapel',
        alamat='$alamat',
        telepon='$telepon',
        email='$email',
        status='$status'

        $update_foto

        WHERE id='$id'"
    );

    header("Location: guru.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS DATA GURU
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id = (int)$_GET['hapus'];

    $guru = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT foto
             FROM guru
             WHERE id='$id'"
        )
    );

    if(!empty($guru['foto'])){
        @unlink(
            "../uploads/".
            $guru['foto']
        );
    }

    mysqli_query(
        $conn,
        "DELETE FROM guru
         WHERE id='$id'"
    );

    header("Location: guru.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA EDIT
|--------------------------------------------------------------------------
*/

$edit = null;

if(isset($_GET['edit'])){

    $id = (int)$_GET['edit'];

    $edit = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT *
             FROM guru
             WHERE id='$id'"
        )
    );
}

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

$total_guru = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM guru"
    )
);

$total_aktif = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT *
         FROM guru
         WHERE status='Aktif'"
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
        AND nama_guru
        LIKE '%$cari%'
    ";
}

if(isset($_GET['mapel']) && $_GET['mapel'] != ''){

    $mapel = mysqli_real_escape_string(
        $conn,
        $_GET['mapel']
    );

    $where .= "
        AND mapel='$mapel'
    ";
}

if(isset($_GET['status']) && $_GET['status'] != ''){

    $status = mysqli_real_escape_string(
        $conn,
        $_GET['status']
    );

    $where .= "
        AND status='$status'
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
         FROM guru
         $where"
    )
);

$total_halaman = ceil(
    $total_data / $batas
);

/*
|--------------------------------------------------------------------------
| QUERY DATA GURU
|--------------------------------------------------------------------------
*/

$data_guru = mysqli_query(
    $conn,
    "SELECT *
     FROM guru
     $where
     ORDER BY id DESC
     LIMIT $mulai,$batas"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Data Guru - Bimbel Online</title>

    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Font -->

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>

    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:'Poppins',sans-serif;
    }

    body{
        background:#f4f7fc;
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

.sidebar{
    width:260px;
    height:100vh;
    background:#071d49;
    position:fixed;
    left:0;
    top:0;
    overflow-y:auto;
}

.sidebar::-webkit-scrollbar{
    width:5px;
}

.sidebar::-webkit-scrollbar-thumb{
    background:#1e40af;
}

.logo{
    padding:25px;
    border-bottom:1px solid rgba(255,255,255,.1);
}

.logo h2{
    color:#ffffff;
    font-size:24px;
    font-weight:700;
    margin-bottom:5px;
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
    padding:14px 15px;
    color:#cbd5e1;
    border-radius:12px;
    margin-bottom:5px;
    transition:.3s;
    font-size:14px;
    font-weight:500;
}

.menu a:hover{
    background:#2563eb;
    color:white;
}

.menu a.active{
    background:#2563eb;
    color:white;
}

.menu i{
    width:20px;
    text-align:center;
    font-size:15px;
}

/*
|--------------------------------------------------------------------------
| TOPBAR
|--------------------------------------------------------------------------
*/

.topbar{
    height:80px;
    background:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.topbar-left h2{
    color:#0f172a;
    font-size:24px;
    font-weight:600;
}

.topbar-left p{
    color:#64748b;
    font-size:13px;
    margin-top:3px;
}

.topbar-right{
    display:flex;
    align-items:center;
    gap:20px;
}

.notif{
    width:45px;
    height:45px;
    background:#f8fafc;
    border-radius:12px;
    display:flex;
    justify-content:center;
    align-items:center;
    cursor:pointer;
    position:relative;
}

.notif i{
    font-size:18px;
    color:#334155;
}

.notif-badge{
    position:absolute;
    top:-3px;
    right:-3px;
    width:18px;
    height:18px;
    background:#ef4444;
    color:white;
    font-size:10px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
}

.btn-tambah{
    background:#2563eb;
    color:white;
    padding:12px 18px;
    border-radius:10px;
    font-size:14px;
    font-weight:500;
    display:flex;
    align-items:center;
    gap:8px;
    transition:.3s;
}

.btn-tambah:hover{
    background:#1d4ed8;
}

.admin{
    display:flex;
    align-items:center;
    gap:12px;
}

.admin img{
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
    padding:30px;
}

/*
|--------------------------------------------------------------------------
| CARD STATISTIK
|--------------------------------------------------------------------------
*/

.cards{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
    margin-bottom:25px;
}

.card{
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
}

.card-body{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.card-info h3{
    font-size:32px;
    color:#0f172a;
    margin-bottom:5px;
}

.card-info p{
    color:#64748b;
    font-size:14px;
}

.card-icon{
    width:65px;
    height:65px;
    border-radius:15px;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:28px;
}

.icon-blue{
    background:#dbeafe;
    color:#2563eb;
}

.icon-green{
    background:#dcfce7;
    color:#16a34a;
}

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

.filter-box{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    margin-bottom:25px;
}

.filter-form{
    display:grid;
    grid-template-columns:2fr 1fr 1fr auto;
    gap:15px;
}

.filter-form input,
.filter-form select{
    padding:12px;
    border:1px solid #dbe2ea;
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
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
    font-weight:500;
}

.btn-filter:hover{
    background:#1d4ed8;
}

/*
|--------------------------------------------------------------------------
| TABEL GURU
|--------------------------------------------------------------------------
*/

.table-box{
    background:white;
    border-radius:15px;
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
    font-size:14px;
    color:#334155;
}

table td{
    padding:15px;
    border-bottom:1px solid #e2e8f0;
    font-size:14px;
}

table tr:hover{
    background:#f8fafc;
}

.foto-guru{
    width:50px;
    height:50px;
    border-radius:50%;
    object-fit:cover;
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

.aksi{
    display:flex;
    gap:8px;
}

.btn-aksi{
    width:35px;
    height:35px;
    display:flex;
    justify-content:center;
    align-items:center;
    border-radius:8px;
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
| FORM GURU
|--------------------------------------------------------------------------
*/

.form-box{
    background:white;
    padding:25px;
    border-radius:15px;
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
}

.form-group input,
.form-group select,
.form-group textarea{
    padding:12px;
    border:1px solid #dbe2ea;
    border-radius:10px;
    outline:none;
}

.form-group textarea{
    resize:none;
    height:100px;
}

.btn-simpan{
    background:#2563eb;
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

        <p>Sistem Manajemen Bimbingan Belajar</p>

    </div>

    <div class="menu">

        <a href="index.php">
            <i class="fa fa-house"></i>
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

        <a href="guru.php" class="active">
            <i class="fa fa-chalkboard-user"></i>
            Data Guru
        </a>

        <a href="kelas.php">
            <i class="fa fa-school"></i>
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
            <i class="fa fa-star"></i>
            Nilai
        </a>

        <a href="pembayaran.php">
            <i class="fa fa-money-bill-wave"></i>
            Pembayaran
        </a>

        <a href="laporan.php">
            <i class="fa fa-chart-line"></i>
            Laporan
        </a>

        <a href="../logout.php">
            <i class="fa fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</div>

    <!-- MAIN CONTENT -->
     <div class="main">

    <!-- TOPBAR -->
     <div class="topbar">

    <div class="topbar-left">

        <h2>Data Guru</h2>

        <p>
            Kelola seluruh data guru bimbingan belajar
        </p>

    </div>

    <div class="topbar-right">

        <div class="notif">

            <i class="fa fa-bell"></i>

            <div class="notif-badge">
                3
            </div>

        </div>

        <a href="?tambah=1" class="btn-tambah">

            <i class="fa fa-plus"></i>

            Tambah Guru

        </a>

        <div class="admin">

            <img
            src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff">

            <div class="admin-info">

                <h4>
                    <?= $_SESSION['nama'] ?? 'Administrator' ?>
                </h4>

                <p>
                    Administrator
                </p>

            </div>

        </div>

    </div>

</div>

    <!-- CONTENT -->

    <div class="content">

    <?php if(isset($_GET['tambah']) || isset($_GET['edit'])): ?>

<div class="form-box">

    <h3>

        <?= isset($_GET['edit'])
        ? 'Edit Data Guru'
        : 'Tambah Data Guru'; ?>

    </h3>

    <form
    method="POST"
    enctype="multipart/form-data">

        <?php if(isset($_GET['edit'])): ?>

        <input
        type="hidden"
        name="id"
        value="<?= $edit['id'] ?>">

        <?php endif; ?>

        <div class="form-row">

            <div class="form-group">

                <label>Nama Guru</label>

                <input
                type="text"
                name="nama_guru"
                required
                value="<?= $edit['nama_guru'] ?? '' ?>">

            </div>

            <div class="form-group">

                <label>NIP</label>

                <input
                type="text"
                name="nip"
                required
                value="<?= $edit['nip'] ?? '' ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group">

                <label>Mata Pelajaran</label>

                <input
                type="text"
                name="mapel"
                required
                value="<?= $edit['mapel'] ?? '' ?>">

            </div>

            <div class="form-group">

                <label>Telepon</label>

                <input
                type="text"
                name="telepon"
                value="<?= $edit['telepon'] ?? '' ?>">

            </div>

        </div>

        <div class="form-row">

            <div class="form-group">

                <label>Email</label>

                <input
                type="email"
                name="email"
                value="<?= $edit['email'] ?? '' ?>">

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status">

                    <option value="Aktif"
                    <?= (($edit['status'] ?? '')=='Aktif')
                    ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option value="Nonaktif"
                    <?= (($edit['status'] ?? '')=='Nonaktif')
                    ? 'selected' : '' ?>>
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>

        <div class="form-group">

            <label>Alamat</label>

            <textarea
            name="alamat"><?= $edit['alamat'] ?? '' ?></textarea>

        </div>

        <br>

        <div class="form-group">

            <label>Foto Guru</label>

            <input
            type="file"
            name="foto">

        </div>

        <br>

        <?php if(isset($_GET['edit'])): ?>

        <button
        type="submit"
        name="update"
        class="btn-simpan">

            Update Data

        </button>

        <?php else: ?>

        <button
        type="submit"
        name="simpan"
        class="btn-simpan">

            Simpan Data

        </button>

        <?php endif; ?>

        <a
        href="guru.php"
        class="btn-batal">

            Batal

        </a>

    </form>

</div>

<?php endif; ?>

    <!-- CARD STATISTIK -->
     <div class="cards">

    <div class="card">

        <div class="card-body">

            <div class="card-info">

                <h3>
                    <?= $total_guru ?>
                </h3>

                <p>
                    Total Guru
                </p>

            </div>

            <div class="card-icon icon-blue">

                <i class="fa fa-chalkboard-user"></i>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <div class="card-info">

                <h3>
                    <?= $total_aktif ?>
                </h3>

                <p>
                    Guru Aktif
                </p>

            </div>

            <div class="card-icon icon-green">

                <i class="fa fa-circle-check"></i>

            </div>

        </div>

    </div>

</div>

<!-- FILTER -->
 <div class="filter-box">

    <form method="GET" class="filter-form">

        <input
            type="text"
            name="cari"
            placeholder="Cari nama guru..."
            value="<?= $_GET['cari'] ?? '' ?>">

        <input
            type="text"
            name="mapel"
            placeholder="Mata Pelajaran"
            value="<?= $_GET['mapel'] ?? '' ?>">

        <select name="status">

            <option value="">
                Semua Status
            </option>

            <option value="Aktif"
                <?= (($_GET['status'] ?? '')=='Aktif') ? 'selected' : '' ?>>
                Aktif
            </option>

            <option value="Nonaktif"
                <?= (($_GET['status'] ?? '')=='Nonaktif') ? 'selected' : '' ?>>
                Nonaktif
            </option>

        </select>

        <button
            type="submit"
            class="btn-filter">

            <i class="fa fa-search"></i>
            Cari

        </button>

    </form>

</div>

<!-- TABEL GURU -->
 <div class="table-box">

    <div class="table-header">

        <div>

            <h3>Daftar Guru</h3>

            <p>
                Total <?= $total_guru ?> Guru
            </p>

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th>No</th>
                <th>Foto</th>
                <th>Nama Guru</th>
                <th>Mapel</th>
                <th>Telepon</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

        <?php
        $no = $mulai + 1;

        while($g = mysqli_fetch_assoc($data_guru)):
        ?>

        <tr>

            <td>
                <?= $no++ ?>
            </td>

            <td>

                <?php if(!empty($g['foto'])): ?>

                <img
                    src="../uploads/<?= $g['foto'] ?>"
                    class="foto-guru">

                <?php else: ?>

                <img
                    src="https://ui-avatars.com/api/?name=<?= urlencode($g['nama_guru']) ?>"
                    class="foto-guru">

                <?php endif; ?>

            </td>

            <td>

                <strong>
                    <?= $g['nama_guru'] ?>
                </strong>

                <br>

                <small style="color:#64748b;">
                    <?= $g['nip'] ?>
                </small>

            </td>

            <td>
                <?= $g['mapel'] ?>
            </td>

            <td>
                <?= $g['telepon'] ?>
            </td>

            <td>

                <?php if($g['status']=='Aktif'): ?>

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

                    <a
                        href="?detail=<?= $g['id'] ?>"
                        class="btn-aksi btn-detail">

                        <i class="fa fa-eye"></i>

                    </a>

                    <a
                        href="?edit=<?= $g['id'] ?>"
                        class="btn-aksi btn-edit">

                        <i class="fa fa-pen"></i>

                    </a>

                    <a
                        href="?hapus=<?= $g['id'] ?>"
                        class="btn-aksi btn-hapus"
                        onclick="return confirm('Yakin hapus data guru?')">

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

<a
href="?hal=<?= $i ?>"
style="
padding:10px 15px;
border-radius:10px;
text-decoration:none;

<?= ($halaman==$i)
? 'background:#2563eb;color:white;'
: 'background:white;color:black;'
?>
">

<?= $i ?>

</a>

<?php endfor; ?>

</div>

</div>

</div>

</div>

</body>
</html>