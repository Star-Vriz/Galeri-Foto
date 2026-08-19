<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header('Location: login.php');
    exit;
}
$userid = $_SESSION['userid'];

// tandai semua sudah dibaca begitu halaman dibuka
mysqli_query($koneksi, "UPDATE notifikasi SET dibaca=1 WHERE userid='$userid'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Galeri Foto</title>
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
            <div class="flex items-center gap-2 shrink-0 ml-auto">
                <a href="admin/index.php" class="px-4 py-2.5 rounded-xl hover:bg-gray-100 transition text-xs font-bold uppercase tracking-wider border border-gray-200">Admin</a>
                <a href="config/aksi_logout.php" class="px-4 py-2.5 rounded-xl bg-brand-accent hover:bg-indigo-700 text-white transition text-xs font-bold uppercase tracking-wider">Logout</a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-2xl mx-auto w-full px-4 md:px-8 py-8">
        <h1 class="font-serif-title font-bold text-2xl mb-1">Notifikasi</h1>
        <p class="text-brand-soft text-sm mb-6">Aktivitas terbaru dari suka, komentar, dan pengikut.</p>

        <div class="space-y-2">
            <?php
            $notif = mysqli_query($koneksi, "SELECT notifikasi.*, user.namalengkap, user.fotoprofil, foto.lokasifile, foto.judulfoto FROM notifikasi INNER JOIN user ON notifikasi.dari_userid=user.userid LEFT JOIN foto ON notifikasi.fotoid=foto.fotoid WHERE notifikasi.userid='$userid' ORDER BY notifikasi.notifid DESC LIMIT 50");
            if (mysqli_num_rows($notif) == 0) { ?>
                <div class="text-center py-16">
                    <i class="fa-regular fa-bell text-4xl text-brand-soft mb-3"></i>
                    <p class="text-brand-soft text-sm">Belum ada notifikasi.</p>
                </div>
            <?php } else {
                while ($n = mysqli_fetch_array($notif)) {
                    if ($n['tipe'] == 'like') { $pesan = 'menyukai foto'; $ikon = 'fa-solid fa-heart text-rose-500'; }
                    elseif ($n['tipe'] == 'komentar') { $pesan = 'mengomentari foto'; $ikon = 'fa-regular fa-comment text-brand-accent'; }
                    else { $pesan = 'mulai mengikuti kamu'; $ikon = 'fa-solid fa-user-plus text-brand-accent'; }
                    $link = $n['tipe'] == 'follow' ? 'profil.php?userid=' . $n['dari_userid'] : 'index.php';
            ?>
                <a href="<?php echo $link ?>" class="flex items-center gap-3 bg-white border border-gray-100 rounded-2xl p-4 hover:shadow-sm transition">
                    <?php if (!empty($n['fotoprofil'])) { ?>
                        <img src="assets/img/<?php echo $n['fotoprofil'] ?>" alt="" class="w-10 h-10 rounded-full object-cover shrink-0">
                    <?php } else { ?>
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                            <span class="font-serif-title font-bold text-xs text-brand-accent"><?php echo strtoupper(substr($n['namalengkap'], 0, 1)) ?></span>
                        </div>
                    <?php } ?>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm"><span class="font-bold"><?php echo $n['namalengkap'] ?></span> <span class="text-brand-soft"><?php echo $pesan ?></span><?php if (!empty($n['judulfoto'])) { ?> <span class="font-semibold">"<?php echo $n['judulfoto'] ?>"</span><?php } ?></p>
                        <p class="text-[11px] text-brand-soft mt-0.5"><?php echo $n['tanggal'] ?></p>
                    </div>
                    <i class="<?php echo $ikon ?>"></i>
                </a>
            <?php } } ?>
        </div>
    </main>

    <footer class="border-t border-gray-100 py-6 text-center">
        <p class="text-xs text-brand-soft">&copy; UKK RPL 2026 | APPLE</p>
    </footer>

</body>
</html>
