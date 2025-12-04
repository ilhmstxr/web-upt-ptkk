<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-bold mb-4">Denah Asrama</h2>

        @if($kamars_by_lantai && $kamars_by_lantai->isNotEmpty())
            <div class="space-y-6">
                @foreach($kamars_by_lantai as $lantai => $kamars)
                    <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-900">
                        <h3 class="font-semibold text-md mb-3">Lantai {{ $lantai }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($kamars as $kamar)
                                @php
                                    $color = match($kamar->status) {
                                        'Tersedia' => 'bg-green-100 text-green-800 border-green-300',
                                        'Penuh' => 'bg-red-100 text-red-800 border-red-300',
                                        'Rusak' => 'bg-gray-100 text-gray-800 border-gray-300',
                                        'Perbaikan' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        default => 'bg-white text-gray-800 border-gray-200',
                                    };
                                @endphp
                                <div class="border {{ $color }} p-3 rounded text-center shadow-sm">
                                    <div class="font-bold text-lg">{{ $kamar->nomor_kamar }}</div>
                                    <div class="text-xs mt-1">{{ $kamar->status }}</div>
                                    <div class="text-xs mt-1 font-mono">
                                        {{ $kamar->available_beds }} / {{ $kamar->total_beds }} Bed
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-4">
                Belum ada data kamar untuk asrama ini.
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
