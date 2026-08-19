<?php
// Salin file ini menjadi koneksi.php lalu sesuaikan dengan pengaturan database lokal Anda.
$hostname = 'localhost';
$userdb = 'root';
$passdb = '';
$namedb = 'galeri_melon';
$port = '3307';

$koneksi = mysqli_connect($hostname, $userdb, $passdb, $namedb, $port);
