{{-- resources/views/dashboard/pages/tes/index.blade.php --}}
@extends('dashboard.layouts.main')

@php
    /** @var string $mode  pre-test | post-test | monev */
    $isMonev = $mode === 'monev' || $mode === 'survey';

    $pageTitle = match($mode) {
        'pre-test' => 'Pre-Test',
        'post-test' => 'Post-Test',
        default => 'Monitoring & Evaluasi',
    };

    $routePrefix = match($mode) {
        'pre-test' => 'pretest',
        'post-test' => 'posttest',
        default => 'monev',
    };
@endphp

@section('title', $pageTitle)
@section('page-title', $isMonev ? "📊 $pageTitle" : $pageTitle)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($tes as $t)
        <div class="p-5 bg-white rounded-xl shadow-md card-hover border border-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start gap-3">
                <div class="space-y-2">
                    <h3 class="font-bold text-lg text-slate-900">
                        {{ $isMonev ? '📊 ' : '' }}{{ $t->judul }}
                    </h3>
                    <p class="text-slate-600 text-sm">
                        {{ \Illuminate\Support\Str::limit($t->deskripsi ?? '-', 150) }}
                    </p>

                    <div class="text-xs text-slate-500 space-y-1 mt-2">
                        <div>
                            Kompetensi:
                            <strong class="text-slate-800">
                                {{ $t->kompetensi->nama_kompetensi ?? '-' }}
                            </strong>
                        </div>
                        <div>
                            Pelatihan:
                            <strong class="text-slate-800">
                                {{ $t->pelatihan->nama_pelatihan ?? '-' }}
                            </strong>
                        </div>
                        @if(!empty($t->durasi_menit))
                            <div>
                                Durasi:
                                <strong>{{ $t->durasi_menit }} menit</strong>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!empty($t->sub_tipe))
                    <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px]">
                        {{ $isMonev ? '📊 Monev' : ucfirst($t->sub_tipe) }}
                    </span>
                @endif
            </div>

            <div class="mt-4 border-t border-slate-100 pt-3">
                @php
                    $participantKey = session('peserta_id')
                        ? 'peserta_id'
                        : (session('pesertaSurvei_id') ? 'pesertaSurvei_id' : null);

                    $participantId = $participantKey ? session($participantKey) : null;
                @endphp

                @if(!empty($t->__already_done))
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                            ✅ Sudah dikerjakan
                        </span>

                        @if(isset($t->__last_score))
                            <span class="text-emerald-700">
                                Nilai: <strong>{{ $t->__last_score }}</strong>
                            </span>
                        @endif

                        @php
                            $percobaanId = null;
                            if ($participantKey && $participantId) {
                                $query = \App\Models\Percobaan::where('tes_id', $t->id)
                                    ->where($participantKey, $participantId)
                                    ->whereNotNull('waktu_selesai');

                                if (\Illuminate\Support\Facades\Schema::hasColumn('percobaan','tipe')) {
                                    $logicalType = $isMonev ? 'monev' : ($mode === 'pre-test' ? 'pre-test' : 'post-test');
                                    $query->where('tipe', $logicalType);
                                }

                                $percobaanId = $query->latest('waktu_selesai')->value('id');
                            }
                        @endphp

                        @if($percobaanId)
                            <a href="{{ route('dashboard.'.$routePrefix.'.result', ['percobaan' => $percobaanId]) }}"
                               class="text-blue-600 hover:text-blue-700 underline">
                                Lihat hasil
                            </a>
                        @endif
                    </div>
                @else
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <span class="text-slate-500">
                            {{ $isMonev ? 'Belum mengisi monev.' : 'Belum dikerjakan.' }}
                        </span>

                        <a href="{{ route('dashboard.'.$routePrefix.'.start', $t->id) }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg text-white text-xs md:text-sm
                                  {{ $isMonev ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-emerald-600 hover:bg-emerald-700' }} transition">
                            @if($isMonev)
                                📊 Isi Monev
                            @else
                                Mulai {{ $mode === 'pre-test' ? 'Pre-Test' : 'Post-Test' }}
                            @endif
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full p-6 bg-white rounded-xl shadow-sm text-sm text-slate-600">
            <p class="mb-1">Tidak ada data tes tersedia saat ini.</p>
            <p class="text-xs text-slate-500">
                Silakan hubungi admin jika Anda yakin seharusnya ada tes atau monev yang tampil.
            </p>
        </div>
    @endforelse
</div>
@endsection
