<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#4f46e5" id="browser-theme-color">
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <title>@yield('title', 'Budget Tracker')</title>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
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
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; -webkit-tap-highlight-color: transparent; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .fade-in { animation: fadeIn 0.25s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        input[type="date"], input[type="month"], input[type="number"], select { min-height: 44px; }
        select, input[type="date"], input[type="month"] {
            transition: border-color 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
        }
        select:hover, input[type="date"]:hover, input[type="month"]:hover { border-color: #818cf8; }
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
        .bottom-nav-item.active { color: #4f46e5; }
        .bottom-nav-item.active svg { transform: scale(1.1); }
        .btn-press:active { transform: scale(0.96); }
        .modal-backdrop { backdrop-filter: blur(4px); }

        .dark select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); }
        .dark select option:first-child { color: #9ca3af; }
        .dark input[type="month"]::-webkit-calendar-picker-indicator { filter: invert(0.7); }
        .dark .stat-card:hover { box-shadow: 0 8px 25px -8px rgba(0,0,0,0.4); }
        .dark .mobile-card-table tbody tr { border-color: #374151; }
        .dark .mobile-card-table tbody td::before { color: #9ca3af; }
        .dark .bottom-nav-item.active { color: #818cf8; }

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
            <div class="flex justify-between items-center h-14 md:h-16">
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('logo.svg') }}" alt="Tracking Pengeluaran" class="h-8 md:h-9 w-auto">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    @php $navItems = [['route'=>'dashboard','label'=>'Dashboard'],['route'=>'budget.index','param'=>'budget.*','label'=>'Budget'],['route'=>'expenses.index','param'=>'expenses.*','label'=>'Pengeluaran'],['route'=>'recurring.index','param'=>'recurring.*','label'=>'Berulang'],['route'=>'categories.index','param'=>'categories.*','label'=>'Kategori'],['route'=>'reports.index','param'=>'reports.*','label'=>'Laporan']]; @endphp
                    @foreach($navItems as $nav)
                        <a href="{{ route($nav['route']) }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($nav['param'] ?? $nav['route']) ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            {{ $nav['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="flex-1 flex justify-center md:hidden">
                    <div class="text-[11px] text-gray-600 dark:text-gray-400 font-medium" id="clock-mobile"></div>
                </div>

                <div class="flex items-center space-x-2 md:space-x-3 shrink-0">
                    <div class="hidden md:block text-sm text-gray-600 dark:text-gray-400 font-medium" id="clock-desktop"></div>

                    <button id="theme-toggle" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 active:bg-gray-200 dark:active:bg-gray-600 transition-all btn-press" title="Ganti tema" aria-label="Ganti tema">
                        <svg id="theme-icon-dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <div class="relative" id="avatar-wrap">
                        <button onclick="toggleAvatarMenu(event)" class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 active:bg-gray-200 dark:active:bg-gray-600 transition-all btn-press" title="Akun" aria-label="Profil">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Profil" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">
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

    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-50">
        <div class="flex justify-around items-center h-16 px-2">
            @php $bottomNav = [['route'=>'dashboard','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','label'=>'Dashboard'],['route'=>'budget.index','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Budget'],['route'=>'expenses.index','icon'=>'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z','label'=>'Pengeluaran'],['route'=>'recurring.index','icon'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15','label'=>'Berulang'],['route'=>'categories.index','icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z','label'=>'Kategori'],['route'=>'reports.index','icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','label'=>'Laporan']]; @endphp
            @foreach($bottomNav as $item)
                <a href="{{ route($item['route']) }}" class="bottom-nav-item flex flex-col items-center justify-center px-2 py-1 rounded-xl transition-all {{ request()->routeIs(str_replace('.', '*', $item['route'])) ? 'active text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500' }}">
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
        }

        document.addEventListener('DOMContentLoaded', function() {
            hideLoading();
        });

        // Theme
        function getTheme() { return localStorage.getItem('theme') || 'light'; }
        function applyTheme(theme) {
            var html = document.documentElement;
            if (theme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            updateThemeIcon(theme);
        }
        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            applyTheme(theme);
        }
        function updateThemeIcon(theme) {
            var darkIcon = document.getElementById('theme-icon-dark');
            var lightIcon = document.getElementById('theme-icon-light');
            if (theme === 'dark') {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }

        var savedTheme = getTheme();
        applyTheme(savedTheme);

        document.getElementById('theme-toggle').addEventListener('click', function(e) {
            e.stopPropagation();
            setTheme(getTheme() === 'dark' ? 'light' : 'dark');
        });

        // Clock
        function updateClock() {
            var now = new Date();
            var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            var months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            var dayName = days[now.getDay()];
            var dateStr = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            var timeStr = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0') + ':' + String(now.getSeconds()).padStart(2,'0') + ' WIB';
            var mobileEl = document.getElementById('clock-mobile');
            var desktopEl = document.getElementById('clock-desktop');
            if (mobileEl) mobileEl.textContent = dayName + ', ' + dateStr;
            if (desktopEl) desktopEl.textContent = dayName + ', ' + dateStr + ' ' + timeStr;
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Confirm Modal
        var _confirmCallback = null;
        var _confirmRequire = null;
        var _modalIcons = {
            danger: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#FEE2E2" stroke="#FCA5A5" stroke-width="1.5"/><path d="M12 7v5" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round"/><circle cx="12" cy="16" r="1.2" fill="#DC2626"/></svg>',
            warning: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#FEF3C7" stroke="#FCD34D" stroke-width="1.5"/><path d="M12 7v6" stroke="#D97706" stroke-width="2.5" stroke-linecap="round"/><circle cx="12" cy="16" r="1.2" fill="#D97706"/></svg>',
            primary: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#DBEAFE" stroke="#93C5FD" stroke-width="1.5"/><path d="M7.5 12.5l3 3 6.5-7" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            success: '<svg class="w-14 h-14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#D1FAE5" stroke="#6EE7B7" stroke-width="1.5"/><path d="M7.5 12.5l3 3 6.5-7" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
        };
        var _modalBtnColors = {
            danger: 'bg-red-600 hover:bg-red-700 active:bg-red-800',
            warning: 'bg-amber-500 hover:bg-amber-600 active:bg-amber-700',
            primary: 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800',
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