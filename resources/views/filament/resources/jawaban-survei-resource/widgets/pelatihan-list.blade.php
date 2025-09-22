<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
            Laporan per Pelatihan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($pelatihan as $pelatihan)
                <a href="{{ route('filament.admin.resources.jawaban-surveis.report', ['pelatihanId' => $pelatihan->id]) }}"
                    class="block p-4 bg-white border rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $pelatihan->nama_pelatihan }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lihat detail laporan &rarr;</p>
                </a>
            @empty
                <p class="text-gray-500 col-span-full">Belum ada data pelatihan.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
