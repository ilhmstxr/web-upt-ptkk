{{-- Resource Tabs Component for Cluster Pelatihan --}}
<div class="mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8">
            {{-- Bidang Tab --}}
            <a 
                href="{{ \App\Filament\Clusters\Pelatihan\Resources\KompetensiResource::getUrl('index') }}"
                class="{{ $activeTab === 'kompetensi' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-rectangle-stack class="w-5 h-5" />
                Bidangs
            </a>
            
            {{-- Pelatihan Tab --}}
            <a 
                href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('index') }}"
                class="{{ $activeTab === 'pelatihans' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-academic-cap class="w-5 h-5" />
                Pelatihan
            </a>
        </nav>
    </div>

    @if($activeTab === 'pelatihans')
        <div class="mt-8 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    Program Pelatihan
                </h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    Buat, kelola, dan pantau seluruh siklus pelatihan dari persiapan hingga pelaporan.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                {{-- Create Button --}}
                <x-filament::button
                    tag="a"
                    :href="\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('create')"
                    color="warning"
                    icon="heroicon-m-plus"
                >
                    Buat Pelatihan Baru
                </x-filament::button>

                {{-- Export Button (Placeholder for now) --}}
                <x-filament::button
                    color="gray"
                    outlined
                    icon="heroicon-m-arrow-down-tray"
                >
                    Export Data
                </x-filament::button>
            </div>
        </div>
    @endif
</div>
