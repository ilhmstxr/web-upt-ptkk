<x-filament-widgets::widget>
    <div class="flex flex-col gap-6 p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800">Waktu Tunggu & Rentang Gaji</h3>
            <select class="text-xs border border-gray-300 rounded px-2 py-1 bg-white">
                <option>Tahun {{ date('Y') }}</option>
                <option>Tahun {{ date('Y') - 1 }}</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div class="p-4 bg-primary-50 rounded-lg border border-primary-100">
                <p class="text-xs text-primary-600 font-bold uppercase mb-2">Rata-rata Waktu Tunggu</p>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-bold text-primary-800">{{ $avgWaitingPeriod }}</span>
                    <span class="text-sm text-primary-600 mb-1">Bulan</span>
                </div>
                <p class="text-xs text-primary-500 mt-2">Dari lulus pelatihan hingga dapat kerja.</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                <p class="text-xs text-green-600 font-bold uppercase mb-2">Rata-rata Gaji Pertama</p>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-bold text-green-800">{{ $avgSalary }}</span>
                    <span class="text-sm text-green-600 mb-1">Juta/Bulan</span>
                </div>
                <p class="text-xs text-green-500 mt-2">Berdasarkan data responden.</p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
