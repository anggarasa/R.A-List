<div class="max-w-5xl mx-auto p-4">
    <div class="flex items-center space-x-5">
        <a href="{{ route('job.project_list') }}" wire:navigate>
            <flux:icon.arrow-left />
        </a>
        <flux:heading size="xl">Detail: {{ $project->name }}</flux:heading>
    </div>

    <!-- Dynamic Add Button based on active tab -->
    @if($activeTab === 'tasks')
    <flux:modal.trigger name="add-task">
        <flux:button icon="plus" variant="primary" class="mt-5">Add Task</flux:button>
    </flux:modal.trigger>
    @else
    <flux:modal.trigger name="add-note">
        <flux:button icon="plus" variant="primary" class="mt-5">Add Notes</flux:button>
    </flux:modal.trigger>
    @endif

    <!-- Tabs -->
    <div class="mt-6 flex space-x-4 border-b border-zinc-300 dark:border-zinc-700">
        <button wire:click="setActiveTab('tasks')"
            class="pb-2 {{ $activeTab === 'tasks' ? 'border-b-2 border-lime-500 text-lime-400' : 'hover:text-lime-400' }}">
            Tasks
        </button>
        <button wire:click="setActiveTab('notes')"
            class="pb-2 {{ $activeTab === 'notes' ? 'border-b-2 border-lime-500 text-lime-400' : 'hover:text-lime-400' }}">
            Notes
        </button>
    </div>

    <!-- Tab Content -->
    @if($activeTab === 'tasks')
    <!-- Task Search and Filters -->
    <div class="mt-6 space-y-4">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="searchTask"
                    placeholder="Search tasks by title or description..." icon="magnifying-glass" />
            </div>

            <!-- Filter Toggles -->
            <div class="flex gap-2">
                <flux:button wire:click="toggleFilters" variant="{{ $showFilters ? 'primary' : 'ghost' }}"
                    icon="adjustments-horizontal" size="sm">
                    Filters
                </flux:button>

                @if($this->hasActiveFilters())
                <flux:button wire:click="clearAllFilters" variant="ghost" icon="x-mark" size="sm">
                    Clear
                </flux:button>
                @endif
            </div>
        </div>

        <!-- Filter Panel -->
        @if($showFilters)
        <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Category Filter -->
                <div>
                    <flux:select wire:model.live="filterCategory" label="Category">
                        <flux:select.option value="">All Categories</flux:select.option>
                        <flux:select.option value="Slicing">🎨 Slicing</flux:select.option>
                        <flux:select.option value="Integration API">🔗 Integration API</flux:select.option>
                        <flux:select.option value="Clean Code">🧹 Clean Code</flux:select.option>
                    </flux:select>
                </div>

                <!-- Status Filter -->
                <div>
                    <flux:select wire:model.live="filterStatus" label="Status">
                        <flux:select.option value="">All Status</flux:select.option>
                        <flux:select.option value="Todo">Todo</flux:select.option>
                        <flux:select.option value="In Progress">In Progress</flux:select.option>
                        <flux:select.option value="Done">Done</flux:select.option>
                        <flux:select.option value="Error">Error</flux:select.option>
                        <flux:select.option value="Revisi">Revisi</flux:select.option>
                    </flux:select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <flux:select wire:model.live="filterPriority" label="Priority">
                        <flux:select.option value="">All Priorities</flux:select.option>
                        <flux:select.option value="Low">Low Priority</flux:select.option>
                        <flux:select.option value="Medium">Medium Priority</flux:select.option>
                        <flux:select.option value="High">High Priority</flux:select.option>
                    </flux:select>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Tasks List -->
    <div class="mt-6 grid gap-4">
        @if(count($tasks) > 0)
        @foreach($tasks as $task)
        <div class="bg-zinc-100 dark:bg-zinc-900 p-4 rounded-xl space-y-3">
            <div class="flex justify-between items-center">
                <flux:heading size="lg">{{ $task->title }}</flux:heading>

                @php
                $statusColor = match($task->status) {
                'Todo' => 'grey',
                'In Progress' => 'blue',
                'Done' => 'emerald',
                'Error' => 'red',
                'Revisi' => 'indigo',
                default => 'yellow'
                };
                @endphp
                <flux:badge color="{{ $statusColor }}">{{ $task->status }}</flux:badge>
            </div>
            <flux:text>{{ $task->description }}</flux:text>
            <div class="flex space-x-5 items-center">
                @php
                $categoryIcon = match($task->category) {
                'Slicing' => '🎨',
                'Integration API' => '🔗',
                'Clean Code' => '🧹'
                };
                @endphp
                <flux:text>{{ $categoryIcon }} {{ $task->category }}</flux:text>

                @php
                $priority = strtolower($task->priority);
                $priorityColor = match($priority) {
                'low' => 'text-green-600',
                'medium' => 'text-yellow-600',
                'high' => 'text-red-600',
                default => 'text-gray-600',
                };
                @endphp

                <div class="{{ $priorityColor }} flex items-center space-x-1">
                    <flux:icon.exclamation-circle variant="micro" class="{{ $priorityColor }}" />
                    <flux:text class="{{ $priorityColor }}">
                        {{ ucfirst($priority) }} Prioritas
                    </flux:text>
                </div>

                <div class="flex items-center space-x-1">
                    <flux:icon.calendar variant="micro" />
                    <flux:text>Due: {{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}</flux:text>
                </div>
            </div>

            <div class="flex items-center space-x-5 mt-7">
                <flux:button wire:click="setEdit({{ $task->id }})" icon="pencil-square" variant="primary">Edit
                </flux:button>
                <flux:button wire:click="confirmDelete({{ $task->id }})" icon="trash" variant="danger">Delete
                </flux:button>
            </div>
        </div>
        @endforeach
        @else
        <div class="bg-zinc-100 dark:bg-zinc-900 p-8 rounded-xl text-center">
            <flux:icon.document-text class="mx-auto mb-4 text-zinc-400" variant="outline" />
            <flux:heading size="lg" class="text-zinc-500 mb-2">
                @if($searchTask || $this->hasActiveFilters())
                No Tasks Found
                @else
                No Tasks Yet
                @endif
            </flux:heading>
            <flux:text class="text-zinc-400">
                @if($searchTask || $this->hasActiveFilters())
                Try adjusting your search or filters to find what you're looking for.
                @else
                Create your first task by clicking the "Add Task" button above.
                @endif
            </flux:text>
        </div>
        @endif
    </div>
    @else
    <!-- Notes Search -->
    <div class="mt-6">
        <flux:input wire:model.live.debounce.300ms="searchNote" placeholder="Search notes by title or content..."
            icon="magnifying-glass" />
    </div>

    <!-- Notes List -->
    <div class="mt-6 grid gap-4">
        @if(count($notes) > 0)
        @foreach($notes as $note)
        <div class="bg-zinc-100 dark:bg-zinc-900 p-4 rounded-xl space-y-3">
            <div class="flex justify-between items-center">
                <flux:heading size="lg">{{ $note->title ?? 'Untitled Note' }}</flux:heading>
                <flux:text variant="caption" class="text-zinc-500">
                    {{ \Carbon\Carbon::parse($note->created_at)->format('d F Y, H:i') }}
                </flux:text>
            </div>
            <flux:text class="whitespace-pre-wrap">{{ $note->content }}</flux:text>
            <div class="flex items-center space-x-5 mt-7">
                <flux:button wire:click="editNote({{ $note->id }})" icon="pencil-square" variant="primary">Edit
                </flux:button>
                <flux:button wire:click="confirmDeleteNote({{ $note->id }})" icon="trash" variant="danger">Delete
                </flux:button>
            </div>
        </div>
        @endforeach
        @else
        <div class="bg-zinc-100 dark:bg-zinc-900 p-8 rounded-xl text-center">
            <flux:icon.document-text class="mx-auto mb-4 text-zinc-400" variant="outline" />
            <flux:heading size="lg" class="text-zinc-500 mb-2">
                @if($searchNote)
                No Notes Found
                @else
                No Notes Yet
                @endif
            </flux:heading>
            <flux:text class="text-zinc-400">
                @if($searchNote)
                Try adjusting your search to find what you're looking for.
                @else
                Create your first note by clicking the "Add Notes" button above.
                @endif
            </flux:text>
        </div>
        @endif
    </div>
    @endif

    {{-- Modal add/edit task --}}
    <flux:modal name="add-task" variant="flyout" @close="clearForm">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $taskId ? 'Update' : 'Add'}} Task</flux:heading>
                <flux:text class="mt-2">{{ $taskId ? 'Update' : 'Add' }} your task.</flux:text>
            </div>

            <form wire:submit="createTask" class="space-y-6">
                {{-- title --}}
                <flux:input wire:model="titleTask" label="Title" autocomplete="off"
                    placeholder="Enter title in here..." />

                {{-- deadline --}}
                @livewire('widget.date-piker', [
                'mode' => 'single',
                'label' => 'Deadline',
                'required' => false,
                'minDate' => now()->format('Y-m-d')
                ])

                {{-- select status --}}
                <flux:select wire:model="statusTask" label="Status">
                    <flux:select.option>Choose status...</flux:select.option>
                    <flux:select.option value="Todo">Todo</flux:select.option>
                    <flux:select.option value="In Progress">In Progress</flux:select.option>
                    <flux:select.option value="Done">Done</flux:select.option>
                    <flux:select.option value="Error">Error</flux:select.option>
                    <flux:select.option value="Revisi">Revisi</flux:select.option>
                </flux:select>

                {{-- select category --}}
                <flux:select wire:model="categoryTask" label="Category">
                    <flux:select.option>Choose category...</flux:select.option>
                    <flux:select.option value="Slicing">Slicing</flux:select.option>
                    <flux:select.option value="Integration API">Integration API</flux:select.option>
                    <flux:select.option value="Clean Code">Clean Code</flux:select.option>
                </flux:select>

                {{-- select priority --}}
                <flux:select wire:model="priorityTask" label="Priority">
                    <flux:select.option>Choose priority...</flux:select.option>
                    <flux:select.option value="Low">Low</flux:select.option>
                    <flux:select.option value="Medium">Medium</flux:select.option>
                    <flux:select.option value="High">High</flux:select.option>
                </flux:select>

                {{-- description --}}
                <flux:textarea wire:model="description" label="Description"
                    placeholder="Enter description in here..." />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ $taskId ? 'Update' : 'Create'}} Task</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Modal add/edit note --}}
    <flux:modal name="add-note" variant="flyout" @close="clearNoteForm">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $noteId ? 'Update' : 'Add'}} Note</flux:heading>
                <flux:text class="mt-2">{{ $noteId ? 'Update' : 'Add' }} your note.</flux:text>
            </div>

            <form wire:submit="createNote" class="space-y-6">
                {{-- title --}}
                <flux:input wire:model="titleNote" label="Title" autocomplete="off" placeholder="Enter note title..." />

                {{-- content --}}
                <flux:textarea wire:model="contentNote" label="Content" placeholder="Write your note here..."
                    rows="8" />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ $noteId ? 'Update' : 'Create'}} Note</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>