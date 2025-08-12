@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
    class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-700 p-4 sm:p-6 shadow-sm">

    {{-- Mobile Pagination --}}
    <div class="flex items-center justify-center gap-3 sm:hidden">
        {{-- Prev Button --}}
        @if ($paginator->onFirstPage())
        <span
            class="flex items-center justify-center w-10 h-10 text-gray-400 dark:text-zinc-500 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-full cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </span>
        @else
        <button wire:click="previousPage" wire:loading.attr="disabled"
            class="flex items-center justify-center w-10 h-10 text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-full hover:bg-gray-50 dark:hover:bg-zinc-750 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        @endif

        {{-- Page Info --}}
        <span class="text-sm font-medium text-gray-700 dark:text-zinc-300">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </span>

        {{-- Next Button --}}
        @if ($paginator->hasMorePages())
        <button wire:click="nextPage" wire:loading.attr="disabled"
            class="flex items-center justify-center w-10 h-10 text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-full hover:bg-gray-50 dark:hover:bg-zinc-750 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        @else
        <span
            class="flex items-center justify-center w-10 h-10 text-gray-400 dark:text-zinc-500 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-full cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </span>
        @endif
    </div>

    {{-- Desktop Pagination --}}
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        {{-- Results Info --}}
        <div class="flex items-center space-x-2">
            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-zinc-100">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $paginator->lastItem() }}</span>
                    @else
                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $paginator->count() }}</span>
                    @endif
                    {!! __('of') !!}
                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
                <p class="text-xs text-gray-500 dark:text-zinc-400">Page {{ $paginator->currentPage() }} of {{
                    $paginator->lastPage() }}</p>
            </div>
        </div>

        {{-- Pagination Controls --}}
        <div class="flex items-center space-x-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <span
                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-400 dark:text-zinc-500 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 cursor-not-allowed rounded-xl transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
            @else
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-600 dark:text-zinc-400 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-750 hover:border-gray-300 dark:hover:border-zinc-600 hover:text-gray-900 dark:hover:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 active:bg-gray-100 dark:active:bg-zinc-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            <span
                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-500 dark:text-zinc-400 cursor-default">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 6a2 2 0 110-4 2 2 0 010 4zM12 14a2 2 0 110-4 2 2 0 010 4zM12 22a2 2 0 110-4 2 2 0 010 4z" />
                </svg>
            </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <span
                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 border border-blue-500 rounded-xl shadow-lg shadow-blue-500/25 dark:shadow-blue-500/20 cursor-default">
                {{ $page }}
            </span>
            @else
            <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-750 hover:border-gray-300 dark:hover:border-zinc-600 hover:text-gray-900 dark:hover:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 active:bg-gray-100 dark:active:bg-zinc-700 transition-all duration-200 shadow-sm hover:shadow-md"
                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                {{ $page }}
            </button>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-600 dark:text-zinc-400 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-750 hover:border-gray-300 dark:hover:border-zinc-600 hover:text-gray-900 dark:hover:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 active:bg-gray-100 dark:active:bg-zinc-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            @else
            <span
                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-400 dark:text-zinc-500 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 cursor-not-allowed rounded-xl transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </span>
            @endif
        </div>
    </div>
</nav>
@endif