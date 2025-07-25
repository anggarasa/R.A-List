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
                <flux:input wire:model="form.nameTask" label="Name Task" placeholder="Enter name in here..." />

                {{-- input category --}}
                <flux:select wire:model="form.categoryTaskId" label="Category">
                    <flux:select.option>Choose Category...</flux:select.option>
                    @foreach ($categories as $categoryTask)
                    <flux:select.option value="{{ $categoryTask->id }}">
                        {{ $categoryTask->name_category_task }}
                    </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- input status --}}
                <flux:select wire:model="form.statusTaskId" label="Status">
                    <flux:select.option>Choose Status...</flux:select.option>
                    @foreach ($statuses as $statusTask)
                    <flux:select.option value="{{ $statusTask->id }}">
                        {{ $statusTask->name_status_task }}
                    </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- textaread description --}}
                <flux:textarea wire:model="form.description" rows="2" label="Description"
                    placeholder="Enter description in here..." />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save Task</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>