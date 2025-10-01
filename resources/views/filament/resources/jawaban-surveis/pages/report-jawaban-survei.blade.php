<x-filament::page>
    <h2 class="text-xl font-bold">Report Jawaban Survei</h2>

    {{-- Widget grafik --}}
    @livewire(\App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerPertanyaanChart::class)

    {{-- Ringkasan tabel --}}
    <div class="mt-6">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-sm font-medium">Pertanyaan</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Jumlah Jawaban</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach(\App\Models\JawabanUser::selectRaw('pertanyaan_id, COUNT(*) as total')
                    ->groupBy('pertanyaan_id')
                    ->with('pertanyaan')
                    ->get() as $row)
                    <tr>
                        <td class="px-4 py-2">{{ $row->pertanyaan->teks_pertanyaan ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $row->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::page>
