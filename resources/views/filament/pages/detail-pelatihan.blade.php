<x-filament-panels::page>
    <!-- Header Halaman -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
            Laporan: {{ $record->nama_pelatihan }}
        </h1>
        <!-- Contoh Stats -->
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Peserta</h3>
                <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $record->peserta()->count() }}
                </p>
            </div>
            <!-- Tambahkan stats lain sesuai kebutuhan -->
        </div>
    </div>

    <!-- Layout Utama (Chart & Tabel) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            @livewire(\App\Filament\Widgets\HasilKeseluruhanChart::class, ['record' => $record])
        </div>
        <div>
            @livewire(\App\Filament\Widgets\PesertaBelumMengerjakanTable::class, ['record' => $record])
        </div>
    </div>
</x-filament-panels::page>
