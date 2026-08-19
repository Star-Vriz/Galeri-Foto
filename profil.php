<?php
session_start();
include 'config/koneksi.php';
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
$sudahlogin = isset($_SESSION['status']) && $_SESSION['status'] == 'login';

if (!isset($_GET['userid']) || $_GET['userid'] == '') {
    header('Location: index.php');
    exit;
}
$profilid = (int) $_GET['userid'];

$sqluser = mysqli_query($koneksi, "SELECT * FROM user WHERE userid='$profilid'");
if (mysqli_num_rows($sqluser) == 0) {
    header('Location: index.php');
    exit;
}
$profil = mysqli_fetch_array($sqluser);

$jmlfoto = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$profilid'"));
$jmlalbum = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$profilid'"));
$totalsuka = mysqli_num_rows(mysqli_query($koneksi, "SELECT likefoto.* FROM likefoto INNER JOIN foto ON likefoto.fotoid=foto.fotoid WHERE foto.userid='$profilid'"));
$jmlpengikut = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM follow WHERE following_id='$profilid'"));
$jmlmengikuti = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM follow WHERE follower_id='$profilid'"));
$sudahfollow = false;
if ($sudahlogin) {
    $cekfollow = mysqli_query($koneksi, "SELECT * FROM follow WHERE follower_id='$userid' AND following_id='$profilid'");
    $sudahfollow = mysqli_num_rows($cekfollow) == 1;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $profil['namalengkap'] ?> - Galeri Foto</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
<body class="text-brand-ink antialiased min-h-screen flex flex-col">

    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto flex items-center gap-4 px-4 md:px-8 py-3.5">
            <a href="index.php" class="flex items-center gap-2.5 shrink-0">
                <div class="w-9 h-9 bg-brand-accent text-white rounded-xl flex items-center justify-center font-serif-title font-bold">G</div>
                <span class="font-serif-title font-bold text-lg hidden sm:inline">Galeri Foto</span>
            </a>
            <div class="flex items-center gap-2 shrink-0 ml-auto">
                <?php if ($sudahlogin) { ?>
                    <a href="admin/index.php" class="px-4 py-2.5 rounded-xl hover:bg-gray-100 transition text-xs font-bold uppercase tracking-wider border border-gray-200">Admin</a>
                    <a href="config/aksi_logout.php" class="px-4 py-2.5 rounded-xl bg-brand-accent hover:bg-indigo-700 text-white transition text-xs font-bold uppercase tracking-wider">Logout</a>
                <?php } else { ?>
                    <a href="register.php" class="px-4 py-2.5 rounded-xl hover:bg-gray-100 transition text-xs font-bold uppercase tracking-wider border border-gray-200">Daftar</a>
                    <a href="login.php" class="px-4 py-2.5 rounded-xl bg-brand-accent hover:bg-indigo-700 text-white transition text-xs font-bold uppercase tracking-wider">Masuk</a>
                <?php } ?>
            </div>
        </div>
    </header>

    <div class="flex-1">
        <?php if (!empty($profil['banner'])) { ?>
            <img src="assets/img/<?php echo $profil['banner'] ?>" alt="" class="w-full h-32 md:h-44 object-cover">
        <?php } else { ?>
            <div class="bg-gradient-to-r from-indigo-500 to-brand-accent h-32 md:h-44"></div>
        <?php } ?>

        <div class="max-w-5xl mx-auto px-4 md:px-8 -mt-12 pb-4">
            <div class="flex items-end justify-between flex-wrap gap-3">
                <?php if (!empty($profil['fotoprofil'])) { ?>
                    <img src="assets/img/<?php echo $profil['fotoprofil'] ?>" alt="" class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-md">
                <?php } else { ?>
                    <div class="w-24 h-24 rounded-2xl bg-white shadow-md border-4 border-white flex items-center justify-center">
                        <span class="font-serif-title font-bold text-3xl text-brand-accent"><?php echo strtoupper(substr($profil['namalengkap'], 0, 1)) ?></span>
                    </div>
                <?php } ?>

                <?php if ($sudahlogin && $userid == $profilid) { ?>
                    <a href="admin/profil.php" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold uppercase tracking-wider hover:bg-gray-50">Edit Profil</a>
                <?php } elseif ($sudahlogin) { ?>
                    <a href="config/aksi_follow.php?userid=<?php echo $profilid ?>" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition <?php echo $sudahfollow ? 'border border-gray-200 text-brand-ink hover:bg-gray-50' : 'bg-brand-accent text-white hover:bg-indigo-700'; ?>">
                        <?php echo $sudahfollow ? 'Mengikuti' : 'Ikuti'; ?>
                    </a>
                <?php } ?>
            </div>

            <h1 class="font-serif-title font-bold text-2xl md:text-3xl mt-3"><?php echo $profil['namalengkap'] ?></h1>
            <p class="text-brand-soft text-sm">@<?php echo $profil['username'] ?></p>

            <div class="flex flex-wrap gap-6 mt-4 pt-4 border-t border-gray-100 text-sm">
                <div><span class="font-bold text-brand-ink"><?php echo $jmlfoto ?></span> <span class="text-brand-soft">Foto</span></div>
                <div><span class="font-bold text-brand-ink"><?php echo $jmlalbum ?></span> <span class="text-brand-soft">Album</span></div>
                <div><span class="font-bold text-brand-ink"><?php echo $totalsuka ?></span> <span class="text-brand-soft">Suka Diterima</span></div>
                <div><span class="font-bold text-brand-ink"><?php echo $jmlpengikut ?></span> <span class="text-brand-soft">Pengikut</span></div>
                <div><span class="font-bold text-brand-ink"><?php echo $jmlmengikuti ?></span> <span class="text-brand-soft">Mengikuti</span></div>
            </div>
        </div>

        <main class="max-w-5xl mx-auto px-4 md:px-8 pb-16">
            <h2 class="font-serif-title font-bold text-xl mb-4">Karya Foto</h2>
            <?php
            $sqlfoto = mysqli_query($koneksi, "SELECT foto.*, album.namaalbum, (SELECT COUNT(*) FROM likefoto WHERE likefoto.fotoid=foto.fotoid) AS jmlsuka FROM foto INNER JOIN album ON foto.albumid=album.albumid WHERE foto.userid='$profilid' ORDER BY foto.fotoid DESC");
            if (mysqli_num_rows($sqlfoto) == 0) { ?>
                <div class="text-center py-16">
                    <i class="fa-regular fa-image text-4xl text-brand-soft mb-3"></i>
                    <p class="text-brand-soft text-sm">Belum ada foto yang diunggah.</p>
                </div>
            <?php } else { ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                <?php while ($f = mysqli_fetch_array($sqlfoto)) { ?>
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition duration-300 group cursor-pointer" onclick="document.getElementById('lihat<?php echo $f['fotoid'] ?>').classList.remove('hidden')">
                        <div class="overflow-hidden aspect-square">
                            <img src="assets/img/<?php echo $f['lokasifile'] ?>" alt="<?php echo $f['judulfoto'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-4">
                            <p class="font-serif-title font-bold text-base truncate"><?php echo $f['judulfoto'] ?></p>
                            <p class="text-xs text-brand-soft mt-0.5"><?php echo $f['namaalbum'] ?></p>
                            <div class="flex items-center gap-1.5 mt-3 pt-3 border-t border-gray-100 text-xs font-semibold text-brand-soft">
                                <i class="fa-solid fa-heart text-rose-500"></i> <?php echo $f['jmlsuka'] ?>
                            </div>
                        </div>
                    </div>

                    <div id="lihat<?php echo $f['fotoid'] ?>" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
                        <img src="assets/img/<?php echo $f['lokasifile'] ?>" alt="" class="max-h-[90vh] max-w-full rounded-xl object-contain">
                    </div>
                <?php } ?>
            </div>
            <?php } ?>
        </main>
    </div>

    <footer class="border-t border-gray-100 py-8 text-center">
        <p class="font-serif-title text-brand-ink text-base font-bold mb-1">Galeri Foto</p>
        <p class="text-xs text-brand-soft">&copy; UKK RPL 2026 | APPLE</p>
    </footer>

</body>
</html>
