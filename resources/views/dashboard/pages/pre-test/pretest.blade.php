@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($tes as $t)
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-bold text-lg mb-1">{{ $t->judul }}</h3>
                    <p class="text-sm text-gray-500 mb-2">
                        Kompetensi: {{ $t->kompetensi->nama_kompetensi ?? '-' }}
                        @if(!empty($t->pelatihan->nama_pelatihan))
                            &middot; <span class="text-gray-400">Pelatihan: {{ $t->pelatihan->nama_pelatihan }}</span>
                        @endif
                    </p>
                    <p class="text-gray-600 mb-3">{{ Str::limit($t->deskripsi, 200) }}</p>

                    <div class="text-xs text-slate-500">
                        <span class="mr-3">Durasi: {{ $t->durasi_menit ?? '-' }} menit</span>
                        <span class="mr-3">Soal: {{ $t->pertanyaan()->count() }}</span>
                        <span>Attempts: {{ $t->__attempts ?? 0 }}</span>
                    </div>
                </div>

                <div class="text-right">
                    {{-- Jika sedang running -> lanjutkan (ke route show dengan percobaan running) --}}
                    @if(!empty($t->__running))
                        <div class="mb-2 text-sm text-amber-600 font-semibold">Sedang Berjalan</div>
                        <a href="{{ route('dashboard.pretest.show', ['tes' => $t->id, 'percobaan' => $t->__running_id]) }}"
                           class="inline-block px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm">
                            Lanjutkan
                        </a>

                    {{-- Sudah selesai -> tampilkan skor & link hasil --}}
                    @elseif(!empty($t->__already_done))
                        <div class="mb-2 text-sm text-green-600 font-semibold">Sudah Selesai</div>
                        <div class="text-sm text-slate-700 font-bold mb-2">{{ $t->__last_score ?? '-' }} <span class="text-xs text-slate-500">/100</span></div>
                        <a href="{{ route('dashboard.pretest.result', $t->__last_percobaan_id) }}"
                           class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                            Lihat Hasil
                        </a>

                    {{-- Belum dikerjakan -> jika allowed, tombol mulai --}}
                    @else
                        <a href="{{ route('dashboard.pretest.start', $t->id) }}"
                           class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                           Kerjakan
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <p class="text-gray-500">Tidak ada data tes tersedia saat ini.</p>
        </div>
    @endforelse
</div>
@endsection
