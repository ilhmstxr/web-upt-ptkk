<div class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
    @if($asrama)
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    Denah Asrama: {{ $asrama->nama }}
                </h2>
                <p class="text-xs text-gray-500">
                    Khusus: {{ $asrama->gender }} &middot;
                    Total kamar: {{ $asrama->kamars()->count() }}
                </p>
            </div>
        </div>

        @forelse($kamars_by_lantai as $lantai => $kamars)
            <div class="mt-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">
                    Lantai {{ $lantai }}
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($kamars as $kamar)
                        @php
                            $occupied = $kamar->penempatan_asrama_count ?? 0;
                            $capacity = $kamar->total_beds;
                            $available = max($capacity - $occupied, 0);

                            if ($kamar->status === 'Rusak' || $kamar->status === 'Perbaikan') {
                                $color = 'bg-red-100 border-red-300 text-red-800';
                            } elseif ($available <= 0) {
                                $color = 'bg-yellow-100 border-yellow-300 text-yellow-800';
                            } else {
                                $color = 'bg-emerald-50 border-emerald-300 text-emerald-800';
                            }
                        @endphp

                        <div class="border rounded-xl px-3 py-2 text-xs {{ $color }}">
                            <div class="flex justify-between items-baseline">
                                <span class="font-semibold">
                                    Kamar {{ $kamar->nomor_kamar }}
                                </span>
                                <span class="text-[10px] uppercase tracking-wide">
                                    {{ $kamar->status }}
                                </span>
                            </div>
                            <div class="mt-1">
                                <span class="block">
                                    Penghuni: {{ $occupied }} / {{ $capacity }}
                                </span>
                                <span class="block text-[11px] text-gray-700">
                                    Sisa bed: {{ $available }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">
                Belum ada data kamar untuk asrama ini.
            </p>
        @endforelse
    @else
        <p class="text-sm text-gray-500">Asrama tidak ditemukan.</p>
    @endif
</div>
