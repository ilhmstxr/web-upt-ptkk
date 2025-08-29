<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            {{-- Kartu Total Peserta --}}
            <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Peserta Pelatihan</h3>
                <p class="text-3xl font-semibold mt-1">{{ $totalPeserta }}</p>
            </div>

            {{-- Kartu Peserta Sudah Mengisi --}}
            <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Peserta Sudah Mengisi</h3>
                <p class="text-3xl font-semibold mt-1">{{ $pesertaMengisi }}</p>
                <div class="mt-2">
                    {{ $this->viewCompletedAction }}
                </div>
            </div>

            {{-- Kartu Peserta Belum Mengisi --}}
            <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Peserta Belum Mengisi</h3>
                <p class="text-3xl font-semibold mt-1">{{ $pesertaBelumMengisi }}</p>
                <div class="mt-2">
                    {{ $this->viewNotCompletedAction }}
                </div>
            </div>
            
            {{-- Kartu Tingkat Partisipasi --}}
            <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tingkat Partisipasi</h3>
                <p class="text-3xl font-semibold mt-1">{{ $persentase }}</p>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>