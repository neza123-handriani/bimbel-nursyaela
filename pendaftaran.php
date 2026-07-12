<?php
session_start();
include 'config/koneksi.php';

$success = false;

if(isset($_POST['simpan'])){

    $nama            = $_POST['nama'];
    $email           = $_POST['email'];
    $jk              = $_POST['jk'];
    $tempat_lahir    = $_POST['tempat_lahir'];
    $tanggal_lahir   = $_POST['tanggal_lahir'];
    $alamat          = $_POST['alamat'];
    $hp              = $_POST['hp'];
    $nama_wali       = $_POST['nama_wali'];
    $hp_wali         = $_POST['hp_wali'];
    $hubungan        = $_POST['hubungan'];
    $program         = $_POST['program'];
    $password        = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $biaya = 0;

    if($program == "SD"){
        $biaya = 150000;
    }elseif($program == "SMP"){
        $biaya = 200000;
    }else{
        $biaya = 250000;
    }

    mysqli_begin_transaction($conn);

    try{

        mysqli_query($conn,"
        INSERT INTO users
        (
            nama,
            email,
            password,
            role,
            status
        )
        VALUES
        (
            '$nama',
            '$email',
            '$password',
            'siswa',
            'pending'
        )
        ");

        $user_id = mysqli_insert_id($conn);

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
            '$hp',
            '$nama_wali',
            '$hp_wali',
            '$hubungan',
            '$program',
            '$biaya'
        )
        ");

        mysqli_commit($conn);
        $success = true;

    }catch(Exception $e){

        mysqli_rollback($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: #f4f7fc;
        color: #1e293b;
    }

    .container {
        width: 95%;
        max-width: 1400px;
        margin: auto;
        padding: 30px 0;
    }

    .header {
        text-align: center;
        margin-bottom: 40px;
    }

    .header h1 {
        font-size: 40px;
        color: #0f172a;
    }

    .header p {
        color: #64748b;
        margin-top: 10px;
    }

    .stepper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 60%;
        width: 80%;
        height: 2px;
        background: #dbeafe;
    }

    .step:last-child::after {
        display: none;
    }

    .circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dbeafe;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-weight: bold;
    }

    .step.active .circle {
        background: #2563eb;
        color: white;
    }

    .card {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, .05);
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 8px;
        font-weight: 600;
    }

    input,
    select,
    textarea {
        padding: 14px;
        border: 1px solid #dbe2ea;
        border-radius: 12px;
        outline: none;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: #2563eb;
    }

    .program-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .program {
        border: 2px solid #e2e8f0;
        padding: 25px;
        border-radius: 20px;
        cursor: pointer;
        transition: .3s;
    }

    .program:hover {
        border-color: #2563eb;
    }

    .program.selected {
        background: #eff6ff;
        border-color: #2563eb;
    }

    .program h3 {
        color: #2563eb;
        margin-bottom: 10px;
    }

    .price {
        font-size: 28px;
        font-weight: bold;
        color: #2563eb;
    }

    .payment-box {
        background: #f8fafc;
        padding: 25px;
        border-radius: 15px;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .total {
        font-size: 26px;
        font-weight: bold;
        color: #2563eb;
        border-top: 1px solid #ddd;
        padding-top: 15px;
    }

    .btn-area {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-next {
        background: #2563eb;
        color: white;
    }

    .btn-prev {
        background: #e2e8f0;
    }

    .success {
        text-align: center;
        padding: 50px;
    }

    .success i {
        font-size: 90px;
        color: #22c55e;
        margin-bottom: 20px;
    }

    .success h2 {
        color: #22c55e;
        margin-bottom: 15px;
    }

    @media(max-width:768px) {

        .grid {
            grid-template-columns: 1fr;
        }

        .program-grid {
            grid-template-columns: 1fr;
        }

        .stepper {
            overflow: auto;
            gap: 20px;
        }

    }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h1>Pendaftaran Siswa</h1>
            <p>Bergabung bersama Bimbel Online</p>
        </div>

        <div class="stepper">

            <div class="step active" id="indicator1">
                <div class="circle">1</div>
                <p>Data Diri</p>
            </div>

            <div class="step" id="indicator2">
                <div class="circle">2</div>
                <p>Pilih Program</p>
            </div>

            <div class="step" id="indicator3">
                <div class="circle">3</div>
                <p>Pembayaran</p>
            </div>

            <div class="step" id="indicator4">
                <div class="circle">4</div>
                <p>Selesai</p>
            </div>

        </div>

        <div class="card">

            <form method="POST">

                <!-- STEP 1 -->

                <div class="step-content active" id="step1">

                    <h2>Data Diri Siswa</h2>
                    <br>

                    <div class="grid">

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jk">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir">
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir">
                        </div>

                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="hp">
                        </div>

                        <div class="form-group">
                            <label>Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_wali">
                        </div>

                        <div class="form-group">
                            <label>No HP Wali</label>
                            <input type="text" name="hp_wali">
                        </div>

                    </div>

                    <br>

                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat"></textarea>
                    </div>

                    <br>

                    <div class="form-group">
                        <label>Hubungan</label>
                        <select name="hubungan">
                            <option>Ayah</option>
                            <option>Ibu</option>
                            <option>Wali</option>
                        </select>
                    </div>

                    <br>

                    <div class="form-group">
                        <label>Password Login</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="btn-area">
                        <div></div>
                        <button type="button" class="btn btn-next" onclick="nextStep(2)">
                            Selanjutnya
                        </button>
                    </div>

                </div>

                <!-- STEP 2 -->

                <div class="step-content" id="step2">

                    <h2>Pilih Program</h2>
                    <br>

                    <div class="program-grid">

                        <div class="program" onclick="pilihProgram('SD',150000,this)">
                            <h3>SD</h3>
                            <p>Kelas 1 - 6</p>
                            <br>
                            <div class="price">Rp150.000</div>
                        </div>

                        <div class="program" onclick="pilihProgram('SMP',200000,this)">
                            <h3>SMP</h3>
                            <p>Kelas 7 - 9</p>
                            <br>
                            <div class="price">Rp200.000</div>
                        </div>

                        <div class="program" onclick="pilihProgram('SMA',250000,this)">
                            <h3>SMA</h3>
                            <p>Kelas 10 - 12</p>
                            <br>
                            <div class="price">Rp250.000</div>
                        </div>

                    </div>

                    <input type="hidden" name="program" id="program">

                    <div class="btn-area">
                        <button type="button" class="btn btn-prev" onclick="nextStep(1)">
                            Kembali
                        </button>

                        <button type="button" class="btn btn-next" onclick="nextStep(3)">
                            Selanjutnya
                        </button>
                    </div>

                </div>

                <!-- STEP 3 -->

                <div class="step-content" id="step3">

                    <h2>Pembayaran</h2>

                    <br>

                    <div class="payment-box">

                        <div class="payment-row">
                            <span>Biaya Pendaftaran</span>
                            <span>Rp50.000</span>
                        </div>

                        <div class="payment-row">
                            <span>Biaya Program</span>
                            <span id="harga-program">Rp0</span>
                        </div>

                        <div class="payment-row total">
                            <span>Total</span>
                            <span id="total">Rp0</span>
                        </div>

                    </div>

                    <br>

                    <label>Metode Pembayaran</label>

                    <select>
                        <option>Transfer Bank</option>
                        <option>Virtual Account</option>
                        <option>E-Wallet</option>
                    </select>

                    <div class="btn-area">
                        <button type="button" class="btn btn-prev" onclick="nextStep(2)">
                            Kembali
                        </button>

                        <button type="button" class="btn btn-next" onclick="nextStep(4)">
                            Bayar Sekarang
                        </button>
                    </div>

                </div>

                <!-- STEP 4 -->

                <div class="step-content" id="step4">

                    <?php if($success): ?>

                    <div class="success">
                        <i class="fas fa-circle-check"></i>

                        <h2>Pendaftaran Berhasil</h2>

                        <p>
                            Akun Anda berhasil dibuat.
                            Silakan login menggunakan email dan password yang didaftarkan.
                        </p>

                        <br>

                        <a href="login.php" class="btn btn-next">
                            Login Sekarang
                        </a>
                    </div>

                    <?php else: ?>

                    <div class="success">
                        <i class="fas fa-user-graduate"></i>

                        <h2>Konfirmasi Pendaftaran</h2>

                        <p>
                            Klik tombol berikut untuk menyelesaikan pendaftaran.
                        </p>

                        <br>

                        <button type="submit" name="simpan" class="btn btn-next">
                            Simpan Data Pendaftaran
                        </button>

                    </div>

                    <?php endif; ?>

                </div>

            </form>

        </div>

    </div>

    <script>
    let harga = 0;

    function nextStep(step) {

        document
            .querySelectorAll('.step-content')
            .forEach(el => {
                el.classList.remove('active');
            });

        document
            .getElementById('step' + step)
            .classList.add('active');

        document
            .querySelectorAll('.step')
            .forEach(el => {
                el.classList.remove('active');
            });

        for (let i = 1; i <= step; i++) {
            document
                .getElementById('indicator' + i)
                .classList.add('active');
        }

    }

    function pilihProgram(nama, hargaProgram, element) {

        document
            .querySelectorAll('.program')
            .forEach(el => {
                el.classList.remove('selected');
            });

        element.classList.add('selected');

        document.getElementById('program').value = nama;

        harga = hargaProgram;

        document.getElementById('harga-program')
            .innerHTML =
            'Rp' + hargaProgram.toLocaleString('id-ID');

        document.getElementById('total')
            .innerHTML =
            'Rp' + (hargaProgram + 50000)
            .toLocaleString('id-ID');
    }
    </script>

</body>

</html>