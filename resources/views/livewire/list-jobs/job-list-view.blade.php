<div>
    <div class="flex justify-between items-center">
        <flux:heading size="xl">Job List Management</flux:heading>
        <livewire:list-jobs.modal-add-job />
    </div>

    {{-- Search and Filter Section --}}
    <div class="mt-6 bg-white dark:bg-zinc-900 rounded-2xl border dark:border-zinc-600 p-6 shadow">
        <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            {{-- Main Search Input --}}
            <div class="lg:col-span-2">
                <flux:input wire:model.live="search" placeholder="Search by name or description..."
                    icon="magnifying-glass" label="Search Jobs" />
            </div>

            {{-- Category Filter --}}
            <div>
                <flux:select wire:model.live="searchCategory" label="Filter by Category" placeholder="All Categories">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->name_category_task }}">{{ $category->name_category_task }}</option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Status Filter --}}
            <div>
                <flux:select wire:model.live="searchStatus" label="Filter by Status" placeholder="All Status">
                    <option value="">All Status</option>
                    @foreach ($statusTasks as $status)
                    <option value="{{ $status->name_status_task }}">{{ $status->name_status_task }}</option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        {{-- Search Actions --}}
        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center space-x-2">
                <flux:text class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ count($jobLists) }} job(s)
                </flux:text>
                @if($search || $searchCategory || $searchStatus)
                <flux:badge color="blue" size="sm">
                    Filtered
                </flux:badge>
                @endif
            </div>

            @if($search || $searchCategory || $searchStatus)
            <flux:button wire:click="resetSearch" size="sm" variant="outline" icon="x-mark">
                Clear Filters
            </flux:button>
            @endif
        </div>
    </div>

    {{-- Job Cards Grid --}}
    @if(count($jobLists) > 0)
    <div class="grid gap-5 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3 mt-6">
        @foreach ($jobLists as $job)
        <flux:modal.trigger :name="'detail-job'.$job->id">
            <div
                class="container bg-white border p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border-zinc-600 cursor-pointer hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <flux:heading>{{ $job->name_job_list }}</flux:heading>

                    @php
                    $badgeColor = match($job->statusTask->name_status_task) {
                    'In Progress' => 'blue',
                    'Completed' => 'green',
                    'Error' => 'red',
                    'Revisi' => 'yellow',
                    default => 'gray'
                    };

                    $categoryColor = match ($job->categoryTask->name_category_task) {
                    'Slicing' => 'sky',
                    'Integration API' => 'indigo',
                    'Clean Code' => 'emerald',
                    default => 'lime'
                    }
                    @endphp
                    <flux:badge color="{{ $badgeColor }}">{{ $job->statusTask->name_status_task }}</flux:badge>
                </div>
                <flux:text class="mt-2">
                    {{ $job->description }}
                </flux:text>

                <div class="flex items-center mt-5 justify-between">
                    <flux:badge size="sm" color='{{ $categoryColor }}'>
                        {{ $job->categoryTask->name_category_task ?? 'Category' }}
                    </flux:badge>
                    <flux:text>
                        {{ \Carbon\Carbon::parse($job->created_at)->format('d F Y') }}
                    </flux:text>
                </div>
            </div>
        </flux:modal.trigger>
        @endforeach
    </div>
    @else
    {{-- No Results State --}}
    <div class="mt-6 bg-white dark:bg-zinc-900 rounded-2xl border dark:border-zinc-600 p-12 shadow text-center">
        <div class="max-w-md mx-auto">
            <flux:icon.magnifying-glass class="mx-auto h-12 w-12 text-gray-400" />
            <flux:heading size="lg" class="mt-4">No jobs found</flux:heading>
            <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                @if($search || $searchCategory || $searchStatus)
                No jobs match your search criteria. Try adjusting your filters.
                @else
                No jobs have been created yet. Create your first job to get started.
                @endif
            </flux:text>
            @if($search || $searchCategory || $searchStatus)
            <flux:button wire:click="resetSearch" class="mt-4" variant="outline">
                Clear all filters
            </flux:button>
            @endif
        </div>
    </div>
    @endif

    {{-- pagination --}}
    <div class="mt-7">
        {{ $jobLists->links('vendor.pagination.tailwind') }}
    </div>

    {{-- Modal detail task --}}
    @foreach ($jobLists as $detailJob)
    <flux:modal :name="'detail-job'.$detailJob->id" class="md:w-1/2" @close="cancelEdit" wire:ignore.self>
        <div class="space-y-6">
            @if ($isEdit)
            <form wire:submit="updateTask" class="space-y-6">
                <div class="space-y-4">
                    <flux:input wire:model="form.nameTask" label="Name Task" placeholder="enter name task in here..." />

                    <flux:textarea wire:model="form.description" label="Description"
                        placeholder="enter description in here..." />
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <flux:button wire:click="cancelEdit">
                        Cancel
                    </flux:button>
                    <flux:button wire:click="updateTask" variant="primary">
                        Save Changes
                    </flux:button>
                </div>
            </form>
            @else
            <div>
                <div class="flex items-center space-x-3">
                    <flux:heading size="lg">{{ $detailJob->name_job_list }}</flux:heading>
                    <flux:text>{{ \Carbon\Carbon::parse($detailJob->created_at)->format('d F Y') }}</flux:text>
                </div>
                <flux:text class="mt-2">{{ $detailJob->description }}</flux:text>

                @php
                $badgeColor = match($detailJob->statusTask->name_status_task) {
                'In Progress' => 'blue',
                'Completed' => 'green',
                'Error' => 'red',
                'Revisi' => 'yellow',
                default => 'gray'
                };

                $categoryColor = match ($detailJob->categoryTask->name_category_task) {
                'Slicing' => 'sky',
                'Integration API' => 'indigo',
                'Clean Code' => 'emerald',
                default => 'lime'
                }
                @endphp

                <div class="flex items-center justify-between mt-5">
                    <div class="space-y-1">
                        <flux:heading>Category Task</flux:heading>
                        <flux:badge color="{{ $categoryColor }}">{{ $detailJob->categoryTask->name_category_task }}
                        </flux:badge>
                    </div>
                    <div class="space-y-1">
                        <flux:heading>Status Task</flux:heading>
                        <flux:badge color="{{ $badgeColor }}">{{ $detailJob->statusTask->name_status_task }}
                        </flux:badge>
                    </div>
                    <div class="space-y-1">
                        <flux:heading>Revision Date</flux:heading>
                        <flux:text>{{ $detailJob->date_job ?
                            \Carbon\Carbon::parse($detailJob->date_job)->format('d-M-Y') : 'No date' }}</flux:text>
                    </div>
                </div>
            </div>

            {{-- Form untuk realtime update --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                {{-- Category Dropdown --}}
                <div class="w-full">
                    <flux:dropdown position="top" class="w-full">
                        <flux:text class="text-black mb-2">Category</flux:text>
                        <flux:button icon:trailing="chevron-down" class="w-full">
                            {{ $detailJob->categoryTask->name_category_task }}
                        </flux:button>

                        <flux:menu>
                            @foreach ($categories as $category)
                            <flux:menu.item wire:click="updateCategoryTaskId({{ $category->id }}, {{ $detailJob->id }})"
                                class="{{ $category->id == $detailJob->category_task_id ? 'bg-blue-50 text-blue-700' : '' }}">
                                {{ $category->name_category_task }}
                            </flux:menu.item>
                            @endforeach
                        </flux:menu>
                    </flux:dropdown>
                </div>

                {{-- Status Dropdown --}}
                <div class="w-full">
                    <flux:dropdown position="top" class="w-full">
                        <flux:text class="text-black mb-2">Status</flux:text>
                        <flux:button icon:trailing="chevron-down" class="w-full justify-between">
                            <span class="truncate">{{ $detailJob->statusTask->name_status_task }}</span>
                        </flux:button>

                        <flux:menu>
                            @foreach ($statusTasks as $status)
                            <flux:menu.item wire:click="updateStatusTaskId({{ $status->id }}, {{ $detailJob->id }})"
                                class="{{ $status->id == $detailJob->status_task_id ? 'bg-blue-50 text-blue-700' : '' }}">
                                {{ $status->name_status_task }}
                            </flux:menu.item>
                            @endforeach
                        </flux:menu>
                    </flux:dropdown>
                </div>

                {{-- Revision Date Input --}}
                <div class="w-full">
                    <flux:input wire:model.live.debounce.500ms="revisionDates.{{ $detailJob->id }}" type="date"
                        max="2999-12-31" label="Revision Date" value="{{ $detailJob->date_job }}" class="w-full" />
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-6">
                <flux:button wire:click="editTask({{ $detailJob->id }})" variant="primary">
                    Edit Task
                </flux:button>
                <flux:button variant="danger" wire:click="deleteJob({{ $detailJob->id }})"
                    wire:confirm="Are you sure you want to delete this job?">Delete</flux:button>
            </div>
            @endif
        </div>
    </flux:modal>
    @endforeach
</div>