<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <flux:heading size="xl" class="text-zinc-900 dark:text-white font-bold">
                        Project Management
                    </flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400 mt-1">
                        Manage and track your projects efficiently
                    </flux:text>
                </div>

                <flux:modal.trigger name="add-project">
                    <flux:button icon="plus" variant="primary"
                        class="shadow-lg hover:shadow-xl transition-all duration-200">
                        <span class="hidden sm:inline">New Project</span>
                        <span class="sm:hidden">New</span>
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 p-6 mb-8 transition-colors duration-300">
            <div class="flex flex-col lg:flex-row gap-4">
                {{-- Search Input --}}
                <div class="flex-1">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search projects by name..."
                        icon="magnifying-glass" clearable />
                </div>

                {{-- Status Filter --}}
                <div class="w-full lg:w-64">
                    <flux:select wire:model.live="statusFilter">
                        <flux:select.option value="">All Status</flux:select.option>
                        <flux:select.option value="Planning">📋 Planning</flux:select.option>
                        <flux:select.option value="In Progress">🚀 In Progress</flux:select.option>
                        <flux:select.option value="Completed">✅ Completed</flux:select.option>
                        <flux:select.option value="On Hold">⏸️ On Hold</flux:select.option>
                    </flux:select>
                </div>

                {{-- Clear Button --}}
                @if($search || $statusFilter)
                <flux:button wire:click="clearSearch" variant="ghost" icon="x-mark" class="shrink-0">
                    Clear
                </flux:button>
                @endif
            </div>

            {{-- Search Results Info --}}
            @if($search || $statusFilter)
            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    @if($projects->count() > 0)
                    <span class="inline-flex items-center gap-1">
                        <flux:icon name="check" class="text-green-500 size-4" />
                        Found {{ $projects->count() }} project{{ $projects->count() > 1 ? 's' : '' }}
                    </span>
                    @if($search)
                    for "<strong class="text-zinc-900 dark:text-zinc-100">{{ $search }}</strong>"
                    @endif
                    @if($statusFilter)
                    with status "<strong class="text-zinc-900 dark:text-zinc-100">{{ $statusFilter }}</strong>"
                    @endif
                    @else
                    <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400">
                        <flux:icon name="exclamation-triangle" variant="solid"
                            class="size-4 text-amber-600 dark:text-amber-400" />
                        No projects found
                    </span>
                    @if($search)
                    for "<strong class="text-zinc-900 dark:text-zinc-100">{{ $search }}</strong>"
                    @endif
                    @if($statusFilter)
                    with status "<strong class="text-zinc-900 dark:text-zinc-100">{{ $statusFilter }}</strong>"
                    @endif
                    @endif
                </flux:text>
            </div>
            @endif
        </div>

        {{-- Projects Grid --}}
        @if($projects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($projects as $project)
            <div wire:click="detailProject({{ $project }})"
                class="group bg-white dark:bg-zinc-900 rounded-2xl shadow-sm hover:shadow-lg border border-zinc-200 dark:border-zinc-800 p-6 cursor-pointer transition-all duration-300 hover:scale-[1.02] hover:border-lime-300 dark:hover:border-lime-600">
                {{-- Project Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <flux:heading
                            class="text-zinc-900 dark:text-white font-semibold truncate group-hover:text-lime-600 dark:group-hover:text-lime-400 transition-colors">
                            {{ $project->name ?? 'Untitled Project' }}
                        </flux:heading>

                        {{-- Time Remaining --}}
                        @php
                        $endDate = \Carbon\Carbon::parse($project->end_date ?? now());
                        $now = \Carbon\Carbon::now();
                        $isOverdue = $endDate->isPast();
                        $timeRemaining = $now->diffForHumans($endDate, true);
                        @endphp

                        <div class="flex items-center gap-1 mt-1">
                            <svg class="w-4 h-4 {{ $isOverdue ? 'text-red-500' : 'text-zinc-400' }}" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <flux:text
                                class="text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-medium' : 'text-zinc-600 dark:text-zinc-400' }}">
                                {{ $timeRemaining }} {{ $isOverdue ? 'overdue' : 'remaining' }}
                            </flux:text>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    @php
                    $statusConfig = match($project->status ?? '') {
                    'In Progress' => ['color' => 'blue', 'icon' => '🚀', 'class' => 'bg-blue-50 text-blue-700
                    border-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-800'],
                    'Completed' => ['color' => 'green', 'icon' => '✅', 'class' => 'bg-green-50 text-green-700
                    border-green-200 dark:bg-green-900/50 dark:text-green-300 dark:border-green-800'],
                    'On Hold' => ['color' => 'orange', 'icon' => '⏸️', 'class' => 'bg-amber-50 text-amber-700
                    border-amber-200 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-800'],
                    'Planning' => ['color' => 'purple', 'icon' => '📋', 'class' => 'bg-purple-50 text-purple-700
                    border-purple-200 dark:bg-purple-900/50 dark:text-purple-300 dark:border-purple-800'],
                    default => ['color' => 'gray', 'icon' => '📄', 'class' => 'bg-zinc-50 text-zinc-700 border-zinc-200
                    dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700']
                    };
                    @endphp

                    <div
                        class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusConfig['class'] }} transition-colors">
                        {{ $statusConfig['icon'] }} {{ $project->status ?? 'Draft' }}
                    </div>
                </div>

                {{-- Project Description --}}
                <flux:text class="text-zinc-600 dark:text-zinc-400 text-sm mb-4 line-clamp-2">
                    {{ Str::limit($project->description ?? 'No description provided.', 100, '...') }}
                </flux:text>

                {{-- Project Footer --}}
                <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{-- Date Range --}}
                    <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-500">
                        <flux:icon name="calendar-date-range" class="size-3" />
                        {{ \Carbon\Carbon::parse($project->start_date ?? now())->format('M j') }} -
                        {{ \Carbon\Carbon::parse($project->end_date ?? now())->format('M j, Y') }}
                    </div>

                    {{-- View Details Link --}}
                    <a href="{{ route('job.project_detail', $project->id ?? '') }}" wire:navigate @click.stop
                        class="inline-flex items-center gap-1 text-xs font-medium text-lime-600 dark:text-lime-400 hover:text-lime-800 dark:hover:text-lime-300 transition-colors group">
                        View Details
                        {{-- <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg> --}}
                        <flux:icon name="chevron-right"
                            class="size-3 transition-transform group-hover:translate-x-0.5" />
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        {{-- Enhanced Empty State --}}
        <div class="text-center py-16">
            <div
                class="mx-auto w-32 h-32 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-zinc-800 dark:to-zinc-700 rounded-3xl flex items-center justify-center mb-6 shadow-sm">
                <flux:icon name="rectangle-stack" class="size-16 text-lime-500 dark:text-lime-400" />
            </div>

            <flux:heading size="xl" class="mb-3 text-zinc-900 dark:text-white">
                @if($search || $statusFilter)
                No Projects Match Your Search
                @else
                Ready to Start Your First Project?
                @endif
            </flux:heading>

            <flux:text class="text-zinc-600 dark:text-zinc-400 mb-8 max-w-md mx-auto">
                @if($search || $statusFilter)
                Try adjusting your search criteria or create a new project to get started.
                @else
                Organize your work and boost productivity by creating your first project. It only takes a minute!
                @endif
            </flux:text>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @if($search || $statusFilter)
                <flux:button wire:click="clearSearch" variant="ghost" icon="arrow-path"
                    class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    Clear Search
                </flux:button>
                @endif

                <flux:modal.trigger name="add-project">
                    <flux:button icon="plus" variant="primary"
                        class="shadow-lg hover:shadow-xl transition-all duration-200">
                        @if($search || $statusFilter)
                        Create New Project
                        @else
                        Create Your First Project
                        @endif
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
        @endif
    </div>

    {{-- Enhanced Add/Edit Project Modal --}}
    <flux:modal name="add-project" variant="flyout" @close="clearForm">
        <div class="space-y-8">
            {{-- Modal Header --}}
            <div class="pb-6 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="xl" class="text-zinc-900 dark:text-white font-bold">
                    {{ $projectDetail ? '✏️ Edit Project' : '✨ Create New Project' }}
                </flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2">
                    {{ $projectDetail ? 'Update your project details below.' : 'Fill in the details to create your new
                    project.' }}
                </flux:text>
            </div>

            {{-- Form --}}
            <form wire:submit="createProject" class="space-y-6">
                {{-- Project Name --}}
                <div>
                    <flux:input wire:model="nameProject" label="Project Name" autocomplete="off"
                        placeholder="e.g., Website Redesign, Mobile App Development..." />
                </div>

                {{-- Status Selection --}}
                <div>
                    <flux:select wire:model="statusProject" label="Project Status">
                        <flux:select.option>Choose status...</flux:select.option>
                        <flux:select.option value="Planning">📋 Planning</flux:select.option>
                        <flux:select.option value="In Progress">🚀 In Progress</flux:select.option>
                        <flux:select.option value="Completed">✅ Completed</flux:select.option>
                        <flux:select.option value="On Hold">⏸️ On Hold</flux:select.option>
                    </flux:select>
                </div>

                {{-- Date Range --}}
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-4">
                    <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">
                        📅 Project Timeline
                    </flux:text>
                    @livewire('widget.date-piker', [
                    'mode' => 'range',
                    'label' => '',
                    'required' => false,
                    'minDate' => '2025-01-01'
                    ])
                </div>

                {{-- Description --}}
                <div>
                    <flux:textarea wire:model="descriptionProject" rows="4" label="Description"
                        placeholder="Describe the project goals, scope, and key deliverables..." />
                </div>

                {{-- Form Actions --}}
                <div class="flex gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:spacer />
                    <flux:button type="button" variant="ghost" wire:click="clearForm"
                        x-on:click="$flux.modal('add-project').close()"
                        class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="{{ $projectDetail ? 'check' : 'plus' }}"
                        class="shadow-lg hover:shadow-xl transition-all duration-200">
                        {{ $projectDetail ? 'Update Project' : 'Create Project' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Enhanced Project Detail Modal --}}
    <flux:modal name="detail-project" class="md:w-3/4 lg:w-2/3" @close="clearDetail">
        <div class="space-y-8">
            {{-- Project Header --}}
            <div class="pb-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <flux:heading size="xl" class="text-zinc-900 dark:text-white font-bold truncate">
                            {{ $projectDetail->name ?? 'Project Details' }}
                        </flux:heading>

                        @php
                        $endDate = \Carbon\Carbon::parse($projectDetail->end_date ?? now());
                        $now = \Carbon\Carbon::now();
                        $isOverdue = $endDate->isPast();
                        $timeRemaining = $now->diffForHumans($endDate, true);
                        @endphp

                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-4 h-4 {{ $isOverdue ? 'text-red-500' : 'text-zinc-400' }}" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <flux:text
                                class="text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-medium' : 'text-zinc-600 dark:text-zinc-400' }}">
                                {{ $timeRemaining }} {{ $isOverdue ? 'overdue' : 'remaining' }}
                            </flux:text>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    @php
                    $statusConfig = match($projectDetail->status ?? '') {
                    'In Progress' => ['color' => 'blue', 'icon' => '🚀', 'class' => 'bg-blue-50 text-blue-700
                    border-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-800'],
                    'Completed' => ['color' => 'green', 'icon' => '✅', 'class' => 'bg-green-50 text-green-700
                    border-green-200 dark:bg-green-900/50 dark:text-green-300 dark:border-green-800'],
                    'On Hold' => ['color' => 'orange', 'icon' => '⏸️', 'class' => 'bg-amber-50 text-amber-700
                    border-amber-200 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-800'],
                    'Planning' => ['color' => 'purple', 'icon' => '📋', 'class' => 'bg-purple-50 text-purple-700
                    border-purple-200 dark:bg-purple-900/50 dark:text-purple-300 dark:border-purple-800'],
                    default => ['color' => 'gray', 'icon' => '📄', 'class' => 'bg-zinc-50 text-zinc-700 border-zinc-200
                    dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700']
                    };
                    @endphp

                    <div
                        class="px-4 py-2 rounded-full text-sm font-medium border {{ $statusConfig['class'] }} transition-colors">
                        {{ $statusConfig['icon'] }} {{ $projectDetail->status ?? 'Draft' }}
                    </div>
                </div>

                {{-- Description --}}
                <flux:text class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    {{ $projectDetail->description ?? "No description provided for this project." }}
                </flux:text>
            </div>

            {{-- Project Timeline --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Start Date --}}
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-6 transition-colors">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <flux:heading class="text-zinc-900 dark:text-white font-semibold">Start Date</flux:heading>
                    </div>
                    <flux:text class="text-zinc-600 dark:text-zinc-400 text-lg font-medium">
                        {{ \Carbon\Carbon::parse($projectDetail->start_date ?? now())->format('F j, Y') }}
                    </flux:text>
                    <flux:text class="text-zinc-500 dark:text-zinc-500 text-sm mt-1">
                        {{ \Carbon\Carbon::parse($projectDetail->start_date ?? now())->format('l') }}
                    </flux:text>
                </div>

                {{-- End Date --}}
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-6 transition-colors">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <flux:heading class="text-zinc-900 dark:text-white font-semibold">End Date</flux:heading>
                    </div>
                    <flux:text class="text-zinc-600 dark:text-zinc-400 text-lg font-medium">
                        {{ \Carbon\Carbon::parse($projectDetail->end_date ?? now())->format('F j, Y') }}
                    </flux:text>
                    <flux:text class="text-zinc-500 dark:text-zinc-500 text-sm mt-1">
                        {{ \Carbon\Carbon::parse($projectDetail->end_date ?? now())->format('l') }}
                    </flux:text>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:spacer />

                <flux:button wire:click="deleteProject" icon="trash" variant="danger"
                    wire:confirm="Are you sure you want to delete this project? This action cannot be undone."
                    class="hover:bg-red-600 transition-colors">
                    Delete Project
                </flux:button>

                <flux:button wire:click="setEdit" icon="pencil-square" variant="primary"
                    class="shadow-lg hover:shadow-xl transition-all duration-200">
                    Edit Project
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>