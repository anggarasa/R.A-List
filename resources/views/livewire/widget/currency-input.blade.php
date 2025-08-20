<div class="w-full" x-data="{
        displayValue: @entangle('value').live,
        rawValue: @entangle('rawValue').live,
        focused: false,
        
        formatCurrency(value) {
            // Parse angka dari string (hapus semua karakter non-digit)
            const numericValue = parseInt(value.toString().replace(/[^0-9]/g, '')) || 0;
            this.rawValue = numericValue;
            
            if (numericValue === 0) {
                return '';
            }
            
            // Format ke Rupiah
            return 'Rp ' + numericValue.toLocaleString('id-ID');
        },
        
        handleInput(event) {
            const inputValue = event.target.value;
            this.displayValue = this.formatCurrency(inputValue);
            
            // Update ke Livewire dengan debounce
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                $wire.updateRawValue(this.rawValue);
            }, 300);
        },
        
        handleFocus() {
            this.focused = true;
        },
        
        handleBlur() {
            this.focused = false;
            // Pastikan format tetap konsisten setelah blur
            this.displayValue = this.formatCurrency(this.rawValue);
        },
        
        clearValue() {
            this.displayValue = '';
            this.rawValue = 0;
            $wire.clear();
        }
     }" x-init="
        // Initialize jika sudah ada nilai
        if (rawValue > 0) {
            displayValue = formatCurrency(rawValue);
        }
        
        // Watch perubahan rawValue dari Livewire
        $watch('rawValue', value => {
            if (!focused) {
                displayValue = formatCurrency(value);
            }
        });
     ">

    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium mb-2 text-zinc-700 dark:text-zinc-300">
        {{ $label }}
        @if($required)
        <span class="text-red-500 dark:text-red-400">*</span>
        @endif
    </label>
    @endif

    <div class="relative">
        <input type="text" id="{{ $id }}" name="{{ $name }}" x-model="displayValue" @input="handleInput($event)"
            @focus="handleFocus()" @blur="handleBlur()" placeholder="{{ $placeholder }}" @if($disabled) disabled @endif
            autocomplete="off" inputmode="numeric"
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
        <button type="button" @click="clearValue()" x-show="displayValue && !{{ $disabled ? 'true' : 'false' }}"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-zinc-600 dark:hover:text-zinc-300 text-zinc-400 dark:text-zinc-500 transition-colors duration-200"
            tabindex="-1">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
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
    <div x-show="rawValue > 0" x-transition>
        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
            Nilai: <span x-text="rawValue.toLocaleString('id-ID')"></span>
        </p>
    </div>

    {{-- Hidden input untuk form submission --}}
    @if($name)
    <input type="hidden" name="{{ $name }}_raw" x-model="rawValue">
    @endif
</div>