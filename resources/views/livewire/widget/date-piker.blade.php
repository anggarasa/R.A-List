{{-- resources/views/livewire/date-picker.blade.php --}}
<div class="w-full {{ $containerClass }}">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif

    @if($mode === 'single')
    {{-- Single Date Input --}}
    <div class="relative">
        <input type="date" wire:model.live="singleDate"
            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg shadow-sm focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition-colors duration-200 {{ $disabled ? 'bg-gray-100 dark:bg-zinc-800 cursor-not-allowed' : 'bg-white dark:bg-zinc-900' }} {{ $inputClass }} text-gray-900 dark:text-gray-100"
            placeholder="{{ $placeholder }}" @if($required) required @endif @if($disabled) disabled @endif @if($minDate)
            min="{{ $minDate }}" @endif @if($maxDate) max="{{ $maxDate }}" @endif />
    </div>
    @else
    {{-- Range Date Input --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Start Date --}}
        <div class="relative">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Start Date</label>
            <input type="date" wire:model.live="startDate"
                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg shadow-sm focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition-colors duration-200 {{ $disabled ? 'bg-gray-100 dark:bg-zinc-800 cursor-not-allowed' : 'bg-white dark:bg-zinc-900' }} {{ $inputClass }} text-gray-900 dark:text-gray-100"
                placeholder="Select start date" @if($required) required @endif @if($disabled) disabled @endif
                @if($minDate) min="{{ $minDate }}" @endif @if($maxDate) max="{{ $maxDate }}" @endif />
        </div>

        {{-- End Date --}}
        <div class="relative">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">End Date</label>
            <input type="date" wire:model.live="endDate"
                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg shadow-sm focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition-colors duration-200 {{ $disabled ? 'bg-gray-100 dark:bg-zinc-800 cursor-not-allowed' : 'bg-white dark:bg-zinc-900' }} {{ $startDate ? '' : 'opacity-50' }} {{ $inputClass }} text-gray-900 dark:text-gray-100"
                placeholder="Select end date" @if($required) required @endif @if($disabled || !$startDate) disabled
                @endif min="{{ $this->getMinDateForEndDate() }}" @if($maxDate) max="{{ $maxDate }}" @endif />
            @if(!$startDate)
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Select the start date first</p>
            @endif
        </div>
    </div>

    {{-- Date Range Display --}}
    @if($startDate && $endDate)
    <div class="mt-3 p-3 bg-blue-50 dark:bg-zinc-800 border border-blue-200 dark:border-zinc-700 rounded-lg">
        <div class="flex items-center text-sm text-blue-800 dark:text-blue-300">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{
            \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            ({{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} day)
        </div>
    </div>
    @endif
    @endif

    {{-- Error Messages --}}
    @error('singleDate')
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
    @error('startDate')
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
    @error('endDate')
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>