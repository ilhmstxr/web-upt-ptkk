@extends('dashboard.layouts.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test')

@section('content')
@php
    // ✅ biar gak error kalau $tes null / bukan collection
    $tes = $tes ?? collect();
    if (!($tes instanceof \Illuminate\Support\Collection)) {
        $tes = collect($tes);
    }

    $tesFiltered = $tes->filter(function($t){
        $sessionPelatihanId  = session('pelatihan_id');
        $sessionKompetensiId = session('kompetensi_id');

        $okPelatihan = empty($sessionPelatihanId)
            || (int)($t->pelatihan_id ?? 0) === (int)$sessionPelatihanId;

        $kompetensiResolvedId = (int) (
            $t->kompetensi_id
            ?? $t->kompetensi_pelatihan_id
            ?? 0
        );

        $okKompetensi = empty($sessionKompetensiId)
            || $kompetensiResolvedId === (int)$sessionKompetensiId;

        $okTipe = ((string)($t->tipe ?? '')) === 'post-test';

        return $okPelatihan && $okKompetensi && $okTipe;
    });

    $t = $tesFiltered->first();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    @if($t)
        @php
            $alreadyDone = (bool) data_get($t, '__already_done', false);
            $lastScore   = data_get($t, '__last_score', null); // ✅ ini nilai POST
            $runningId   = data_get($t, '__running_id', null);
            $doneId      = data_get($t, '__done_id', null);
            $aboveAvg    = (bool) data_get($t, '__above_avg', false);

            // ✅ data dari controller posttest()
            $preScore    = data_get($t, '__pre_score', null);
            $imprPoints  = data_get($t, '__improvement_points', null);
            $imprPercent = data_get($t, '__improvement_percent', null);

            $pelatihanNama = optional($t->pelatihan)->nama_pelatihan ?? '-';
        @endphp

        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover">

            <div class="flex justify-between items-start gap-4">
                <div class="min-w-0">
                    <h3 class="font-bold text-lg mb-2 truncate">{{ $t->judul ?? '-' }}</h3>
                    <p class="text-gray-600 mb-3">
                        {!! \Illuminate\Support\Str::limit($t->deskripsi ?? '-', 140) !!}
                    </p>

                    <div class="text-sm text-gray-500 space-y-1">
                        <div>Pelatihan:
                            <strong class="text-gray-700">
                                {{ $pelatihanNama }}
                            </strong>
                        </div>
                        @if(!empty($t->durasi_menit))
                            <div>Durasi: <strong>{{ $t->durasi_menit }} menit</strong></div>
                        @endif
                    </div>
                </div>

                @if(!empty($t->sub_tipe))
                    <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                        {{ ucfirst($t->sub_tipe) }}
                    </span>
                @else
                    <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                        Post-Test
                    </span>
                @endif
            </div>

            {{-- STATUS AREA --}}
            <div class="mt-5 border-t pt-4">

                @if($alreadyDone)
                    {{-- SUDAH SELESAI --}}
                    <div class="flex flex-col gap-2">
                        <div class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg w-fit">
                            ✅ Sudah selesai dikerjakan
                        </div>

                        {{-- ✅ NILAI POST + KENAIKAN (DI SEBELAHNYA) --}}
                        <div class="text-sm text-slate-700">
                            Nilai Post-Test:
                            <span class="font-bold text-blue-700">{{ $lastScore ?? '-' }}</span>

                            @if($imprPoints !== null)
                                <span class="ml-2 text-xs px-2 py-1 rounded-full
                                    {{ $imprPoints >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $imprPoints >= 0 ? '+' : '' }}{{ $imprPoints }} poin
                                    @if($imprPercent !== null)
                                        ({{ $imprPercent >= 0 ? '+' : '' }}{{ $imprPercent }}%)
                                    @endif
                                </span>
                            @endif
                        </div>

                        {{-- Optional: tampilkan pre-score biar jelas pembandingnya --}}
                        @if($preScore !== null)
                            <div class="text-sm text-slate-700">
                                Nilai Pre-Test:
                                <span class="font-bold text-emerald-700">{{ $preScore }}</span>
                            </div>
                        @endif

                        <div class="text-sm">
                            Status:
                            @if($aboveAvg)
                                <span class="font-semibold text-emerald-700">Aman / di atas passing</span>
                            @else
                                <span class="font-semibold text-amber-700">Di bawah passing</span>
                            @endif
                        </div>

                        @if(!empty($doneId))
                            <a href="{{ route('dashboard.posttest.result', ['percobaan' => $doneId]) }}"
                               class="text-sm text-blue-600 font-semibold underline w-fit">
                                Lihat hasil detail →
                            </a>
                        @endif
                    </div>

                @elseif(!empty($runningId))
                    {{-- SUDAH MULAI TAPI BELUM SELESAI --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-slate-600">
                            ⏳ Tes sedang berjalan
                        </div>

                        <a href="{{ route('dashboard.posttest.show', $t->id).'?percobaan='.$runningId }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm font-semibold">
                            Lanjutkan
                        </a>
                    </div>

                @else
                    {{-- BELUM DIBUKA --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-slate-600">Belum dikerjakan</div>

                        <a href="{{ route('dashboard.posttest.start', $t->id) }}"
                           class="inline-block px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition text-sm font-semibold">
                           Mulai
                        </a>
                    </div>
                @endif

            </div>
        </div>

    @else
        <div class="col-span-full p-6 bg-white rounded-xl shadow-sm text-center">
            <p class="text-gray-500 mb-2">Tidak ada Post-Test untuk pelatihan/kompetensi Anda.</p>
            <p class="text-gray-500 text-sm">Silakan hubungi admin jika seharusnya ada tes.</p>
        </div>
    @endif

</div>
@endsection
