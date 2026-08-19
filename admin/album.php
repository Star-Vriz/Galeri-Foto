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
    <title>Album - Admin</title>
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
            <a href="album.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-indigo-50 text-brand-accent text-sm font-semibold"><i class="fa-solid fa-layer-group w-4"></i> Album</a>
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
        <h1 class="font-serif-title font-bold text-2xl mb-1">Kelola Album</h1>
        <p class="text-brand-soft text-sm mb-6">Buat dan atur album untuk mengelompokkan fotomu.</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-serif-title font-bold text-lg mb-4">Tambah Album</h2>
                    <form action="../config/aksi_album.php" method="POST" class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Nama Album</label>
                            <input type="text" name="namaalbum" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" required rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent"></textarea>
                        </div>
                        <button type="submit" name="tambah" class="w-full bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded-xl transition">Tambah Data</button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <h2 class="font-serif-title font-bold text-lg p-5 pb-0">Data Album</h2>
                    <div class="overflow-x-auto p-5">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs uppercase tracking-wider text-brand-soft border-b border-gray-100">
                                    <th class="py-2 pr-3">#</th>
                                    <th class="py-2 pr-3">Nama Album</th>
                                    <th class="py-2 pr-3">Deskripsi</th>
                                    <th class="py-2 pr-3">Tanggal</th>
                                    <th class="py-2 pr-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php
                                $no = 1;
                                $sql = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                                while ($data = mysqli_fetch_array($sql)) {
                                ?>
                                    <tr>
                                        <td class="py-3 pr-3"><?php echo $no++ ?></td>
                                        <td class="py-3 pr-3 font-semibold"><?php echo $data['namaalbum'] ?></td>
                                        <td class="py-3 pr-3 text-brand-soft max-w-[200px] truncate"><?php echo $data['deskripsi'] ?></td>
                                        <td class="py-3 pr-3 text-brand-soft"><?php echo $data['tanggalbuat'] ?></td>
                                        <td class="py-3 pr-3">
                                            <div class="flex gap-2">
                                                <button onclick="document.getElementById('edit<?php echo $data['albumid'] ?>').classList.remove('hidden')" class="w-8 h-8 rounded-lg bg-indigo-50 text-brand-accent hover:bg-indigo-100 flex items-center justify-center"><i class="fa-solid fa-pen text-xs"></i></button>
                                                <button onclick="document.getElementById('hapus<?php echo $data['albumid'] ?>').classList.remove('hidden')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center"><i class="fa-solid fa-trash text-xs"></i></button>
                                            </div>

                                            <!-- MODAL EDIT -->
                                            <div id="edit<?php echo $data['albumid'] ?>" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                                                <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl">
                                                    <form action="../config/aksi_album.php" method="POST">
                                                        <div class="p-5 flex items-center justify-between border-b border-gray-100">
                                                            <h3 class="font-serif-title font-bold text-lg">Edit Album</h3>
                                                            <button type="button" onclick="document.getElementById('edit<?php echo $data['albumid'] ?>').classList.add('hidden')" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                                                        </div>
                                                        <div class="p-5 space-y-3">
                                                            <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                                                            <div>
                                                                <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Nama Album</label>
                                                                <input type="text" name="namaalbum" value="<?php echo $data['namaalbum'] ?>" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Deskripsi</label>
                                                                <textarea name="deskripsi" required rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-accent"><?php echo $data['deskripsi']; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="p-5 pt-0">
                                                            <button type="submit" name="edit" class="w-full bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded-xl transition">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- MODAL HAPUS -->
                                            <div id="hapus<?php echo $data['albumid'] ?>" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                                                <div class="bg-white w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl">
                                                    <form action="../config/aksi_album.php" method="POST">
                                                        <div class="p-5 text-center">
                                                            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                                            <h3 class="font-serif-title font-bold text-lg mb-1">Hapus Album</h3>
                                                            <p class="text-sm text-brand-soft">Yakin ingin menghapus <strong><?php echo $data['namaalbum'] ?></strong>?</p>
                                                            <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                                                        </div>
                                                        <div class="p-5 pt-0 flex gap-2">
                                                            <button type="button" onclick="document.getElementById('hapus<?php echo $data['albumid'] ?>').classList.add('hidden')" class="flex-1 border border-gray-200 text-xs font-bold uppercase tracking-wider py-3 rounded-xl hover:bg-gray-50">Batal</button>
                                                            <button type="submit" name="hapus" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider py-3 rounded-xl">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
