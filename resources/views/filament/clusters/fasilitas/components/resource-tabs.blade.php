{{-- Resource Tabs Component for Cluster Fasilitas --}}
<div class="mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8">
            {{-- Asramas Tab --}}
            <a 
                href="{{ \App\Filament\Clusters\Fasilitas\Resources\AsramaResource::getUrl('index') }}"
                class="{{ $activeTab === 'asramas' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-home-modern class="w-5 h-5" />
                Asramas
            </a>
            
            {{-- Penempatan Asrama Tab --}}
            <a 
                href="{{ \App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource::getUrl('index') }}"
                class="{{ $activeTab === 'penempatan' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-home class="w-5 h-5" />
                Penempatan Asrama
            </a>
        </nav>
    </div>

    {{-- Header Content for Asramas --}}
    @if($activeTab === 'asramas')
        <div class="mt-8 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    Fasilitas Asrama
                </h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    Ringkasan kapasitas, jumlah kamar, total bed, serta kondisi kamar tiap asrama. Deskripsi dihitung otomatis dari config kamar.php dan data kamar di database.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <x-filament::button
                    tag="a"
                    :href="\App\Filament\Clusters\Fasilitas\Resources\AsramaResource::getUrl('create')"
                    color="warning"
                    icon="heroicon-m-plus"
                >
                    Tambah Asrama
                </x-filament::button>
            </div>
        </div>
    @endif

    {{-- Header Content for Penempatan Asrama --}}
    @if($activeTab === 'penempatan')
        <div class="mt-8 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    Fasilitas Asrama
                </h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    Asrama global (dari config), kamar bisa dipakai per pelatihan via kamar_pelatihan.
                </p>
            </div>
            
            {{-- No actions for Penempatan Asrama based on previous file analysis (empty actions) --}}
        </div>
    @endif
</div>
