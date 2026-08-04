@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-2 flex-wrap">
        @if ($paginator->onFirstPage())
            <span class="flex items-center gap-1 px-4 py-2.5 border border-line text-sm font-semibold text-ash cursor-not-allowed">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Back
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="flex items-center gap-1 px-4 py-2.5 border border-line text-sm font-semibold text-ink hover:border-brand hover:text-brand transition-colors duration-200">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Back
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 text-sm text-faint">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="w-10 h-10 border border-brand bg-brand text-white font-semibold text-sm flex items-center justify-center">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-10 h-10 border border-line text-sm font-semibold text-ink flex items-center justify-center hover:border-brand hover:text-brand transition-colors duration-200">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="px-4 py-2.5 border border-line text-sm font-semibold text-ink hover:border-brand hover:text-brand transition-colors duration-200">Next ›</a>
        @else
            <span class="px-4 py-2.5 border border-line text-sm font-semibold text-ash cursor-not-allowed">Next ›</span>
        @endif
    </nav>
@endif
