<x-filament::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Pembagian & Alokasi Kamar</h2>
            <div>
                <x-filament::button wire:click="resetAllAllocations" color="danger" size="sm">Reset Semua Alokasi</x-filament::button>
            </div>
        </div>

        {{-- Kolom: kiri = peserta belum dialokasikan, kanan = daftar asrama & kamar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Peserta belum dialokasikan --}}
            <div class="col-span-1 bg-white p-4 rounded-lg shadow">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Peserta</label>
                <input type="text" wire:model.debounce.300ms="pesertaSearch" placeholder="Cari nama..." class="w-full border rounded p-2" />
                <div class="mt-3 space-y-2">
                    @foreach($this->unallocatedPesertas as $p)
                        <div class="flex items-center justify-between p-2 border rounded">
                            <div>
                                <div class="font-medium">{{ $p->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $p->jenis_kelamin }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-400">ID {{ $p->id }}</span>
                            </div>
                        </div>
                    @endforeach
                    @if($this->unallocatedPesertas->isEmpty())
                        <div class="text-sm text-gray-500">Semua peserta telah dialokasikan atau tidak ada peserta.</div>
                    @endif
                </div>
            </div>

            {{-- Daftar Asrama & Kamar (simplified) --}}
            <div class="col-span-2 bg-white p-4 rounded-lg shadow space-y-4">
                @foreach($asramas as $asrama)
                    <div class="border rounded p-3">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <div class="font-semibold">{{ $asrama->nama }}</div>
                                <div class="text-xs text-gray-500">Khusus: {{ $asrama->gender }}</div>
                            </div>
                            <div class="text-sm text-gray-600">Kamar: {{ $asrama->kamars->count() }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach($asrama->kamars as $kamar)
                                <div class="p-2 border rounded">
                                    <div class="text-sm font-medium">No. {{ $kamar->nomor_kamar }}</div>
                                    <div class="text-xs text-gray-500">Bed: {{ $kamar->total_beds ?? ($kamar->kapasitas ?? '-') }}</div>
                                    <div class="text-xs text-gray-500">Terisi: {{ $kamar->current_occupancy ?? 0 }}</div>

                                    {{-- Simple actions: (1) klik untuk memilih kamar -> contoh memanggil allocatePeserta --}}
                                    <div class="mt-2 flex gap-2">
                                        {{-- NOTE: untuk demo, alokasi memerlukan pesertaId; integrasikan JS front-end untuk pilih peserta dulu --}}
                                        <button class="px-2 py-1 text-xs bg-blue-600 text-white rounded" disabled>Alokasikan (UI belum terintegrasi)</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament::page>
