<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($this->pelatihan as $p)
                <a href="{{ route('filament.admin.pages.detail-pelatihan', ['record' => $p]) }}"
                    class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $p->nama_pelatihan }}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        Timeline: {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                    </p>
                    <div
                        class="mt-4 flex justify-between items-center text-sm font-medium text-gray-500 dark:text-gray-400">
                        <span>Total Peserta: {{ $p->peserta->count() }}</span>
                        <!-- Tambahkan data lain jika perlu -->
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
