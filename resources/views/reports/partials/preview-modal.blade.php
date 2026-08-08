{{-- resources/views/reports/partials/preview-modal.blade.php --}}
<div id="report-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 md:p-8" aria-hidden="true">
    {{-- backdrop --}}
    <div id="report-preview-backdrop"
         onclick="closeReportPreview()"
         class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-200 opacity-0"></div>

    {{-- panel --}}
    <div id="report-preview-panel"
         class="relative w-full max-w-5xl h-[88vh] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden transform transition-all duration-200 opacity-0 scale-95">

        {{-- header --}}
        <div class="flex items-center justify-between gap-3 px-4 md:px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p id="report-preview-filename" class="text-sm md:text-base font-semibold text-gray-900 dark:text-white truncate">laporan-pengeluaran.pdf</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pratinjau Laporan Pengeluaran</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a id="report-preview-download" href="#"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs md:text-sm font-semibold text-white bg-[#1BA37A] hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">Download</span>
                </a>
                <button type="button" onclick="closeReportPreview()"
                        class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                        aria-label="Tutup pratinjau">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- body --}}
        <div class="relative flex-1 bg-gray-100 dark:bg-gray-900">
            <div id="report-preview-loading" class="absolute inset-0 flex flex-col items-center justify-center gap-3">
                <svg class="w-8 h-8 animate-spin text-[#1BA37A]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <p class="text-xs text-gray-500 dark:text-gray-400">Memuat pratinjau…</p>
            </div>
            <iframe id="report-preview-iframe"
                    title="Pratinjau Laporan PDF"
                    class="w-full h-full border-0 bg-white"
                    onload="document.getElementById('report-preview-loading').classList.add('hidden')"></iframe>
        </div>
    </div>
</div>

<script>
    function openReportPreview(trigger) {
        const modal = document.getElementById('report-preview-modal');
        const backdrop = document.getElementById('report-preview-backdrop');
        const panel = document.getElementById('report-preview-panel');
        const iframe = document.getElementById('report-preview-iframe');
        const loading = document.getElementById('report-preview-loading');
        const filename = document.getElementById('report-preview-filename');
        const download = document.getElementById('report-preview-download');

        filename.textContent = trigger.dataset.filename;
        download.href = trigger.dataset.downloadUrl;
        loading.classList.remove('hidden');
        iframe.src = trigger.dataset.previewUrl;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
        });
    }

    function closeReportPreview() {
        const modal = document.getElementById('report-preview-modal');
        const backdrop = document.getElementById('report-preview-backdrop');
        const panel = document.getElementById('report-preview-panel');
        const iframe = document.getElementById('report-preview-iframe');

        backdrop.classList.add('opacity-0');
        panel.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            iframe.src = 'about:blank';
        }, 200);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeReportPreview();
    });
</script>