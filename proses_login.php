<?php

session_start();

include "config/koneksi.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE email='$email'"
);

$user = mysqli_fetch_assoc($query);

if($user){

    if(password_verify(
        $password,
        $user['password']
    )){

        $_SESSION['id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if($user['role']=="admin"){
    header("Location: admin/index.php");
    exit;
}
elseif($user['role']=="guru"){
    header("Location: guru/index.php");
    exit;
}
elseif($user['role']=="siswa"){
    header("Location: siswa/index.php");
    exit;
}

    }else{

        echo "Password salah";
    }

}else{

    echo "Email tidak ditemukan";
}

$user = mysqli_fetch_assoc($query);

if($user){

    if(password_verify(
        $password,
        $user['password']
    )){

        $_SESSION['id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if($user['role']=="admin"){
            header("Location: ../dashboard/admin/");
        }
        elseif($user['role']=="guru"){
            header("Location: ../dashboard/guru/");
        }
        else{
            header("Location: ../dashboard/siswa/");
        }
    }else{

        echo "
        <script>
        alert('Password salah');
        history.back();
        </script>";
    }

}else{

    echo "
    <script>
    alert('Email tidak ditemukan');
    history.back();
    </script>";
}