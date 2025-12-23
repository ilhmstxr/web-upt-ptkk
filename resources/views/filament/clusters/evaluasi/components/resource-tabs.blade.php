{{-- Resource Tabs Component for Cluster Evaluasi --}}
<div class="mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8">
            {{-- Tes --}}
            <a 
                href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('index') }}"
                class="{{ $activeTab === 'tes' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                Tes
            </a>

            {{-- Statistik --}}
            <a 
                href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResultResource::getUrl('index') }}"
                class="{{ $activeTab === 'statistik' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-chart-bar class="w-5 h-5" />
                Statistik
            </a>

            {{-- Materi Pelatihan --}}
            <a 
                href="{{ \App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource::getUrl('index') }}"
                class="{{ $activeTab === 'materi' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-book-open class="w-5 h-5" />
                Materi Pelatihan
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
            case 'tes':
                $title = 'Tes';
                $createUrl = \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('create');
                $createLabel = 'New tes';
                break;
            case 'statistik':
                $title = 'Statistik Evaluasi';
                $showCreate = false;
                break;
            case 'materi':
                $title = 'Materi Pelatihan';
                $createUrl = \App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource::getUrl('create');
                $createLabel = 'New materi pelatihan';
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
