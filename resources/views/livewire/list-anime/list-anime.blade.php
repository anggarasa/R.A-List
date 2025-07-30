<div class="p-6 max-w-4xl mx-auto space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Anime yang Sedang Ditonton</h1>

        <flux:modal.trigger name="add-anime">
            <flux:button icon="plus" variant="primary">Add Anime</flux:button>
        </flux:modal.trigger>
    </div>
    <!-- Loop this -->
    <div
        class="bg-white rounded-2xl shadow-sm p-5 flex items-start gap-4 border border-gray-200 hover:shadow-md transition dark:bg-zinc-900 dark:border-zinc-600">
        <!-- Poster Anime -->
        <img src="{{ asset('assets/images/jujutsu-kaisen-s2.jpg') }}" alt="Poster Jujutsu Kaisen"
            class="w-24 h-36 object-cover rounded-xl shadow-sm border border-gray-100 flex-shrink-0 dark:border-zinc-600">

        <!-- Detail Anime -->
        <div class="flex-1 flex flex-col justify-between">
            <div class="mb-2">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Jujutsu Kaisen S2</h2>
                <p class="text-sm text-gray-500 dark:text-zinc-300">Summer 2023 • <span
                        class="capitalize">Finished</span></p>
                <p class="text-sm text-gray-500 dark:text-zinc-300">Tayang tiap <strong>Kamis</strong></p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <span class="text-sm text-gray-600 dark:text-zinc-400">
                    Episode ditonton: <strong>20</strong> / 23
                </span>
                <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full font-medium
                             bg-green-100 text-green-700">
                    🔔 Reminder Aktif
                </span>
            </div>
        </div>
    </div>

    {{-- Kalau kosong --}}
    {{-- <div class="text-center text-gray-500 py-10">Belum ada anime yang ditambahkan.</div> --}}

    {{-- modal --}}
    <flux:modal name="add-anime" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Anime</flux:heading>
                <flux:text class="mt-2">add anime watched.</flux:text>
            </div>
            <form wire:submit="save" class="space-y-6">
                {{-- Poster --}}
                <flux:field>
                    <flux:label badge="Required">Poster</flux:label>
                    <flux:input type="file" wire:model="poster" />
                </flux:field>

                {{-- title --}}
                <flux:field>
                    <flux:label badge="Required">Anime Title</flux:label>
                    <flux:input wire:model="form.title" required autocomplete="off"
                        placeholder="Enter anime title in here..." />
                </flux:field>

                {{-- musim --}}
                <flux:field>
                    <flux:label badge="Required">Season</flux:label>
                    <flux:select wire:model="form.season" required autocomplete="off" placeholder="Choose season...">
                        <flux:select.option value="Winter">Winter</flux:select.option>
                        <flux:select.option value="Spring">Spring</flux:select.option>
                        <flux:select.option value="Summer">Summer</flux:select.option>
                        <flux:select.option value="Fall">Fall</flux:select.option>
                    </flux:select>
                </flux:field>

                {{-- tahun --}}
                <flux:field>
                    <flux:label badge="Required">Release Year</flux:label>
                    <flux:input wire:model="form.year" required autocomplete="off"
                        placeholder="Enter release year in here..." />
                </flux:field>

                {{-- status --}}
                <flux:field>
                    <flux:label badge="Required">Status</flux:label>
                    <flux:select wire:model="form.status" required autocomplete="off" placeholder="Choose status...">
                        <flux:select.option value="Ongoing">Ongoing</flux:select.option>
                        <flux:select.option value="Finished">Finished</flux:select.option>
                    </flux:select>
                </flux:field>

                {{-- total episode --}}
                <flux:field>
                    <flux:label badge="Optional">Total Episode</flux:label>
                    <flux:input wire:model="form.totalEpisode" autocomplete="off"
                        placeholder="Enter total episode in here..." />
                </flux:field>
            </form>
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>
</div>