<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda harus login untuk mengikuti kreator');
    location.href='../login.php';
    </script>";
    exit;
}

$userid = $_SESSION['userid'];
$target = (int) $_GET['userid'];

if ($target != $userid) {
    $cek = mysqli_query($koneksi, "SELECT * FROM follow WHERE follower_id='$userid' AND following_id='$target'");
    if (mysqli_num_rows($cek) == 1) {
        mysqli_query($koneksi, "DELETE FROM follow WHERE follower_id='$userid' AND following_id='$target'");
    } else {
        $tanggal = date('Y-m-d');
        mysqli_query($koneksi, "INSERT INTO follow (follower_id, following_id, tanggal) VALUES ('$userid','$target','$tanggal')");

        $sekarang = date('Y-m-d H:i:s');
        mysqli_query($koneksi, "INSERT INTO notifikasi (userid, dari_userid, tipe, fotoid, dibaca, tanggal) VALUES ('$target','$userid','follow',NULL,0,'$sekarang')");
    }
}

echo "<script>
location.href='../profil.php?userid=$target';
</script>";
