<div class="max-w-5xl mx-auto p-4">
    <flux:heading size="xl">Detail: {{ $project->name }}</flux:heading>
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
        <div
            class="bg-zinc-100 dark:bg-zinc-900 p-4 rounded-xl space-y-3">
            <div class="flex justify-between items-center">
                <flux:heading size="lg">Name Task</flux:heading>

                <flux:badge color="blue">In Progress</flux:badge>
            </div>
            <flux:text>Lorem ipsum dolor sit amet consectetur adipisicing elit.</flux:text>
            <div class="flex space-x-5 items-center">
                <flux:text>🔗 Integration API</flux:text>

                <div class="text-red-600 flex items-center space-x-1">
                    <flux:icon.exclamation-circle variant="micro"/>
                    <flux:text class="text-red-600">High Prioritas</flux:text>
                </div>

                <div class="flex items-center space-x-1">
                    <flux:icon.calendar variant="micro"/>
                    <flux:text>Due: 15 Agustus 2025</flux:text>
                </div>
            </div>

            <div class="flex items-center space-x-5 mt-7">
                <flux:button icon="pencil-square" variant="primary">Edit</flux:button>
                <flux:button icon="trash" variant="danger">Delete</flux:button>
            </div>
        </div>
    </div>

    {{--Modal add--}}
    <flux:modal name="add-task" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Task</flux:heading>
                <flux:text class="mt-2">Add your task.</flux:text>
            </div>

            <form wire:submit="createTask" class="space-y-6">
                {{-- title --}}
                <flux:input wire:model="titleTask" label="Title" placeholder="Enter title in here..." />

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
                <flux:textarea label="Description" placeholder="Enter description in here..." />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Create Task</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
