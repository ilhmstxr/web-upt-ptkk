{{-- WRAPPER FULL LAYAR, KONTEN DI TENGAH --}}
<div class="w-full flex justify-center px-4 md:px-8 py-6">
    {{-- CARD UTAMA DIBUAT GEDE + CENTER --}}
    <div class="w-full max-w-6xl bg-gradient-to-br from-indigo-50 via-pink-50 to-yellow-50 rounded-3xl shadow-lg p-6 space-y-5 border-2 border-indigo-200">

        @if($asrama)
            {{-- HEADER ASRAMA --}}
            <div class="flex justify-between items-center mb-4 p-5 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white shadow-md">
                <div>
                    <h2 class="text-2xl font-extrabold tracking-wide drop-shadow">
                        Denah Asrama: {{ $asrama->nama }}
                    </h2>
                    <p class="text-sm font-semibold text-white/90 mt-1">
                        Khusus: <span class="font-bold">{{ $asrama->jenis_kelamin }}</span>
                        &middot; Total kamar: <span class="font-bold">{{ $asrama->kamars()->count() }}</span>
                    </p>
                </div>
            </div>

            @forelse($kamars_by_lantai as $lantai => $kamars)
                <div class="mt-3">
                    {{-- LABEL LANTAI --}}
                    <h3 class="inline-block text-base font-extrabold px-5 py-2 rounded-xl
                               bg-gradient-to-r from-amber-400 via-orange-400 to-rose-400
                               text-white shadow-md mb-3">
                        Lantai {{ $lantai }}
                    </h3>

                    {{-- GRID KAMAR: FULL LEBAR CARD --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($kamars as $kamar)
                            @php
                                $occupied  = $kamar->penempatan_asrama_count ?? 0;
                                $capacity  = $kamar->total_beds;
                                $available = max($capacity - $occupied, 0);

                                if ($kamar->status === 'Rusak' || $kamar->status === 'Perbaikan') {
                                    $color = 'bg-gradient-to-br from-red-500 to-rose-500 border-red-700 text-white';
                                    $badge = 'bg-red-200 text-red-900';
                                } elseif ($available <= 0) {
                                    $color = 'bg-gradient-to-br from-yellow-400 to-amber-500 border-yellow-700 text-white';
                                    $badge = 'bg-yellow-200 text-yellow-900';
                                } else {
                                    $color = 'bg-gradient-to-br from-emerald-500 to-teal-500 border-emerald-700 text-white';
                                    $badge = 'bg-emerald-200 text-emerald-900';
                                }
                            @endphp

                            <div class="border-2 rounded-2xl px-4 py-4 text-sm shadow-md {{ $color }}">
                                <div class="flex justify-between items-center">
                                    <span class="font-extrabold tracking-wide text-base">
                                        Kamar {{ $kamar->nomor_kamar }}
                                    </span>
                                    <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-1 rounded-lg {{ $badge }}">
                                        {{ $kamar->status }}
                                    </span>
                                </div>

                                <div class="mt-2 space-y-1 font-semibold">
                                    <span class="block">
                                        Penghuni: {{ $occupied }} / {{ $capacity }}
                                    </span>
                                    <span class="block text-[12px] text-white/95">
                                        Sisa bed: {{ $available }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-4 rounded-2xl bg-gradient-to-r from-fuchsia-200 to-pink-200 text-fuchsia-900 font-semibold shadow">
                    Belum ada data kamar untuk asrama ini.
                </div>
            @endforelse
        @else
            <div class="p-4 rounded-2xl bg-gradient-to-r from-red-200 to-rose-200 text-red-900 font-semibold shadow">
                Asrama tidak ditemukan.
            </div>
        @endif

    </div>
</div>
