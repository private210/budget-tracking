@extends('layouts.app')

@section('title', 'Dashboard - Budget Tracker')

@section('content')
<div class="space-y-4 md:space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p id="dashboard-month" class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1"></p>
        </div>
        <button onclick="confirmReset()" class="text-xs md:text-sm text-red-500 hover:text-red-600 dark:text-red-400 font-medium flex items-center gap-1.5 btn-press transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Reset Data
        </button>
    </div>

    <div class="fade-in-card rounded-2xl p-5 md:p-6 bg-[#1BA37A] shadow-[0_12px_32px_-10px_rgba(27,163,122,0.6)] ring-1 ring-black/10 dark:ring-white/10">
        <div class="flex items-center gap-3.5 flex-wrap md:flex-nowrap">
            <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-white font-brand text-lg md:text-xl leading-tight">{{ $timeGreeting }}, {{ $greetingName }}!</p>
                <p class="mt-0.5 text-white/85 font-slogan text-xs md:text-sm leading-snug">{{ $motivation }}</p>
            </div>
            <div class="w-full md:w-auto mt-2 md:mt-0 md:ml-auto">
                <p class="md:text-right inline-flex md:inline-flex items-center gap-1.5 bg-white/15 text-white rounded-full px-3 py-1 text-xs md:text-sm font-slogan">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="greeting-clock"></span>
                </p>
            </div>
        </div>
    </div>

    @if(!$salary)
        <div class="fade-in-card bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl p-5 text-center">
            <div class="w-12 h-12 rounded-2xl bg-yellow-100 dark:bg-yellow-800/50 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-yellow-800 dark:text-yellow-300 font-medium text-sm md:text-base">Belum ada data gaji bulan ini.</p>
            <a href="{{ route('budget.index') }}" class="mt-3 inline-block bg-yellow-500 text-white px-6 py-2.5 rounded-2xl hover:bg-yellow-600 active:bg-yellow-700 transition-all btn-press text-sm md:text-base font-medium shadow-sm">
                Atur Budget Sekarang
            </a>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-3 md:gap-4">
        <div class="stat-card fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-5 border border-gray-200 dark:border-gray-700">
            <div class="w-9 h-9 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Gaji Bulan Ini</p>
            <p class="text-lg md:text-2xl font-bold text-gray-900 dark:text-white truncate mt-1" data-count="{{ $salary?->amount ?? 0 }}">Rp {{ number_format($salary?->amount ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-5 border border-gray-200 dark:border-gray-700">
            <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Total Alokasi</p>
            <p class="text-lg md:text-2xl font-bold text-blue-600 dark:text-blue-400 truncate mt-1" data-count="{{ $totalAllocated }}">Rp {{ number_format($totalAllocated, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-5 border border-gray-200 dark:border-gray-700">
            <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17V9m0 0L9 13m4-4l4 4M20 12a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
            </div>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Sudah Terpakai</p>
            <p class="text-lg md:text-2xl font-bold text-red-600 dark:text-red-400 truncate mt-1" data-count="{{ $totalSpent }}">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-5 border border-gray-200 dark:border-gray-700">
            <div class="w-9 h-9 rounded-xl {{ $remaining >= 0 ? 'bg-green-100 dark:bg-green-900/40' : 'bg-red-100 dark:bg-red-900/40' }} flex items-center justify-center mb-3">
                <svg class="w-4 h-4 {{ $remaining >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Sisa Alokasi</p>
            <p class="text-lg md:text-2xl font-bold {{ $remaining >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} truncate mt-1" data-count="{{ $remaining }}">Rp {{ number_format($remaining, 0, ',', '.') }}</p>
        </div>
    </div>

    @if($allocations->count() > 0)
        <div id="alloc-section" class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Alokasi Budget
            </h2>
            <div class="space-y-3 md:space-y-4">
                @foreach($allocations as $allocation)
                    @php
                        $percentage = $allocation->amount > 0 ? ($allocation->spent / $allocation->amount) * 100 : 0;
                        $color = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-amber-500' : 'bg-green-500');
                    @endphp
                    <div class="alloc-item">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $allocation->category->icon }} {{ $allocation->category->name }}
                            </span>
                            <span class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($allocation->spent, 0, ',', '.') }} / Rp {{ number_format($allocation->amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 md:h-2.5">
                            <div class="{{ $color }} alloc-bar h-2 md:h-2.5 rounded-full transition-all duration-700" style="width: 0%" data-width="{{ min($percentage, 100) }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($dueRecurring->count() > 0)
        <div id="due-section" class="fade-in-card bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border border-orange-200 dark:border-orange-800 rounded-2xl p-4 md:p-6">
            <h2 class="text-base md:text-lg font-semibold text-orange-800 dark:text-orange-300 flex items-center gap-2 mb-3 md:mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                Tagihan Jatuh Tempo
            </h2>
            <div class="space-y-2 md:space-y-3">
                @foreach($dueRecurring as $recurring)
                    <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl p-3 md:p-4 border border-orange-100 dark:border-orange-800 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white text-sm md:text-base">{{ $recurring->name }}</p>
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">{{ $recurring->category->name }} â€¢ Rp {{ number_format($recurring->amount, 0, ',', '.') }}</p>
                        </div>
                        <form action="{{ route('recurring.pay', $recurring, false) }}" method="POST" class="shrink-0">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-2xl text-sm hover:bg-green-600 active:bg-green-700 transition-all btn-press shadow-sm">
                                Bayar
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div id="recent-section" class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Pengeluaran Terakhir
            </h2>
            <a href="{{ route('expenses.create') }}" class="bg-[#1BA37A] text-white px-3 md:px-4 py-2 rounded-2xl text-sm hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press inline-block shadow-sm">
                + Tambah
            </a>
        </div>
        @if($recentExpenses->count() > 0)
            <div class="space-y-2">
                @foreach($recentExpenses as $expense)
                    <div class="flex items-center gap-3 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/20 hover:bg-gray-100 dark:hover:bg-gray-700/40 transition-all">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0" style="background-color: {{ $expense->category->color }}15;">
                            {{ $expense->category->icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $expense->description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $expense->spent_at->format('d M Y') }} â€¢ {{ $expense->category->name }}</p>
                        </div>
                        <p class="text-sm font-semibold text-red-600 dark:text-red-400 shrink-0">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada pengeluaran bulan ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmReset() {
        showConfirm({
            type: 'danger',
            title: 'Reset Semua Data?',
            message: 'Semua gaji, alokasi dana, pengeluaran, dan tagihan berulang akan dihapus permanen. Kategori tetap tersimpan. Ketik HAPUS untuk melanjutkan.',
            confirmText: 'Hapus Semua',
            requireText: 'HAPUS',
            onConfirm: function() {
                var f = document.createElement('form');
                f.method = 'POST';
                f.action = '{{ route('reset-data', [], false) }}';
                var csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                f.appendChild(csrf);
                document.body.appendChild(f);
                f.submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var clockEl = document.getElementById('greeting-clock');
        var monthEl = document.getElementById('dashboard-month');

        var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        var months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        function pad(n) { return (n < 10 ? '0' : '') + n; }

        function updateClock() {
            var now = new Date();
            var dateStr = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            var timeStr = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds()) + ' WIB';
            if (clockEl) clockEl.textContent = dateStr + ' - ' + timeStr;
        }

        if (clockEl) {
            updateClock();
            setInterval(updateClock, 1000);
        }
        if (monthEl) {
            var now = new Date();
            monthEl.textContent = months[now.getMonth()] + ' ' + now.getFullYear();
        }

        anime({ targets: '#alloc-section', opacity: [0, 1], translateY: [16, 0], duration: 400, delay: 150, easing: 'easeOutCubic' });
        anime({ targets: '#due-section', opacity: [0, 1], translateY: [16, 0], duration: 400, delay: 250, easing: 'easeOutCubic' });
        anime({ targets: '#recent-section', opacity: [0, 1], translateY: [16, 0], duration: 400, delay: 350, easing: 'easeOutCubic' });
        anime({ targets: '.alloc-item', opacity: [0, 1], translateX: [-8, 0], duration: 300, delay: anime.stagger(60, { start: 200 }), easing: 'easeOutCubic' });
        anime({ targets: '.alloc-bar', width: (el) => el.dataset.width + '%', duration: 800, delay: anime.stagger(80, { start: 400 }), easing: 'easeOutCubic' });
    });
</script>
@endpush