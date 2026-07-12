<?php
session_start();

if(isset($_SESSION['role'])){

    if($_SESSION['role']=="admin"){
        header("Location: ../dashboard/admin/");
    }

    if($_SESSION['role']=="guru"){
        header("Location: ../dashboard/guru/");
    }

    if($_SESSION['role']=="siswa"){
        header("Location: ../dashboard/siswa/");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Bimbel</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
    body {
        margin: 0;
        font-family: Segoe UI;
        background: #f5f7fb;
        display: flex;
        height: 100vh;
    }

    .left {
        width: 50%;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .right {
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        width: 500px;
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
    }

    .input {
        width: 100%;
        padding: 15px;
        margin-top: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
    }

    .btn {
        width: 100%;
        padding: 15px;
        background: #2563eb;
        border: none;
        color: white;
        font-size: 18px;
        border-radius: 10px;
        cursor: pointer;
    }
    </style>
</head>

<body>

    <div class="left">

        <h1>
            Belajar Jadi Lebih Mudah
            Bersama Bimbel Online
        </h1>

        <p>
            Platform bimbingan belajar online
            untuk siswa, guru dan administrator.
        </p>

    </div>

    <div class="right">

        <div class="card">

            <h1 align="center">Login</h1>

            <form action="proses_login.php" method="POST">

                <label>Email</label>

                <input type="email" name="email" class="input" required>

                <label>Password</label>

                <input type="password" name="password" class="input" required>

                <button class="btn">
                    Login
                </button>

            </form>

        </div>

    </div>

</body>

</html>