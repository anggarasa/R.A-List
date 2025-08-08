<div class="max-w-5xl mx-auto p-4">
    <div class="flex items-center space-x-5">
        <a href="{{ route('job.project_list') }}" wire:navigate>
            <flux:icon.arrow-left/>
        </a>
        <flux:heading size="xl">Detail: {{ $project->name }}</flux:heading>
    </div>

    <flux:modal.trigger name="add-task">
        <flux:button icon="plus" variant="primary" class="mt-5">Add Task</flux:button>
    </flux:modal.trigger>

    <!-- Tabs -->
    <div class="mt-6 flex space-x-4 border-b border-zinc-300 dark:border-zinc-700">
        <button class="pb-2 border-b-2 border-lime-500 text-lime-400">Tasks</button>
        <button class="pb-2 hover:text-lime-400">Notes</button>
    </div>

    <!-- Tasks List -->
    <div class="mt-4 grid gap-4">
        <!-- Task Item -->
        @foreach($tasks as $task)
            <div
                class="bg-zinc-100 dark:bg-zinc-900 p-4 rounded-xl space-y-3">
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
                        $priority = strtolower($task->priority); // misalnya: Low, Medium, High
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
                        <flux:icon.calendar variant="micro"/>
                        <flux:text>Due: {{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}</flux:text>
                    </div>
                </div>

                <div class="flex items-center space-x-5 mt-7">
                    <flux:button wire:click="setEdit({{ $task->id }})" icon="pencil-square" variant="primary">Edit</flux:button>
                    <flux:button wire:click="confirmDelete({{ $task->id }})" icon="trash" variant="danger">Delete</flux:button>
                </div>
            </div>
        @endforeach
    </div>

    {{--Modal add--}}
    <flux:modal name="add-task" variant="flyout" @close="clearForm">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $taskId ? 'Update' : 'Add'}} Task</flux:heading>
                <flux:text class="mt-2">{{ $taskId ? 'Update' : 'Add' }} your task.</flux:text>
            </div>

            <form wire:submit="createTask" class="space-y-6">
                {{-- title --}}
                <flux:input wire:model="titleTask" label="Title" autocomplete="off" placeholder="Enter title in here..." />

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

                {{-- select category --}}
                <flux:select wire:model="priorityTask" label="Priority">
                    <flux:select.option>Choose priority...</flux:select.option>
                    <flux:select.option value="Low">Low</flux:select.option>
                    <flux:select.option value="Medium">Medium</flux:select.option>
                    <flux:select.option value="High">High</flux:select.option>
                </flux:select>

                {{-- description --}}
                <flux:textarea wire:model="description" label="Description" placeholder="Enter description in here..." />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ $taskId ? 'Update' : 'Create'}} Task</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
