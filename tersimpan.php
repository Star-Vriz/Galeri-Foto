<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header('Location: login.php');
    exit;
}
$userid = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Tersimpan - Galeri Foto</title>
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
                <a href="explore.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-soft hover:text-brand-ink">Explore</a>
                <a href="tersimpan.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-accent bg-indigo-50">Tersimpan</a>
            </nav>
            <div class="flex items-center gap-2 shrink-0 ml-auto">
                <?php $jmlnotif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM notifikasi WHERE userid='$userid' AND dibaca=0")); ?>
                <a href="notifikasi.php" class="relative w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-100 transition flex items-center justify-center">
                    <i class="fa-regular fa-bell text-brand-ink"></i>
                    <?php if ($jmlnotif > 0) { ?>
                        <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo $jmlnotif > 9 ? '9+' : $jmlnotif ?></span>
                    <?php } ?>
                </a>
                <a href="admin/index.php" class="px-4 py-2.5 rounded-xl hover:bg-gray-100 transition text-xs font-bold uppercase tracking-wider border border-gray-200">Admin</a>
                <a href="config/aksi_logout.php" class="px-4 py-2.5 rounded-xl bg-brand-accent hover:bg-indigo-700 text-white transition text-xs font-bold uppercase tracking-wider">Logout</a>
            </div>
        </div>
    </header>

    <div class="flex-1">
        <section class="max-w-7xl mx-auto px-4 md:px-8 pt-10 pb-6">
            <span class="inline-block px-3 py-1 bg-indigo-50 text-brand-accent text-[10px] font-bold tracking-widest uppercase rounded-full mb-4">Koleksi Pribadi</span>
            <h1 class="text-3xl md:text-4xl font-serif-title font-bold leading-tight">Foto <span class="italic text-brand-accent">Tersimpan</span></h1>
            <p class="text-brand-soft text-sm mt-2">Foto-foto yang sudah kamu simpan untuk dilihat lagi nanti.</p>
        </section>

        <main class="max-w-7xl mx-auto px-4 md:px-8 pb-16">
            <?php
            $query = mysqli_query($koneksi, "SELECT foto.*, album.namaalbum, user.namalengkap AS pemilik, user.userid AS pemilikid, bookmark.tanggal AS tanggalsimpan FROM bookmark INNER JOIN foto ON bookmark.fotoid=foto.fotoid INNER JOIN album ON foto.albumid=album.albumid INNER JOIN user ON foto.userid=user.userid WHERE bookmark.userid='$userid' ORDER BY bookmark.bookmarkid DESC");
            if (mysqli_num_rows($query) == 0) { ?>
                <div class="text-center py-16">
                    <i class="fa-regular fa-bookmark text-4xl text-brand-soft mb-3"></i>
                    <p class="text-brand-soft text-sm">Belum ada foto yang disimpan.</p>
                </div>
            <?php } else { ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                <?php while ($f = mysqli_fetch_array($query)) { ?>
                    <div id="foto-<?php echo $f['fotoid'] ?>" class="bg-white rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition duration-300 group">
                        <div class="overflow-hidden aspect-square relative">
                            <img src="assets/img/<?php echo $f['lokasifile'] ?>" alt="<?php echo $f['judulfoto'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <a href="config/aksi_bookmark.php?fotoid=<?php echo $f['fotoid'] ?>&kembali=tersimpan.php" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-white/90 shadow-sm flex items-center justify-center text-brand-accent hover:bg-rose-50 hover:text-rose-600 transition" title="Hapus dari tersimpan">
                                <i class="fa-solid fa-bookmark text-xs"></i>
                            </a>
                        </div>
                        <div class="p-3">
                            <p class="font-serif-title font-bold text-sm truncate"><?php echo $f['judulfoto'] ?></p>
                            <a href="profil.php?userid=<?php echo $f['pemilikid'] ?>" class="text-[11px] text-brand-soft hover:text-brand-accent hover:underline truncate block">oleh <?php echo $f['pemilik'] ?></a>
                        </div>
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
