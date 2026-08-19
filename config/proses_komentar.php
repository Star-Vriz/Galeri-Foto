<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda harus login untuk berkomentar');
    location.href='../login.php';
    </script>";
    exit;
}

$fotoid = $_POST['fotoid'];
$userid = $_SESSION['userid'];
$isikomentar = mysqli_real_escape_string($koneksi, $_POST['isikomentar']);
$tanggalkomentar = date('Y-m-d');
$parent_id = (isset($_POST['parent_id']) && $_POST['parent_id'] != '') ? (int)$_POST['parent_id'] : null;

$halaman_diizinkan = ['home.php', 'admin/index.php', 'index.php'];
$kembali = isset($_POST['kembali']) && in_array($_POST['kembali'], $halaman_diizinkan) ? $_POST['kembali'] : 'index.php';
$tujuan = ($kembali == 'index.php') ? '../index.php' : '../admin/' . str_replace('admin/', '', $kembali);

if ($parent_id === null) {
    $sql = mysqli_query($koneksi, "INSERT INTO komentarfoto (fotoid, userid, isikomentar, tanggalkomentar) VALUES ('$fotoid','$userid','$isikomentar','$tanggalkomentar')");
} else {
    $sql = mysqli_query($koneksi, "INSERT INTO komentarfoto (fotoid, parent_id, userid, isikomentar, tanggalkomentar) VALUES ('$fotoid','$parent_id','$userid','$isikomentar','$tanggalkomentar')");
}

$pemilik = mysqli_fetch_array(mysqli_query($koneksi, "SELECT userid FROM foto WHERE fotoid='$fotoid'"));
if ($pemilik && $pemilik['userid'] != $userid) {
    $sekarang = date('Y-m-d H:i:s');
    mysqli_query($koneksi, "INSERT INTO notifikasi (userid, dari_userid, tipe, fotoid, dibaca, tanggal) VALUES ('{$pemilik['userid']}','$userid','komentar','$fotoid',0,'$sekarang')");
}

echo "<script>
    location.href='$tujuan#komentar-$fotoid';
    </script>";
