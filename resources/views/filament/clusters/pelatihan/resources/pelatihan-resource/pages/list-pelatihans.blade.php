<x-filament-panels::page>
    <!-- Header Actions (Create & Export) -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Program Pelatihan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat, kelola, dan pantau seluruh siklus pelatihan dari persiapan hingga pelaporan.</p>
        </div>
        <div class="flex gap-2">
            <x-filament::button tag="a" href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('create') }}">
                <x-heroicon-o-plus class="w-5 h-5 mr-1 inline" /> Buat Pelatihan Baru
            </x-filament::button>
            <x-filament::button color="gray" outlined>
                <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-1 inline" /> Export Data
            </x-filament::button>
        </div>
    </div>

    <!-- Stats Overview -->
    @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PelatihanStatsOverview::class)

    <div x-data="{ view: 'list' }">
        <!-- Filters & View Toggle -->
        <div class="bg-white dark:!bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center mb-6">
            
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" wire:model.live.debounce.500ms="tableSearch" placeholder="Cari pelatihan..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-primary-600 focus:border-primary-600 outline-none transition-all bg-white dark:!bg-gray-700 dark:text-white dark:placeholder-gray-400">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" />
                </div>
                
                <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-primary-600 outline-none bg-white dark:!bg-gray-700 dark:text-white">
                    <option value="">Semua Kategori</option>
                    <option value="teknis">Teknis</option>
                    <option value="manajemen">Manajemen</option>
                    <option value="softskill">Soft Skill</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-primary-600 outline-none bg-white dark:!bg-gray-700 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="pendaftaran">Pendaftaran</option>
                    <option value="berjalan">Berjalan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <div class="flex bg-gray-100 dark:!bg-gray-700 p-1 rounded-lg">
                <button @click="view = 'list'" 
                    :class="{ 'bg-white dark:!bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm': view === 'list', 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200': view !== 'list' }"
                    class="p-2 rounded-md transition-all">
                    <x-heroicon-o-list-bullet class="w-5 h-5" />
                </button>
                <button @click="view = 'grid'" 
                    :class="{ 'bg-white dark:!bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm': view === 'grid', 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200': view !== 'grid' }"
                    class="p-2 rounded-md transition-all">
                    <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Content Area -->
        
        <!-- GRID VIEW -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="view === 'grid'">
            @foreach($this->getTableRecords() as $record)
            <div class="bg-white dark:!bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all group overflow-hidden">
                <div class="relative h-48 bg-gray-200 dark:!bg-gray-700 overflow-hidden">
                    <img src="{{ $record->gambar ? asset('storage/' . $record->gambar) : 'https://via.placeholder.com/400x200' }}" 
                        alt="{{ $record->nama_pelatihan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 right-3 bg-white/90 dark:bg-gray-800/90 backdrop-blur px-2 py-1 rounded text-xs font-bold text-gray-700 dark:text-gray-200 shadow-sm">
                        {{ $record->jenis_program }}
                    </div>
                    <div class="absolute top-3 left-3">
                        @php
                            $statusColor = match($record->status) {
                                'Sedang Berjalan' => 'bg-green-500',
                                'Pendaftaran Buka' => 'bg-blue-500',
                                'Selesai' => 'bg-gray-500',
                                default => 'bg-gray-500'
                            };
                        @endphp
                        <span class="{{ $statusColor }} text-white text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wider shadow-sm">
                            {{ $record->status }}
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-1">
                            {{ $record->nama_pelatihan }}
                        </h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">{{ strip_tags($record->deskripsi) }}</p>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <x-heroicon-o-calendar class="w-4 h-4 mr-2 text-gray-400" />
                            <span>{{ \Carbon\Carbon::parse($record->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <x-heroicon-o-users class="w-4 h-4 mr-2 text-gray-400" />
                            <span>{{ $record->kuota_peserta }} Peserta</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <div class="flex -space-x-2">
                            <!-- Avatars placeholder -->
                            <div class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-gray-200 dark:!bg-gray-700 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400">A</div>
                            <div class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-gray-200 dark:!bg-gray-700 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400">B</div>
                            <div class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-gray-100 dark:!bg-gray-600 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400 font-bold">+12</div>
                        </div>
                        <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view', ['record' => $record]) }}" 
                        class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">Detail <i class="fa-solid fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- LIST VIEW -->
        <div class="bg-white dark:!bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden" x-show="view === 'list'">
            {{ $this->table }}
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $this->getPaginator()->links() }}
        </div>
    </div>
</x-filament-panels::page>
