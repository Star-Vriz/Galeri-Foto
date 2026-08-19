<?php
session_start();
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $judulfoto = mysqli_real_escape_string($koneksi, $_POST['judulfoto']);
    $deskripsifoto = mysqli_real_escape_string($koneksi, $_POST['deskripsifoto']);
    $tanggalunggah = date('Y-m-d');
    $albumid = $_POST['albumid'];
    $userid = $_SESSION['userid'];
    $foto = $_FILES['lokasifile']['name'];
    $tmp = $_FILES['lokasifile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namafoto = rand() . '_' . $foto;

    move_uploaded_file($tmp, $lokasi . $namafoto);

    $sql = mysqli_query($koneksi, "INSERT INTO foto VALUES('','$judulfoto','$deskripsifoto','$tanggalunggah','$namafoto','$albumid','$userid')");

    echo "<script>
    alert('Data berhasil disimpan');
    location.href='../admin/foto.php';
    </script>";
    exit;
}

if (isset($_POST['edit'])) {
    $fotoid = $_POST['fotoid'];
    $judulfoto = mysqli_real_escape_string($koneksi, $_POST['judulfoto']);
    $deskripsifoto = mysqli_real_escape_string($koneksi, $_POST['deskripsifoto']);
    $tanggalunggah = date('Y-m-d');
    $albumid = $_POST['albumid'];
    $lokasi = '../assets/img/';
    $foto = $_FILES['lokasifile']['name'];
    $tmp = $_FILES['lokasifile']['tmp_name'];

    if (empty($foto)) {
        $sql = mysqli_query($koneksi, "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', tanggalunggah='$tanggalunggah', albumid='$albumid' WHERE fotoid='$fotoid'");
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE fotoid='$fotoid'");
        $data = mysqli_fetch_array($query);
        if (is_file('../assets/img/' . $data['lokasifile'])) {
            unlink('../assets/img/' . $data['lokasifile']);
        }
        $namafoto = rand() . '_' . $foto;
        move_uploaded_file($tmp, $lokasi . $namafoto);
        $sql = mysqli_query($koneksi, "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', tanggalunggah='$tanggalunggah', lokasifile='$namafoto', albumid='$albumid' WHERE fotoid='$fotoid'");
    }

    echo "<script>
    alert('Data berhasil diperbarui');
    location.href='../admin/foto.php';
    </script>";
    exit;
}

if (isset($_POST['hapus'])) {
    $fotoid = $_POST['fotoid'];

    $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE fotoid='$fotoid'");
    $data = mysqli_fetch_array($query);
    if (is_file('../assets/img/' . $data['lokasifile'])) {
        unlink('../assets/img/' . $data['lokasifile']);
    }

    $sql = mysqli_query($koneksi, "DELETE FROM foto WHERE fotoid='$fotoid'");

    echo "<script>
    alert('Data berhasil dihapus');
    location.href='../admin/foto.php';
    </script>";
    exit;
}
