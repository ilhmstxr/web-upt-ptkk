{{-- Lokasi: resources/views/filament/widgets/custom/pengaturan-tes-widget.blade.php --}}

<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Judul Widget --}}
        <x-slot name="heading">
            PENGATURAN TES & SURVEI PELATIHAN
        </x-slot>

        {{-- Konten --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Pre-Test -->
            <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 space-y-2 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">PRE-TEST</h3>
                        {{-- Logika Badge Sederhana --}}
                        <x-filament::badge :color="\Illuminate\Support\Arr::get([
                            'BELUM' => 'gray',
                            'DIBUKA' => 'success',
                            'DITUTUP' => 'danger',
                        ], $this->preTestData['status'], 'gray')">
                            {{ $this->preTestData['status'] }}
                        </x-filament::badge>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Soal: {{ $this->preTestData['soal'] }}</p>
                </div>
                <div class="pt-2">
                    <x-filament::button tag="a" :href="$this->preTestData['url']" color="gray" size="sm" class="w-full">
                        {{ $this->preTestData['label'] }}
                    </x-filament::button>
                </div>
            </div>

            <!-- Card 2: Post-Test -->
            <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 space-y-2 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">POST-TEST</h3>
                        <x-filament::badge :color="\Illuminate\Support\Arr::get([
                            'BELUM' => 'gray',
                            'DIBUKA' => 'success',
                            'DITUTUP' => 'danger',
                        ], $this->postTestData['status'], 'gray')">
                            {{ $this->postTestData['status'] }}
                        </x-filament::badge>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Soal: {{ $this->postTestData['soal'] }}</p>
                </div>
                <div class="pt-2">
                    <x-filament::button tag="a" :href="$this->postTestData['url']" color="gray" size="sm" class="w-full">
                        {{ $this->postTestData['label'] }}
                    </x-filament::button>
                </div>
            </div>

            <!-- Card 3: Survei Monev -->
            <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 space-y-2 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">SURVEI MONEV</h3>
                        <x-filament::badge :color="\Illuminate\Support\Arr::get([
                            'BELUM' => 'gray',
                            'DIBUKA' => 'success',
                            'DITUTUP' => 'danger',
                        ], $this->surveiData['status'], 'gray')">
                            {{ $this->surveiData['status'] }}
                        </x-filament::badge>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pertanyaan: {{ $this->surveiData['pertanyaan'] }}</p>
                </div>
                <div class="pt-2">
                    <x-filament::button tag="a" :href="$this->surveiData['url']" color="gray" size="sm" class="w-full">
                        {{ $this->surveiData['label'] }}
                    </x-filament::button>
                </div>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>

