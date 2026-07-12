<?php

include 'config/koneksi.php';

$nama = $_POST['nama_lengkap'];
$email = $_POST['email'];
$password = $_POST['password'];

$jk = $_POST['jk'];
$tempat_lahir = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];

$alamat = $_POST['alamat'];
$no_hp = $_POST['no_hp'];

$nama_wali = $_POST['nama_wali'];
$hp_wali = $_POST['hp_wali'];
$hubungan = $_POST['hubungan'];

$program = $_POST['program'];

if($program=="SD"){
    $biaya = 150000;
}

if($program=="SMP"){
    $biaya = 200000;
}

if($program=="SMA"){
    $biaya = 250000;
}

$password_hash =
password_hash(
    $password,
    PASSWORD_DEFAULT
);

mysqli_begin_transaction($conn);

try{

mysqli_query($conn,"
INSERT INTO users
(
nama,
email,
password,
role
)

VALUES
(
'$nama',
'$email',
'$password_hash',
'siswa'
)
");

$user_id =
mysqli_insert_id($conn);

mysqli_query($conn,"
INSERT INTO siswa
(
user_id,
nama_lengkap,
jenis_kelamin,
tempat_lahir,
tanggal_lahir,
alamat,
no_hp,
nama_wali,
no_hp_wali,
hubungan,
program,
biaya
)

VALUES
(
'$user_id',
'$nama',
'$jk',
'$tempat_lahir',
'$tanggal_lahir',
'$alamat',
'$no_hp',
'$nama_wali',
'$hp_wali',
'$hubungan',
'$program',
'$biaya'
)
");

mysqli_commit($conn);

header("Location: pembayaran.php?id=$user_id");

}catch(Exception $e){

mysqli_rollback($conn);

echo "Gagal";
}