<x-filament-widgets::widget>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Total Pelatihan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPelatihan }}</h3>
                </div>
                <div class="p-2 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-lg">
                    <x-heroicon-o-rectangle-stack class="w-6 h-6" />
                </div>
            </div>

        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Sedang Berjalan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $sedangBerjalan }}</h3>
                </div>
                <div class="p-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-lg">
                    <x-heroicon-o-play class="w-6 h-6" />
                </div>
            </div>

        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Total Peserta</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPeserta }}</h3>
                </div>
                <div class="p-2 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 rounded-lg">
                    <x-heroicon-o-users class="w-6 h-6" />
                </div>
            </div>

        </div>


    </div>
</x-filament-widgets::widget>
