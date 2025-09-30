@extends('dashboard.layouts.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($tes as $t)
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-lg mb-2">{{ $t->judul }}</h3>
                    <p class="text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($t->deskripsi ?? '-', 140) }}</p>

                    <div class="text-sm text-gray-500 space-y-1">
                        <div>Bidang: <strong class="text-gray-700">{{ $t->bidang->nama_bidang ?? '-' }}</strong></div>
                        <div>Pelatihan: <strong class="text-gray-700">{{ $t->pelatihan->nama_pelatihan ?? '-' }}</strong></div>
                        @if(!empty($t->durasi_menit))
                            <div>Durasi: <strong>{{ $t->durasi_menit }} menit</strong></div>
                        @endif
                    </div>
                </div>

                {{-- Optional small badge for sub_tipe --}}
                <div class="ml-4 text-xs">
                    @if(!empty($t->sub_tipe))
                        <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ ucfirst($t->sub_tipe) }}</span>
                    @endif
                </div>
            </div>

            {{-- Action / status area --}}
            <div class="mt-4">
                @php
                    // Determine participant key used in session (prefer peserta_id then pesertaSurvei_id)
                    $participantKey = session('peserta_id') ? 'peserta_id' : (session('pesertaSurvei_id') ? 'pesertaSurvei_id' : null);
                    $participantId = session($participantKey);
                @endphp

                @if(!empty($t->__already_done))
                    <div class="mt-3 flex items-center gap-3">
                        <span class="px-3 py-2 bg-gray-100 text-gray-700 rounded">Sudah dikerjakan</span>

                        @if(isset($t->__last_score))
                            <span class="ml-1 text-sm text-green-600">Nilai: <strong>{{ $t->__last_score }}</strong></span>
                        @endif

                        {{-- Link ke hasil: cari percobaan terakhir yang selesai untuk peserta ini --}}
                        @php
                            $percobaanId = null;
                            if ($participantKey && $participantId) {
                                $percobaanId = \App\Models\Percobaan::where('tes_id', $t->id)
                                    ->where($participantKey, $participantId)
                                    ->whereNotNull('waktu_selesai')
                                    ->latest('waktu_selesai')
                                    ->value('id');
                            }
                        @endphp

                        @if($percobaanId)
                            <a href="{{ route('dashboard.posttest.result', ['percobaan' => $percobaanId]) }}"
                               class="ml-3 text-sm text-blue-600 underline">
                                Lihat hasil
                            </a>
                        @else
                            {{-- fallback: jika percobaanId tidak ditemukan (sangat jarang), arahkan ke index hasil --}}
                            <a href="{{ route('dashboard.posttest.index') }}" class="ml-3 text-sm text-blue-600 underline">
                                Lihat hasil
                            </a>
                        @endif
                    </div>
                @else
                    <div class="mt-4 flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-500">Belum dikerjakan</div>

                        <a href="{{ route('dashboard.posttest.start', $t->id) }}"
                           class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                           Mulai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full p-6 bg-white rounded-xl shadow-sm">
            <p class="text-gray-500 mb-2">Tidak ada data tes tersedia saat ini.</p>
            <p class="text-gray-500 text-sm">Silakan hubungi admin jika Anda yakin seharusnya ada tes.</p>
        </div>
    @endforelse
</div>
@endsection