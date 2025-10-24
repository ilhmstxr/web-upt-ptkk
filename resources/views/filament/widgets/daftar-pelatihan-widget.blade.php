{{-- 
  Gunakan <x-filament-widgets::widget> sebagai pembungkus utama.
  Variabel $pelatihan otomatis tersedia dari file PHP widget.
--}}
<x-filament-widgets::widget>
    <x-filament::section>
        {{-- BAGIAN HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Daftar Pelatihan Aktif & Mendatang
            </h2>
            
            {{-- Badge Program --}}
            <span class="px-3 py-1 text-sm font-medium text-primary-700 bg-primary-50 dark:text-primary-400 dark:bg-primary-900/10 rounded-full">
                {{ $pelatihan->count() }} Program
            </span>
        </div>

        {{-- BAGIAN DAFTAR KARTU (LIST VIEW) --}}
        <div class="space-y-4">
            @forelse ($pelatihan as $item)
                {{-- Ini adalah satu kartu (Card) --}}
                <div class="p-4 border rounded-lg shadow-sm fi-section-content dark:border-gray-700 dark:bg-gray-800">
                    
                    {{-- Baris atas: Judul dan Status --}}
                    <div class="flex items-center justify-between">
                        <h3 class="text-md font-semibold text-gray-900 dark:text-white">
                            {{ $item->nama }}
                        </h3>
                        
                        {{-- Badge Status (Aktif / Mendatang) --}}
                        <span @class([
                            'px-2 py-0.5 text-xs font-medium rounded-full',
                            'text-green-700 bg-green-100 dark:text-green-300 dark:bg-green-900/50' => $item->status == 'aktif',
                            'text-yellow-700 bg-yellow-100 dark:text-yellow-300 dark:bg-yellow-900/50' => $item->status == 'mendatang',
                        ])>
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>

                    {{-- Baris bawah: Detail (Tipe dan Tanggal) --}}
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                        
                        {{-- Tipe Pelatihan (Contoh: Reguler, Akselerasi, MTU) --}}
                        <div class="flex items-center space-x-1">
                            <x-heroicon-s-identification class="w-4 h-4" />
                            <span>{{ $item->tipe }}</span> {{-- Asumsi ada kolom 'tipe' --}}
                        </div>

                        {{-- Tanggal Pelatihan --}}
                        <div class="flex items-center space-x-1">
                            <x-heroicon-s-calendar-days class="w-4 h-4" />
                            <span>
                                {{ $item->tanggal_mulai->format('d M y') }} - {{ $item->tanggal_selesai->format('d M y') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Tampilan jika tidak ada data --}}
                <p class="text-center text-gray-500 dark:text-gray-400">
                    Belum ada pelatihan yang aktif atau mendatang.
                </p>
            @endforelse
        </div>

        {{-- BAGIAN FOOTER (TOMBOL) --}}
        <div class="mt-6 text-center">
            <x-filament::button
                {{-- Arahkan ke halaman resource Anda --}}
                :href="\App\Filament\Resources\PelatihanResource::getUrl('index')"
                tag="a"
                icon="heroicon-m-arrow-right"
                icon-position="after"
            >
                Lihat Semua Pelatihan
            </x-filament::button>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>