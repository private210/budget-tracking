@extends('layouts.app')

@section('title', 'Pengeluaran - Budget Tracker')

@section('content')
<div class="space-y-4 md:space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Pengeluaran</h1>
        <a href="{{ route('expenses.create') }}" class="bg-indigo-600 text-white px-3 md:px-4 py-2 rounded-2xl hover:bg-indigo-700 active:bg-indigo-800 transition-all btn-press text-sm font-medium shadow-sm">
            + Tambah
        </a>
    </div>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <form id="filter-form" action="{{ route('expenses.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 mb-5 items-end">
            <div class="flex-1 min-w-0">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Bulan</label>
                <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}"
                    onchange="animateFilterAndSubmit(this)"
                    class="w-full border border-gray-200 dark:border-gray-600/80 bg-gray-50 dark:bg-gray-700/80 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2.5 transition-all duration-200 cursor-pointer">
            </div>
            <div class="flex-1 min-w-0">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Kategori</label>
                <select name="category_id" onchange="animateFilterAndSubmit(this)" class="w-full border border-gray-200 dark:border-gray-600/80 bg-gray-50 dark:bg-gray-700/80 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2.5 transition-all duration-200 cursor-pointer">
                    <option value="" class="py-3">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div id="summary-box" class="bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl p-4 mb-5">
            <p class="text-xs text-indigo-600 dark:text-indigo-400">Total Pengeluaran</p>
            <p class="text-xl md:text-2xl font-bold text-indigo-700 dark:text-indigo-300">Rp {{ number_format($totalThisMonth, 0, ',', '.') }}</p>
        </div>

        @if($expenses->count() > 0)
            <div id="expense-list" class="space-y-2.5">
                @foreach($expenses as $expense)
                    <div class="expense-item flex items-center gap-3 p-3 md:p-4 rounded-2xl border border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/20 hover:bg-gray-100 dark:hover:bg-gray-700/40 active:bg-gray-200 dark:active:bg-gray-600/40 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0" style="background-color: {{ $expense->category->color }}15;">
                            {{ $expense->category->icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $expense->description }}</p>
                                @if($expense->is_recurring)
                                    <span class="text-[10px] bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-1.5 py-0.5 rounded-full shrink-0">Berulang</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $expense->spent_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-semibold text-red-600 dark:text-red-400">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
                            <div class="flex items-center justify-end gap-1 mt-1">
                                <a href="{{ route('expenses.edit', $expense) }}" class="p-1.5 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 active:bg-yellow-300 dark:active:bg-yellow-900/70 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <button type="button" onclick="confirmDeleteExpense('{{ route('expenses.destroy', $expense) }}', '{{ addslashes($expense->description) }}')" class="p-1.5 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 active:bg-red-300 dark:active:bg-red-900/70 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $expenses->withQueryString()->links() }}
            </div>
        @else
            <div id="empty-state" class="text-center py-10">
                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada pengeluaran untuk periode ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function animateFilterAndSubmit(el) {
        showLoading();
        anime({
            targets: el,
            scale: [1, 0.97, 1],
            duration: 250,
            easing: 'easeInOutQuad',
            complete: function() {
                el.form.submit();
            }
        });
    }

    function confirmDeleteExpense(url, name) {
        showConfirm({
            type: 'danger',
            title: 'Hapus Pengeluaran?',
            message: 'Yakin ingin menghapus "' + name + '?". Tindakan ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Hapus',
            onConfirm: function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        anime({
            targets: '#summary-box',
            opacity: [0, 1],
            translateY: [12, 0],
            duration: 400,
            easing: 'easeOutCubic',
            delay: 100
        });

        anime({
            targets: '.expense-item',
            opacity: [0, 1],
            translateY: [16, 0],
            scale: [0.97, 1],
            duration: 350,
            delay: anime.stagger(50, { start: 200 }),
            easing: 'easeOutCubic'
        });

        var emptyState = document.getElementById('empty-state');
        if (emptyState) {
            anime({
                targets: emptyState,
                opacity: [0, 1],
                scale: [0.9, 1],
                duration: 500,
                easing: 'easeOutBack',
                delay: 150
            });
        }
    });
</script>
@endpush