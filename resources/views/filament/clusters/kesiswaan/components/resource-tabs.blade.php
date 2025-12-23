{{-- Resource Tabs Component for Cluster Kesiswaan --}}
<div class="mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8">
            {{-- Data Instansi --}}
            <a 
                href="{{ \App\Filament\Clusters\Kesiswaan\Resources\InstansiResource::getUrl('index') }}"
                class="{{ $activeTab === 'instansi' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-building-office class="w-5 h-5" />
                Data Instansi
            </a>

            {{-- Instrukturs --}}
            <a 
                href="{{ \App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource::getUrl('index') }}"
                class="{{ $activeTab === 'instruktur' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-academic-cap class="w-5 h-5" />
                Instrukturs
            </a>

            {{-- Peserta --}}
            <a 
                href="{{ \App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource::getUrl('index') }}"
                class="{{ $activeTab === 'peserta' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
            >
                <x-heroicon-o-users class="w-5 h-5" />
                Peserta
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
        $showExport = false;

        switch($activeTab) {
            case 'instansi':
                $title = 'Instansis';
                $createUrl = \App\Filament\Clusters\Kesiswaan\Resources\InstansiResource::getUrl('create'); // Note: Instansi uses slideOver? If so, URL might be index but action is handled differently. 
                // Wait, if it uses slideOver, the button must trigger the action, not a link.
                // Filament Actions can be rendered in blade but it's complex.
                // If the original used `Actions\CreateAction::make()->slideOver()`, then it's an action on the page.
                // The header view replaces the header, so we lose the default action button location.
                // We can use `{{ $this->createAction }}` if we define it in the Livewire component?
                // Or we can just use a link to 'create' page if slideOver is not strictly required, OR we can try to trigger the action.
                // For now, I'll use URL. If slideOver is critical, I might need to adjust.
                $createLabel = 'New instansi';
                break;
            case 'instruktur':
                $title = 'Instrukturs';
                $createUrl = \App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource::getUrl('create');
                $createLabel = 'New instruktur';
                break;
            case 'peserta':
                $title = 'Pendaftaran Pelatihan';
                $createUrl = \App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource::getUrl('create');
                $createLabel = 'New pendaftaran pelatihan';
                $showExport = true;
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

            @if($showExport)
                 <x-filament::button
                    wire:click="export_excel" 
                    color="success"
                    icon="heroicon-m-arrow-down-tray"
                >
                    Export Excel
                </x-filament::button>
            @endif
        </div>
    </div>
</div>
