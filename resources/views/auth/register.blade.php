<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1BA37A" id="browser-theme-color">
    <link rel="icon" href="/favicon.ico">
    <title>Daftar - Titik Simpan</title>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <style>
        body { font-family: 'Nunito', 'Inter', system-ui, -apple-system, sans-serif; -webkit-tap-highlight-color: transparent; }
        .font-brand { font-family: 'Poppins', 'Nunito', 'Inter', sans-serif; font-weight: 700; }
        .font-slogan { font-family: 'Nunito', 'Inter', sans-serif; font-weight: 600; }
        .font-bold, .font-extrabold { font-family: 'Poppins', 'Nunito', 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.35s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="fixed top-4 right-4">
        <button onclick="toggleThemeMenu(event)" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" title="Pilih tema" aria-label="Pilih tema">
            <svg id="theme-icon-dark" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <svg id="theme-icon-light" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </button>
        <div id="theme-menu" class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
            <button onclick="selectTheme('light')" data-theme="light" class="theme-opt w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Terang
                <svg class="theme-check w-4 h-4 ml-auto hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
            <button onclick="selectTheme('dark')" data-theme="dark" class="theme-opt w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-t border-gray-100 dark:border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                Gelap
                <svg class="theme-check w-4 h-4 ml-auto hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
            <button onclick="selectTheme('auto')" data-theme="auto" class="theme-opt w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-t border-gray-100 dark:border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Sistem
                <svg class="theme-check w-4 h-4 ml-auto hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </div>

    <div class="w-full max-w-md fade-in">
        <div class="flex flex-col items-center text-center mb-8">
<div class="p-3 rounded-[24px] bg-white dark:bg-gray-800 shadow-[0_15px_40px_-8px_rgba(27,163,122,0.45)] dark:shadow-[0_15px_40px_-8px_rgba(0,0,0,0.7)] ring-1 ring-gray-200 dark:ring-gray-700">
                <img src="/assets/icon-light.svg" alt="Titik Simpan" class="h-16 w-16 sm:h-20 sm:w-20 object-contain mx-auto dark:hidden">
                <img src="/assets/icon-dark.svg" alt="Titik Simpan" class="h-16 w-16 sm:h-20 sm:w-20 object-contain mx-auto hidden dark:block">
            </div>
            <h1 class="mt-5 text-2xl sm:text-3xl font-brand tracking-tight">
                <span class="text-[#1F3A56] dark:text-white">Titik</span> <span class="text-[#1BA37A]">Simpan</span>
            </h1>
            <p class="mt-2 text-sm sm:text-base font-slogan text-[#1F3A56] dark:text-white max-w-xs">
                Catat <span class="text-[#1BA37A]">Sekarang</span>, <span class="text-[#1BA37A]">Hemat</span> Hari ini, Untuk <span class="text-[#1BA37A]">Masa Depan</span> Yang Lebih Baik
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <a href="{{ route('google.redirect', [], false) }}" class="flex items-center justify-center gap-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all btn-press shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0012 23z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 010-4.2V7.06H2.18a11 11 0 000 9.88l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15A11 11 0 002 7.06l3.84 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                Daftar dengan Google
            </a>
            <div class="flex items-center gap-3 my-5">
                <span class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></span>
                <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">ATAU</span>
                <span class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></span>
            </div>

            <form action="{{ route('register.attempt', [], false) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" maxlength="255" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kata Sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Ulangi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                </div>
                <button type="submit" class="w-full bg-[#1BA37A] text-white py-3 rounded-xl text-sm font-semibold hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press shadow-sm">
                    Daftar
                </button>
            </form>

            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-5">
                Sudah punya akun?
                <a href="{{ route('login', [], false) }}" class="text-[#1BA37A] dark:text-[#6EE7B0] font-medium hover:underline">Masuk</a>
            </p>
        </div>
    </div>

    <script>
        var _themeMql = window.matchMedia('(prefers-color-scheme: dark)');
        function getTheme() { var t = localStorage.getItem('theme'); return (t === 'dark' || t === 'light') ? t : 'auto'; }
        function resolveTheme(t) { return t === 'auto' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : t; }
        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            var resolved = resolveTheme(theme);
            document.documentElement.classList.toggle('dark', resolved === 'dark');
            var darkIcon = document.getElementById('theme-icon-dark');
            var lightIcon = document.getElementById('theme-icon-light');
            darkIcon.classList.toggle('hidden', resolved === 'dark');
            lightIcon.classList.toggle('hidden', resolved === 'light');
            document.querySelectorAll('.theme-opt').forEach(function(btn) {
                var active = btn.getAttribute('data-theme') === theme;
                btn.querySelector('.theme-check').classList.toggle('hidden', !active);
            });
        }
        function toggleThemeMenu(e) {
            e.stopPropagation();
            document.getElementById('theme-menu').classList.toggle('hidden');
        }
        function selectTheme(theme) {
            setTheme(theme);
            document.getElementById('theme-menu').classList.add('hidden');
        }
        if (_themeMql.addEventListener) _themeMql.addEventListener('change', function() { if (getTheme() === 'auto') setTheme('auto'); });
        document.addEventListener('click', function(e) {
            var menu = document.getElementById('theme-menu');
            if (menu && !menu.contains(e.target) && !e.target.closest('button[title="Pilih tema"]')) {
                menu.classList.add('hidden');
            }
        });
        setTheme(getTheme());
    </script>
</body>
</html>

