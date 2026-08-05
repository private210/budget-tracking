@extends('layouts.app')

@section('title', 'Kategori - Budget Tracker')

@section('content')
<div class="space-y-4 md:space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Kategori</h1>
    </div>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Tambah Kategori
        </h2>
        <form action="{{ route('categories.store', [], false) }}" method="POST" class="flex flex-col sm:flex-row gap-3 items-end">
            @csrf
            <div class="flex-1 min-w-0 w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="cth: Transportasi" required maxlength="50"
                    class="w-full border border-gray-200 dark:border-gray-600/80 bg-gray-50 dark:bg-gray-700/80 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2.5 transition-all duration-200">
                @error('name')
                    <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="w-full sm:w-24">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Ikon</label>
                <input type="text" name="icon" value="{{ old('icon') }}" placeholder="🍜" required maxlength="10"
                    class="w-full text-center border border-gray-200 dark:border-gray-600/80 bg-gray-50 dark:bg-gray-700/80 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2.5 transition-all duration-200">
            </div>
            <div class="w-full sm:w-24">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Warna</label>
                <input type="color" name="color" value="{{ old('color', '#6366f1') }}"
                    class="w-full h-[42px] border border-gray-200 dark:border-gray-600/80 bg-gray-50 dark:bg-gray-700/80 rounded-2xl shadow-sm cursor-pointer p-1">
            </div>
            <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-5 py-2.5 rounded-2xl hover:bg-indigo-700 active:bg-indigo-800 transition-all btn-press font-medium text-sm md:text-base shadow-sm">
                Simpan
            </button>
        </form>
    </div>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Daftar Kategori
        </h2>

        @if($categories->count() > 0)
            <div class="space-y-2.5">
                @foreach($categories as $category)
                    <div class="flex items-center gap-3 p-3 md:p-4 rounded-2xl border border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/20">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0" style="background-color: {{ $category->color }}15;">
                            {{ $category->icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate flex items-center gap-2">
                                {{ $category->name }}
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $category->color }}"></span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $category->expenses_count }} pengeluaran &middot; Rp {{ number_format($category->expenses_sum_amount ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('expenses.index', ['category_id' => $category->id], false) }}"
                                class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all"
                                title="Lihat pengeluaran" onclick="showLoading()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <button type="button" onclick="toggleEditCategory({{ $category->id }})" class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button type="button" onclick="confirmDeleteCategory('{{ route('categories.destroy', $category, false) }}', '{{ addslashes($category->name) }}')" class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-all" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    <form id="edit-cat-{{ $category->id }}" action="{{ route('categories.update', $category, false) }}" method="POST"
                        class="hidden ml-3 mr-3 -mt-1 mb-2 flex flex-col sm:flex-row gap-3 items-end p-3 rounded-2xl bg-indigo-50/60 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50">
                        @csrf
                        @method('PATCH')
                        <div class="flex-1 min-w-0 w-full">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Nama</label>
                            <input type="text" name="name" value="{{ $category->name }}" required maxlength="50"
                                class="w-full border border-gray-200 dark:border-gray-600/80 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2 transition-all duration-200">
                        </div>
                        <div class="w-full sm:w-24">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Ikon</label>
                            <input type="text" name="icon" value="{{ $category->icon }}" required maxlength="10"
                                class="w-full text-center border border-gray-200 dark:border-gray-600/80 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2 transition-all duration-200">
                        </div>
                        <div class="w-full sm:w-24">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 ml-1">Warna</label>
                            <input type="color" name="color" value="{{ $category->color }}"
                                class="w-full h-[38px] border border-gray-200 dark:border-gray-600/80 bg-white dark:bg-gray-700 rounded-2xl shadow-sm cursor-pointer p-1">
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-2xl hover:bg-green-700 active:bg-green-800 transition-all btn-press font-medium text-sm shadow-sm">
                            Simpan
                        </button>
                    </form>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada kategori. Tambahkan kategori pertama di atas.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleEditCategory(id) {
        var form = document.getElementById('edit-cat-' + id);
        if (!form) return;
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.querySelector('input[name="name"]').focus();
        }
    }

    function confirmDeleteCategory(url, name) {
        showConfirm({
            type: 'danger',
            title: 'Hapus Kategori?',
            message: 'Yakin ingin menghapus kategori "' + name + '"?',
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
</script>
@endpush
