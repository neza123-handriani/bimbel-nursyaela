<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_bimbel";

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

if(!$conn){
    die("Koneksi gagal");
}