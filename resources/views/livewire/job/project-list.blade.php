<div class="max-w-7xl mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-lime-500">📁 My Projects</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Project -->
        <div class="bg-zinc-100 dark:bg-zinc-900 p-4 rounded-2xl shadow hover:shadow-lg transition">
            <h2 class="text-xl font-semibold text-lime-400">Nama Project</h2>
            <p class="text-sm mt-1 text-zinc-500 dark:text-zinc-300">Deskripsi singkat...</p>
            <div class="mt-4 flex justify-between items-center">
                <span class="text-sm bg-lime-200 dark:bg-lime-700 text-zinc-900 dark:text-white px-2 py-1 rounded">In
                    Progress</span>
                <a href="{{ route('job.project_detail') }}"
                    class="text-lime-600 dark:text-lime-400 text-sm hover:underline">Lihat Detail →</a>
            </div>
        </div>
        <!-- ...Ulangi card... -->
    </div>

    <div class="mt-6">
        <button class="bg-lime-500 text-white px-4 py-2 rounded hover:bg-lime-600">+ Tambah Project</button>
    </div>
</div>