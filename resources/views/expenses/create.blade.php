@extends('layouts.app')

@section('title', 'Tambah Pengeluaran - Budget Tracker')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-3 mb-5">
        <a href="{{ route('expenses.index') }}" class="p-2 -m-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 active:bg-gray-200 dark:active:bg-gray-600 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Tambah Pengeluaran</h1>
    </div>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('expenses.store', [], false) }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                <input type="text" name="description" value="{{ old('description') }}" required
                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all"
                    placeholder="Contoh: Makan siang, Beli bensin, dll">
                @error('description')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jumlah</label>
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-[#1BA37A] focus-within:border-[#1BA37A] transition-all bg-white dark:bg-gray-700">
                    <span class="pl-4 pr-3 py-2.5 md:py-3 text-black dark:text-white text-sm md:text-md font-medium border-r border-gray-300 dark:border-gray-600 shrink-0">Rp</span>
                    <input type="text" name="amount_display" inputmode="numeric"
                        value="{{ old('amount_display') }}" required
                        class="w-full min-w-0 border-0 px-3 py-2.5 md:py-3 bg-transparent text-gray-900 dark:text-white text-sm md:text-base focus:outline-none focus:ring-0"
                        placeholder="0" oninput="formatRupiah(this)" onfocus="this.select()">
                    <input type="hidden" name="amount" value="{{ old('amount') }}">
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kategori</label>
                <select name="category_id" required class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            @if($allocations->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alokasi Budget <span class="text-gray-400 dark:text-gray-500 font-normal">(Opsional)</span></label>
                    <select name="budget_allocation_id" class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all">
                        <option value="">Tidak ada alokasi</option>
                        @foreach($allocations as $allocation)
                            <option value="{{ $allocation->id }}" {{ old('budget_allocation_id') == $allocation->id ? 'selected' : '' }}>
                                {{ $allocation->category->icon }} {{ $allocation->category->name }}
                                - Sisa: Rp {{ number_format($allocation->remaining(), 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Pilih alokasi untuk melacak pengeluaran dari budget tertentu</p>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal</label>
                <input type="date" name="spent_at" value="{{ old('spent_at', now()->format('Y-m-d')) }}" required
                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all">
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" class="flex-1 bg-[#1BA37A] text-white py-3 rounded-2xl hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press font-medium text-sm md:text-base shadow-sm">
                    Simpan Pengeluaran
                </button>
                <button type="button" onclick="confirmCancel('{{ route('expenses.index', [], false) }}')" class="flex-1 text-center bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-2xl hover:bg-gray-300 dark:hover:bg-gray-500 active:bg-gray-400 dark:active:bg-gray-500 transition-all btn-press font-medium text-sm md:text-base">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(el) {
        var raw = el.value.replace(/[^0-9]/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        var hidden = el.parentElement.querySelector('input[type="hidden"]');
        if (hidden) hidden.value = raw || '0';
    }

    function confirmCancel(url) {
        showConfirm({
            type: 'warning',
            title: 'Batalkan Pengisian?',
            message: 'Data yang belum disimpan akan hilang. Yakin ingin kembali?',
            confirmText: 'Ya, Kembali',
            onConfirm: function() { showLoading(); window.location.href = url; }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        anime({ targets: '.fade-in-card form > div', opacity: [0, 1], translateY: [10, 0], duration: 300, delay: anime.stagger(60, { start: 100 }), easing: 'easeOutCubic' });
    });
</script>
@endpush