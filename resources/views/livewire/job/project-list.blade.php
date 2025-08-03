<div>
    <div class="flex justify-between items-center mb-20">
        <flux:heading size="xl">Management Project</flux:heading>

        <flux:modal.trigger name="add-project">
            <flux:button icon="plus" variant="primary">Add Project</flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Search Section --}}
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search projects by name..."
                    icon="magnifying-glass" clearable />
            </div>

            {{-- Status Filter --}}
            <div class="w-full sm:w-48">
                <flux:select wire:model.live="statusFilter">
                    <flux:select.option value="">All Status</flux:select.option>
                    <flux:select.option value="Planning">Planning</flux:select.option>
                    <flux:select.option value="In Progress">In Progress</flux:select.option>
                    <flux:select.option value="Completed">Completed</flux:select.option>
                    <flux:select.option value="On Hold">On Hold</flux:select.option>
                </flux:select>
            </div>

            {{-- Clear Button --}}
            @if($search || $statusFilter)
            <flux:button wire:click="clearSearch" variant="ghost" icon="x-mark">
                Clear
            </flux:button>
            @endif
        </div>

        {{-- Search Results Info --}}
        @if($search || $statusFilter)
        <div class="text-sm text-zinc-600 dark:text-zinc-400">
            @if($projects->count() > 0)
            Showing {{ $projects->count() }} project(s)
            @if($search)
            for "<strong>{{ $search }}</strong>"
            @endif
            @if($statusFilter)
            with status "<strong>{{ $statusFilter }}</strong>"
            @endif
            @else
            No projects found
            @if($search)
            for "<strong>{{ $search }}</strong>"
            @endif
            @if($statusFilter)
            with status "<strong>{{ $statusFilter }}</strong>"
            @endif
            @endif
        </div>
        @endif
    </div>

    {{-- Projects Grid --}}
    @if($projects->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($projects as $project)
        <div wire:click="detailProject({{ $project }})"
            class="bg-zinc-100 dark:bg-zinc-900 p-4 rounded-2xl shadow hover:shadow-lg transition cursor-pointer">
            <div class="flex justify-between items-center">
                <flux:heading>
                    {{ $project->name }}
                </flux:heading>

                <flux:text>
                    {{ \Carbon\Carbon::now()->diffForHumans(\Carbon\Carbon::parse($project->end_date), true) }}
                    remaining
                </flux:text>
            </div>
            <flux:text class="mt-2">
                {{ Str::limit($project->description, 50, '...') }}
            </flux:text>
            <div class="mt-4 flex justify-between items-center">
                @php
                $statusColor = match($project->status) {
                'In Progress' => 'blue',
                'Completed' => 'green',
                'On Hold' => 'orange',
                'Planning' => 'purple',
                default => 'gray'
                };
                @endphp
                <flux:badge color="{{ $statusColor }}">{{ $project->status }}</flux:badge>
                <a href="{{ route('job.project_detail') }}" wire:navigate
                    class="text-lime-600 dark:text-lime-400 text-sm hover:underline">Lihat Detail →</a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Empty State --}}
    <div class="text-center py-12">
        <div class="mx-auto w-24 h-24 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
            <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <flux:heading size="lg" class="mb-2">No Projects Found</flux:heading>
        <flux:text class="text-zinc-500 dark:text-zinc-400 mb-4">
            @if($search || $statusFilter)
            Try adjusting your search criteria or create a new project.
            @else
            Get started by creating your first project.
            @endif
        </flux:text>
        @if($search || $statusFilter)
        <flux:button wire:click="clearSearch" variant="ghost">
            Clear Search
        </flux:button>
        @else
        <flux:modal.trigger name="add-project">
            <flux:button icon="plus" variant="primary">Add Your First Project</flux:button>
        </flux:modal.trigger>
        @endif
    </div>
    @endif

    {{-- Modal add --}}
    <flux:modal name="add-project" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $projectDetail ? 'Edit Project' : 'Add Project' }}</flux:heading>
                <flux:text class="mt-2">{{ $projectDetail ? 'Change your project' : 'create your project.' }}
                </flux:text>
            </div>

            <form wire:submit="createProject" class="space-y-6">
                {{-- input name --}}
                <flux:input wire:model="nameProject" label="Name" autocomplete="off"
                    placeholder="Enter name project in here..." />

                {{-- input select status --}}
                <flux:select wire:model="statusProject" label="Status">
                    <flux:select.option>Chose status project...</flux:select.option>
                    <flux:select.option value="Planning">Planning</flux:select.option>
                    <flux:select.option value="In Progress">In Progress</flux:select.option>
                    <flux:select.option value="Completed">Completed</flux:select.option>
                    <flux:select.option value="On Hold">On Hold</flux:select.option>
                </flux:select>

                {{-- input start date & end date --}}
                @livewire('widget.date-piker', [
                'mode' => 'range',
                'label' => 'Period',
                'required' => false,
                'minDate' => now()->format('Y-m-d')
                ])

                {{-- deskripsi --}}
                <flux:textarea wire:model="descriptionProject" rows="2" label="Description"
                    placeholder="Enter description in here..." />

                {{-- bottom --}}
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ $projectDetail ? 'Change Project' : 'Create Project'
                        }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- modal detail --}}
    <flux:modal name="detail-project" class="md:w-1/2">
        <div class="space-y-6">
            <div>
                <div class="flex items-center space-x-5">
                    <flux:heading size="lg">{{ $projectDetail->name ?? '' }}</flux:heading>

                    <flux:text>
                        {{ \Carbon\Carbon::now()->diffForHumans(\Carbon\Carbon::parse($project->end_date), true) }}
                        remaining
                    </flux:text>
                </div>
                <flux:text class="mt-2">{{ $projectDetail->description ?? "" }}</flux:text>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:heading>Start Date</flux:heading>
                    <flux:text class="mt-2">
                        {{ \Carbon\Carbon::parse($projectDetail->start_date ?? '')->format('d M Y') }}
                    </flux:text>
                </div>

                <div>
                    <flux:heading>End Date</flux:heading>
                    <flux:text class="mt-2">
                        {{ \Carbon\Carbon::parse($projectDetail->end_date ?? '')->format('d M Y') }}
                    </flux:text>
                </div>

                <div>
                    @php
                    $statusColor = match($projectDetail->status ?? '') {
                    'In Progress' => 'blue',
                    'Completed' => 'green',
                    'On Hold' => 'orange',
                    'Planning' => 'purple',
                    default => 'gray'
                    };
                    @endphp
                    <flux:heading>Status</flux:heading>
                    <flux:badge color="{{ $statusColor }}" class="mt-2">{{ $projectDetail->status ?? '' }}</flux:badge>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <flux:spacer />
                <flux:button icon="trash" variant="danger">Delete</flux:button>
                <flux:button wire:click="setEdit" icon="pencil-square" variant="primary">Edit</flux:button>
            </div>
    </flux:modal>
</div>