<?php
$title = "Bimbel Nursyaelah";
?>

<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_bimbel"
);

if(isset($_POST['nama'])){

    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $subjek = $_POST['subjek'];
    $pesan  = $_POST['pesan'];

    mysqli_query($conn,"
        INSERT INTO kontak
        (nama,email,subjek,pesan)
        VALUES
        ('$nama','$email','$subjek','$pesan')
    ");

    echo "<script>
        alert('Pesan berhasil dikirim');
    </script>";
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        background: #ffffff;
        color: #1d2144;
    }

    .container {
        width: 90%;
        max-width: 1200px;
        margin: auto;
    }

    header {
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 15px rgba(0, 0, 0, .05);
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo i {
        color: #2563eb;
        font-size: 40px;
    }

    .logo h2 {
        font-size: 24px;
        color: #0f172a;
    }

    .logo small {
        color: #64748b;
    }

    nav ul {
        display: flex;
        list-style: none;
        gap: 35px;
    }

    nav a {
        text-decoration: none;
        color: #1e293b;
        font-weight: 500;
    }

    nav a:hover {
        color: #2563eb;
    }

    .login-btn {
        padding: 10px 25px;
        border: 1px solid #2563eb;
        border-radius: 10px;
        color: #2563eb;
        text-decoration: none;
    }

    .hero {
        padding: 80px 0;
    }

    .hero-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
    }

    .badge {
        background: #eef4ff;
        color: #2563eb;
        display: inline-block;
        padding: 10px 20px;
        border-radius: 30px;
        margin-bottom: 20px;
    }

    .hero h1 {
        font-size: 60px;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .hero h1 span {
        color: #2563eb;
    }

    .hero p {
        line-height: 1.8;
        color: #64748b;
        margin-bottom: 30px;
    }

    .hero img {
        width: 100%;
    }

    .feature-box {
        display: flex;
        gap: 20px;
    }

    .feature {
        text-align: center;
    }

    .feature i {
        color: #2563eb;
        font-size: 30px;
        margin-bottom: 10px;
    }

    .about {
        background: #f8fafc;
        padding: 80px 0;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
    }

    .about img {
        width: 100%;
    }

    .section-title {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-title small {
        color: #2563eb;
        font-weight: 600;
    }

    .section-title h2 {
        margin-top: 10px;
        font-size: 40px;
    }

    .program {
        padding: 80px 0;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    .card {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        transition: .3s;
    }

    .card:hover {
        transform: translateY(-8px);
    }

    .card img {
        width: 100%;
        height: 260px;
        object-fit: cover;
    }

    .card-body {
        padding: 25px;
    }

    .card h3 {
        color: #2563eb;
        font-size: 30px;
    }

    .advantages {
        background: #f8fbff;
        padding: 80px 0;
    }

    .adv-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .adv-item {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
    }

    .adv-item i {
        font-size: 40px;
        color: #2563eb;
        margin-bottom: 15px;
    }

    .contact {
        padding: 80px 0;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
    }

    .contact img {
        width: 100%;
        border-radius: 20px;
    }

    .contact {
        padding: 80px 0;
        background: #f8fafc;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: start;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        background: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .05);
    }

    .contact-item i {
        font-size: 22px;
        color: #2563eb;
        margin-top: 5px;
    }

    .contact-item h4 {
        margin-bottom: 5px;
    }

    .contact-form {
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .05);
    }

    .contact-form form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 14px;
        border: 1px solid #ddd;
        border-radius: 10px;
        outline: none;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
        border-color: #2563eb;
    }

    .contact-form button {
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 14px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
    }

    .contact-form button:hover {
        background: #1d4ed8;
    }

    @media(max-width:768px) {

        .contact-grid {
            grid-template-columns: 1fr;
        }

    }

    footer {
        background: linear-gradient(90deg, #0f3fd9, #2563eb);
        color: #fff;
        padding: 60px 0 20px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 30px;
    }

    footer ul {
        list-style: none;
    }

    footer ul li {
        margin-bottom: 10px;
    }

    footer a {
        color: white;
        text-decoration: none;
    }

    .copy {
        margin-top: 40px;
        text-align: center;
        border-top: 1px solid rgba(255, 255, 255, .2);
        padding-top: 20px;
    }

    @media(max-width:768px) {

        .hero-content,
        .about-grid,
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .cards {
            grid-template-columns: 1fr;
        }

        .adv-grid {
            grid-template-columns: 1fr 1fr;
        }

        nav {
            display: none;
        }

        .hero h1 {
            font-size: 38px;
        }

    }
    </style>
</head>

<body>

    <header>
        <div class="container navbar">

            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <div>
                    <h2>BIMBEL NURSYAELAH</h2>
                    <small>Sistem Bimbingan Belajar</small>
                </div>
            </div>

            <nav>
                <ul>
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#program">Program</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </nav>

            <a href="login.php" class="login-btn">
                <i class="fas fa-user"></i> Login
            </a>

        </div>
    </header>

    <section class="hero" id="home">
        <div class="container hero-content">

            <div>
                <span class="badge">
                    Belajar Kapan Saja, di Mana Saja
                </span>

                <h1>
                    Belajar Jadi Lebih Mudah Bersama
                    <span>Bimbel Nursyaelah</span>
                </h1>

                <p>
                    Platform bimbingan belajar online untuk siswa,
                    guru, dan orang tua dalam satu sistem terintegrasi.
                </p>

                <div class="feature-box">
                    <div class="feature">
                        <i class="fas fa-book-open"></i>
                        <h4>Materi Lengkap</h4>
                    </div>

                    <div class="feature">
                        <i class="fas fa-user-graduate"></i>
                        <h4>Pengajar Profesional</h4>
                    </div>

                    <div class="feature">
                        <i class="fas fa-clock"></i>
                        <h4>Akses Fleksibel</h4>
                    </div>
                </div>

            </div>

            <div>
                <img src="assets/hero.png" alt="">
            </div>

        </div>
    </section>

    <!-- SECTION TENTANG -->
    <section class="about" id="about">
        <div class="container about-grid">

            <div>
                <img src="assets/about.png">
            </div>

            <div>
                <small style="color:#2563eb;">TENTANG KAMI</small>
                <h2>Bimbel Nursyaelah</h2>
                <br>
                <p>
                    Bimbel Nursyaelah hadir untuk membantu siswa
                    mencapai prestasi terbaik melalui metode belajar
                    modern dan pengajar profesional.
                </p>
            </div>

        </div>
    </section>

    <!-- PROGRAM -->
    <section class="program" id="program">

        <div class="container">

            <div class="section-title">
                <small>PROGRAM BIMBINGAN BELAJAR</small>
                <h2>Program Kami</h2>
            </div>

            <div class="cards">

                <div class="card">
                    <img src="assets/sd.jpg">
                    <div class="card-body">
                        <h3>SD</h3>
                        <p>Kelas 1 - 6</p>
                    </div>
                </div>

                <div class="card">
                    <img src="assets/smp.jpg">
                    <div class="card-body">
                        <h3>SMP</h3>
                        <p>Kelas 7 - 9</p>
                    </div>
                </div>

                <div class="card">
                    <img src="assets/sma.jpg">
                    <div class="card-body">
                        <h3>SMA</h3>
                        <p>Kelas 10 - 12</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="contact">
        <div class="container">

            <div class="section-title">
                <small>KONTAK KAMI</small>
                <h2>Hubungi Kami</h2>
            </div>

            <div class="contact-grid">

                <div class="contact-info">

                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Telepon</h4>
                            <p>0812-3456-7890</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>info@bimbelnursyaelah.id</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-location-dot"></i>
                        <div>
                            <h4>Alamat</h4>
                            <p>
                                Jl. Pendidikan No.123<br>
                                Jakarta, Indonesia
                            </p>
                        </div>
                    </div>

                </div>

                <div class="contact-form">

                    <form action="" method="POST">

                        <input type="text" name="nama" placeholder="Nama Lengkap" required>

                        <input type="email" name="email" placeholder="Email" required>

                        <input type="text" name="subjek" placeholder="Subjek" required>

                        <textarea name="pesan" rows="5" placeholder="Tulis pesan Anda..." required></textarea>

                        <button type="submit">
                            Kirim Pesan
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-grid">

                <div>
                    <h3>Bimbel Nursyaelah</h3>
                    <p>Platform bimbingan belajar online terpercaya.</p>
                </div>

                <div>
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Program</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Program</h4>
                    <ul>
                        <li>SD</li>
                        <li>SMP</li>
                        <li>SMA</li>
                    </ul>
                </div>

                <div>
                    <h4>Kontak</h4>
                    <p>0812-3456-7890</p>
                    <p>info@bimbelnursyaelah.id</p>
                </div>

            </div>

            <div class="copy">
                © <?= date('Y'); ?> Bimbel Nursyaelah
            </div>

        </div>
    </footer>

    <script>
    const menu = document.querySelectorAll("nav a");

    menu.forEach(item => {
        item.addEventListener("click", function(e) {
            e.preventDefault();

            const target =
                document.querySelector(
                    this.getAttribute("href")
                );

            target.scrollIntoView({
                behavior: "smooth"
            });
        });
    });
    </script>

</body>

</html>