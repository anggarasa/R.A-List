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
                    <flux:input type="file" wire:model="form.poster" />
                    <div wire:loading wire:target="form.poster"
                        class="mt-2 text-gray-500 flex justify-center items-center">
                        <flux:error name="form.poster" />
                        <flux:icon.arrow-path class="animate-spin text-xl mr-3" />
                        <span>Mengunggah...</span>
                    </div>

                    @if ($form->poster)
                    <div wire:loading.remove wire:target="form.foto" class="mt-4">
                        <div class="w-32 h-32 overflow-hidden rounded-lg shadow-md">
                            <img src="{{ $form->poster->temporaryUrl() }}" alt="Preview"
                                class="w-full h-full object-cover" />
                        </div>
                    </div>
                    @elseif ($form->oldPoster)
                    <!-- Jika tidak ada gambar baru, tampilkan gambar lama -->
                    <div class="mt-4">
                        <div class="w-32 h-32 overflow-hidden rounded-lg shadow-md">
                            <img src="{{ asset('storage/' . $form->oldPoster) }}" alt="Old Foto"
                                class="w-full h-full object-cover" />
                        </div>
                    </div>
                    @endif
                </flux:field>

                {{-- title --}}
                <flux:field>
                    <flux:label badge="Required">Anime Title</flux:label>
                    <flux:input wire:model="form.title" required autocomplete="off"
                        placeholder="Enter anime title in here..." />
                    <flux:error name="form.title" />
                </flux:field>

                {{-- musim --}}
                <flux:field>
                    <flux:label badge="Required">Season</flux:label>
                    <flux:select wire:model="form.season" required autocomplete="off">
                        <flux:select.option>Choose season...</flux:select.option>
                        <flux:select.option value="Winter">Winter</flux:select.option>
                        <flux:select.option value="Spring">Spring</flux:select.option>
                        <flux:select.option value="Summer">Summer</flux:select.option>
                        <flux:select.option value="Fall">Fall</flux:select.option>
                    </flux:select>
                    <flux:error name="form.season" />
                </flux:field>

                {{-- tahun --}}
                <flux:field>
                    <flux:label badge="Required">Release Year</flux:label>
                    <flux:input wire:model="form.year" required autocomplete="off"
                        placeholder="Enter release year in here..." />
                    <flux:error name="form.year" />
                </flux:field>

                {{-- status --}}
                <flux:field>
                    <flux:label badge="Required">Status</flux:label>
                    <flux:select wire:model="form.status" required autocomplete="off">
                        <flux:select.option>Choose status...</flux:select.option>
                        <flux:select.option value="Ongoing">Ongoing</flux:select.option>
                        <flux:select.option value="Finished">Finished</flux:select.option>
                    </flux:select>
                    <flux:error name="form.status" />
                </flux:field>

                {{-- total episode --}}
                <flux:field>
                    <flux:label badge="Optional">Total Episode</flux:label>
                    <flux:input wire:model="form.totalEpisode" type="number" autocomplete="off"
                        placeholder="Enter total episode in here..." />
                    <flux:error name="form.totalEpisode" />
                </flux:field>

                {{-- hari tayang --}}
                <flux:field>
                    <flux:label badge="Optional">Air Date</flux:label>
                    <flux:select wire:model="form.airDate">
                        <flux:select.option>Choose air date...</flux:select.option>
                        <flux:select.option value="Sunday">Sunday</flux:select.option>
                        <flux:select.option value="Monday">Monday</flux:select.option>
                        <flux:select.option value="Tuesday">Tuesday</flux:select.option>
                        <flux:select.option value="Wednesday">Wednesday</flux:select.option>
                        <flux:select.option value="Thursday">Thursday</flux:select.option>
                        <flux:select.option value="Friday">Friday</flux:select.option>
                        <flux:select.option value="Saturday">Saturday</flux:select.option>
                    </flux:select>
                    <flux:error name="form.airDate" />
                </flux:field>

                {{-- episode terakhir --}}
                <flux:field>
                    <flux:label badge="Required">Last watched episode</flux:label>
                    <flux:input wire:model="form.lastWatch" type="number" required autocomplete="off"
                        placeholder="Enter last watch episode in here..." />
                    <flux:error name="form.lastWatch" />
                </flux:field>

                {{-- pengingat --}}
                <flux:field variant="inline">
                    <flux:checkbox wire:model="form.reminder" />

                    <flux:label>Activate reminder</flux:label>
                </flux:field>

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save Anime</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>