{{-- resources/views/dashboard/pages/pre-test/pretest.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($tes as $t)
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover flex flex-col">
            <div class="flex justify-between items-start gap-3">
                <div class="min-w-0">
                    <h3 class="font-bold text-lg mb-2 truncate">{{ $t->judul }}</h3>

                    <p class="text-gray-600 mb-3">
                        {{ \Illuminate\Support\Str::limit($t->deskripsi ?? '-', 140) }}
                    </p>

                    <div class="text-sm text-gray-500 space-y-1">
                        <div>
                            Bidang:
                            <strong class="text-gray-700">
                                {{ $t->bidang->nama_bidang ?? '-' }}
                            </strong>
                        </div>

                        <div>
                            Pelatihan:
                            <strong class="text-gray-700">
                                {{ $t->pelatihan->nama_pelatihan ?? '-' }}
                            </strong>
                        </div>

                        @if(!empty($t->durasi_menit))
                            <div>Durasi: <strong>{{ $t->durasi_menit }} menit</strong></div>
                        @endif
                    </div>
                </div>

                {{-- Badge tipe/sub_tipe --}}
                <div class="text-xs shrink-0">
                    @if(!empty($t->sub_tipe))
                        <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                            {{ ucfirst($t->sub_tipe) }}
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                            Pre-Test
                        </span>
                    @endif
                </div>
            </div>

            {{-- Status / Action --}}
            <div class="mt-4 pt-4 border-t border-slate-100">
                @php
                    $participantKey = session('peserta_id')
                        ? 'peserta_id'
                        : (session('pesertaSurvei_id') ? 'pesertaSurvei_id' : null);
                    $participantId = $participantKey ? session($participantKey) : null;

                    // cari percobaan selesai terakhir buat link hasil
                    $percobaanDoneId = null;
                    if (!empty($t->__already_done) && $participantKey && $participantId) {
                        $percobaanDoneId = \App\Models\Percobaan::where('tes_id', $t->id)
                            ->where($participantKey, $participantId)
                            ->whereNotNull('waktu_selesai')
                            ->latest('waktu_selesai')
                            ->value('id');
                    }
                @endphp

                {{-- 1) SUDAH SELESAI --}}
                @if(!empty($t->__already_done))
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-semibold">
                            ✅ Sudah selesai
                        </span>

                        @if(isset($t->__last_score))
                            <span class="text-sm text-emerald-700">
                                Nilai: <strong>{{ $t->__last_score }}</strong>
                            </span>
                        @endif

                        @if($percobaanDoneId)
                            <a href="{{ route('dashboard.pretest.result', ['percobaan' => $percobaanDoneId]) }}"
                               class="text-sm text-blue-600 underline ml-1">
                                Lihat hasil
                            </a>
                        @endif
                    </div>

                {{-- 2) SEDANG BERJALAN --}}
                @elseif(!empty($t->__running_id))
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-amber-600 font-semibold">
                            ⏳ Tes sedang berjalan
                        </div>

                        <a href="{{ route('dashboard.pretest.show', $t->id).'?percobaan='.$t->__running_id }}"
                           class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm">
                            Lanjutkan
                        </a>
                    </div>

                {{-- 3) BELUM MULAI --}}
                @else
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-500">Belum dikerjakan</div>

                        <a href="{{ route('dashboard.pretest.start', $t->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                            Mulai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full p-6 bg-white rounded-xl shadow-sm text-center">
            <p class="text-gray-500 mb-2">Tidak ada data Pre-Test tersedia saat ini.</p>
            <p class="text-gray-500 text-sm">Silakan hubungi admin jika Pre-Test seharusnya ada.</p>
        </div>
    @endforelse
</div>
@endsection
