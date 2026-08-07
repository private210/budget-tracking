@extends('layouts.app')

@section('title', 'Pengeluaran Berulang - Budget Tracker')

@section('content')
<div class="space-y-4 md:space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Pengeluaran Berulang</h1>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola tagihan bulanan, mingguan, atau tahunan</p>
        </div>
        <a href="{{ route('recurring.create') }}"
            class="bg-[#1BA37A] text-white px-3 md:px-4 py-2 rounded-2xl hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press text-sm font-medium inline-block shadow-sm">
            + Tambah Baru
        </a>
    </div>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        @if($recurringExpenses->count() > 0)
            <div id="recurring-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                @foreach($recurringExpenses as $recurring)
                    <div class="recurring-item border rounded-2xl p-4 transition-all {{ !$recurring->is_active ? 'border-gray-200 dark:border-gray-700 opacity-55' : 'border-gray-200 dark:border-gray-700 hover:border-[#1BA37A]/40 dark:hover:border-[#1BA37A]/60 hover:shadow-sm' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 {{ $recurring->is_active ? 'bg-[#BDE0D2] dark:bg-[#1BA37A]/25' : 'bg-gray-100 dark:bg-gray-700/50' }}">
                                    <svg class="w-5 h-5 {{ $recurring->is_active ? 'text-[#1BA37A] dark:text-[#6EE7B0]' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm md:text-base truncate">{{ $recurring->name }}</p>
                                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 truncate">{{ $recurring->category->icon }} {{ $recurring->category->name }}</p>
                                </div>
                            </div>
                            <form action="{{ route('recurring.update', $recurring, false) }}" method="POST" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $recurring->is_active ? 0 : 1 }}">
                                <button type="submit" class="text-xs px-3 py-1.5 rounded-xl font-medium {{ $recurring->is_active ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} active:scale-95 transition-all btn-press">
                                    {{ $recurring->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </div>

                        <div class="space-y-2 text-xs md:text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Jumlah:</span>
                                <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($recurring->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Frekuensi:</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ ucfirst($recurring->frequency) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Jatuh Tempo:</span>
                                <span class="{{ $recurring->isDue() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $recurring->next_due_date->format('d M Y') }}
                                    @if($recurring->isDue())
                                        <span class="ml-1 text-red-600 dark:text-red-400">⚡</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 md:mt-4 flex gap-2">
                            @if($recurring->isDue())
                                <form action="{{ route('recurring.pay', $recurring, false) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-2xl text-sm hover:bg-green-600 active:bg-green-700 transition-all btn-press shadow-sm">
                                        Bayar & Perbarui
                                    </button>
                                </form>
                            @endif
                            <button type="button" onclick="confirmDeleteRecurring('{{ route('recurring.destroy', $recurring, false) }}', '{{ addslashes($recurring->name) }}')" class="flex-1 bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 py-2 rounded-2xl text-sm hover:bg-red-200 dark:hover:bg-red-800/50 active:bg-red-300 dark:active:bg-red-700/50 transition-all btn-press">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada pengeluaran berulang.</p>
                <a href="{{ route('recurring.create') }}" class="mt-3 inline-block bg-[#1BA37A] text-white px-5 py-2 rounded-2xl text-sm hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press">Tambah Baru</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDeleteRecurring(url, name) {
        showConfirm({
            type: 'danger',
            title: 'Hapus Pengeluaran Berulang?',
            message: 'Yakin ingin menghapus "' + name + '" dari daftar berulang? Tindakan ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Hapus',
            onConfirm: function() {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        anime({ targets: '.recurring-item', opacity: [0, 1], translateY: [12, 0], scale: [0.97, 1], duration: 350, delay: anime.stagger(50, { start: 100 }), easing: 'easeOutCubic' });
    });
</script>
@endpush