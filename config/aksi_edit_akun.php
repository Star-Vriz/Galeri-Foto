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
$passwordlama = md5($_POST['passwordlama']);

$cek = mysqli_query($koneksi, "SELECT * FROM user WHERE userid='$userid' AND password='$passwordlama'");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>
    alert('Password saat ini salah, perubahan dibatalkan');
    location.href='../admin/profil.php';
    </script>";
    exit;
}

$update = [];

if (!empty($_POST['username'])) {
    $username_baru = mysqli_real_escape_string($koneksi, $_POST['username']);
    $cekusername = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username_baru' AND userid!='$userid'");
    if (mysqli_num_rows($cekusername) > 0) {
        echo "<script>
        alert('Username sudah dipakai orang lain');
        location.href='../admin/profil.php';
        </script>";
        exit;
    }
    $update[] = "username='$username_baru'";
}

if (!empty($_POST['passwordbaru'])) {
    $passwordbaru = md5($_POST['passwordbaru']);
    $update[] = "password='$passwordbaru'";
}

if (count($update) > 0) {
    $set = implode(', ', $update);
    mysqli_query($koneksi, "UPDATE user SET $set WHERE userid='$userid'");
    if (!empty($_POST['username'])) {
        $_SESSION['username'] = $_POST['username'];
    }
}

echo "<script>
alert('Akun berhasil diperbarui');
location.href='../admin/profil.php';
</script>";
