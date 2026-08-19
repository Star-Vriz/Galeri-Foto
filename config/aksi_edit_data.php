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

$userid = $_SESSION['userid'];
$namalengkap = mysqli_real_escape_string($koneksi, $_POST['namalengkap']);
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

mysqli_query($koneksi, "UPDATE user SET namalengkap='$namalengkap', alamat='$alamat' WHERE userid='$userid'");

echo "<script>
alert('Data diri berhasil diperbarui');
location.href='../admin/profil.php';
</script>";
