<?php 
session_start();
session_destroy();
echo "<script>
    alert('logout berhasil');
    location.href='../index.php';
    </script>";

?>