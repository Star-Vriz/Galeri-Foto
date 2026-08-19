<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda harus login untuk menyukai foto');
    location.href='../login.php';
    </script>";
    exit;
}

$fotoid = $_GET['fotoid'];
$userid = $_SESSION['userid'];

// halaman asal dikirim lewat parameter 'kembali', contoh: 'home.php', 'admin/index.php', 'index.php'
$halaman_diizinkan = ['home.php', 'admin/index.php', 'index.php'];
$kembali = isset($_GET['kembali']) && in_array($_GET['kembali'], $halaman_diizinkan) ? $_GET['kembali'] : 'home.php';
$tujuan = ($kembali == 'index.php') ? '../index.php' : '../admin/' . str_replace('admin/', '', $kembali);

$ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");

if (mysqli_num_rows($ceksuka) == 1) {
    $row = mysqli_fetch_array($ceksuka);
    $likeid = $row['likeid'];
    mysqli_query($koneksi, "DELETE FROM likefoto WHERE likeid='$likeid'");
} else {
    $tanggallike = date('Y-m-d');
    mysqli_query($koneksi, "INSERT INTO likefoto VALUES('','$fotoid','$userid','$tanggallike')");

    $pemilik = mysqli_fetch_array(mysqli_query($koneksi, "SELECT userid FROM foto WHERE fotoid='$fotoid'"));
    if ($pemilik && $pemilik['userid'] != $userid) {
        $sekarang = date('Y-m-d H:i:s');
        mysqli_query($koneksi, "INSERT INTO notifikasi (userid, dari_userid, tipe, fotoid, dibaca, tanggal) VALUES ('{$pemilik['userid']}','$userid','like','$fotoid',0,'$sekarang')");
    }
}

echo "<script>
location.href='$tujuan';
</script>";
