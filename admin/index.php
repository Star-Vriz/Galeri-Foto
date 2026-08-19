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
    <title>Beranda - Admin</title>
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

    <aside class="md:w-60 shrink-0 bg-white border-b md:border-b-0 md:border-r border-gray-100 md:min-h-screen">
        <div class="flex items-center justify-between md:block px-4 md:px-5 py-4">
            <a href="../index.php" class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-brand-accent text-white rounded-xl flex items-center justify-center font-serif-title font-bold">G</div>
                <span class="font-serif-title font-bold text-base">Galeri Foto</span>
            </a>
            <button onclick="document.getElementById('navmenu').classList.toggle('hidden')" class="md:hidden w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center"><i class="fa-solid fa-bars"></i></button>
        </div>
        <nav id="navmenu" class="hidden md:block px-3 pb-4 space-y-1">
            <a href="home.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-brand-ink text-sm font-semibold"><i class="fa-solid fa-house w-4"></i> Home</a>
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

    <main class="flex-1 px-4 md:px-8 py-6">
        <h1 class="font-serif-title font-bold text-2xl mb-1">Semua Foto Komunitas</h1>
        <p class="text-brand-soft text-sm mb-6">Lihat, sukai, dan komentari foto dari semua pengguna.</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            $perhalaman = 16;
            $halaman = isset($_GET['halaman']) ? max(1, (int) $_GET['halaman']) : 1;
            $offset = ($halaman - 1) * $perhalaman;

            $totalbaris = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM foto"));
            $totalfoto = $totalbaris['total'];
            $totalhalaman = max(1, ceil($totalfoto / $perhalaman));

            $query = mysqli_query($koneksi, "SELECT * FROM foto INNER JOIN user ON foto.userid=user.userid INNER JOIN album ON foto.albumid=album.albumid ORDER BY foto.fotoid DESC LIMIT $perhalaman OFFSET $offset");
            while ($data = mysqli_fetch_array($query)) {
                $fotoid = $data['fotoid'];
                $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                $sudahsuka = mysqli_num_rows($ceksuka) == 1;
                $jmlsuka = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid'"));
                $jmlkomen = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM komentarfoto WHERE fotoid='$fotoid'"));
            ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <img src="../assets/img/<?php echo $data['lokasifile'] ?>" alt="" class="w-full aspect-square object-cover cursor-pointer" onclick="document.getElementById('komentar<?php echo $fotoid ?>').classList.remove('hidden')">
                    <div class="p-3 flex items-center justify-between text-xs font-semibold text-brand-soft">
                        <a href="../config/proses_like.php?fotoid=<?php echo $fotoid ?>&kembali=admin/index.php" class="flex items-center gap-1.5 <?php echo $sudahsuka ? 'text-rose-500' : ''; ?>">
                            <i class="<?php echo $sudahsuka ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i> <?php echo $jmlsuka ?>
                        </a>
                        <button onclick="document.getElementById('komentar<?php echo $fotoid ?>').classList.remove('hidden')" class="flex items-center gap-1.5">
                            <i class="fa-regular fa-comment"></i> <?php echo $jmlkomen ?>
                        </button>
                        <?php $ceksimpan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bookmark WHERE userid='$userid' AND fotoid='$fotoid'")) == 1; ?>
                        <a href="../config/aksi_bookmark.php?fotoid=<?php echo $fotoid ?>&kembali=admin/index.php" class="<?php echo $ceksimpan ? 'text-brand-accent' : ''; ?>">
                            <i class="<?php echo $ceksimpan ? 'fa-solid' : 'fa-regular'; ?> fa-bookmark"></i>
                        </a>
                    </div>
                </div>

                <div id="komentar<?php echo $fotoid ?>" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-2xl overflow-hidden flex flex-col md:flex-row relative shadow-2xl">
                        <button onclick="document.getElementById('komentar<?php echo $fotoid ?>').classList.add('hidden')" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-white shadow-md flex items-center justify-center hover:bg-gray-100 transition"><i class="fa-solid fa-xmark"></i></button>
                        <div class="md:w-3/5 bg-gray-50 flex items-center justify-center">
                            <img src="../assets/img/<?php echo $data['lokasifile'] ?>" alt="" class="max-h-[90vh] w-full object-contain">
                        </div>
                        <div class="md:w-2/5 p-5 overflow-y-auto max-h-[90vh]">
                            <p class="text-xs font-bold uppercase tracking-wider text-brand-accent"><?php echo $data['namaalbum'] ?></p>
                            <h3 class="font-serif-title font-bold text-xl mt-1"><?php echo $data['judulfoto'] ?></h3>
                            <p class="text-[11px] text-brand-soft mt-1"><a href="../profil.php?userid=<?php echo $data['userid'] ?>" class="text-brand-accent font-semibold hover:underline"><?php echo $data['namalengkap'] ?></a> &middot; <?php echo $data['tanggalunggah'] ?></p>
                            <p class="text-sm mt-3"><?php echo $data['deskripsifoto'] ?></p>

                            <?php
                            $albumidnow = $data['albumid'];
                            $satualbum = mysqli_query($koneksi, "SELECT * FROM foto WHERE albumid='$albumidnow' AND fotoid != '$fotoid' LIMIT 10");
                            if (mysqli_num_rows($satualbum) > 0) { ?>
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-brand-soft mb-2">Foto Lain di Album Ini</p>
                                    <div class="flex gap-2 overflow-x-auto pb-1">
                                        <?php while ($fs = mysqli_fetch_array($satualbum)) { ?>
                                            <img src="../assets/img/<?php echo $fs['lokasifile'] ?>" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0 cursor-pointer border border-gray-100 hover:border-brand-accent transition"
                                                onclick="document.getElementById('komentar<?php echo $fotoid ?>').classList.add('hidden'); document.getElementById('komentar<?php echo $fs['fotoid'] ?>').classList.remove('hidden')">
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                                <?php
                                $komentar = mysqli_query($koneksi, "SELECT * FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE komentarfoto.fotoid='$fotoid' AND komentarfoto.parent_id IS NULL ORDER BY komentarfoto.komentarid DESC");
                                while ($row = mysqli_fetch_array($komentar)) {
                                    $komentarid = $row['komentarid'];
                                ?>
                                    <div class="text-xs flex gap-2">
                                        <?php if (!empty($row['fotoprofil'])) { ?>
                                            <img src="../assets/img/<?php echo $row['fotoprofil'] ?>" alt="" class="w-7 h-7 rounded-full object-cover shrink-0 mt-0.5">
                                        <?php } else { ?>
                                            <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                                                <span class="font-serif-title font-bold text-[10px] text-brand-accent"><?php echo strtoupper(substr($row['namalengkap'], 0, 1)) ?></span>
                                            </div>
                                        <?php } ?>
                                        <div class="flex-1">
                                        <p><span class="font-bold"><?php echo $row['namalengkap'] ?></span>
                                        <span class="text-brand-soft"> — <?php echo $row['isikomentar'] ?></span></p>
                                        <div class="flex items-center gap-3 mt-1 text-[11px] text-brand-soft">
                                            <button type="button" onclick="document.getElementById('balas<?php echo $komentarid ?>').classList.toggle('hidden')" class="font-semibold hover:text-brand-accent">Balas</button>
                                            <?php if ($row['userid'] == $userid) { ?>
                                                <form action="../config/aksi_hapus_komentar.php" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                    <input type="hidden" name="komentarid" value="<?php echo $komentarid ?>">
                                                    <input type="hidden" name="kembali" value="admin/index.php">
                                                    <button type="submit" class="font-semibold hover:text-rose-600">Hapus</button>
                                                </form>
                                            <?php } ?>
                                        </div>

                                        <?php
                                        $balasan = mysqli_query($koneksi, "SELECT * FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE komentarfoto.parent_id='$komentarid' ORDER BY komentarfoto.komentarid ASC");
                                        while ($rb = mysqli_fetch_array($balasan)) { ?>
                                            <div class="ml-4 mt-2 pl-3 border-l-2 border-gray-100 flex gap-2">
                                                <?php if (!empty($rb['fotoprofil'])) { ?>
                                                    <img src="../assets/img/<?php echo $rb['fotoprofil'] ?>" alt="" class="w-6 h-6 rounded-full object-cover shrink-0 mt-0.5">
                                                <?php } else { ?>
                                                    <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                                                        <span class="font-serif-title font-bold text-[9px] text-brand-accent"><?php echo strtoupper(substr($rb['namalengkap'], 0, 1)) ?></span>
                                                    </div>
                                                <?php } ?>
                                                <div class="flex-1">
                                                <p><span class="font-bold"><?php echo $rb['namalengkap'] ?></span>
                                                <span class="text-brand-soft"> — <?php echo $rb['isikomentar'] ?></span></p>
                                                <?php if ($rb['userid'] == $userid) { ?>
                                                    <form action="../config/aksi_hapus_komentar.php" method="POST" onsubmit="return confirm('Hapus balasan ini?')" class="mt-0.5">
                                                        <input type="hidden" name="komentarid" value="<?php echo $rb['komentarid'] ?>">
                                                        <input type="hidden" name="kembali" value="admin/index.php">
                                                        <button type="submit" class="text-[11px] text-brand-soft font-semibold hover:text-rose-600">Hapus</button>
                                                    </form>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <form action="../config/proses_komentar.php" method="POST" id="balas<?php echo $komentarid ?>" class="hidden ml-4 mt-2 flex gap-2">
                                            <input type="hidden" name="fotoid" value="<?php echo $fotoid ?>">
                                            <input type="hidden" name="parent_id" value="<?php echo $komentarid ?>">
                                            <input type="hidden" name="kembali" value="admin/index.php">
                                            <input type="text" name="isikomentar" placeholder="Tulis balasan..." required class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-[11px] focus:outline-none focus:border-brand-accent">
                                            <button type="submit" name="kirimkomentar" class="px-3 py-1.5 bg-brand-accent hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg transition">Kirim</button>
                                        </form>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <form action="../config/proses_komentar.php" method="POST" class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
                                <input type="hidden" name="fotoid" value="<?php echo $fotoid ?>">
                                <input type="hidden" name="kembali" value="admin/index.php">
                                <input type="text" name="isikomentar" placeholder="Tulis komentar..." required class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-brand-accent">
                                <button type="submit" name="kirimkomentar" class="px-4 py-2 bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition">Kirim</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if ($totalhalaman > 1) { ?>
            <div class="flex items-center justify-center gap-2 mt-10">
                <?php if ($halaman > 1) { ?>
                    <a href="?halaman=<?php echo $halaman - 1 ?>" class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-sm"><i class="fa-solid fa-chevron-left"></i></a>
                <?php } ?>
                <?php for ($p = 1; $p <= $totalhalaman; $p++) {
                    if ($p == 1 || $p == $totalhalaman || abs($p - $halaman) <= 1) { ?>
                        <a href="?halaman=<?php echo $p ?>" class="w-10 h-10 rounded-xl <?php echo $p == $halaman ? 'bg-brand-accent text-white' : 'border border-gray-200 hover:bg-gray-50 text-brand-ink'; ?> flex items-center justify-center text-sm font-semibold"><?php echo $p ?></a>
                    <?php } elseif ($p == $halaman - 2 || $p == $halaman + 2) { ?>
                        <span class="text-brand-soft px-1">&hellip;</span>
                    <?php } } ?>
                <?php if ($halaman < $totalhalaman) { ?>
                    <a href="?halaman=<?php echo $halaman + 1 ?>" class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-sm"><i class="fa-solid fa-chevron-right"></i></a>
                <?php } ?>
            </div>
        <?php } ?>
    </main>

</body>
</html>
