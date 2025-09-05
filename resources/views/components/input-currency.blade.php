@props([
'name' => 'amount',
'id' => null,
'placeholder' => 'Masukkan jumlah',
'disabled' => false,
'required' => false,
'min' => 0,
'max' => null,
'class' => '',
'label' => null,
'error' => null,
'helper' => null,
// UI options
'size' => 'md', // sm | md | lg
'prefix' => 'Rp',
'showPrefix' => true,
])

@php
$inputId = $id ?? $name;
@endphp

<div class="w-full">
    @if($label)
    <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif

    <div x-data="{
            // Keep Livewire binding intact (modifiers like .live are preserved)
            rawValue: @entangle($attributes->wire('model')),
            displayValue: '',
            minValue: @js($min),
            maxValue: @js($max),

            init() {
                this.updateDisplay();
                this.$watch('rawValue', () => this.updateDisplay());
            },

            formatCurrency(value) {
                if (value === null || value === undefined || value === '') return '';

                const sanitized = value.toString().replace(/[^\d]/g, '');
                const number = sanitized ? parseInt(sanitized) : 0;
                if (isNaN(number)) return '';

                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            },

            updateDisplay() {
                this.displayValue = this.formatCurrency(this.rawValue);
            },

            handleInput(event) {
                const input = event.target.value;

                const numbers = input.replace(/[^\d]/g, '');
                const numericValue = numbers ? parseInt(numbers) : null;

                this.rawValue = numericValue;
                this.displayValue = this.formatCurrency(numericValue);

                this.$nextTick(() => {
                    const cursorPos = this.displayValue.length;
                    event.target.setSelectionRange(cursorPos, cursorPos);
                });
            },

            handleFocus(event) {
                setTimeout(() => event.target.select(), 0);
            },

            handleBlur() {
                // clamp to min/max if provided
                if (this.rawValue !== null && this.rawValue !== '' && !isNaN(parseInt(this.rawValue))) {
                    let value = parseInt(this.rawValue);
                    if (this.minValue !== null && this.minValue !== undefined && !isNaN(parseInt(this.minValue))) {
                        value = Math.max(value, parseInt(this.minValue));
                    }
                    if (this.maxValue !== null && this.maxValue !== undefined && !isNaN(parseInt(this.maxValue))) {
                        value = Math.min(value, parseInt(this.maxValue));
                    }
                    this.rawValue = value;
                }
                this.updateDisplay();
            }
        }" class="relative">
        <div class="relative">
            @php
            $sizePaddingLeft = match($size) {
            'sm' => $showPrefix ? 'pl-9' : 'pl-3',
            'lg' => $showPrefix ? 'pl-14' : 'pl-4',
            default => $showPrefix ? 'pl-12' : 'pl-3',
            };

            $sizePaddingRight = match($size) {
            'sm' => 'pr-9',
            'lg' => 'pr-14',
            default => 'pr-12',
            };

            $sizeHeight = match($size) {
            'sm' => 'h-9 text-sm',
            'lg' => 'h-12 text-base',
            default => 'h-11 text-sm',
            };

            $iconSize = match($size) {
            'sm' => 'w-4 h-4',
            'lg' => 'w-5 h-5',
            default => 'w-5 h-5',
            };

            $prefixTextSize = match($size) {
            'sm' => 'text-xs',
            'lg' => 'text-sm',
            default => 'text-sm',
            };
            @endphp

            @if($showPrefix)
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none select-none">
                <span class="{{ $prefixTextSize }} text-zinc-500 dark:text-zinc-400">{{ $prefix }}</span>
            </div>
            @endif

            <input type="text" name="{{ $name }}" id="{{ $inputId }}" x-model="displayValue"
                @input="handleInput($event)" @focus="handleFocus($event)" @blur="handleBlur()"
                placeholder="{{ $placeholder }}" {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }}
                autocomplete="off" inputmode="numeric" pattern="[0-9]*" aria-invalid="{{ $error ? 'true' : 'false' }}"
                @if($error) aria-describedby="{{ $inputId }}-error" @elseif($helper)
                aria-describedby="{{ $inputId }}-helper" @endif
                class="block w-full {{ $sizePaddingLeft }} {{ $sizePaddingRight }} {{ $sizeHeight }} border rounded-lg shadow-sm transition-all duration-200 focus:ring-2 focus:ring-accent focus:ring-offset-0 focus:outline-none border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 placeholder-zinc-500 dark:placeholder-zinc-400 {{ $disabled ? 'bg-gray-50 cursor-not-allowed' : 'bg-white' }} {{ $error ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }} {{ $class }}">

            <!-- Currency Icon -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="{{ $iconSize }} text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Livewire is directly entangled with rawValue; hidden input is unnecessary -->
    </div>

    <!-- Helper Text -->
    @if($helper && !$error)
    <p id="{{ $inputId }}-helper" class="mt-2 text-sm text-gray-600">{{ $helper }}</p>
    @endif

    <!-- Error Message -->
    @if($error)
    <p id="{{ $inputId }}-error" class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif

    <!-- Display Raw Value (for debugging, remove in production) -->
    <div x-show="false" class="mt-2 text-xs text-gray-500">
        Raw Value: <span x-text="rawValue"></span>
    </div>
</div>