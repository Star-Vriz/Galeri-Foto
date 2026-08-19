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
    <title>Website Galeri Foto</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: { accent: '#4F46E5', accent2: '#F59E0B', ink: '#1C1B1A', soft: '#6B7280' }
            },
            fontFamily: {
              serif: ['"Playfair Display"', 'serif'],
              sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            }
          }
        }
      }
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
                <span class="font-serif-title font-bold text-lg text-brand-ink hidden sm:inline">Galeri Foto</span>
            </a>
            <nav class="hidden sm:flex items-center gap-1 ml-6">
                <a href="index.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-accent bg-indigo-50">Beranda</a>
                <a href="explore.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-soft hover:text-brand-ink">Explore</a>
                <?php if ($sudahlogin) { ?>
                    <a href="tersimpan.php" class="px-3 py-2 rounded-lg text-sm font-semibold text-brand-soft hover:text-brand-ink">Tersimpan</a>
                <?php } ?>
            </nav>
            <div class="flex items-center gap-2 shrink-0 ml-auto">
                <?php if ($sudahlogin) {
                    $jmlnotif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM notifikasi WHERE userid='$userid' AND dibaca=0"));
                ?>
                    <a href="notifikasi.php" class="relative w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-100 transition flex items-center justify-center">
                        <i class="fa-regular fa-bell text-brand-ink"></i>
                        <?php if ($jmlnotif > 0) { ?>
                            <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo $jmlnotif > 9 ? '9+' : $jmlnotif ?></span>
                        <?php } ?>
                    </a>
                    <a href="admin/index.php" class="px-4 py-2.5 rounded-xl text-brand-ink hover:bg-gray-100 transition text-xs font-bold uppercase tracking-wider border border-gray-200">Admin</a>
                    <a href="config/aksi_logout.php" class="px-4 py-2.5 rounded-xl bg-brand-accent hover:bg-indigo-700 text-white transition text-xs font-bold uppercase tracking-wider">Logout</a>
                <?php } else { ?>
                    <a href="register.php" class="px-4 py-2.5 rounded-xl text-brand-ink hover:bg-gray-100 transition text-xs font-bold uppercase tracking-wider border border-gray-200">Daftar</a>
                    <a href="login.php" class="px-4 py-2.5 rounded-xl bg-brand-accent hover:bg-indigo-700 text-white transition text-xs font-bold uppercase tracking-wider">Masuk</a>
                <?php } ?>
            </div>
        </div>
    </header>

    <div class="flex-1">
    <section class="max-w-7xl mx-auto px-4 md:px-8 pt-12 pb-8 text-center md:text-left">
        <span class="inline-block px-3 py-1 bg-indigo-50 text-brand-accent text-[10px] font-bold tracking-widest uppercase rounded-full mb-4">Galeri Komunitas</span>
        <h1 class="text-3xl md:text-5xl font-serif-title font-bold leading-tight text-brand-ink">
            Foto <span class="italic text-brand-accent">Terpopuler</span> Minggu Ini
        </h1>
        <p class="text-brand-soft text-sm md:text-base leading-relaxed mt-3 max-w-xl mx-auto md:mx-0">
            Foto-foto dengan suka terbanyak dari komunitas, beri suka dan tinggalkan komentar untuk karya favoritmu.
        </p>
    </section>

    <section class="max-w-7xl mx-auto px-4 md:px-8 pb-6">
        <form action="index.php" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-brand-soft text-sm"></i>
                <input type="text" name="cari" value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>" placeholder="Cari judul foto..." class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-brand-accent">
            </div>
            <select name="albumid" class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-accent">
                <option value="">Semua Album</option>
                <?php
                $sql_semua_album = mysqli_query($koneksi, "SELECT * FROM album");
                while ($ab = mysqli_fetch_array($sql_semua_album)) {
                    $terpilih = (isset($_GET['albumid']) && $_GET['albumid'] == $ab['albumid']) ? 'selected' : '';
                ?>
                    <option value="<?php echo $ab['albumid'] ?>" <?php echo $terpilih ?>><?php echo $ab['namaalbum'] ?></option>
                <?php } ?>
            </select>
            <button type="submit" class="px-6 py-3 bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition">Cari</button>
            <?php if (isset($_GET['cari']) || isset($_GET['albumid'])) { ?>
                <a href="index.php" class="px-6 py-3 border border-gray-200 text-brand-ink text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-gray-50 text-center">Reset</a>
            <?php } ?>
        </form>
    </section>

    <main class="max-w-7xl mx-auto px-4 md:px-8 pb-16">
        <?php
        $cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';
        $filteralbum = isset($_GET['albumid']) && $_GET['albumid'] != '' ? $_GET['albumid'] : '';
        $modepencarian = ($cari != '' || $filteralbum != '');

        $perhalaman = 12;
        $halaman = isset($_GET['halaman']) ? max(1, (int) $_GET['halaman']) : 1;
        $offset = ($halaman - 1) * $perhalaman;

        $basequery = "FROM foto INNER JOIN album ON foto.albumid=album.albumid INNER JOIN user ON foto.userid=user.userid";
        if ($modepencarian) {
            $where = [];
            if ($cari != '') $where[] = "foto.judulfoto LIKE '%$cari%'";
            if ($filteralbum != '') $where[] = "foto.albumid='$filteralbum'";
            $whereclause = implode(' AND ', $where);
            $basequery .= " WHERE $whereclause";
            $urutan = "ORDER BY foto.fotoid DESC";
        } else {
            $urutan = "ORDER BY (SELECT COUNT(*) FROM likefoto WHERE likefoto.fotoid = foto.fotoid) DESC, foto.fotoid DESC";
        }

        $totalbaris = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) AS total $basequery"));
        $totalfoto = $totalbaris['total'];
        $totalhalaman = max(1, ceil($totalfoto / $perhalaman));

        $query = mysqli_query($koneksi, "SELECT foto.*, album.namaalbum, user.namalengkap AS pemilik, user.userid AS pemilikid, user.fotoprofil AS pemilikfoto, (SELECT COUNT(*) FROM likefoto WHERE likefoto.fotoid = foto.fotoid) AS jmlsuka $basequery $urutan LIMIT $perhalaman OFFSET $offset");

        $jumlahhasil = mysqli_num_rows($query);
        if ($modepencarian) { ?>
            <p class="text-sm text-brand-soft mb-4"><?php echo $totalfoto ?> foto ditemukan<?php echo $cari != '' ? ' untuk "' . htmlspecialchars($cari) . '"' : ''; ?></p>
        <?php }

        if ($jumlahhasil == 0) { ?>
            <div class="text-center py-16">
                <i class="fa-regular fa-image text-4xl text-brand-soft mb-3"></i>
                <p class="text-brand-soft text-sm">Tidak ada foto yang cocok.</p>
            </div>
        <?php } ?>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php
            while ($data = mysqli_fetch_array($query)) {
                $fotoid = $data['fotoid'];
                $jmlsuka = $data['jmlsuka'];
                $jmlkomen = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM komentarfoto WHERE fotoid='$fotoid'"));
                $sudahsuka = false;
                if ($sudahlogin) {
                    $sudahsuka = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'")) == 1;
                }
            ?>
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition duration-300 group cursor-pointer" onclick="document.getElementById('modal<?php echo $fotoid ?>').classList.remove('hidden')">
                    <div class="overflow-hidden aspect-square">
                        <img src="assets/img/<?php echo $data['lokasifile'] ?>" alt="<?php echo $data['judulfoto'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <div class="p-4">
                        <p class="font-serif-title font-bold text-base text-brand-ink truncate"><?php echo $data['judulfoto'] ?></p>
                        <a href="profil.php?userid=<?php echo $data['pemilikid'] ?>" onclick="event.stopPropagation()" class="flex items-center gap-2.5 mt-3 group/creator">
                            <?php if (!empty($data['pemilikfoto'])) { ?>
                                <img src="assets/img/<?php echo $data['pemilikfoto'] ?>" alt="" class="w-9 h-9 rounded-full object-cover border border-gray-100 shrink-0">
                            <?php } else { ?>
                                <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                                    <span class="font-serif-title font-bold text-xs text-brand-accent"><?php echo strtoupper(substr($data['pemilik'], 0, 1)) ?></span>
                                </div>
                            <?php } ?>
                            <span class="text-xs font-semibold text-brand-ink group-hover/creator:text-brand-accent truncate"><?php echo $data['pemilik'] ?></span>
                        </a>
                        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100 text-xs font-semibold text-brand-soft">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-heart text-rose-500"></i> <?php echo $jmlsuka ?></span>
                            <span class="flex items-center gap-1.5"><i class="fa-regular fa-comment text-brand-accent"></i> <?php echo $jmlkomen ?></span>
                        </div>
                    </div>
                </div>

                <div id="modal<?php echo $fotoid ?>" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-2xl overflow-hidden flex flex-col md:flex-row relative shadow-2xl">
                        <button onclick="event.stopPropagation(); document.getElementById('modal<?php echo $fotoid ?>').classList.add('hidden')" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-white shadow-md text-brand-ink flex items-center justify-center hover:bg-gray-100 transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="md:w-3/5 bg-gray-50 flex items-center justify-center">
                            <img src="assets/img/<?php echo $data['lokasifile'] ?>" alt="" class="max-h-[90vh] w-full object-contain">
                        </div>
                        <div class="md:w-2/5 p-5 overflow-y-auto max-h-[90vh]">
                            <p class="text-xs font-bold uppercase tracking-wider text-brand-accent"><?php echo $data['namaalbum'] ?></p>
                            <h3 class="font-serif-title font-bold text-xl text-brand-ink mt-1"><?php echo $data['judulfoto'] ?></h3>
                            <a href="profil.php?userid=<?php echo $data['pemilikid'] ?>" class="text-[11px] text-brand-accent font-semibold hover:underline">oleh <?php echo $data['pemilik'] ?></a>
                            <p class="text-[11px] text-brand-soft mt-1"><?php echo $data['tanggalunggah'] ?></p>
                            <p class="text-sm text-brand-ink/90 mt-3"><?php echo $data['deskripsifoto'] ?></p>

                            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100 text-sm">
                                <?php if ($sudahlogin) { ?>
                                    <a href="config/proses_like.php?fotoid=<?php echo $fotoid ?>&kembali=index.php" class="flex items-center gap-1.5 <?php echo $sudahsuka ? 'text-rose-500' : 'text-brand-soft hover:text-brand-ink'; ?>">
                                        <i class="<?php echo $sudahsuka ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i> <?php echo $jmlsuka ?> suka
                                    </a>
                                <?php } else { ?>
                                    <span class="flex items-center gap-1.5 text-brand-soft"><i class="fa-regular fa-heart"></i> <?php echo $jmlsuka ?> suka</span>
                                <?php } ?>
                                <span class="flex items-center gap-1.5 text-brand-soft"><i class="fa-regular fa-comment"></i> <?php echo $jmlkomen ?> komentar</span>
                                <?php if ($sudahlogin) {
                                    $ceksimpan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bookmark WHERE userid='$userid' AND fotoid='$fotoid'")) == 1;
                                ?>
                                    <a href="config/aksi_bookmark.php?fotoid=<?php echo $fotoid ?>&kembali=index.php" class="flex items-center gap-1.5 ml-auto <?php echo $ceksimpan ? 'text-brand-accent' : 'text-brand-soft hover:text-brand-ink'; ?>">
                                        <i class="<?php echo $ceksimpan ? 'fa-solid' : 'fa-regular'; ?> fa-bookmark"></i> <?php echo $ceksimpan ? 'Tersimpan' : 'Simpan'; ?>
                                    </a>
                                <?php } ?>
                            </div>

                            <?php
                            $albumidnow = $data['albumid'];
                            $satualbum = mysqli_query($koneksi, "SELECT * FROM foto WHERE albumid='$albumidnow' AND fotoid != '$fotoid' LIMIT 10");
                            if (mysqli_num_rows($satualbum) > 0) { ?>
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-brand-soft mb-2">Foto Lain di Album Ini</p>
                                    <div class="flex gap-2 overflow-x-auto pb-1">
                                        <?php while ($fs = mysqli_fetch_array($satualbum)) { ?>
                                            <img src="assets/img/<?php echo $fs['lokasifile'] ?>" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0 cursor-pointer border border-gray-100 hover:border-brand-accent transition"
                                                onclick="document.getElementById('modal<?php echo $fotoid ?>').classList.add('hidden'); document.getElementById('modal<?php echo $fs['fotoid'] ?>').classList.remove('hidden')">
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="mt-4 pt-4 border-t border-gray-100 space-y-3" id="komentar-<?php echo $fotoid ?>">
                                <?php
                                $komentar = mysqli_query($koneksi, "SELECT * FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE komentarfoto.fotoid='$fotoid' AND komentarfoto.parent_id IS NULL ORDER BY komentarfoto.komentarid DESC");
                                while ($row = mysqli_fetch_array($komentar)) {
                                    $komentarid = $row['komentarid'];
                                ?>
                                    <div class="text-xs flex gap-2">
                                        <?php if (!empty($row['fotoprofil'])) { ?>
                                            <img src="assets/img/<?php echo $row['fotoprofil'] ?>" alt="" class="w-7 h-7 rounded-full object-cover shrink-0 mt-0.5">
                                        <?php } else { ?>
                                            <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                                                <span class="font-serif-title font-bold text-[10px] text-brand-accent"><?php echo strtoupper(substr($row['namalengkap'], 0, 1)) ?></span>
                                            </div>
                                        <?php } ?>
                                        <div class="flex-1">
                                        <div class="flex items-start justify-between gap-2">
                                            <p><span class="font-bold text-brand-ink"><?php echo $row['namalengkap'] ?></span>
                                            <span class="text-brand-soft"> — <?php echo $row['isikomentar'] ?></span></p>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1 text-[11px] text-brand-soft">
                                            <?php if ($sudahlogin) { ?>
                                                <button type="button" onclick="document.getElementById('balas<?php echo $komentarid ?>').classList.toggle('hidden')" class="font-semibold hover:text-brand-accent">Balas</button>
                                            <?php } ?>
                                            <?php if ($sudahlogin && $row['userid'] == $userid) { ?>
                                                <form action="config/aksi_hapus_komentar.php" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                    <input type="hidden" name="komentarid" value="<?php echo $komentarid ?>">
                                                    <input type="hidden" name="kembali" value="index.php">
                                                    <button type="submit" class="font-semibold hover:text-rose-600">Hapus</button>
                                                </form>
                                            <?php } ?>
                                        </div>

                                        <!-- daftar balasan -->
                                        <?php
                                        $balasan = mysqli_query($koneksi, "SELECT * FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE komentarfoto.parent_id='$komentarid' ORDER BY komentarfoto.komentarid ASC");
                                        while ($rb = mysqli_fetch_array($balasan)) { ?>
                                            <div class="ml-4 mt-2 pl-3 border-l-2 border-gray-100 flex gap-2">
                                                <?php if (!empty($rb['fotoprofil'])) { ?>
                                                    <img src="assets/img/<?php echo $rb['fotoprofil'] ?>" alt="" class="w-6 h-6 rounded-full object-cover shrink-0 mt-0.5">
                                                <?php } else { ?>
                                                    <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                                                        <span class="font-serif-title font-bold text-[9px] text-brand-accent"><?php echo strtoupper(substr($rb['namalengkap'], 0, 1)) ?></span>
                                                    </div>
                                                <?php } ?>
                                                <div class="flex-1">
                                                <p><span class="font-bold text-brand-ink"><?php echo $rb['namalengkap'] ?></span>
                                                <span class="text-brand-soft"> — <?php echo $rb['isikomentar'] ?></span></p>
                                                <?php if ($sudahlogin && $rb['userid'] == $userid) { ?>
                                                    <form action="config/aksi_hapus_komentar.php" method="POST" onsubmit="return confirm('Hapus balasan ini?')" class="mt-0.5">
                                                        <input type="hidden" name="komentarid" value="<?php echo $rb['komentarid'] ?>">
                                                        <input type="hidden" name="kembali" value="index.php">
                                                        <button type="submit" class="text-[11px] text-brand-soft font-semibold hover:text-rose-600">Hapus</button>
                                                    </form>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <!-- form balas -->
                                        <?php if ($sudahlogin) { ?>
                                        <form action="config/proses_komentar.php" method="POST" id="balas<?php echo $komentarid ?>" class="hidden ml-4 mt-2 flex gap-2">
                                            <input type="hidden" name="fotoid" value="<?php echo $fotoid ?>">
                                            <input type="hidden" name="parent_id" value="<?php echo $komentarid ?>">
                                            <input type="hidden" name="kembali" value="index.php">
                                            <input type="text" name="isikomentar" placeholder="Tulis balasan..." required class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-[11px] focus:outline-none focus:border-brand-accent">
                                            <button type="submit" name="kirimkomentar" class="px-3 py-1.5 bg-brand-accent hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg transition">Kirim</button>
                                        </form>
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php if ($sudahlogin) { ?>
                                <form action="config/proses_komentar.php" method="POST" class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
                                    <input type="hidden" name="fotoid" value="<?php echo $fotoid ?>">
                                    <input type="hidden" name="kembali" value="index.php">
                                    <input type="text" name="isikomentar" placeholder="Tulis komentar..." required class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs text-brand-ink placeholder:text-brand-soft focus:outline-none focus:border-brand-accent">
                                    <button type="submit" name="kirimkomentar" class="px-4 py-2 bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition">Kirim</button>
                                </form>
                            <?php } else { ?>
                                <p class="mt-4 pt-4 border-t border-gray-100 text-xs text-brand-soft"><a href="login.php" class="text-brand-accent hover:underline font-semibold">Masuk</a> untuk berkomentar.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if ($totalhalaman > 1) {
            $urlparam = [];
            if ($cari != '') $urlparam[] = 'cari=' . urlencode($cari);
            if ($filteralbum != '') $urlparam[] = 'albumid=' . urlencode($filteralbum);
            $baseurl = 'index.php?' . implode('&', $urlparam) . (count($urlparam) > 0 ? '&' : '');
        ?>
            <div class="flex items-center justify-center gap-2 mt-10">
                <?php if ($halaman > 1) { ?>
                    <a href="<?php echo $baseurl ?>halaman=<?php echo $halaman - 1 ?>" class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-sm"><i class="fa-solid fa-chevron-left"></i></a>
                <?php } ?>

                <?php for ($p = 1; $p <= $totalhalaman; $p++) {
                    if ($p == 1 || $p == $totalhalaman || abs($p - $halaman) <= 1) { ?>
                        <a href="<?php echo $baseurl ?>halaman=<?php echo $p ?>" class="w-10 h-10 rounded-xl <?php echo $p == $halaman ? 'bg-brand-accent text-white' : 'border border-gray-200 hover:bg-gray-50 text-brand-ink'; ?> flex items-center justify-center text-sm font-semibold"><?php echo $p ?></a>
                    <?php } elseif ($p == $halaman - 2 || $p == $halaman + 2) { ?>
                        <span class="text-brand-soft px-1">&hellip;</span>
                    <?php } } ?>

                <?php if ($halaman < $totalhalaman) { ?>
                    <a href="<?php echo $baseurl ?>halaman=<?php echo $halaman + 1 ?>" class="w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-sm"><i class="fa-solid fa-chevron-right"></i></a>
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
