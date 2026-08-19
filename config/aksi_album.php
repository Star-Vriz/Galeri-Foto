<?php
session_start();
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $namaalbum = mysqli_real_escape_string($koneksi, $_POST['namaalbum']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $tanggal = date('Y-m-d');
    $userid = $_SESSION['userid'];

    $sql = mysqli_query($koneksi, "INSERT INTO album VALUES('','$namaalbum','$deskripsi','$tanggal','$userid')");

    echo "<script>
    alert('Data berhasil disimpan');
    location.href='../admin/album.php';
    </script>";
    exit;
}

if (isset($_POST['edit'])) {
    $albumid = $_POST['albumid'];
    $namaalbum = mysqli_real_escape_string($koneksi, $_POST['namaalbum']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $tanggal = date('Y-m-d');

    $sql = mysqli_query($koneksi, "UPDATE album SET namaalbum='$namaalbum', deskripsi='$deskripsi', tanggalbuat='$tanggal' WHERE albumid='$albumid'");

    echo "<script>
    alert('Data berhasil diperbarui');
    location.href='../admin/album.php';
    </script>";
    exit;
}

if (isset($_POST['hapus'])) {
    $albumid = $_POST['albumid'];

    $sql = mysqli_query($koneksi, "DELETE FROM album WHERE albumid='$albumid'");

    echo "<script>
    alert('Data berhasil dihapus');
    location.href='../admin/album.php';
    </script>";
    exit;
}
