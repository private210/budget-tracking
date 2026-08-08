@extends('layouts.app')

@section('title', 'Pratinjau Laporan - Titik Simpan')

@section('content')
<div class="max-w-5xl mx-auto pb-8">
    <div class="flex items-center justify-between gap-3 mb-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 px-4 py-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm md:text-base font-semibold text-gray-900 dark:text-white truncate">laporan-pengeluaran-{{ $month }}.pdf</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Pratinjau — Laporan Pengeluaran {{ $month }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('reports.index', ['month' => $month], false) }}" onclick="showLoading()"
                class="inline-flex items-center gap-1.5 px-3 md:px-4 py-2.5 rounded-xl text-xs md:text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-500 transition-all btn-press">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <a href="{{ route('reports.export', ['format' => 'pdf', 'month' => $month], false) }}" onclick="showLoading()"
                class="inline-flex items-center gap-1.5 px-3 md:px-4 py-2.5 rounded-xl text-xs md:text-sm font-semibold text-white bg-[#1BA37A] hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden" style="height: calc(100vh - 210px); min-height: 480px;">
        <iframe src="{{ route('reports.previewFile', ['format' => 'pdf', 'month' => $month], false) }}" title="Pratinjau Laporan PDF" class="w-full h-full block border-0 bg-white"></iframe>
    </div>
</div>
@endsection
