<?php
session_start();
include 'config/koneksi.php';
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
$sudahlogin = isset($_SESSION['status']) && $_SESSION['status'] == 'login';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore - Galeri Foto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
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
            <nav class="hidden sm:flex items-center gap-1 ml-6">
                <a href="index.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-soft hover:text-brand-ink">Beranda</a>
                <a href="explore.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-accent bg-indigo-50">Explore</a>
                <?php if ($sudahlogin) { ?>
                    <a href="tersimpan.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-soft hover:text-brand-ink">Tersimpan</a>
                <?php } ?>
            </nav>
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
        <section class="max-w-7xl mx-auto px-4 md:px-8 pt-10 pb-6">
            <span class="inline-block px-3 py-1 bg-indigo-50 text-brand-accent text-[10px] font-bold tracking-widest uppercase rounded-full mb-4">Explore</span>
            <h1 class="text-3xl md:text-4xl font-serif-title font-bold leading-tight">Kreator <span class="italic text-brand-accent">Populer</span></h1>
            <p class="text-brand-soft text-sm mt-2">Kreator dengan suka terbanyak dari komunitas.</p>
        </section>

        <main class="max-w-7xl mx-auto px-4 md:px-8 pb-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-12">
                <?php
                $leaderboard = mysqli_query($koneksi, "SELECT user.userid, user.namalengkap, user.username, user.fotoprofil,
                    (SELECT COUNT(*) FROM foto WHERE foto.userid=user.userid) AS jmlfoto,
                    (SELECT COUNT(*) FROM likefoto INNER JOIN foto ON likefoto.fotoid=foto.fotoid WHERE foto.userid=user.userid) AS jmlsuka,
                    (SELECT COUNT(*) FROM follow WHERE follow.following_id=user.userid) AS jmlpengikut
                    FROM user
                    HAVING jmlfoto > 0
                    ORDER BY jmlsuka DESC, jmlpengikut DESC LIMIT 9");
                $peringkat = 1;
                while ($k = mysqli_fetch_array($leaderboard)) {
                ?>
                    <a href="profil.php?userid=<?php echo $k['userid'] ?>" class="flex items-center gap-3 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg transition">
                        <span class="w-6 text-center font-serif-title font-bold text-brand-soft"><?php echo $peringkat++ ?></span>
                        <?php if (!empty($k['fotoprofil'])) { ?>
                            <img src="assets/img/<?php echo $k['fotoprofil'] ?>" alt="" class="w-12 h-12 rounded-full object-cover shrink-0">
                        <?php } else { ?>
                            <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                                <span class="font-serif-title font-bold text-brand-accent"><?php echo strtoupper(substr($k['namalengkap'], 0, 1)) ?></span>
                            </div>
                        <?php } ?>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-sm truncate"><?php echo $k['namalengkap'] ?></p>
                            <p class="text-[11px] text-brand-soft"><?php echo $k['jmlsuka'] ?> suka &middot; <?php echo $k['jmlpengikut'] ?> pengikut</p>
                        </div>
                    </a>
                <?php } ?>
            </div>

            <h2 class="font-serif-title font-bold text-xl mb-4">Foto Terbaru</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                <?php
                $terbaru = mysqli_query($koneksi, "SELECT foto.*, album.namaalbum, user.namalengkap AS pemilik, user.userid AS pemilikid FROM foto INNER JOIN album ON foto.albumid=album.albumid INNER JOIN user ON foto.userid=user.userid ORDER BY foto.fotoid DESC LIMIT 12");
                while ($f = mysqli_fetch_array($terbaru)) { ?>
                    <a href="index.php" class="block bg-white rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition duration-300 group">
                        <div class="overflow-hidden aspect-square">
                            <img src="assets/img/<?php echo $f['lokasifile'] ?>" alt="<?php echo $f['judulfoto'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-3">
                            <p class="font-serif-title font-bold text-sm truncate"><?php echo $f['judulfoto'] ?></p>
                            <p class="text-[11px] text-brand-soft truncate">oleh <?php echo $f['pemilik'] ?></p>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </main>
    </div>

    <footer class="border-t border-gray-100 py-8 text-center">
        <p class="font-serif-title text-brand-ink text-base font-bold mb-1">Galeri Foto</p>
        <p class="text-xs text-brand-soft">&copy; UKK RPL 2026 | APPLE</p>
    </footer>

</body>
</html>
