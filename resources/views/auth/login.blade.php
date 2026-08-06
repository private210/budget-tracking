<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4f46e5" id="browser-theme-color">
    <link rel="icon" type="image/png" href="/logo.png">
    <title>Masuk - Budget Tracker</title>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; -webkit-tap-highlight-color: transparent; }
        .fade-in { animation: fadeIn 0.35s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <button onclick="toggleTheme()" class="fixed top-4 right-4 p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" title="Ganti tema" aria-label="Ganti tema">
        <svg id="theme-icon-dark" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        <svg id="theme-icon-light" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </button>

    <div class="w-full max-w-md fade-in">
        <div class="text-center mb-6">
            <img src="/logo.png" alt="Tracking Pengeluaran" class="h-24 w-auto mx-auto">
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <a href="{{ route('google.redirect', [], false) }}" class="flex items-center justify-center gap-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all btn-press shadow-sm mt-5">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0012 23z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 010-4.2V7.06H2.18a11 11 0 000 9.88l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15A11 11 0 002 7.06l3.84 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                Masuk dengan Google
            </a>
            <div class="flex items-center gap-3 my-5">
                <span class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></span>
                <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">ATAU</span>
                <span class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></span>
            </div>

            <form action="{{ route('login.attempt', [], false) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kata Sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                    Ingat saya
                </label>
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700 active:bg-indigo-800 transition-all btn-press shadow-sm">
                    Masuk
                </button>
            </form>

            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-5">
                Belum punya akun?
                <a href="{{ route('register', [], false) }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Daftar</a>
            </p>
        </div>
    </div>

    <script>
        function toggleTheme() {
            var html = document.documentElement;
            html.classList.toggle('dark');
            document.getElementById('theme-icon-dark').classList.toggle('hidden');
            document.getElementById('theme-icon-light').classList.toggle('hidden');
        }
    </script>
</body>
</html>
