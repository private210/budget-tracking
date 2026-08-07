@extends('layouts.app')

@section('title', 'Budget - Budget Tracker')

@section('content')
<div class="space-y-4 md:space-y-6">
    <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Alokasi Budget</h1>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Input Gaji
        </h2>
        <form action="{{ route('budget.salary.store', [], false) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jumlah Gaji</label>
                    <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-[#1BA37A] focus-within:border-[#1BA37A] transition-all bg-white dark:bg-gray-700">
                        <span class="pl-4 pr-3 py-2.5 md:py-3 text-black dark:text-white text-sm md:text-md font-medium border-r border-gray-300 dark:border-gray-600 shrink-0">Rp</span>
                        <input type="text" name="amount_display" inputmode="numeric"
                            value="{{ $salary ? number_format($salary->amount, 0, ',', '.') : old('amount_display') }}" required
                            class="w-full min-w-0 border-0 px-3 py-2.5 md:py-3 bg-transparent text-gray-900 dark:text-white text-sm md:text-base focus:outline-none focus:ring-0"
                            placeholder="0" oninput="formatRupiah(this)" onfocus="this.select()">
                        <input type="hidden" name="amount" value="{{ $salary->amount ?? old('amount') }}">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Diterima</label>
                    <input type="date" name="received_at" value="{{ $salary?->received_at?->format('Y-m-d') ?? now()->format('Y-m-d') }}" required
                        class="w-full min-w-0 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-3 py-2 transition-all">
                    @error('received_at')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Catatan</label>
                    <input type="text" name="note" value="{{ $salary?->note ?? old('note') }}"
                        class="w-full min-w-0 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-3 py-2 transition-all"
                        placeholder="Opsional">
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto bg-[#1BA37A] text-white px-6 py-2.5 rounded-2xl hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press font-medium text-sm md:text-base shadow-sm">
                Simpan Gaji
            </button>
        </form>
    </div>

    @if($salary)
        <div class="flex justify-end">
            <button type="button" id="toggle-alloc" onclick="toggleAllocForm()" class="bg-[#1BA37A] text-white px-4 md:px-5 py-2.5 rounded-2xl hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press font-medium text-sm md:text-base shadow-sm">
                + Alokasikan Dana
            </button>
        </div>
        <div id="alloc-form" class="hidden fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Bagi Budget ke Kategori
            </h2>
            <form action="{{ route('budget.allocate', [], false) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="salary_id" value="{{ $salary->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                    @foreach($categories as $category)
                        @php
                            $currentAllocation = $allocations->firstWhere('category_id', $category->id);
                        @endphp
                        <div class="border border-gray-200 dark:border-gray-700 rounded-2xl p-3 md:p-4 hover:border-[#1BA37A]/50 dark:hover:border-[#1BA37A]/60 transition-all active:scale-[0.98] hover:shadow-sm">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0" style="background-color: {{ $category->color }}15;">
                                    {{ $category->icon }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white text-sm md:text-base truncate">{{ $category->name }}</p>
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></div>
                                </div>
                            </div>
                            <input type="hidden" name="allocations[{{ $loop->index }}][category_id]" value="{{ $category->id }}">
                            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-[#1BA37A] focus-within:border-[#1BA37A] transition-all bg-white dark:bg-gray-700">
                                <span class="pl-4 pr-3 py-2.5 md:py-3 text-black dark:text-white text-sm md:text-md font-medium border-r border-gray-300 dark:border-gray-600 shrink-0">Rp</span>
                                <input type="text" name="alloc_display[{{ $loop->index }}]" inputmode="numeric"
                                    value="{{ number_format($currentAllocation->amount ?? 0, 0, ',', '.') }}"
                                    class="w-full min-w-0 border-0 px-3 py-2.5 md:py-3 bg-transparent text-gray-900 dark:text-white text-sm md:text-base focus:outline-none focus:ring-0 allocation-input"
                                    placeholder="0" oninput="formatRupiah(this)" onfocus="this.select()">
                                <input type="hidden" name="allocations[{{ $loop->index }}][amount]" value="{{ $currentAllocation->amount ?? 0 }}" class="alloc-raw">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Total Gaji:</span>
                        <span class="font-semibold text-gray-900 dark:text-white" data-count="{{ $salary->amount }}">Rp {{ number_format($salary->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Sisa Belum Dialokasikan:</span>
                        <span id="remaining" class="font-semibold text-green-600 dark:text-green-400" data-count="{{ $salary->amount }}">Rp {{ number_format($salary->amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="button" onclick="confirmSaveAllocate()" class="w-full md:w-auto bg-green-600 text-white px-6 py-2.5 rounded-2xl hover:bg-green-700 active:bg-green-800 transition-all btn-press font-medium text-sm md:text-base shadow-sm">
                    Simpan Alokasi
                </button>
            </form>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(el) {
        let raw = el.value.replace(/[^0-9]/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        const hidden = el.parentElement.querySelector('input[type="hidden"]');
        if (hidden) hidden.value = raw || '0';
    }

    function confirmSaveAllocate() {
        showConfirm({
            type: 'success',
            title: 'Simpan Alokasi?',
            message: 'Alokasi budget akan diperbarui sesuai jumlah yang dimasukkan.',
            confirmText: 'Ya, Simpan',
            onConfirm: function() {
                document.querySelector('form[action="{{ route('budget.allocate', [], false) }}"]').submit();
            }
        });
    }

    function toggleAllocForm() {
        var form = document.getElementById('alloc-form');
        var btn = document.getElementById('toggle-alloc');
        if (!form) return;
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            btn.textContent = 'Sembunyikan Alokasi';
            anime({ targets: '#alloc-form', opacity: [0, 1], translateY: [16, 0], duration: 400, delay: 100, easing: 'easeOutCubic' });
            anime({ targets: '.alloc-item', opacity: [0, 1], translateX: [-8, 0], duration: 300, delay: anime.stagger(60, { start: 200 }), easing: 'easeOutCubic' });
            if (typeof animateNumbers !== 'undefined') animateNumbers();
        } else {
            form.classList.add('hidden');
            btn.textContent = '+ Alokasikan Dana';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var salaryAmount = {{ $salary->amount ?? 0 }};

        function updateRemaining() {
            var total = 0;
            document.querySelectorAll('.alloc-raw').forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });
            var remaining = salaryAmount - total;
            var el = document.getElementById('remaining');
            el.textContent = 'Rp ' + remaining.toLocaleString('id-ID');
            el.className = remaining >= 0 ? 'font-semibold text-green-600 dark:text-green-400' : 'font-semibold text-red-600 dark:text-red-400';
            anime({ targets: el, scale: [1.05, 1], duration: 200, easing: 'easeOutCubic' });
        }

        document.querySelectorAll('.allocation-input').forEach(function(input) {
            input.addEventListener('input', function() {
                var hidden = this.parentElement.querySelector('.alloc-raw');
                if (hidden) hidden.value = this.value.replace(/[^0-9]/g, '') || '0';
                updateRemaining();
            });
        });
    });
</script>
@endpush