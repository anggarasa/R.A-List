<div>
    <div class="flex justify-between items-center">
        <flux:heading size="xl">Job List Management</flux:heading>

        {{-- <flux:button icon="plus" variant="primary">Add Job</flux:button> --}}
        {{-- button modal add --}}
        <livewire:list-jobs.modal-add-job />
    </div>

    <div class="grid gap-5 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        <div class="mt-10 container bg-white border p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border-zinc-600">
            <div class="flex items-center gap-3">
                <flux:heading>Nama Job</flux:heading>
                <flux:badge color="lime">New</flux:badge>
            </div>
            <flux:text class="mt-2">
                This is the standard text component for body copy and general content throughout your application.
            </flux:text>
            <flux:text class="mt-5">17 June 2025</flux:text>
        </div>
    </div>
</div>