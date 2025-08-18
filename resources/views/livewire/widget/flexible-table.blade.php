<div>
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
        {{-- Header with Search, Filters and Per Page --}}
        <div class="p-4 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex flex-col gap-4">
                {{-- Search and Per Page Row --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    {{-- Search --}}
                    @if($showSearch)
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari data..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md leading-5 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-zinc-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-lime-500 focus:border-lime-500 sm:text-sm" />
                    </div>
                    @endif

                    {{-- Per Page Selector --}}
                    @if($showPerPage)
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-700 dark:text-zinc-300">Tampilkan:</label>
                        <select wire:model.live="perPage"
                            class="border border-gray-300 dark:border-zinc-600 rounded-md px-3 py-1 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    @endif
                </div>

                {{-- Filters Row --}}
                @if($showFilters && (!empty($filters) || !empty($dateFilters)))
                <div class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                    <div class="flex flex-col gap-4">
                        {{-- Filter Inputs and Reset Button Row --}}
                        <div class="flex flex-col gap-4">
                            {{-- All Filter Inputs Container --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                {{-- Select Filters --}}
                                @foreach($filters as $key => $filter)
                                <div class="w-full">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-zinc-300 mb-1">
                                        {{ $filter['label'] ?? ucfirst($key) }}
                                    </label>
                                    <select wire:model.live="selectedFilters.{{ $key }}"
                                        class="w-full border border-gray-300 dark:border-zinc-600 rounded-md px-3 py-2 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500">
                                        <option value="">Semua {{ $filter['label'] ?? ucfirst($key) }}</option>
                                        @foreach($this->getFilterOptions($key) as $option)
                                        <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endforeach

                                {{-- Date Filters --}}
                                @foreach($dateFilters as $key => $dateFilter)
                                <div class="w-full sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-zinc-300 mb-1">
                                        {{ $dateFilter['label'] ?? ucfirst($key) }}
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="date" wire:model.live="dateFilterValues.{{ $key }}.from"
                                            placeholder="Dari"
                                            class="w-full border border-gray-300 dark:border-zinc-600 rounded-md px-3 py-2 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500">
                                        <input type="date" wire:model.live="dateFilterValues.{{ $key }}.to"
                                            placeholder="Sampai"
                                            class="w-full border border-gray-300 dark:border-zinc-600 rounded-md px-3 py-2 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500">
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Clear Filters Button - Below filter inputs --}}
                            <div class="flex justify-start">
                                <button wire:click="clearFilters"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-zinc-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-600 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reset Filter
                                </button>
                            </div>
                        </div>

                        {{-- Active Filters Display --}}
                        <div class="flex flex-wrap gap-2">
                            {{-- Selected Filter Tags --}}
                            @foreach($selectedFilters as $field => $value)
                            @if(!empty($value))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-200">
                                {{ isset($filters[$field]['label']) ? $filters[$field]['label'] : ucfirst($field) }}: {{
                                ucfirst($value) }}
                                <button wire:click="$set('selectedFilters.{{ $field }}', '')"
                                    class="ml-1.5 -mr-1 h-4 w-4 rounded-full inline-flex items-center justify-center text-lime-600 hover:bg-lime-200 hover:text-lime-900 dark:text-lime-300 dark:hover:bg-lime-800">
                                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"></path>
                                    </svg>
                                </button>
                            </span>
                            @endif
                            @endforeach

                            {{-- Date Filter Tags --}}
                            @foreach($dateFilterValues as $field => $dateRange)
                            @if(!empty($dateRange['from']) || !empty($dateRange['to']))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ isset($dateFilters[$field]['label']) ? $dateFilters[$field]['label'] :
                                ucfirst($field)
                                }}:
                                @if(!empty($dateRange['from']) && !empty($dateRange['to']))
                                {{ \Carbon\Carbon::parse($dateRange['from'])->format('d M Y') }} - {{
                                \Carbon\Carbon::parse($dateRange['to'])->format('d M Y') }}
                                @elseif(!empty($dateRange['from']))
                                Dari {{ \Carbon\Carbon::parse($dateRange['from'])->format('d M Y') }}
                                @else
                                Sampai {{ \Carbon\Carbon::parse($dateRange['to'])->format('d M Y') }}
                                @endif
                                <button wire:click="$set('dateFilterValues.{{ $field }}', ['from' => '', 'to' => ''])"
                                    class="ml-1.5 -mr-1 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-600 hover:bg-blue-200 hover:text-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"></path>
                                    </svg>
                                </button>
                            </span>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        @foreach($columns as $key => $column)
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            @if(in_array($key, $sortable))
                            <button wire:click="sortByTable('{{ $key }}')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-zinc-200">
                                <span>{{ $column['label'] ?? ucfirst($key) }}</span>
                                @if($sortBy === $key)
                                @if($sortDirection === 'asc')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                </svg>
                                @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                                @endif
                                @else
                                <svg class="w-4 h-4 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                                @endif
                            </button>
                            @else
                            {{ $column['label'] ?? ucfirst($key) }}
                            @endif
                        </th>
                        @endforeach

                        @if(!empty($actions))
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Action
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($data as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors duration-150">
                        @foreach($columns as $key => $column)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            @if(isset($column['format']) && $column['format'] === 'currency')
                            Rp {{ number_format($item->$key, 0, ',', '.') }}
                            @elseif(isset($column['format']) && $column['format'] === 'date')
                            {{ $item->$key ? \Carbon\Carbon::parse($item->$key)->format('d M Y') : '-' }}
                            @elseif(isset($column['format']) && $column['format'] === 'datetime')
                            {{ $item->$key ? \Carbon\Carbon::parse($item->$key)->format('d M Y H:i') : '-' }}
                            @elseif(isset($column['format']) && $column['format'] === 'badge')
                            @php
                            $badgeColor = 'gray'; // default color
                            $badgeClasses = 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300'; // default

                            // Check if custom badge colors are defined
                            if (isset($column['badge_colors']) && isset($column['badge_colors'][$item->$key])) {
                            $color = $column['badge_colors'][$item->$key];
                            $badgeClasses = "bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900
                            dark:text-{$color}-200";
                            }
                            // Legacy support for old badge format
                            elseif ($item->$key === 'income') {
                            $badgeClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                            }
                            elseif ($item->$key === 'expense') {
                            $badgeClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                            }
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                {{ isset($column['badge_labels'][$item->$key]) ? $column['badge_labels'][$item->$key] :
                                ucfirst($item->$key) }}
                            </span>
                            @elseif(isset($column['relation']))
                            {{ data_get($item, $column['relation']) }}
                            @else
                            {{ $item->$key }}
                            @endif
                        </td>
                        @endforeach

                        @if(!empty($actions))
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                @foreach($actions as $action)
                                <button @if($action['method']==='confirmDelete' )
                                    wire:click="{{ $action['method'] }}({{ $item->id }}, '{{ $action['confirm'] }}')"
                                    @else wire:click="{{ $action['method'] }}({{ $item->id }})" @endif class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md 
    {{ $action['class'] ?? 'text-lime-600 hover:text-lime-900 dark:text-lime-400 dark:hover:text-lime-300' }} 
    transition-colors duration-150">

                                    @if(isset($action['icon']))
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $action['icon'] !!}
                                    </svg>
                                    @endif

                                    {{ $action['label'] }}
                                </button>

                                @endforeach
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($columns) + (!empty($actions) ? 1 : 0) }}"
                            class="px-6 py-12 text-center text-sm text-gray-500 dark:text-zinc-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-gray-400 dark:text-zinc-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Tidak ada data yang ditemukan</p>
                                @if($search || !empty(array_filter($selectedFilters)) ||
                                !empty(array_filter($dateFilterValues, function($date) { return !empty($date['from']) ||
                                !empty($date['to']); })))
                                <p class="mt-1 text-xs">Coba ubah kata kunci pencarian atau filter</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($showPagination && $data->hasPages())
        <div class="px-4 py-3 bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-700">
            {{ $data->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>