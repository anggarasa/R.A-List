<div>
    <div class="flex justify-between items-center mb-20">
        <flux:heading size="xl">Management Project</flux:heading>

        <flux:modal.trigger name="add-project">
            <flux:button icon="plus" variant="primary">Add Project</flux:button>
        </flux:modal.trigger>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Project -->
        <div class="bg-zinc-100 dark:bg-zinc-900 p-4 rounded-2xl shadow hover:shadow-lg transition">
            <flux:heading>Nama Project</flux:heading>
            <flux:text class="mt-2">
                Lorem ipsum dolor sit amet consectetur adipisicing elit...
            </flux:text>
            <div class="mt-4 flex justify-between items-center">
                <flux:badge color="lime">In Progress</flux:badge>
                <a href="{{ route('job.project_detail') }}"
                    class="text-lime-600 dark:text-lime-400 text-sm hover:underline">Lihat Detail →</a>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <flux:modal name="add-project" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Project</flux:heading>
                <flux:text class="mt-2">create your project.</flux:text>
            </div>

            <form wire:submit="createProject" class="space-y-6">
                {{-- input name --}}
                <flux:input wire:model="nameProject" label="Name" placeholder="Enter name project in here..." />

                {{-- input select status --}}
                <flux:select wire:model="statusProject" label="Status">
                    <flux:select.option>Chose status project...</flux:select.option>
                    <flux:select.option value="Planning">Planning</flux:select.option>
                    <flux:select.option value="In Progress">In Progress</flux:select.option>
                    <flux:select.option value="Completed">Completed</flux:select.option>
                    <flux:select.option value="On Hold">On Hold</flux:select.option>
                </flux:select>

                @livewire('widget.date-piker', [
                'mode' => 'range',
                'label' => 'Period',
                'required' => false,
                'minDate' => now()->format('Y-m-d')
                ])

                {{-- bottom --}}
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>