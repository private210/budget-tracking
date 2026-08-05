@extends('layouts.app')

@section('title', 'Laporan - Budget Tracker')

@section('content')
<div class="space-y-4 md:space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Laporan Keuangan</h1>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan pengeluaran dan pendapatan</p>
        </div>
        <form action="{{ route('reports.index', [], false) }}" method="GET">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                class="w-full sm:w-auto border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2.5 transition-all">
        </form>
    </div>

    <div class="grid grid-cols-3 gap-3 md:gap-4">
        <div class="stat-card fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-3 md:p-5 border border-gray-200 dark:border-gray-700">
            <p class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400">Gaji</p>
            <p class="text-sm md:text-2xl font-bold text-gray-900 dark:text-white truncate mt-1" data-count="{{ $salary?->amount ?? 0 }}">Rp {{ number_format($salary?->amount ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-3 md:p-5 border border-gray-200 dark:border-gray-700">
            <p class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400">Pengeluaran</p>
            <p class="text-sm md:text-2xl font-bold text-red-600 dark:text-red-400 truncate mt-1" data-count="{{ $totalExpenses }}">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-3 md:p-5 border border-gray-200 dark:border-gray-700">
            @php $remaining = ($salary?->amount ?? 0) - $totalExpenses; @endphp
            <p class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400">Sisa</p>
            <p class="text-sm md:text-2xl font-bold {{ $remaining >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} truncate mt-1" data-count="{{ $remaining }}">
                Rp {{ number_format($remaining, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div id="charts-card" class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
                    Per Kategori
                </h3>
                <div class="relative flex items-center justify-center" style="height: 200px; max-height: 240px;">
                    <canvas id="categoryChart"></canvas>
                    @if($categoryBreakdown->count() === 0)
                        <div class="absolute inset-0 flex items-center justify-center">
                            <p class="text-gray-400 dark:text-gray-500 text-sm text-center">Belum ada data</p>
                        </div>
                    @endif
                </div>
                @if($categoryBreakdown->count() > 0)
                    <div class="space-y-1.5 mt-3">
                        @foreach($categoryBreakdown as $item)
                            @php $percentage = $totalExpenses > 0 ? ($item->total / $totalExpenses) * 100 : 0; @endphp
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $item->category->color }}"></div>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $item->category->icon }} {{ $item->category->name }}</span>
                                </div>
                                <span class="text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    Rp {{ number_format($item->total, 0, ',', '.') }} ({{ number_format($percentage, 1) }}%)
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Pengeluaran Harian
                </h3>
                <div class="relative" style="height: 200px; max-height: 280px;">
                    <canvas id="dailyChart"></canvas>
                    @if($dailyExpenses->count() === 0)
                        <div class="absolute inset-0 flex items-center justify-center">
                            <p class="text-gray-400 dark:text-gray-500 text-sm text-center">Belum ada data</p>
                        </div>
                    @endif
                </div>
                @if($dailyExpenses->count() > 1)
                    <div id="dailySummary" class="mt-3 space-y-1.5"></div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <div id="top-expenses" class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17V9m0 0L9 13m4-4l4 4M20 12a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
                Pengeluaran Terbesar
            </h2>
            @if($topExpenses->count() > 0)
                <div class="space-y-2 md:space-y-3">
                    @foreach($topExpenses as $expense)
                        <div class="flex items-center gap-3 p-2 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm shrink-0" style="background-color: {{ $expense->category->color }}15;">
                                {{ $expense->category->icon }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs md:text-sm font-medium text-gray-900 dark:text-white truncate">{{ $expense->description }}</p>
                                <p class="text-[10px] md:text-xs text-gray-500 dark:text-gray-400">{{ $expense->spent_at->format('d M Y') }}</p>
                            </div>
                            <span class="font-semibold text-red-600 dark:text-red-400 text-xs md:text-sm whitespace-nowrap">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada pengeluaran untuk bulan ini.</p>
                </div>
            @endif
        </div>

        <div id="daily-table" class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Ringkasan Harian
            </h2>
            @if($dailyExpenses->count() > 0)
                <div class="overflow-x-auto max-h-60 md:max-h-80 scrollbar-hide">
                    <table class="w-full mobile-card-table">
                        <thead>
                            <tr class="text-left text-sm text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                <th class="pb-3 font-medium">Tanggal</th>
                                <th class="pb-3 font-medium text-right">Jumlah</th>
                                <th class="pb-3 font-medium text-right">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyExpenses as $idx => $daily)
                                @php
                                    $prevTotal = $idx > 0 ? $dailyExpenses[$idx - 1]->total : 0;
                                    $diff = $daily->total - $prevTotal;
                                @endphp
                                <tr class="border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <td class="py-2 text-xs md:text-sm text-gray-700 dark:text-gray-300" data-label="Tanggal">{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</td>
                                    <td class="py-2 text-xs md:text-sm text-right font-medium text-gray-900 dark:text-white" data-label="Jumlah">Rp {{ number_format($daily->total, 0, ',', '.') }}</td>
                                    <td class="py-2 text-xs md:text-sm text-right font-medium {{ $diff > 0 ? 'text-red-500 dark:text-red-400' : ($diff < 0 ? 'text-green-500 dark:text-green-400' : 'text-gray-400 dark:text-gray-500') }}" data-label="Selisih">
                                        {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada pengeluaran untuk bulan ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var isDark = document.documentElement.classList.contains('dark');
        var textColor = isDark ? '#9ca3af' : '#6b7280';
        var gridColor = isDark ? 'rgba(75,85,99,0.3)' : 'rgba(209,213,219,0.5)';
        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;

        var categoryData = {!! json_encode($categoryBreakdown->map(fn($item) => ['label' => $item->category->icon . ' ' . $item->category->name, 'value' => $item->total, 'color' => $item->category->color]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
        var dailyData = {!! json_encode($dailyExpenses->map(fn($d) => ['label' => \Carbon\Carbon::parse($d->date)->format('d M'), 'value' => $d->total]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};

        var catCtx = document.getElementById('categoryChart');
        if (catCtx) {
            if (categoryData.length === 0) {
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: { labels: ['Kosong'], datasets: [{ data: [1], backgroundColor: [isDark ? '#374151' : '#e5e7eb'], borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }
                });
            } else {
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categoryData.map(function(d) { return d.label; }),
                        datasets: [{ data: categoryData.map(function(d) { return d.value; }), backgroundColor: categoryData.map(function(d) { return d.color; }), borderWidth: 0, hoverOffset: 6 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '55%',
                        plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx) { return 'Rp ' + ctx.parsed.toLocaleString('id-ID'); } } } }
                    }
                });
            }
        }

        var dayCtx = document.getElementById('dailyChart');
        if (dayCtx) {
            if (dailyData.length === 0) {
                new Chart(dayCtx, {
                    type: 'line',
                    data: { labels: ['Kosong'], datasets: [{ data: [0], borderColor: isDark ? '#374151' : '#d1d5db', borderWidth: 2, pointRadius: 0, fill: false }] },
                    options: { responsive: true, maintainAspectRatio: false, scales: { x: { display: false }, y: { display: false } }, plugins: { legend: { display: false }, tooltip: { enabled: false } } }
                });
            } else {
                var values = dailyData.map(function(d) { return d.value; });
                var differences = values.map(function(v, i) { return i === 0 ? 0 : v - values[i - 1]; });
                var pointColors = differences.map(function(d) { return d > 0 ? 'rgba(239,68,68,1)' : d < 0 ? 'rgba(34,197,94,1)' : 'rgba(99,102,241,1)'; });

                var gradient = dayCtx.getContext('2d').createLinearGradient(0, 0, 0, 280);
                gradient.addColorStop(0, isDark ? 'rgba(99,102,241,0.3)' : 'rgba(99,102,241,0.2)');
                gradient.addColorStop(1, 'rgba(99,102,241,0.0)');

                new Chart(dayCtx, {
                    type: 'line',
                    data: {
                        labels: dailyData.map(function(d) { return d.label; }),
                        datasets: [{
                            label: 'Pengeluaran',
                            data: values,
                            borderColor: isDark ? 'rgba(99,102,241,0.8)' : 'rgba(99,102,241,1)',
                            backgroundColor: gradient,
                            borderWidth: 3, fill: true, tension: 0.4,
                            pointRadius: 4, pointHoverRadius: 6,
                            pointBackgroundColor: pointColors,
                            pointBorderColor: '#fff', pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { callback: function(v) { return v >= 1000 ? (v/1000) + 'k' : v; }, font: { size: 9 } } }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        var idx = ctx.dataIndex;
                                        var val = 'Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                                        if (idx === 0) return val;
                                        var diff = values[idx] - values[idx - 1];
                                        var sign = diff > 0 ? '+' : '';
                                        return [val, 'Selisih: ' + sign + 'Rp ' + diff.toLocaleString('id-ID')];
                                    }
                                }
                            }
                        },
                        interaction: { intersect: false, mode: 'index' }
                    }
                });

                var summaryEl = document.getElementById('dailySummary');
                if (summaryEl && differences.length > 1) {
                    var maxInc = Math.max.apply(null, differences);
                    var negDiffs = differences.filter(function(d) { return d < 0; });
                    var maxDec = negDiffs.length ? Math.min.apply(null, negDiffs) : 0;
                    var maxIncIdx = differences.indexOf(maxInc);
                    var maxDecIdx = differences.indexOf(maxDec);
                    var html = '';
                    if (maxInc > 0) {
                        html += '<div class="flex items-center justify-between text-xs p-2 bg-red-50 dark:bg-red-900/20 rounded-xl"><span class="text-red-600 dark:text-red-400">Naik tertinggi</span><span class="font-semibold text-red-600 dark:text-red-400">+' + maxInc.toLocaleString('id-ID') + ' (' + dailyData[maxIncIdx].label + ')</span></div>';
                    }
                    if (maxDec < 0) {
                        html += '<div class="flex items-center justify-between text-xs p-2 bg-green-50 dark:bg-green-900/20 rounded-xl"><span class="text-green-600 dark:text-green-400">Turun terbesar</span><span class="font-semibold text-green-600 dark:text-green-400">' + maxDec.toLocaleString('id-ID') + ' (' + dailyData[maxDecIdx].label + ')</span></div>';
                    }
                    summaryEl.innerHTML = html;
                }
            }
        }

        anime({ targets: '#charts-card', opacity: [0, 1], translateY: [16, 0], duration: 400, delay: 150, easing: 'easeOutCubic' });
        anime({ targets: '#top-expenses', opacity: [0, 1], translateY: [16, 0], duration: 400, delay: 250, easing: 'easeOutCubic' });
        anime({ targets: '#daily-table', opacity: [0, 1], translateY: [16, 0], duration: 400, delay: 350, easing: 'easeOutCubic' });
    });
</script>
@endpush