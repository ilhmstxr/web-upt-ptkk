<x-filament::page>
    <div class="space-y-1 mb-6">
        @if ($pelatihan)
            <h1 class="text-2xl font-bold">{{ static::getTitle() }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
        @else
            <h1 class="text-2xl font-bold text-danger-600">Error</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
        @endif
    </div>

    @if ($pelatihan)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $types = [
                    'pre-test' => [
                        'label' => 'Pre-Test',
                        'description' => 'Lihat hasil laporan pre-test untuk pelatihan ini.',
                        'icon' => 'heroicon-o-clipboard-document-list',
                        'color' => 'warning',
                    ],
                    'post-test' => [
                        'label' => 'Post-Test',
                        'description' => 'Lihat hasil laporan post-test untuk pelatihan ini.',
                        'icon' => 'heroicon-o-clipboard-document-check',
                        'color' => 'success',
                    ],
                    'survey' => [
                        'label' => 'Survey',
                        'description' => 'Lihat hasil laporan survey kepuasan.',
                        'icon' => 'heroicon-o-chart-pie',
                        'color' => 'primary',
                    ],
                ];
            @endphp

            @foreach ($types as $key => $type)
                <a href="{{ \App\Filament\Resources\JawabanSurveiResource::getUrl('report', ['pelatihanId' => $pelatihan->id, 'tipe' => $key]) }}"
                    class="relative p-6 bg-white rounded-lg shadow dark:bg-gray-800 hover:shadow-lg transition-shadow duration-300 ease-in-out block group">

                    <span @class([
                        'absolute top-4 right-4 p-2 rounded-full',
                        'bg-warning-50 text-warning-600 dark:bg-warning-800/20 dark:text-warning-500' =>
                            $type['color'] === 'warning',
                        'bg-success-50 text-success-600 dark:bg-success-800/20 dark:text-success-500' =>
                            $type['color'] === 'success',
                        'bg-primary-50 text-primary-600 dark:bg-primary-800/20 dark:text-primary-500' =>
                            $type['color'] === 'primary',
                    ])>
                        <x-filament::icon :icon="$type['icon']" class="h-6 w-6" />
                    </span>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $type['label'] }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $type['description'] }}
                        </p>
                    </div>

                    <div class="mt-4">
                        <span class="text-sm font-medium text-primary-600 dark:text-primary-500 group-hover:underline">
                            Lihat Laporan &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-filament::page>
