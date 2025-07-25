<div>
    <flux:modal.trigger name="add-job">
        <flux:button icon="plus" variant="primary">Add Tasks</flux:button>
    </flux:modal.trigger>

    <flux:modal name="add-job" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Tasks</flux:heading>
                <flux:text class="mt-2">add tasks and organize tasks.</flux:text>
            </div>

            <form wire:submit="createTask" class="space-y-6">
                {{-- input name job --}}
                <flux:input wire:model="nameTask" label="Name Job" placeholder="Enter name in here..." />

                {{-- input category --}}
                <flux:select wire:model="categoryTaskId" label="Category" placeholder="Choose Category...">
                    <flux:select.option>Slicing</flux:select.option>
                    <flux:select.option>Integration API</flux:select.option>
                    <flux:select.option>Clean Code</flux:select.option>
                </flux:select>

                {{-- input status --}}
                <flux:select wire:model="statusTaskId" label="Status" placeholder="Choose Status...">
                    <flux:select.option>Pending</flux:select.option>
                    <flux:select.option>In Progress</flux:select.option>
                    <flux:select.option>Completed</flux:select.option>
                    <flux:select.option>Error</flux:select.option>
                    <flux:select.option>Revision</flux:select.option>
                </flux:select>

                {{-- textaread description --}}
                <flux:textarea wire:model="description" rows="2" label="Description"
                    placeholder="Enter description in here..." />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save Task</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>