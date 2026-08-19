<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda harus login');
    location.href='../login.php';
    </script>";
    exit;
}

$komentarid = $_POST['komentarid'];
$userid = $_SESSION['userid'];

// hanya boleh hapus komentar milik sendiri
$cek = mysqli_query($koneksi, "SELECT * FROM komentarfoto WHERE komentarid='$komentarid' AND userid='$userid'");
if (mysqli_num_rows($cek) == 1) {
    // hapus juga semua balasan dari komentar ini
    mysqli_query($koneksi, "DELETE FROM komentarfoto WHERE parent_id='$komentarid'");
    mysqli_query($koneksi, "DELETE FROM komentarfoto WHERE komentarid='$komentarid'");
}

$halaman_diizinkan = ['home.php', 'admin/index.php', 'index.php'];
$kembali = isset($_POST['kembali']) && in_array($_POST['kembali'], $halaman_diizinkan) ? $_POST['kembali'] : 'index.php';
$tujuan = ($kembali == 'index.php') ? '../index.php' : '../admin/' . str_replace('admin/', '', $kembali);

echo "<script>
location.href='$tujuan';
</script>";
