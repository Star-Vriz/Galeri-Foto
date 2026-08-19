<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['status'] != 'login') {
    echo "<script>alert('Anda belum Login!'); location.href='../index.php';</script>";
    exit;
}
$userid = $_SESSION['userid'];
$jmlnotif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM notifikasi WHERE userid='$userid' AND dibaca=0"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
      tailwind.config = { theme: { extend: {
        colors: { brand: { accent: '#4F46E5', ink: '#1C1B1A', soft: '#6B7280' } },
        fontFamily: { serif: ['"Playfair Display"', 'serif'], sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
      } } }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAF9; }
        .font-serif-title { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="text-brand-ink antialiased md:flex">

    <!-- SIDEBAR -->
    <aside class="md:w-60 shrink-0 bg-white border-b md:border-b-0 md:border-r border-gray-100 md:min-h-screen">
        <div class="flex items-center justify-between md:block px-4 md:px-5 py-4">
            <a href="../index.php" class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-brand-accent text-white rounded-xl flex items-center justify-center font-serif-title font-bold">G</div>
                <span class="font-serif-title font-bold text-base">Galeri Foto</span>
            </a>
            <button onclick="document.getElementById('navmenu').classList.toggle('hidden')" class="md:hidden w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center"><i class="fa-solid fa-bars"></i></button>
        </div>
        <nav id="navmenu" class="hidden md:block px-3 pb-4 space-y-1">
            <a href="home.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-indigo-50 text-brand-accent text-sm font-semibold"><i class="fa-solid fa-house w-4"></i> Home</a>
            <a href="album.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-brand-ink text-sm font-semibold"><i class="fa-solid fa-layer-group w-4"></i> Album</a>
            <a href="foto.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-brand-ink text-sm font-semibold"><i class="fa-solid fa-image w-4"></i> Foto</a>
            <a href="profil.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-brand-ink text-sm font-semibold"><i class="fa-solid fa-user w-4"></i> Profil Saya</a>
            <a href="../explore.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-brand-ink text-sm font-semibold"><i class="fa-solid fa-compass w-4"></i> Explore</a>
            <a href="../tersimpan.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-brand-ink text-sm font-semibold"><i class="fa-regular fa-bookmark w-4"></i> Tersimpan</a>
            <a href="../notifikasi.php" class="flex items-center justify-between px-3 py-2.5 rounded-xl hover:bg-gray-50 text-brand-ink text-sm font-semibold">
                <span class="flex items-center gap-3"><i class="fa-regular fa-bell w-4"></i> Notifikasi</span>
                <?php if ($jmlnotif > 0) { ?><span class="min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo $jmlnotif > 9 ? '9+' : $jmlnotif ?></span><?php } ?>
            </a>
            <a href="../config/aksi_logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-rose-50 text-rose-600 text-sm font-semibold mt-4"><i class="fa-solid fa-right-from-bracket w-4"></i> Logout</a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 px-4 md:px-8 py-6">
        <h1 class="font-serif-title font-bold text-2xl mb-1">Beranda</h1>
        <p class="text-brand-soft text-sm mb-6">Ringkasan album dan koleksi fotomu.</p>

        <div class="flex flex-wrap gap-2 mb-6">
            <a href="home.php" class="px-4 py-2 rounded-xl border <?php echo !isset($_GET['albumid']) ? 'bg-brand-accent text-white border-brand-accent' : 'border-gray-200 text-brand-ink hover:bg-gray-50'; ?> text-xs font-bold">Semua</a>
            <?php
            $album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
            while ($row = mysqli_fetch_array($album)) {
                $aktif = isset($_GET['albumid']) && $_GET['albumid'] == $row['albumid'];
            ?>
                <a href="home.php?albumid=<?php echo $row['albumid'] ?>" class="px-4 py-2 rounded-xl border <?php echo $aktif ? 'bg-brand-accent text-white border-brand-accent' : 'border-gray-200 text-brand-ink hover:bg-gray-50'; ?> text-xs font-bold"><?php echo $row['namaalbum'] ?></a>
            <?php } ?>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            if (isset($_GET['albumid'])) {
                $albumid = $_GET['albumid'];
                $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid' AND albumid='$albumid'");
            } else {
                $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid'");
            }
            while ($data = mysqli_fetch_array($query)) {
                $fotoid = $data['fotoid'];
                $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                $sudahsuka = mysqli_num_rows($ceksuka) == 1;
                $jmlsuka = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid'"));
            ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <img src="../assets/img/<?php echo $data['lokasifile'] ?>" alt="" class="w-full h-40 object-cover">
                    <div class="p-3 flex items-center justify-between">
                        <a href="../config/proses_like.php?fotoid=<?php echo $fotoid ?>&kembali=home.php" class="text-xs font-semibold flex items-center gap-1.5 <?php echo $sudahsuka ? 'text-rose-500' : 'text-brand-soft'; ?>">
                            <i class="<?php echo $sudahsuka ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i> <?php echo $jmlsuka ?>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

</body>
</html>
