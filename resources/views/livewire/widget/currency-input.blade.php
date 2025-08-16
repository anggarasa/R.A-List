<div class="w-full">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium mb-2 text-zinc-700 dark:text-zinc-300">
        {{ $label }}
        @if($required)
        <span class="text-red-500 dark:text-red-400">*</span>
        @endif
    </label>
    @endif

    <div class="relative">
        {{-- Currency Icon --}}
        {{-- <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-zinc-400 dark:text-zinc-500" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
            </svg>
        </div> --}}

        <input type="text" id="{{ $id }}" name="{{ $name }}" wire:model.live="value" placeholder="{{ $placeholder }}"
            @if($disabled) disabled @endif
            @class([ 'block w-full pl-3 pr-12 border rounded-lg shadow-sm transition-all duration-200 focus:ring-2 focus:ring-offset-0 focus:outline-none'
            , 'py-2 text-sm'=> $size === 'sm',
        'py-2.5 text-base' => $size === 'md',
        'py-3 text-lg' => $size === 'lg',
        // Normal state
        'border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100
        placeholder-zinc-500 dark:placeholder-zinc-400' => !$error && !$disabled,
        'focus:border-lime-500 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400' => !$error &&
        !$disabled,
        // Error state
        'border-red-300 dark:border-red-600 bg-red-50 dark:bg-red-950/20 text-red-900 dark:text-red-100
        placeholder-red-400 dark:placeholder-red-500' => $error && !$disabled,
        'focus:border-red-500 dark:focus:border-red-400 focus:ring-red-500 dark:focus:ring-red-400' => $error &&
        !$disabled,
        // Disabled state
        'bg-zinc-100 dark:bg-zinc-500 text-zinc-500 dark:text-zinc-100 border-zinc-200 dark:border-zinc-600
        cursor-not-allowed' => $disabled,
        ])
        >

        {{-- Clear Button --}}
        @if($value && !$disabled)
        <button type="button" wire:click="clear"
            class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-zinc-600 dark:hover:text-zinc-300 text-zinc-400 dark:text-zinc-500 transition-colors duration-200"
            tabindex="-1">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        @endif
    </div>

    {{-- Error Message --}}
    @if($error)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
        <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
        </svg>
        {{ $error }}
    </p>
    @endif

    {{-- Helper Text --}}
    @if($rawValue > 0)
    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
        Nilai: {{ number_format($rawValue, 0, ',', '.') }}
    </p>
    @endif

    {{-- Hidden input untuk form submission --}}
    @if($name)
    <input type="hidden" name="{{ $name }}_raw" value="{{ $rawValue }}">
    @endif
</div>