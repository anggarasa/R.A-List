<div>
    <div class="flex justify-between items-center">
        <flux:heading size="xl">Job List Management</flux:heading>

        {{-- button modal add --}}
        <livewire:list-jobs.modal-add-job />
    </div>

    <div class="grid gap-5 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($jobLists as $job)
        <flux:modal.trigger :name="'detail-task'.$job->id">
            <div
                class="mt-10 container bg-white border p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border-zinc-600 cursor-pointer">
                <div class="flex items-center gap-3">
                    <flux:heading>{{ $job->name_job_list }}</flux:heading>

                    @php
                    $badgeColor = 'zinc';
                    switch ($job->statusTask->name_status_task) {
                    case 'In Progress':
                    $badgeColor = 'amber';
                    break;
                    case 'Completed':
                    $badgeColor = 'green';
                    break;
                    case 'Error':
                    $badgeColor = 'red';
                    break;
                    case 'Revisi':
                    $badgeColor = 'indigo';
                    break;
                    default:
                    $badgeColor = 'zinc';
                    break;
                    }
                    @endphp
                    <flux:badge color="{{ $badgeColor }}">{{ $job->statusTask->name_status_task }}</flux:badge>
                </div>
                <flux:text class="mt-2">
                    {{ $job->description }}
                </flux:text>

                <div class="flex items-center mt-5 justify-between">
                    <flux:badge size="sm" color='lime'>
                        {{ $job->categoryTask->name_category_task ?? 'Category' }}
                    </flux:badge>
                    <flux:text>
                        {{ $job->date_job == null ? \Carbon\Carbon::parse($job->created_at)->format('d-M-Y') :
                        \Carbon\Carbon::parse($job->date_job)->format('d-M-Y') }}
                    </flux:text>
                </div>
            </div>
        </flux:modal.trigger>
        @endforeach
    </div>

    {{-- modal detail task --}}
    @foreach ($jobLists as $job)
    <flux:modal :name="'detail-task'.$job->id" class="md:w-1/2">
        <div class="space-y-6">
            <div>
                <div class="flex items-center space-x-3">
                    <flux:heading size="lg">{{ $job->name_job_list }}</flux:heading>
                    <flux:text>{{ \Carbon\Carbon::parse($job->created_at)->format('d-M-Y') }}</flux:text>
                </div>
                <flux:text class="mt-2">{{ $job->description }}</flux:text>

                @php
                $badgeColor = 'zinc';
                switch ($job->statusTask->name_status_task) {
                case 'In Progress':
                $badgeColor = 'amber';
                break;
                case 'Completed':
                $badgeColor = 'green';
                break;
                case 'Error':
                $badgeColor = 'red';
                break;
                case 'Revisi':
                $badgeColor = 'indigo';
                break;
                default:
                $badgeColor = 'zinc';
                break;
                }
                @endphp
                <div class="flex items-center justify-between mt-2">
                    <div class="space-y-1">
                        <flux:heading>Category Task</flux:heading>
                        <flux:badge color="lime">{{ $job->CategoryTask->name_category_task }}</flux:badge>
                    </div>
                    <div class="space-y-1">
                        <flux:heading>Status Task</flux:heading>
                        <flux:badge color="{{ $badgeColor }}">{{ $job->statusTask->name_status_task }}</flux:badge>
                    </div>
                    <div class="space-y-1">
                        <flux:heading>Revision Data</flux:heading>
                        <flux:text>{{ $job->date_job ?? 'No date' }}</flux:text>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-5">
                <flux:select wire:model="categoryTaskId" label="Category" placeholder="Choose Category...">
                    <flux:select.option>Photography</flux:select.option>
                    <flux:select.option>Design services</flux:select.option>
                    <flux:select.option>Web development</flux:select.option>
                    <flux:select.option>Accounting</flux:select.option>
                    <flux:select.option>Legal services</flux:select.option>
                    <flux:select.option>Consulting</flux:select.option>
                    <flux:select.option>Other</flux:select.option>
                </flux:select>
                <flux:select wire:model="statusTaskId" label="Status" placeholder="Choose Status...">
                    <flux:select.option>Photography</flux:select.option>
                    <flux:select.option>Design services</flux:select.option>
                    <flux:select.option>Web development</flux:select.option>
                    <flux:select.option>Accounting</flux:select.option>
                    <flux:select.option>Legal services</flux:select.option>
                    <flux:select.option>Consulting</flux:select.option>
                    <flux:select.option>Other</flux:select.option>
                </flux:select>
                <flux:input type="date" max="2999-12-31" label="Date" />
            </div>

            <div class="flex items-center space-x-3">
                <flux:spacer />
                <flux:button variant="primary">Edit</flux:button>
                <flux:button variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
    @endforeach
</div>