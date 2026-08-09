<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1BA37A" id="browser-theme-color">
    <link rel="icon" href="/assets/icon-light.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">
    <title>@yield('title', 'Titik Simpan')</title>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <style>
        body { font-family: 'Nunito', 'Inter', system-ui, -apple-system, sans-serif; -webkit-tap-highlight-color: transparent; }
        .font-brand { font-family: 'Poppins', 'Nunito', 'Inter', sans-serif; font-weight: 700; }
        .font-slogan { font-family: 'Nunito', 'Inter', sans-serif; font-weight: 600; }
        .font-bold, .font-extrabold { font-family: 'Poppins', 'Nunito', 'Inter', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .fade-in { animation: fadeIn 0.25s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        input[type="date"], input[type="month"], input[type="number"], select { min-height: 44px; }
        select, input[type="date"], input[type="month"] {
            transition: border-color 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
        }
        select:hover, input[type="date"]:hover, input[type="month"]:hover { border-color: #6EE7B0; }
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px;
            padding-right: 32px !important;
        }
        select option { padding: 10px 14px; font-size: 14px; border-radius: 10px; margin: 2px 4px; }
        select option:first-child { color: #6b7280; }
        input[type="month"]::-webkit-calendar-picker-indicator {
            filter: invert(0.4); cursor: pointer; opacity: 0.6; transition: opacity 0.2s ease;
        }
        input[type="month"]::-webkit-calendar-picker-indicator:hover { opacity: 1; }
        .stat-card { transition: all 0.25s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px -8px rgba(0,0,0,0.1); }
        @media (max-width: 767px) {
            .mobile-card-table thead { display: none; }
            .mobile-card-table tbody tr { display: block; margin-bottom: 12px; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px; }
            .mobile-card-table tbody td { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border: none !important; }
            .mobile-card-table tbody td::before { content: attr(data-label); font-weight: 600; font-size: 0.75rem; color: #6b7280; }
        }
        .bottom-nav-item.active { color: #1BA37A; }
        .bottom-nav-item.active svg { transform: scale(1.1); }
        .btn-press:active { transform: scale(0.96); }
        .modal-backdrop { backdrop-filter: blur(4px); }

        .dark select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); }
        .dark select option:first-child { color: #9ca3af; }
        .dark input[type="month"]::-webkit-calendar-picker-indicator { filter: invert(0.7); }
        .dark .stat-card:hover { box-shadow: 0 8px 25px -8px rgba(0,0,0,0.4); }
        .dark .mobile-card-table tbody tr { border-color: #374151; }
        .dark .mobile-card-table tbody td::before { color: #9ca3af; }
        .dark .bottom-nav-item.active { color: #6EE7B0; }

        /* Loading Bar — smooth simulated progress, colors stack as the bar grows */
        #loading-bar { position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; z-index: 99999; pointer-events: none; opacity: 0; transition: opacity 0.2s ease; }
        #loading-bar.active { opacity: 1; }
        #loading-bar .bar { display: block; height: 100%; width: 0%; border-radius: 0 2px 2px 0; box-shadow: 0 0 8px rgba(99, 102, 241, 0.35); transition: width 0.2s ease-out; background: linear-gradient(90deg, #ef4444, #f97316, #eab308, #22c55e, #3b82f6, #8b5cf6, #ec4899, #ef4444); background-size: 200% 100%; animation: loading-shift 4s linear infinite; }
        @keyframes loading-shift { 0% { background-position: 0% 50%; } 100% { background-position: 200% 50%; } }
        #loading-bar.complete { opacity: 0; transition: opacity 0.25s ease 0.1s; }

        /* Disabled button state */
        button:disabled, .btn-disabled { opacity: 0.6; cursor: not-allowed; pointer-events: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen pb-20 md:pb-0">
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-28">
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img id="navbar-logo" src="/assets/logo-light.webp" alt="Titik Simpan" class="h-14 md:h-28 w-auto object-contain select-none drop-shadow-[0_4px_8px_rgba(27,163,122,0.35)] dark:drop-shadow-[0_4px_10px_rgba(110,231,176,0.3)]">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    @php $navItems = [['route'=>'dashboard','label'=>'Dashboard'],['route'=>'budget.index','param'=>'budget.*','label'=>'Budget'],['route'=>'expenses.index','param'=>'expenses.*','label'=>'Pengeluaran'],['route'=>'recurring.index','param'=>'recurring.*','label'=>'Berulang'],['route'=>'categories.index','param'=>'categories.*','label'=>'Kategori'],['route'=>'reports.index','param'=>'reports.*','label'=>'Laporan']]; @endphp
                    @foreach($navItems as $nav)
                        <a href="{{ route($nav['route']) }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($nav['param'] ?? $nav['route']) ? 'bg-[#1BA37A]/10 dark:bg-[#1BA37A]/25 text-[#1BA37A] dark:text-[#6EE7B0]' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            {{ $nav['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="flex-1 flex justify-center md:hidden"></div>

                <div class="flex items-center space-x-2 md:space-x-3 shrink-0">
                    <div class="relative" id="theme-wrap">
                        <button onclick="toggleThemeMenu(event)" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 active:bg-gray-200 dark:active:bg-gray-600 transition-all btn-press shadow-md shadow-black/10 dark:shadow-black/40 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 hover:shadow-lg" title="Pilih tema" aria-label="Pilih tema">
                            <svg id="theme-icon-dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
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
                    <div class="relative ml-1 md:ml-2" id="avatar-wrap">
                        <button onclick="toggleAvatarMenu(event)" class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 active:bg-gray-200 dark:active:bg-gray-600 transition-all btn-press" title="Akun" aria-label="Profil">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Profil" class="w-10 h-10 md:w-11 md:h-11 rounded-full object-cover ring-2 ring-[#1BA37A]/50 dark:ring-[#6EE7B0]/70 shadow-md shadow-[#1BA37A]/30 dark:shadow-black/40">
                            @else
                                <div class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-[#1BA37A] text-white flex items-center justify-center text-base font-bold ring-2 ring-[#1BA37A]/50 dark:ring-[#6EE7B0]/70 shadow-md shadow-[#1BA37A]/30 dark:shadow-black/40">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </button>
                        <div id="avatar-menu" class="hidden absolute right-0 mt-2 w-52 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.index', [], false) }}" class="flex items-center gap-2.5 px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil
                            </a>
                            <form action="{{ route('logout', [], false) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loading-bar"><span class="bar"></span></div>
    </nav>

    <main class="max-w-7xl mx-auto py-4 md:py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="flash-message mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl text-sm md:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flash-message mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm md:text-base">
                {{ session('error') }}
            </div>
        @endif

        <div id="page-content" class="fade-in">
            @yield('content')
        </div>
    </main>

    <footer class="md:block pb-24 md:pb-0">
        <div class="h-px bg-gradient-to-r from-transparent via-[#1BA37A]/60 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <img src="/assets/icon-monokrom.svg" alt="Titik Simpan" class="w-7 h-7 object-contain select-none">
                    <span class="font-brand font-bold text-gray-900 dark:text-white">Titik Simpan</span>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Kelola pemasukan & pengeluaran lebih bijak, pencatatan sederhana untuk hidup lebih teratur.</p>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">© {{ date('Y') }} <span class="font-bold text-[#1BA37A] dark:text-[#6EE7B0]">Titik Simpan</span> - Ard Production</p>
            </div>
        </div>
    </footer>

    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-50">
        <div class="flex justify-around items-center h-16 px-2">
            @php $bottomNav = [['route'=>'dashboard','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','label'=>'Dashboard'],['route'=>'budget.index','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Budget'],['route'=>'expenses.index','icon'=>'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z','label'=>'Pengeluaran'],['route'=>'recurring.index','icon'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15','label'=>'Berulang'],['route'=>'categories.index','icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z','label'=>'Kategori'],['route'=>'reports.index','icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','label'=>'Laporan']]; @endphp
            @foreach($bottomNav as $item)
                <a href="{{ route($item['route']) }}" class="bottom-nav-item flex flex-col items-center justify-center px-2 py-1 rounded-xl transition-all {{ request()->routeIs(str_replace('.', '*', $item['route'])) ? 'active text-[#1BA37A] dark:text-[#6EE7B0]' : 'text-gray-400 dark:text-gray-500' }}">
                    <svg class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                    <span class="text-[10px] mt-0.5 font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <div id="confirmModal" class="hidden fixed inset-0 bg-black/60 modal-backdrop flex items-center justify-center z-[100] p-4">
        <div id="confirmModalBox" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div id="confirmModalIcon" class="flex items-center justify-center pt-8 pb-2"></div>
            <div class="px-6 pb-2 text-center">
                <h3 id="confirmModalTitle" class="text-lg font-bold text-gray-900 dark:text-white mb-2"></h3>
                <p id="confirmModalMessage" class="text-sm text-gray-500 dark:text-gray-400"></p>
            </div>
            <div id="confirmModalInputWrap" class="px-6 pb-1 hidden">
                <input id="confirmModalInput" type="text" autocomplete="off" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm text-center font-semibold tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500/50">
            </div>
            <div class="px-6 pb-6 pt-4 flex space-x-3">
                <button id="confirmModalCancel" class="flex-1 px-4 py-3 rounded-xl text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-500 transition-all btn-press">
                    Batal
                </button>
                <button id="confirmModalOk" class="flex-1 px-4 py-3 rounded-xl text-sm font-semibold text-white transition-all btn-press">
                    Ya
                </button>
            </div>
        </div>
    </div>

    <script>
        // Loading Bar — smooth scrolling effect
        var _loadingTimer = null;
        function showLoading() {
            var bar = document.getElementById('loading-bar');
            if (!bar) return;
            var inner = bar.querySelector('.bar');
            bar.classList.remove('complete');
            clearInterval(_loadingTimer);
            inner.style.width = '0%';
            void bar.offsetHeight;
            bar.classList.add('active');
            var width = 0;
            _loadingTimer = setInterval(function() {
                if (width < 90) {
                    width += Math.random() * 5 + 2;
                    if (width > 90) width = 90;
                    inner.style.width = width + '%';
                }
            }, 110);
        }
        function hideLoading() {
            var bar = document.getElementById('loading-bar');
            if (!bar) return;
            clearInterval(_loadingTimer);
            var inner = bar.querySelector('.bar');
            if (inner) inner.style.width = '100%';
            bar.classList.add('complete');
            setTimeout(function() {
                bar.classList.remove('active', 'complete');
            }, 350);
        }

        // Count-up animation for money figures (elements with [data-count])
        function animateNumbers() {
            var els = document.querySelectorAll('[data-count]');
            if (!els.length) return;
            if (typeof anime === 'undefined') {
                els.forEach(function(el) {
                    el.textContent = 'Rp ' + Math.round(parseFloat(el.getAttribute('data-count')) || 0).toLocaleString('id-ID');
                });
                return;
            }
            els.forEach(function(el) {
                var target = parseFloat(el.getAttribute('data-count'));
                if (isNaN(target)) return;
                var obj = { v: 0 };
                anime({
                    targets: obj,
                    v: target,
                    round: 1,
                    duration: 900,
                    easing: 'easeOutCubic',
                    update: function() {
                        el.textContent = 'Rp ' + obj.v.toLocaleString('id-ID');
                    }
                });
            });
        }
        document.addEventListener('DOMContentLoaded', animateNumbers);

        // Show loading bar on link clicks (internal navigation)
        document.addEventListener('click', function(e) {
            var link = e.target.closest('a');
            if (link && link.href && !link.hasAttribute('download') && !link.getAttribute('href').startsWith('#') && !link.getAttribute('href').startsWith('javascript:') && link.hostname === window.location.hostname) {
                sessionStorage.setItem('np', '1');
                showLoading();
            }
        });

        // Show loading bar on form submits
        document.addEventListener('submit', function(e) {
            var form = e.target;
            sessionStorage.setItem('np', '1');
            showLoading();
            // Disable submit buttons
            form.querySelectorAll('button[type="submit"]').forEach(function(btn) {
                btn.disabled = true;
            });
        });

        // Catch page refresh / back-forward via beforeunload
        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('np', '1');
        });

        // Check for pending navigation on page load
        if (sessionStorage.getItem('np') === '1') {
            sessionStorage.removeItem('np');
            showLoading();
            var npStart = Date.now();
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof npStart === 'number') {
                var ms = 600 - (Date.now() - npStart);
                if (ms > 0) {
                    setTimeout(hideLoading, ms);
                    return;
                }
            }
            hideLoading();
        });

        // Theme: light | dark | auto (follows OS)
        var _mql = window.matchMedia('(prefers-color-scheme: dark)');
        function getTheme() { var t = localStorage.getItem('theme'); return (t === 'dark' || t === 'light') ? t : 'auto'; }
        function resolveTheme(t) { return t === 'auto' ? (_mql.matches ? 'dark' : 'light') : t; }
        function applyTheme(theme) {
            var resolved = resolveTheme(theme);
            document.documentElement.classList.toggle('dark', resolved === 'dark');
            updateThemeIcon(resolved);
            updateThemeChecks(theme);
            var logo = document.getElementById('navbar-logo');
            if (logo) logo.src = resolved === 'dark' ? '/assets/logo-dark.webp' : '/assets/logo-light.webp';
        }
        function setTheme(theme) { localStorage.setItem('theme', theme); applyTheme(theme); }
        function updateThemeIcon(resolved) {
            var darkIcon = document.getElementById('theme-icon-dark');
            var lightIcon = document.getElementById('theme-icon-light');
            if (!darkIcon || !lightIcon) return;
            darkIcon.classList.toggle('hidden', resolved === 'dark');
            lightIcon.classList.toggle('hidden', resolved === 'light');
        }
        function updateThemeChecks(theme) {
            document.querySelectorAll('.theme-opt').forEach(function(btn) {
                var active = btn.getAttribute('data-theme') === theme;
                btn.querySelector('.theme-check').classList.toggle('hidden', !active);
            });
        }
        function toggleThemeMenu(e) {
            e.stopPropagation();
            var menu = document.getElementById('theme-menu');
            if (menu) menu.classList.toggle('hidden');
        }
        function selectTheme(theme) {
            setTheme(theme);
            document.getElementById('theme-menu').classList.add('hidden');
        }
        if (_mql.addEventListener) _mql.addEventListener('change', function() { if (getTheme() === 'auto') applyTheme('auto'); });
        applyTheme(getTheme());

        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('theme-wrap');
            if (wrap && !wrap.contains(e.target)) {
                var menu = document.getElementById('theme-menu');
                if (menu) menu.classList.add('hidden');
            }
        });

        // (clock moved to dashboard greeting banner)

        // Confirm Modal
        var _confirmCallback = null;
        var _confirmRequire = null;
        var _modalIcons = {
            danger: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#FEE2E2" stroke="#FCA5A5" stroke-width="1.5"/><path d="M12 7v5" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round"/><circle cx="12" cy="16" r="1.2" fill="#DC2626"/></svg>',
            warning: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#FEF3C7" stroke="#FCD34D" stroke-width="1.5"/><path d="M12 7v6" stroke="#D97706" stroke-width="2.5" stroke-linecap="round"/><circle cx="12" cy="16" r="1.2" fill="#D97706"/></svg>',
            primary: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#BDE0D2" stroke="#88B239" stroke-width="1.5"/><path d="M7.5 12.5l3 3 6.5-7" stroke="#0C7A59" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            success: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#D1FAE5" stroke="#6EE7B7" stroke-width="1.5"/><path d="M7.5 12.5l3 3 6.5-7" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
        };
        var _modalBtnColors = {
            danger: 'bg-red-600 hover:bg-red-700 active:bg-red-800',
            warning: 'bg-amber-500 hover:bg-amber-600 active:bg-amber-700',
            primary: 'bg-[#1BA37A] hover:bg-[#0F8F68] active:bg-[#0C7A59]',
            success: 'bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800'
        };

        function showConfirm(opts) {
            var modal = document.getElementById('confirmModal');
            var box = document.getElementById('confirmModalBox');
            var type = opts.type || 'danger';
            document.getElementById('confirmModalIcon').innerHTML = _modalIcons[type] || _modalIcons.danger;
            document.getElementById('confirmModalTitle').textContent = opts.title || 'Konfirmasi';
            document.getElementById('confirmModalMessage').textContent = opts.message || '';
            var okBtn = document.getElementById('confirmModalOk');
            okBtn.textContent = opts.confirmText || 'Ya, Lanjutkan';
            okBtn.className = 'flex-1 px-4 py-3 rounded-xl text-sm font-semibold text-white transition-all btn-press ' + (_modalBtnColors[type] || _modalBtnColors.danger);
            _confirmCallback = opts.onConfirm || null;
            _confirmRequire = opts.requireText || null;
            var iw = document.getElementById('confirmModalInputWrap');
            var inp = document.getElementById('confirmModalInput');
            if (_confirmRequire) {
                iw.classList.remove('hidden');
                inp.value = '';
                inp.placeholder = 'Ketik "' + _confirmRequire + '"';
                inp.focus();
            } else {
                iw.classList.add('hidden');
            }
            modal.classList.remove('hidden');
            anime({ targets: '#confirmModal', opacity: [0, 1], duration: 250, easing: 'easeOutCubic' });
            anime({ targets: '#confirmModalBox', scale: [0.8, 1], opacity: [0, 1], duration: 350, easing: 'easeOutBack' });
            anime({ targets: '#confirmModalIcon svg', scale: [0, 1], rotate: [-15, 0], duration: 500, delay: 150, easing: 'easeOutElastic(1, .6)' });
        }

        function hideConfirm() {
            anime({ targets: '#confirmModalBox', scale: [1, 0.85], opacity: [1, 0], duration: 200, easing: 'easeInCubic' });
            anime({ targets: '#confirmModal', opacity: [1, 0], duration: 250, easing: 'easeInCubic', complete: function() {
                document.getElementById('confirmModal').classList.add('hidden');
            }});
        }

        document.getElementById('confirmModalOk').addEventListener('click', function() {
            var cb = _confirmCallback;
            if (_confirmRequire && document.getElementById('confirmModalInput').value !== _confirmRequire) {
                anime({ targets: '#confirmModalBox', translateX: [0, -8, 8, -6, 6, -3, 3, 0], duration: 400, easing: 'easeInOutSine' });
                return;
            }
            hideConfirm();
            showLoading();
            if (cb) setTimeout(cb, 200);
        });
        document.getElementById('confirmModalCancel').addEventListener('click', hideConfirm);
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) hideConfirm();
        });

        function toggleAvatarMenu(e) {
            e.stopPropagation();
            var menu = document.getElementById('avatar-menu');
            menu.classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('avatar-wrap');
            if (wrap && !wrap.contains(e.target)) {
                document.getElementById('avatar-menu').classList.add('hidden');
            }
        });

        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.flash-message').forEach(function(el) {
                anime({ targets: el, opacity: [0, 1], translateY: [-10, 0], easing: 'easeOutBack', duration: 400 });
                setTimeout(function() {
                    anime({ targets: el, opacity: [1, 0], translateY: [0, -10], easing: 'easeInCubic', duration: 300, delay: 3000 });
                }, 4000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>