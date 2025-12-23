{{-- Resource Tabs Component for Cluster Konten Website --}}
<div class="mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8 overflow-x-auto">
            {{-- Kepala UPT --}}
            <a 
                href="{{ \App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource::getUrl('index') }}"
                class="{{ $activeTab === 'kepala_upt' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-user class="w-5 h-5" />
                Kepala UPT
            </a>

            {{-- Program Pelatihan --}}
            <a 
                href="{{ \App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource::getUrl('index') }}"
                class="{{ $activeTab === 'program_pelatihan' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-photo class="w-5 h-5" />
                Program Pelatihan
            </a>

            {{-- Sorotan Pelatihan --}}
            <a 
                href="{{ \App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource::getUrl('index') }}"
                class="{{ $activeTab === 'sorotan' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-camera class="w-5 h-5" />
                Sorotan Pelatihan (4-8 Foto)
            </a>

            {{-- Banner --}}
            <a 
                href="{{ \App\Filament\Clusters\KontenWebsite\Resources\BannerResource::getUrl('index') }}"
                class="{{ $activeTab === 'banner' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-photo class="w-5 h-5" />
                Banner
            </a>

            {{-- Berita --}}
            <a 
                href="{{ \App\Filament\Clusters\KontenWebsite\Resources\BeritaResource::getUrl('index') }}"
                class="{{ $activeTab === 'berita' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-document-text class="w-5 h-5" />
                Berita
            </a>

            {{-- Cerita Kami --}}
            <a 
                href="{{ \App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource::getUrl('index') }}"
                class="{{ $activeTab === 'cerita_kami' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-book-open class="w-5 h-5" />
                Cerita Kami
            </a>
        </nav>
    </div>

    {{-- Content Logic --}}
    @php
        $title = '';
        $description = '';
        $createUrl = '';
        $createLabel = 'Buat Baru';
        $showCreate = true;

        switch($activeTab) {
            case 'kepala_upt':
                $title = 'Kepala UPT';
                $createUrl = \App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource::getUrl('create');
                $createLabel = 'New Kepala UPT';
                break;
            case 'program_pelatihan':
                $title = 'Program Pelatihan';
                $createUrl = \App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource::getUrl('create');
                $createLabel = 'Tambah Program';
                break;
            case 'sorotan':
                $title = 'Sorotan Pelatihan';
                $createUrl = \App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource::getUrl('create');
                break;
            case 'banner':
                $title = 'Banner';
                $createUrl = \App\Filament\Clusters\KontenWebsite\Resources\BannerResource::getUrl('create');
                break;
            case 'berita':
                $title = 'Berita';
                $createUrl = \App\Filament\Clusters\KontenWebsite\Resources\BeritaResource::getUrl('create');
                break;
            case 'cerita_kami':
                $title = 'Cerita Kami';
                $createUrl = \App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource::getUrl('create');
                // Logic for hiding create button if data exists handled in controller/view logic?
                // For simplicity, I'll just show it here, or I can check count if needed.
                // But blade view shouldn't do DB queries ideally.
                // I'll stick to standard button.
                break;
        }
    @endphp

    <div class="mt-8 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                {{ $title }}
            </h1>
            @if($description)
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    {{ $description }}
                </p>
            @endif
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @if($showCreate && $createUrl)
                <x-filament::button
                    tag="a"
                    :href="$createUrl"
                    color="warning"
                    icon="heroicon-m-plus"
                >
                    {{ $createLabel }}
                </x-filament::button>
            @endif
        </div>
    </div>
</div>
