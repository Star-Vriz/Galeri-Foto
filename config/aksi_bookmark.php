<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda harus login untuk menyimpan foto');
    location.href='../login.php';
    </script>";
    exit;
}

$userid = $_SESSION['userid'];
$fotoid = (int) $_GET['fotoid'];

$halaman_diizinkan = ['home.php', 'admin/index.php', 'index.php', 'explore.php', 'tersimpan.php'];
$kembali = isset($_GET['kembali']) && in_array($_GET['kembali'], $halaman_diizinkan) ? $_GET['kembali'] : 'index.php';
$tujuan = (strpos($kembali, 'admin/') === 0) ? '../' . $kembali : '../' . $kembali;

$cek = mysqli_query($koneksi, "SELECT * FROM bookmark WHERE userid='$userid' AND fotoid='$fotoid'");
if (mysqli_num_rows($cek) == 1) {
    mysqli_query($koneksi, "DELETE FROM bookmark WHERE userid='$userid' AND fotoid='$fotoid'");
} else {
    $tanggal = date('Y-m-d');
    mysqli_query($koneksi, "INSERT INTO bookmark (userid, fotoid, tanggal) VALUES ('$userid','$fotoid','$tanggal')");
}

echo "<script>
location.href='$tujuan#foto-$fotoid';
</script>";
