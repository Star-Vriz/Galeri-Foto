<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['status'] != 'login') {
    echo "<script>alert('Anda belum Login!'); location.href='../index.php';</script>";
    exit;
}
$userid = $_SESSION['userid'];
$jmlnotif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM notifikasi WHERE userid='$userid' AND dibaca=0"));
$sqluser = mysqli_query($koneksi, "SELECT * FROM user WHERE userid='$userid'");
$profil = mysqli_fetch_array($sqluser);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Admin</title>
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
            <a href="profil.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-indigo-50 text-brand-accent text-sm font-semibold"><i class="fa-solid fa-user w-4"></i> Profil Saya</a>
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
        <h1 class="font-serif-title font-bold text-2xl mb-1">Profil Saya</h1>
        <p class="text-brand-soft text-sm mb-6">Kelola foto profil dan banner akunmu.</p>

        <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            <?php if (!empty($profil['banner'])) { ?>
                <img src="../assets/img/<?php echo $profil['banner'] ?>" alt="" class="w-full h-40 md:h-52 object-cover">
            <?php } else { ?>
                <div class="w-full h-40 md:h-52 bg-gradient-to-r from-indigo-500 to-brand-accent"></div>
            <?php } ?>
            <div class="px-6 md:px-8 pb-6 -mt-12">
                <?php if (!empty($profil['fotoprofil'])) { ?>
                    <img src="../assets/img/<?php echo $profil['fotoprofil'] ?>" alt="" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                <?php } else { ?>
                    <div class="w-24 h-24 rounded-full bg-white shadow-md border-4 border-white flex items-center justify-center">
                        <span class="font-serif-title font-bold text-3xl text-brand-accent"><?php echo strtoupper(substr($profil['namalengkap'], 0, 1)) ?></span>
                    </div>
                <?php } ?>
                <h2 class="font-serif-title font-bold text-xl mt-3"><?php echo $profil['namalengkap'] ?></h2>
                <p class="text-brand-soft text-xs mb-3">@<?php echo $profil['username'] ?></p>

                <div class="flex items-center gap-2">
                    <span class="px-4 py-2 rounded-full bg-brand-ink text-white text-xs font-bold">Edit Profil</span>
                    <a href="../profil.php?userid=<?php echo $userid ?>" class="px-4 py-2 rounded-full border border-gray-200 text-brand-ink text-xs font-bold hover:bg-gray-50 transition">Lihat Profil Publik</a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="font-serif-title font-bold text-base mb-4">Ubah Foto Profil & Banner</h3>
                    <form action="../config/aksi_edit_profil.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Foto Profil</label>
                                <input type="file" name="fotoprofil" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-brand-accent file:text-white file:text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Banner</label>
                                <input type="file" name="banner" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-brand-accent file:text-white file:text-xs">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded-xl transition">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-serif-title font-bold text-lg mb-4">Ubah Nama & Alamat</h2>
                <form action="../config/aksi_edit_data.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" name="namalengkap" value="<?php echo $profil['namalengkap'] ?>" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Alamat</label>
                        <input type="text" name="alamat" value="<?php echo $profil['alamat'] ?>" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent">
                    </div>
                    <button type="submit" class="w-full sm:w-auto sm:px-8 bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded-xl transition">Simpan Nama & Alamat</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-serif-title font-bold text-lg mb-1">Ubah Username & Password</h2>
                <p class="text-brand-soft text-xs mb-4">Kosongkan kolom yang tidak ingin diubah. Password saat ini wajib diisi untuk konfirmasi.</p>
                <form action="../config/aksi_edit_akun.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Username Baru</label>
                        <input type="text" name="username" placeholder="<?php echo $profil['username'] ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Password Baru</label>
                        <input type="password" name="passwordbaru" placeholder="Kosongkan jika tidak diubah" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent">
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Password Saat Ini (wajib)</label>
                        <input type="password" name="passwordlama" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent">
                    </div>
                    <button type="submit" class="w-full sm:w-auto sm:px-8 bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded-xl transition">Simpan Perubahan Akun</button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
