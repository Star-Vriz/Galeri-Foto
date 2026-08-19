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
$lokasi = '../assets/img/';
$update = [];

if (!empty($_FILES['fotoprofil']['name'])) {
    $namafile = rand() . '_' . $_FILES['fotoprofil']['name'];
    move_uploaded_file($_FILES['fotoprofil']['tmp_name'], $lokasi . $namafile);
    $update[] = "fotoprofil='$namafile'";
}

if (!empty($_FILES['banner']['name'])) {
    $namabanner = rand() . '_' . $_FILES['banner']['name'];
    move_uploaded_file($_FILES['banner']['tmp_name'], $lokasi . $namabanner);
    $update[] = "banner='$namabanner'";
}

if (count($update) > 0) {
    $set = implode(', ', $update);
    mysqli_query($koneksi, "UPDATE user SET $set WHERE userid='$userid'");
}

echo "<script>
alert('Profil berhasil diperbarui');
location.href='../admin/profil.php';
</script>";
