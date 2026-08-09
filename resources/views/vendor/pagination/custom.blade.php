@if ($paginator->hasPages())
    <nav class="flex flex-col items-center gap-3 mt-4">
        <ul class="flex flex-wrap items-center justify-center gap-1.5 p-1.5 bg-gray-50 dark:bg-gray-700/40 rounded-2xl border border-gray-200 dark:border-gray-600/60">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <li class="flex items-center justify-center w-9 h-9 rounded-xl text-gray-300 dark:text-gray-600 cursor-not-allowed" aria-disabled="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" onclick="showLoading()" class="flex items-center justify-center w-9 h-9 rounded-xl bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-[#1BA37A]/10 hover:text-[#1BA37A] dark:hover:text-[#6EE7B0] active:scale-95 transition-all btn-press shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                </li>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="flex items-center justify-center w-9 h-9 text-sm text-gray-400 dark:text-gray-500">...</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="flex items-center justify-center w-9 h-9 rounded-xl bg-[#1BA37A] text-white text-sm font-semibold shadow-md shadow-[#1BA37A]/30">{{ $page }}</li>
                        @else
                            <li>
                                <a href="{{ $url }}" onclick="showLoading()" class="flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 active:scale-95 transition-all">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" onclick="showLoading()" class="flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-[#1BA37A]/10 hover:text-[#1BA37A] dark:hover:text-[#6EE7B0] active:scale-95 transition-all btn-press shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            @else
                <li class="flex items-center justify-center w-9 h-9 rounded-xl text-gray-300 dark:text-gray-600 cursor-not-allowed" aria-disabled="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
            @endif
        </ul>

        <p class="text-xs text-gray-500 dark:text-gray-400">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>
    </nav>
@endif