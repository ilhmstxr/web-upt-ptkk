{{-- resources/views/dashboard/pages/pre-test/pretest.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test')

@section('content')
@php
    $tesFiltered = $tes->filter(function($t){
        $okPelatihan  = !session('pelatihan_id') || $t->pelatihan_id == session('pelatihan_id');

        $okKompetensi = !session('kompetensi_id')
            || $t->kompetensi_id == session('kompetensi_id');

        $okTipe = ($t->tipe ?? null) === 'pre-test';

        return $okPelatihan && $okKompetensi && $okTipe;
    });

    $t = $tesFiltered->first();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    @if($t)
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover">

            <div class="flex justify-between items-start gap-4">
                <div class="min-w-0">
                    <h3 class="font-bold text-lg mb-2 truncate">{{ $t->judul }}</h3>
                    <p class="text-gray-600 mb-3">
                        {{ \Illuminate\Support\Str::limit($t->deskripsi ?? '-', 140) }}
                    </p>

                    <div class="text-sm text-gray-500 space-y-1">
                        <div>Pelatihan:
                            <strong class="text-gray-700">
                                {{ $t->pelatihan->nama_pelatihan ?? '-' }}
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
                        Pre-Test
                    </span>
                @endif
            </div>

            {{-- STATUS AREA --}}
            <div class="mt-5 border-t pt-4">

                @if($t->__already_done)
                    {{-- SUDAH SELESAI --}}
                    <div class="flex flex-col gap-2">
                        <div class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg w-fit">
                            ✅ Sudah selesai dikerjakan
                        </div>

                        <div class="text-sm text-slate-700">
                            Nilai Pre-Test:
                            <span class="font-bold text-emerald-700">{{ $t->__last_score ?? '-' }}</span>
                        </div>

                        {{-- OPTIONAL: tampilkan nilai post-test bila tersedia untuk perbandingan --}}
                        @if(!empty($t->__post_score))
                            <div class="text-sm text-slate-700">
                                Nilai Post-Test:
                                <span class="font-bold text-blue-700">{{ $t->__post_score ?? '-' }}</span>
                            </div>
                        @endif

                        {{-- improvement tetap dipakai --}}
                        @if($t->__improvement_points !== null)
                            <div class="text-sm">
                                Peningkatan:
                                <span class="font-semibold {{ $t->__improvement_points >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $t->__improvement_points >= 0 ? '+' : '' }}{{ $t->__improvement_points }}
                                </span>
                                @if($t->__improvement_percent !== null)
                                    <span class="text-slate-500">
                                        ({{ $t->__improvement_percent >= 0 ? '+' : '' }}{{ $t->__improvement_percent }}%)
                                    </span>
                                @endif
                            </div>
                        @endif

                        <div class="text-sm">
                            Status:
                            @if($t->__above_avg)
                                <span class="font-semibold text-emerald-700">Aman / di atas passing</span>
                            @else
                                <span class="font-semibold text-amber-700">Di bawah passing</span>
                            @endif
                        </div>

                        @if(!empty($t->__done_id))
                            <a href="{{ route('dashboard.pretest.result', ['percobaan' => $t->__done_id]) }}"
                               class="text-sm text-blue-600 font-semibold underline w-fit">
                                Lihat hasil detail →
                            </a>
                        @endif
                    </div>

                @elseif($t->__running_id)
                    {{-- SUDAH MULAI TAPI BELUM SELESAI --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-slate-600">
                            ⏳ Tes sedang berjalan
                        </div>

                        <a href="{{ route('dashboard.pretest.show', $t->id).'?percobaan='.$t->__running_id }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm font-semibold">
                            Lanjutkan
                        </a>
                    </div>

                @else
                    {{-- BELUM DIBUKA --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-slate-600">Belum dikerjakan</div>

                        <a href="{{ route('dashboard.pretest.start', $t->id) }}"
                           class="inline-block px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition text-sm font-semibold">
                           Mulai
                        </a>
                    </div>
                @endif

            </div>
        </div>

    @else
        <div class="col-span-full p-6 bg-white rounded-xl shadow-sm text-center">
            <p class="text-gray-500 mb-2">Tidak ada Pre-Test untuk pelatihan/kompetensi Anda.</p>
            <p class="text-gray-500 text-sm">Silakan hubungi admin jika seharusnya ada tes.</p>
        </div>
    @endif

</div>
@endsection
