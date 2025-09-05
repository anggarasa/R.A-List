<div class="transition-colors duration-300">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('job.project_list') }}" wire:navigate
                        class="p-2 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-colors">
                        <flux:icon.arrow-left class="w-5 h-5" />
                    </a>
                    <div>
                        <flux:heading size="2xl" class="text-zinc-900 dark:text-zinc-100">{{ $project->name }}
                        </flux:heading>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 mt-1">Project Details & Management
                        </flux:text>
                    </div>
                </div>

                <!-- Quick Action Button -->
                @if($activeTab === 'tasks')
                <flux:modal.trigger name="add-task">
                    <flux:button icon="plus" variant="primary" class="shadow-lg hover:shadow-xl transition-all">
                        New Task
                    </flux:button>
                </flux:modal.trigger>
                @else
                <flux:modal.trigger name="add-note">
                    <flux:button icon="plus" variant="primary" class="shadow-lg hover:shadow-xl transition-all">
                        New Note
                    </flux:button>
                </flux:modal.trigger>
                @endif
            </div>

            <!-- Modern Tab Navigation -->
            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-2xl p-2 border border-zinc-200 dark:border-zinc-700">
                <div class="flex space-x-2">
                    <button wire:click="setActiveTab('tasks')"
                        class="flex-1 px-6 py-3 rounded-xl font-medium transition-all duration-200 {{ $activeTab === 'tasks' 
                            ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm border border-zinc-200 dark:border-zinc-600' 
                            : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-white/50 dark:hover:bg-zinc-800/50' }}">
                        <div class="flex items-center justify-center space-x-2">
                            <flux:icon.check-circle class="w-4 h-4" />
                            <span>Tasks</span>
                        </div>
                    </button>
                    <button wire:click="setActiveTab('notes')"
                        class="flex-1 px-6 py-3 rounded-xl font-medium transition-all duration-200 {{ $activeTab === 'notes' 
                            ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm border border-zinc-200 dark:border-zinc-600' 
                            : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-white/50 dark:hover:bg-zinc-800/50' }}">
                        <div class="flex items-center justify-center space-x-2">
                            <flux:icon.document-text class="w-4 h-4" />
                            <span>Notes</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        @if($activeTab === 'tasks')
        <!-- Tasks Section -->
        <div class="space-y-6">
            <!-- Search and Filter Controls -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
                <div class="flex flex-col lg:flex-row gap-4 mb-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <flux:input wire:model.live.debounce.300ms="searchTask"
                            placeholder="Search tasks by title or description..." icon="magnifying-glass"
                            class="bg-zinc-50 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700" />
                    </div>

                    <!-- Filter Controls -->
                    <div class="flex gap-3">
                        <flux:button wire:click="toggleFilters" variant="{{ $showFilters ? 'primary' : 'ghost' }}"
                            icon="adjustments-horizontal" class="whitespace-nowrap">
                            Filters
                        </flux:button>

                        @if($this->hasActiveFilters())
                        <flux:button wire:click="clearAllFilters" variant="ghost" icon="x-mark"
                            class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                            Clear
                        </flux:button>
                        @endif
                    </div>
                </div>

                <!-- Collapsible Filter Panel -->
                @if($showFilters)
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 animate-in slide-in-from-top-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Category Filter -->
                        <flux:select wire:model.live="filterCategory" label="Category">
                            <flux:select.option value="">All Categories</flux:select.option>
                            <flux:select.option value="Slicing">🎨 Slicing</flux:select.option>
                            <flux:select.option value="Integration API">🔗 Integration API</flux:select.option>
                            <flux:select.option value="Clean Code">🧹 Clean Code</flux:select.option>
                        </flux:select>

                        <!-- Status Filter -->
                        <flux:select wire:model.live="filterStatus" label="Status">
                            <flux:select.option value="">All Status</flux:select.option>
                            <flux:select.option value="Todo">📋 Todo</flux:select.option>
                            <flux:select.option value="In Progress">⚡ In Progress</flux:select.option>
                            <flux:select.option value="Done">✅ Done</flux:select.option>
                            <flux:select.option value="Error">❌ Error</flux:select.option>
                            <flux:select.option value="Revisi">🔄 Revisi</flux:select.option>
                        </flux:select>

                        <!-- Priority Filter -->
                        <flux:select wire:model.live="filterPriority" label="Priority">
                            <flux:select.option value="">All Priorities</flux:select.option>
                            <flux:select.option value="Low">🟢 Low Priority</flux:select.option>
                            <flux:select.option value="Medium">🟡 Medium Priority</flux:select.option>
                            <flux:select.option value="High">🔴 High Priority</flux:select.option>
                        </flux:select>
                    </div>
                </div>
                @endif
            </div>

            <!-- Tasks Grid -->
            <div class="grid gap-4">
                @if(count($tasks) > 0)
                @foreach($tasks as $task)
                <div
                    class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
                    <!-- Task Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <flux:heading size="lg"
                                class="text-zinc-900 dark:text-zinc-100 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $task->title }}
                            </flux:heading>
                            <flux:text class="text-zinc-600 dark:text-zinc-400 line-clamp-2">
                                {{ $task->description }}
                            </flux:text>
                        </div>

                        <!-- Status Badge -->
                        @php
                        $statusConfig = match($task->status) {
                        'Todo' => ['color' => 'zinc', 'icon' => '📋'],
                        'In Progress' => ['color' => 'blue', 'icon' => '⚡'],
                        'Done' => ['color' => 'emerald', 'icon' => '✅'],
                        'Error' => ['color' => 'red', 'icon' => '❌'],
                        'Revisi' => ['color' => 'indigo', 'icon' => '🔄'],
                        default => ['color' => 'yellow', 'icon' => '📌']
                        };
                        @endphp
                        <flux:badge color="{{ $statusConfig['color'] }}" class="ml-4">
                            {{ $statusConfig['icon'] }} {{ $task->status }}
                        </flux:badge>
                    </div>

                    <!-- Task Metadata -->
                    <div class="flex flex-wrap items-center gap-4 text-sm mb-4">
                        <!-- Category -->
                        @php
                        $categoryIcon = match($task->category) {
                        'Slicing' => '🎨',
                        'Integration API' => '🔗',
                        'Clean Code' => '🧹'
                        };
                        @endphp
                        <div class="flex items-center space-x-2 px-3 py-1 bg-zinc-100 dark:bg-zinc-800 rounded-full">
                            <span>{{ $categoryIcon }}</span>
                            <span class="text-zinc-600 dark:text-zinc-400">{{ $task->category }}</span>
                        </div>

                        <!-- Priority -->
                        @php
                        $priority = strtolower($task->priority);
                        $priorityConfig = match($priority) {
                        'low' => ['color' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-100
                        dark:bg-emerald-900/30', 'icon' => '🟢'],
                        'medium' => ['color' => 'text-amber-600 dark:text-amber-400', 'bg' => 'bg-amber-100
                        dark:bg-amber-900/30', 'icon' => '🟡'],
                        'high' => ['color' => 'text-red-600 dark:text-red-400', 'bg' => 'bg-red-100 dark:bg-red-900/30',
                        'icon' => '🔴'],
                        default => ['color' => 'text-zinc-600 dark:text-zinc-400', 'bg' => 'bg-zinc-100
                        dark:bg-zinc-800', 'icon' => '⚪'],
                        };
                        @endphp
                        <div class="flex items-center space-x-2 px-3 py-1 {{ $priorityConfig['bg'] }} rounded-full">
                            <span>{{ $priorityConfig['icon'] }}</span>
                            <span class="{{ $priorityConfig['color'] }}">{{ ucfirst($priority) }} Priority</span>
                        </div>

                        <!-- Due Date -->
                        <div class="flex items-center space-x-2 px-3 py-1 bg-zinc-100 dark:bg-zinc-800 rounded-full">
                            <flux:icon.calendar class="w-4 h-4 text-zinc-500" />
                            <span class="text-zinc-600 dark:text-zinc-400">
                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex items-center justify-end space-x-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button wire:click="setEdit({{ $task->id }})" icon="pencil-square" variant="ghost"
                            size="sm"
                            class="text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30">
                            Edit
                        </flux:button>
                        <flux:button wire:click="confirmDelete({{ $task->id }})" icon="trash" variant="ghost" size="sm"
                            class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30">
                            Delete
                        </flux:button>
                    </div>
                </div>
                @endforeach
                @else
                <!-- Empty State -->
                <div
                    class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center">
                        <flux:icon.document-text class="w-8 h-8 text-zinc-400" />
                    </div>
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100 mb-2">
                        @if($searchTask || $this->hasActiveFilters())
                        No Tasks Found
                        @else
                        No Tasks Yet
                        @endif
                    </flux:heading>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 mb-6">
                        @if($searchTask || $this->hasActiveFilters())
                        Try adjusting your search or filters to find what you're looking for.
                        @else
                        Get started by creating your first task for this project.
                        @endif
                    </flux:text>
                    @if(!$searchTask && !$this->hasActiveFilters())
                    <flux:modal.trigger name="add-task">
                        <flux:button icon="plus" variant="primary">Create First Task</flux:button>
                    </flux:modal.trigger>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- Notes Section -->
        <div class="space-y-6">
            <!-- Search Control -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
                <flux:input wire:model.live.debounce.300ms="searchNote"
                    placeholder="Search notes by title or content..." icon="magnifying-glass"
                    class="bg-zinc-50 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700" />
            </div>

            <!-- Notes Grid -->
            <div class="grid gap-4">
                @if(count($notes) > 0)
                @foreach($notes as $note)
                <div
                    class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
                    <!-- Note Header -->
                    <div class="flex justify-between items-start mb-4">
                        <flux:heading size="lg"
                            class="text-zinc-900 dark:text-zinc-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $note->title ?? 'Untitled Note' }}
                        </flux:heading>
                        <div class="flex items-center space-x-2 text-zinc-500 dark:text-zinc-400 text-sm">
                            <flux:icon.clock class="w-4 h-4" />
                            <span>{{ \Carbon\Carbon::parse($note->created_at)->format('M d, Y • H:i') }}</span>
                        </div>
                    </div>

                    <!-- Note Content -->
                    <flux:text class="text-zinc-600 dark:text-zinc-400 whitespace-pre-wrap line-clamp-4 mb-4">
                        {{ $note->content }}
                    </flux:text>

                    <!-- Action Buttons -->
                    <div
                        class="flex items-center justify-end space-x-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button wire:click="editNote({{ $note->id }})" icon="pencil-square" variant="ghost"
                            size="sm"
                            class="text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30">
                            Edit
                        </flux:button>
                        <flux:button wire:click="confirmDeleteNote({{ $note->id }})" icon="trash" variant="ghost"
                            size="sm"
                            class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30">
                            Delete
                        </flux:button>
                    </div>
                </div>
                @endforeach
                @else
                <!-- Empty State -->
                <div
                    class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center">
                        <flux:icon.document-text class="w-8 h-8 text-zinc-400" />
                    </div>
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100 mb-2">
                        @if($searchNote)
                        No Notes Found
                        @else
                        No Notes Yet
                        @endif
                    </flux:heading>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 mb-6">
                        @if($searchNote)
                        Try adjusting your search to find what you're looking for.
                        @else
                        Start documenting your ideas and project notes here.
                        @endif
                    </flux:text>
                    @if(!$searchNote)
                    <flux:modal.trigger name="add-note">
                        <flux:button icon="plus" variant="primary">Create First Note</flux:button>
                    </flux:modal.trigger>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Task Modal -->
    <flux:modal name="add-task" variant="flyout" @close="clearForm">
        <div class="space-y-6">
            <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
                <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                    {{ $taskId ? 'Update Task' : 'Create New Task'}}
                </flux:heading>
                <flux:text class="text-zinc-500 dark:text-zinc-400 mt-2">
                    {{ $taskId ? 'Make changes to your task' : 'Add a new task to your project' }}
                </flux:text>
            </div>

            <form wire:submit="createTask" class="space-y-6">
                <div class="grid gap-6">
                    <!-- Title -->
                    <flux:input wire:model="titleTask" label="Task Title" autocomplete="off"
                        placeholder="Enter task title..." class="bg-zinc-50 dark:bg-zinc-800" />

                    <!-- Due Date -->
                    @livewire('widget.date-piker', [
                    'mode' => 'single',
                    'label' => 'Due Date',
                    'required' => false,
                    'minDate' => now()->format('Y-m-d')
                    ])

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Status -->
                        <flux:select wire:model="statusTask" label="Status">
                            <flux:select.option value="">Choose status...</flux:select.option>
                            <flux:select.option value="Todo">📋 Todo</flux:select.option>
                            <flux:select.option value="In Progress">⚡ In Progress</flux:select.option>
                            <flux:select.option value="Done">✅ Done</flux:select.option>
                            <flux:select.option value="Error">❌ Error</flux:select.option>
                            <flux:select.option value="Revisi">🔄 Revisi</flux:select.option>
                        </flux:select>

                        <!-- Category -->
                        <flux:select wire:model="categoryTask" label="Category">
                            <flux:select.option value="">Choose category...</flux:select.option>
                            <flux:select.option value="Slicing">🎨 Slicing</flux:select.option>
                            <flux:select.option value="Integration API">🔗 Integration API</flux:select.option>
                            <flux:select.option value="Clean Code">🧹 Clean Code</flux:select.option>
                        </flux:select>

                        <!-- Priority -->
                        <flux:select wire:model="priorityTask" label="Priority">
                            <flux:select.option value="">Choose priority...</flux:select.option>
                            <flux:select.option value="Low">🟢 Low</flux:select.option>
                            <flux:select.option value="Medium">🟡 Medium</flux:select.option>
                            <flux:select.option value="High">🔴 High</flux:select.option>
                        </flux:select>
                    </div>

                    <!-- Description -->
                    <flux:textarea wire:model="description" label="Description"
                        placeholder="Describe the task details..." rows="4" class="bg-zinc-50 dark:bg-zinc-800" />
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" type="button" x-on:click="$flux.modal('add-task').close()">Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ $taskId ? 'Update Task' : 'Create Task'}}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Note Modal -->
    <flux:modal name="add-note" variant="flyout" @close="clearNoteForm">
        <div class="space-y-6">
            <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
                <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                    {{ $noteId ? 'Update Note' : 'Create New Note'}}
                </flux:heading>
                <flux:text class="text-zinc-500 dark:text-zinc-400 mt-2">
                    {{ $noteId ? 'Make changes to your note' : 'Add a new note to your project' }}
                </flux:text>
            </div>

            <form wire:submit="createNote" class="space-y-6">
                <div class="grid gap-6">
                    <!-- Title -->
                    <flux:input wire:model="titleNote" label="Note Title" autocomplete="off"
                        placeholder="Enter note title..." class="bg-zinc-50 dark:bg-zinc-800" />

                    <!-- Content -->
                    <flux:textarea wire:model="contentNote" label="Content"
                        placeholder="Write your note content here..." rows="8" class="bg-zinc-50 dark:bg-zinc-800" />
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" type="button" x-on:click="$flux.modal('add-note').close()">Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ $noteId ? 'Update Note' : 'Create Note'}}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>