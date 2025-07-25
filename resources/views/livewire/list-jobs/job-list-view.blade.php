<div>
    <div class="flex justify-between items-center">
        <flux:heading size="xl">Job List Management</flux:heading>

        {{-- button modal add --}}
        <livewire:list-jobs.modal-add-job />
    </div>

    <div class="grid gap-5 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($jobLists as $job)
        <div class="mt-10 container bg-white border p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border-zinc-600">
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
        @endforeach
    </div>
</div>