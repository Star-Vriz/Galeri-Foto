# WEBSITE GALERI FOTO — UKK RPL

Website galeri foto berbasis **PHP native + MySQL**, tampilan dibangun dengan
**Tailwind CSS** (dimuat via CDN, tanpa proses build). Pengunjung bisa melihat
foto tanpa login; pengguna yang sudah login bisa mengunggah foto, membuat
album, menyukai, mengomentari, menyimpan (bookmark), mengikuti (follow) kreator
lain, dan menerima notifikasi.

## Daftar Isi

- [Fitur](#fitur)
- [Yang Harus Disiapkan Dulu](#yang-harus-disiapkan-dulu)
- [Tutorial Setup dari Awal](#tutorial-setup-dari-awal)
- [Coba Semua Fiturnya](#coba-semua-fiturnya)
- [Mengatasi Masalah](#mengatasi-masalah)
- [Dokumentasi Project](#dokumentasi-project)
- [Lampiran A — Mengunggah Project ke GitHub](#lampiran-a--mengunggah-project-ke-github)
- [Checklist Sebelum Dikumpulkan](#checklist-sebelum-dikumpulkan)

---

## Fitur

### Akun & Profil
- Registrasi, login, logout
- Edit foto profil dan banner
- Edit nama lengkap dan alamat
- Edit username dan password (wajib konfirmasi password lama)
- Halaman profil kreator publik (statistik foto, album, suka, pengikut)
- Follow / unfollow pengguna lain

### Galeri & Foto
- Manajemen album (tambah, edit, hapus)
- Manajemen foto (unggah, edit, hapus), dikelompokkan per album
- Pencarian foto berdasarkan judul
- Filter foto berdasarkan album
- Pagination pada galeri
- Halaman Explore — kreator populer dan foto terbaru

### Interaksi
- Suka (like) foto
- Komentar dan balas komentar
- Hapus komentar milik sendiri
- Simpan (bookmark) foto ke koleksi pribadi
- Notifikasi ketika foto disukai, dikomentari, atau ada follower baru

---

## Yang Harus Disiapkan Dulu

| Kebutuhan | Keterangan |
|-----------|------------|
| **XAMPP** | Versi 8.x (sudah termasuk Apache, PHP, MySQL/MariaDB, phpMyAdmin). Unduh di <https://www.apachefriends.org> |
| **Git** | Hanya kalau mau ambil source code lewat `git clone`. Unduh di <https://git-scm.com> |
| **Browser** | Chrome, Edge, atau Firefox — wajib ada koneksi internet aktif, karena Tailwind CSS, Font Awesome, dan Google Fonts dimuat lewat CDN |
| **Teks editor** | VS Code / Sublime / Notepad++ |

Minimal PHP 7.4. Tidak ada dependency PHP atau JavaScript yang perlu diinstal
lewat Composer/NPM — murni PHP native.

---

## Tutorial Setup dari Awal

### Langkah 1 — Ambil Source Code

Buka **Command Prompt** atau **Git Bash**, lalu clone repository langsung ke
dalam folder `htdocs` milik XAMPP:

```bash
cd C:\xampp\htdocs
git clone https://github.com/USERNAME/my-photo-gallery.git
```

> Ganti `USERNAME` dengan username GitHub pemilik repository.

**Tidak punya Git?** Buka halaman repository di GitHub → tombol hijau **Code**
→ **Download ZIP** → ekstrak isinya ke `C:\xampp\htdocs\my-photo-gallery`.

**Project belum ada di GitHub?** Lihat
[Lampiran A](#lampiran-a--mengunggah-project-ke-github) untuk cara
mengunggahnya dulu.

Pastikan strukturnya seperti ini — `index.php` harus berada tepat di dalam
folder project, bukan di dalam folder bertingkat:

```
C:\xampp\htdocs\my-photo-gallery\
├── index.php        ← harus ada di sini
├── login.php
├── admin\
├── config\
└── assets\
```

### Langkah 2 — Nyalakan Apache dan MySQL

Buka **XAMPP Control Panel**, klik tombol **Start** pada **Apache** dan
**MySQL**. Kalau berhasil, keduanya berwarna hijau dan muncul nomor PID.

> **Kalau MySQL gagal menyala (langsung merah / berhenti sendiri):**
> penyebab paling umum adalah **port 3306 sudah dipakai program lain**
> (misalnya service Windows bernama *MySQL80* yang otomatis menyala sendiri
> saat komputer dinyalakan). Cek dulu lewat:
> ```bash
> netstat -ano | findstr :3306
> ```
> Solusi tercepat: `Win + R` → `services.msc` → cari **MySQL80** → klik kanan
> **Stop** → klik kanan lagi **Properties** → *Startup type* → **Manual** →
> OK. Baru Start MySQL dari XAMPP lagi.
>
> Alternatif: pindahkan MySQL XAMPP ke port lain lewat
> `C:\xampp\mysql\bin\my.ini` (ubah `port=3306` di bagian `[client]` **dan**
> `[mysqld]`), lalu sesuaikan juga `$port` di `config/koneksi.php` pada
> Langkah 4.

> **Kalau Apache gagal menyala:** biasanya port 80 dipakai aplikasi lain
> (Skype, IIS, VMware). Ubah `Listen 80` menjadi `Listen 8080` di
> `C:\xampp\apache\conf\httpd.conf`, lalu alamat website jadi
> `http://localhost:8080/my-photo-gallery/`.

### Langkah 3 — Buat dan Import Database

1. Buka **<http://localhost/phpmyadmin>**
2. Buat database baru, beri nama misalnya `galeri_melon`
3. Klik database itu, lalu tab **SQL**, jalankan struktur tabel utama (lihat
   [Struktur Database](#struktur-database)) — atau kalau kamu punya file
   `.sql` hasil export, tinggal **Import** lewat tab **Import**.
4. Jalankan juga query pembuatan tabel fitur lanjutan (`follow`,
   `notifikasi`, `bookmark`) beserta relasi foreign key-nya — lihat bagian
   [Tabel Fitur Lanjutan](#tabel-fitur-lanjutan).

### Langkah 4 — Sesuaikan Koneksi Database

Salin `config/koneksi.example.php` menjadi `config/koneksi.php`, lalu
sesuaikan:

```php
$hostname = 'localhost';
$userdb   = 'root';
$passdb   = '';
$namedb   = 'galeri_melon';
$port     = '3307';      // sesuaikan dengan port MySQL yang kamu pakai
```

- `$userdb` = `root` dan `$passdb` = kosong adalah pengaturan **bawaan
  XAMPP**. Kalau MySQL-mu punya password root, isi `$passdb` sesuai itu.
- `$port` harus sama dengan port MySQL yang menyala di Langkah 2.
- Pastikan folder `assets/img/` bisa ditulis (writable) oleh server, karena
  dipakai menyimpan file unggahan (foto, foto profil, banner).

### Langkah 5 — Buka Website

Buka browser, kunjungi:

```
http://localhost/my-photo-gallery/
```

Halaman utama galeri foto akan tampil. Karena belum ada foto, akan muncul
pesan *"Tidak ada foto yang cocok"* atau galeri kosong — itu normal.

✅ **Sampai di sini website sudah berhasil berjalan.** Kalau yang muncul
justru error, lompat ke bagian [Mengatasi Masalah](#mengatasi-masalah).

### Langkah 6 — Daftar dan Login

Klik tombol **Daftar** di kanan atas, isi form registrasi. Setelah itu klik
**Masuk**, login dengan akun yang baru dibuat.

---

## Coba Semua Fiturnya

Ikuti urutan ini untuk memastikan seluruh fitur berjalan:

1. **Buat album** — masuk ke Admin → menu **Album** → isi *Nama Album* dan
   *Deskripsi* → **Tambah Data**.
2. **Unggah foto** — menu **Foto** → isi *Judul Foto*, *Deskripsi*, pilih
   *Album*, unggah gambar → **Tambah Data**.
   > Album harus dibuat lebih dulu, karena setiap foto wajib punya album.
3. **Lihat di galeri publik** — buka `index.php`, foto tadi harus muncul di
   grid galeri.
4. **Suka dan komentar** — klik foto untuk membuka modal detail. Klik ikon
   hati untuk menyukai, tulis komentar dan kirim, coba juga **balas**
   komentar dan **hapus** komentar milik sendiri.
5. **Simpan (bookmark)** — di modal detail foto, klik tombol **Simpan**, lalu
   cek halaman `tersimpan.php` — foto itu harus muncul di sana.
6. **Follow** — buka `profil.php?userid=...` milik user lain (bisa lewat
   nama pemilik foto di kartu galeri), klik **Ikuti**.
7. **Notifikasi** — login sebagai user lain, sukai/komentari foto milik user
   pertama, lalu login kembali sebagai user pertama dan buka
   `notifikasi.php` — notifikasi baru harus muncul dengan badge di ikon
   lonceng.
8. **Pencarian & filter** — di `index.php`, coba cari foto berdasarkan judul
   dan filter berdasarkan album.
9. **Explore** — buka `explore.php`, cek daftar kreator populer dan foto
   terbaru.
10. **Edit dan hapus** — kembali ke menu **Album** atau **Foto** di admin,
    coba tombol **Edit** dan **Hapus**.
11. **Edit profil** — buka `admin/profil.php`, coba ganti foto profil,
    banner, nama, alamat, username, dan password.
12. **Logout** — klik tombol **Logout**.

Kalau seluruh langkah ini berjalan lancar, semua fitur sudah berfungsi. 🎉

---

## Mengatasi Masalah

| Yang muncul di layar | Penyebab | Solusi |
|---|---|---|
| `Koneksi database gagal` / halaman putih kosong | MySQL belum menyala, atau port/password salah di `config/koneksi.php` | Start MySQL di XAMPP, cek ulang `$hostname`, `$port`, `$passdb` |
| `Unknown database` | Database belum dibuat / belum di-import | Ulangi [Langkah 3](#langkah-3--buat-dan-import-database) |
| `Table ... doesn't exist` | Sebagian query `CREATE TABLE` belum dijalankan (terutama tabel fitur lanjutan) | Cek tab Struktur di phpMyAdmin, jalankan ulang query tabel yang belum ada |
| Muncul kode PHP mentah (`<?php ...`) di browser | Dibuka lewat `file:///`, bukan lewat server | Harus dibuka lewat `http://localhost/...`, bukan klik dua kali filenya |
| `Object not found! Error 404` | Nama folder di `htdocs` tidak sama dengan di URL, atau file dituju memang belum ada di lokasi tersebut | Samakan nama folder dengan URL; pastikan nama file persis (huruf kecil semua) dan ada di folder yang benar |
| Tampilan polos tanpa gaya (kotak-kotak biasa) | Tidak ada koneksi internet, sehingga Tailwind CSS (CDN) gagal dimuat | Pastikan komputer/server terkoneksi internet saat membuka halaman |
| Foto tidak muncul (ikon gambar rusak) | Folder `assets/img/` tidak ada, kosong, atau tidak writable | Pastikan folder `assets/img/` ada dan bisa ditulis server, lalu unggah ulang |
| Foto gagal diunggah | Ukuran file terlalu besar, atau batas upload PHP terlalu kecil | Cek `C:\xampp\php\php.ini`, baris `upload_max_filesize` dan `post_max_size`, naikkan ke `40M` bila perlu, lalu restart Apache |
| `Anda belum Login!` terus-menerus | Session tidak tersimpan / mencampur akses lewat `localhost` dan `127.0.0.1` | Selalu akses lewat `localhost`, jangan dicampur dengan `127.0.0.1` |
| Notifikasi tidak muncul padahal sudah like/komentar/follow | Tabel `notifikasi` belum dibuat, atau like/komentar/follow dilakukan ke foto/akun milik sendiri (sengaja tidak dinotifikasi) | Pastikan tabel `notifikasi` sudah dibuat; uji dengan dua akun berbeda |
| MySQL tidak mau start | Port 3306 bentrok dengan service lain | Lihat catatan di [Langkah 2](#langkah-2--nyalakan-apache-dan-mysql) |

---

## Dokumentasi Project

### Struktur Folder

```
my-photo-gallery/
├── admin/                 Halaman khusus pengguna yang sudah login
│   ├── home.php            Beranda admin (galeri milik sendiri per album)
│   ├── album.php           Manajemen album
│   ├── foto.php            Manajemen foto
│   ├── index.php           Feed komunitas (semua foto, semua pengguna)
│   └── profil.php          Edit profil, akun, foto profil & banner
├── assets/
│   └── img/                Foto profil, banner, dan foto yang diunggah pengguna
├── config/                  Koneksi database & skrip proses (CRUD, notifikasi, dll)
├── index.php                Beranda publik
├── explore.php               Kreator populer & foto terbaru
├── tersimpan.php             Koleksi foto yang disimpan (bookmark)
├── profil.php                Halaman profil kreator publik
├── notifikasi.php             Daftar notifikasi
├── login.php
├── register.php
├── .gitignore
└── README.md
```

### Struktur Database

**Tabel utama**

**`user`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| userid | INT, PK, AUTO_INCREMENT | |
| username | VARCHAR(255), UNIQUE | |
| password | VARCHAR(255) | Disimpan dengan `md5()` |
| email | VARCHAR(255) | |
| namalengkap | VARCHAR(255) | |
| nohp | VARCHAR(20) | |
| alamat | TEXT | |
| fotoprofil | VARCHAR(255), NULL | Nama file, disimpan di `assets/img/` |
| banner | VARCHAR(255), NULL | Nama file, disimpan di `assets/img/` |

**`album`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| albumid | INT, PK, AUTO_INCREMENT | |
| namaalbum | VARCHAR(255) | |
| deskripsi | TEXT | |
| tanggalbuat | DATE | |
| userid | INT | FK → user |

**`foto`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| fotoid | INT, PK, AUTO_INCREMENT | |
| judulfoto | VARCHAR(255) | |
| deskripsifoto | TEXT | |
| tanggalunggah | DATE | |
| lokasifile | VARCHAR(255) | Nama file di `assets/img/` |
| albumid | INT | FK → album |
| userid | INT | FK → user |

**`likefoto`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| likeid | INT, PK, AUTO_INCREMENT | |
| fotoid | INT | FK → foto |
| userid | INT | FK → user |
| tanggallike | DATE | |

**`komentarfoto`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| komentarid | INT, PK, AUTO_INCREMENT | |
| fotoid | INT | FK → foto |
| parent_id | INT, NULL | Isi kalau komentar ini balasan ke komentar lain |
| userid | INT | FK → user |
| isikomentar | TEXT | |
| tanggalkomentar | DATE | |

#### Tabel Fitur Lanjutan

**`follow`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| followid | INT, PK, AUTO_INCREMENT | |
| follower_id | INT | FK → user (yang mengikuti) |
| following_id | INT | FK → user (yang diikuti) |
| tanggal | DATE | |

**`notifikasi`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| notifid | INT, PK, AUTO_INCREMENT | |
| userid | INT | FK → user, penerima notifikasi |
| dari_userid | INT | FK → user, pemicu notifikasi |
| tipe | VARCHAR(20) | `like`, `komentar`, `balasan`, atau `follow` |
| fotoid | INT, NULL | FK → foto, kosong untuk tipe `follow` |
| dibaca | TINYINT(1), DEFAULT 0 | |
| tanggal | DATETIME | |

**`bookmark`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| bookmarkid | INT, PK, AUTO_INCREMENT | |
| userid | INT | FK → user |
| fotoid | INT | FK → foto |
| tanggal | DATE | |

Tabel fitur lanjutan (`follow`, `notifikasi`, `bookmark`) memiliki foreign key
dengan `ON DELETE CASCADE`, sehingga data terkait otomatis terhapus saat
pengguna atau foto induknya dihapus.

### Alur Aplikasi

**Pengunjung (belum login)**
- Melihat foto populer/terbaru di `index.php` dan `explore.php`
- Mencari dan memfilter foto
- Membuka detail foto dan membaca komentar
- Tombol suka, simpan, komentar, dan follow akan mengarahkan ke halaman login

**Pengguna (sudah login)**
- Semua hal di atas, ditambah:
- Menyukai/membatalkan suka, berkomentar/membalas/menghapus komentar sendiri
- Menyimpan foto ke `tersimpan.php`
- Mengikuti/berhenti mengikuti kreator lain
- Membuat, mengedit, menghapus album dan foto miliknya sendiri
- Mengedit profil, akun, foto profil, dan banner miliknya sendiri
- Menerima dan melihat notifikasi

Setiap aksi edit/hapus selalu dicek kepemilikannya lewat `userid` di session,
sehingga pengguna tidak bisa mengubah atau menghapus data milik pengguna lain.

### Catatan Upload File

- File disimpan di folder `assets/img/` dengan nama unik berpola
  `angkaacak_namaasli.ekstensi`
- Berlaku untuk foto, foto profil, dan banner
- Saat foto diedit dan gambarnya diganti, file lama otomatis dihapus
- Saat foto dihapus, file gambar beserta seluruh suka, komentar, dan
  bookmark-nya ikut terhapus

---

## Lampiran A — Mengunggah Project ke GitHub

**1. Buat repository kosong di GitHub**

Buka <https://github.com/new>, isi *Repository name*, pilih Public atau
Private, klik **Create repository**. Jangan centang opsi *Add a README file*
(karena project ini sudah punya `README.md` sendiri).

**2. Upload dari komputer**

Buka Command Prompt/Terminal di dalam folder project, lalu jalankan:

```bash
git init
git add .
git commit -m "Website Galeri Foto UKK RPL"
git branch -M main
git remote add origin https://github.com/USERNAME/my-photo-gallery.git
git push -u origin main
```

Ganti `USERNAME` dengan username GitHub-mu. Saat diminta login, gunakan
*Personal Access Token* sebagai pengganti password (GitHub → Settings →
Developer settings → Personal access tokens).

**3. Cek dulu sebelum push**

Setelah `git add .`, jalankan `git status` — pastikan `config/koneksi.php`
**tidak** muncul di daftar file yang mau di-commit (harus sudah diabaikan
oleh `.gitignore`).

**4. Selesai**

Orang lain sekarang bisa mengambil source code-nya dengan:

```bash
git clone https://github.com/USERNAME/my-photo-gallery.git
```

---

## Checklist Sebelum Dikumpulkan

- [ ] Sesuaikan nama/tahun pada footer (`&copy; UKK RPL 2026 | APPLE`) bila perlu
- [ ] Hapus akun contoh/percobaan lewat phpMyAdmin, sisakan akun yang relevan
- [ ] Unggah beberapa foto sebagai contoh isi galeri
- [ ] Uji ulang seluruh langkah di [Coba Semua Fiturnya](#coba-semua-fiturnya)
- [ ] Pastikan seluruh tabel di [Struktur Database](#struktur-database) sudah dibuat, termasuk tabel fitur lanjutan
- [ ] Export database terbaru lewat phpMyAdmin (tab **Export** → **Go**), simpan sebagai file `.sql` untuk dilampirkan/di-commit
- [ ] Pastikan `config/koneksi.php` **tidak** ikut ter-commit ke GitHub (cek `.gitignore`)

## Kontributor

UKK RPL 2026 | APPLE
