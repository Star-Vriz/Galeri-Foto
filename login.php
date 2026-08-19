<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Website Galeri Foto</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="text-brand-ink antialiased">

    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto flex items-center gap-4 px-4 md:px-8 py-3.5">
            <a href="index.php" class="flex items-center gap-2.5 shrink-0">
                <div class="w-9 h-9 bg-brand-accent text-white rounded-xl flex items-center justify-center font-serif-title font-bold">G</div>
                <span class="font-serif-title font-bold text-lg text-brand-ink hidden sm:inline">Galeri Foto</span>
            </a>
            <div class="flex items-center gap-2 shrink-0 ml-auto">
                <a href="register.php" class="px-4 py-2.5 rounded-xl text-brand-ink hover:bg-gray-100 transition text-xs font-bold uppercase tracking-wider border border-gray-200">Daftar</a>
                <a href="login.php" class="px-4 py-2.5 rounded-xl bg-brand-accent text-white transition text-xs font-bold uppercase tracking-wider">Masuk</a>
            </div>
        </div>
    </header>

    <main class="min-h-[calc(100vh-68px)] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-8">
                <div class="text-center mb-6">
                    <div class="w-12 h-12 bg-brand-accent text-white rounded-xl flex items-center justify-center font-serif-title font-bold text-xl mx-auto mb-4">G</div>
                    <h1 class="font-serif-title font-bold text-2xl text-brand-ink">Masuk Akun</h1>
                    <p class="text-brand-soft text-xs mt-1">Masuk untuk mengelola & menikmati galeri</p>
                </div>

                <form action="config/aksi_login.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Username</label>
                        <input type="text" name="username" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-brand-ink focus:outline-none focus:border-brand-accent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft uppercase tracking-wider mb-1.5">Password</label>
                        <input type="password" name="password" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-brand-ink focus:outline-none focus:border-brand-accent transition">
                    </div>
                    <button type="submit" name="kirim" class="w-full bg-brand-accent hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider py-3.5 rounded-xl transition">Masuk</button>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-brand-soft">Belum punya akun? <a href="register.php" class="text-brand-accent font-semibold hover:underline">Daftar di sini</a></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="border-t border-gray-100 py-6 text-center">
        <p class="text-xs text-brand-soft">&copy; UKK RPL 2026 | APPLE</p>
    </footer>

</body>
</html>
