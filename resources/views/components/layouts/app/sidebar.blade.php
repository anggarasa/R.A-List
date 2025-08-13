<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    {{-- Notification Popup --}}
    <livewire:notification.popup />

    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                {{-- dashboard --}}
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                {{-- job list --}}
                <flux:navlist.item class="mt-3" icon="list-bullet" :href="route('job.project_list')"
                    :current="request()->routeIs('job.project_list')" wire:navigate>{{ __('Job List') }}
                </flux:navlist.item>

                {{-- financial dropdown --}}
                <div class="mt-3" x-data="{ 
                        open: {{ request()->routeIs('financial.*') ? 'true' : 'false' }},
                        toggle() {
                            this.open = !this.open;
                        },
                        openDropdown() {
                            this.open = true;
                        },
                        init() {
                            // Auto-open if we're on a financial route
                            if ({{ request()->routeIs('financial.*') ? 'true' : 'false' }}) {
                                this.open = true;
                            }
                        }
                     }" x-init="init()" @financial-page-loaded.window="openDropdown()">

                    {{-- Main Financial Item --}}
                    <div class="relative">
                        <a href="{{ route('financial.dashboard') }}" wire:navigate
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 transition-colors duration-200 group
                                {{ request()->routeIs('financial.*') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100' : '' }}">
                            <div class="flex items-center gap-3">
                                <flux:icon name="banknotes" class="h-5 w-5 shrink-0" />
                                <span>{{ __('Financial Report') }}</span>
                            </div>
                            <flux:icon.chevron-down class="h-4 w-4 transition-transform duration-200" />
                        </a>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-8 space-y-1">

                            {{-- Dashboard Sub-item --}}
                            <a href="{{ route('financial.dashboard') }}" wire:navigate
                                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 transition-colors duration-200
                               {{ request()->routeIs('financial.dashboard') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100 border-l-2 border-lime-500' : '' }}">
                                <flux:icon name="chart-bar" class="h-4 w-4 shrink-0" />
                                <span>{{ __('Dashboard') }}</span>
                            </a>

                            {{-- Categories Sub-item --}}
                            <a href="{{ route('financial.category') }}" wire:navigate
                                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 transition-colors duration-200
                               {{ request()->routeIs('financial.category') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100 border-l-2 border-lime-500' : '' }}">
                                <flux:icon name="tag" class="h-4 w-4 shrink-0" />
                                <span>{{ __('Categories') }}</span>
                            </a>

                            {{-- Transactions Sub-item (example) --}}
                            <a href="#"
                                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 transition-colors duration-200">
                                <flux:icon name="clipboard-document-list" class="h-4 w-4 shrink-0" />
                                <span>{{ __('Transactions') }}</span>
                            </a>

                            {{-- Budget Sub-item (example) --}}
                            <a href="#"
                                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 transition-colors duration-200">
                                <flux:icon name="calculator" class="h-4 w-4 shrink-0" />
                                <span>{{ __('Budget') }}</span>
                            </a>

                            {{-- Reports Sub-item (example) --}}
                            <a href="#"
                                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 transition-colors duration-200">
                                <flux:icon name="document-text" class="h-4 w-4 shrink-0" />
                                <span>{{ __('Reports') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>