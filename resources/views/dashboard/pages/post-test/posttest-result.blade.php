{{-- resources/views/dashboard/pages/post-test/posttest-result.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Hasil Post-Test')
@section('page-title', 'Hasil Post-Test')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-xl text-slate-800">Hasil Post-Test</h2>

        <a href="{{ route('dashboard.posttest.index') }}"
           class="text-sm text-blue-600 font-semibold hover:underline">
            ← Kembali ke Daftar Post-Test
        </a>
    </div>

    @if(!empty($percobaan))
        @php
            use App\Models\Percobaan;
            use App\Models\Tes;

            $namaPeserta =
                $percobaan->pesertaSurvei?->nama
                ?? $percobaan->peserta?->nama
                ?? 'Peserta';

            $skor = (float) ($percobaan->skor ?? 0);

            // Passing score: ambil dari tes kalau ada, fallback 70
            $passing = (float) ($percobaan->tes?->passing_score ?? 70);
            $lulus = $skor >= $passing;

            // =========================
            // Ambil PRE-TEST terakhir
            // =========================
            $participantKey = !empty($percobaan->peserta_id)
                ? 'peserta_id'
                : (!empty($percobaan->pesertaSurvei_id) ? 'pesertaSurvei_id' : null);

            $participantId = $participantKey ? $percobaan->{$participantKey} : null;

            $preAttempt = null;
            if ($participantKey && $participantId) {
                $preAttempt = Percobaan::query()
                    ->where($participantKey, $participantId)
                    ->whereNotNull('waktu_selesai')
                    ->whereHas('tes', function($q){
                        $q->where('tipe', 'pre-test');
                    })
                    ->latest('waktu_selesai')
                    ->first();
            }

            $preScore = $preAttempt?->skor;

            // Improvement
            $improvementPoints  = null;
            $improvementPercent = null;

            if ($preScore !== null) {
                $improvementPoints = (float)$skor - (float)$preScore;
                if ((float)$preScore > 0) {
                    $improvementPercent = round(($improvementPoints / (float)$preScore) * 100, 2);
                }
            }

            // Kategori nilai (ubah batas sesuai kebijakan kamu)
            $kategori = match (true) {
                $skor >= 85 => 'Sangat Baik',
                $skor >= 70 => 'Baik',
                $skor >= 55 => 'Cukup',
                default     => 'Perlu Belajar Lagi',
            };

            $kategoriClass = match ($kategori) {
                'Sangat Baik'        => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'Baik'              => 'bg-blue-100 text-blue-700 border-blue-200',
                'Cukup'             => 'bg-amber-100 text-amber-700 border-amber-200',
                default             => 'bg-red-100 text-red-700 border-red-200',
            };

            $improveClass = ($improvementPoints ?? 0) >= 0 ? 'text-emerald-700' : 'text-red-600';
        @endphp

        {{-- Nama --}}
        <div class="mb-6">
            <div class="text-sm text-slate-500">Nama Peserta</div>
            <div class="text-lg font-bold text-slate-800 mt-1">
                {{ $namaPeserta }}
            </div>
        </div>

        {{-- Ringkasan Hasil --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            {{-- Skor Post-Test --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Skor Post-Test
                </div>
                <div class="text-2xl font-bold text-slate-800 mt-2">
                    {{ rtrim(rtrim(number_format($skor, 2), '0'), '.') }}
                </div>
                <div class="text-xs text-slate-500 mt-1">
                    Passing: {{ rtrim(rtrim(number_format($passing, 2), '0'), '.') }}
                </div>
            </div>

            {{-- Kategori --}}
            <div class="p-4 rounded-xl border {{ $kategoriClass }}">
                <div class="text-xs font-semibold uppercase tracking-wide opacity-80">
                    Kategori
                </div>
                <div class="text-xl font-bold mt-2">
                    {{ $kategori }}
                </div>
                <div class="text-xs mt-1 opacity-80">
                    Berdasarkan skor Post-Test
                </div>
            </div>

            {{-- Status Lulus --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Status
                </div>
                <div class="text-xl font-bold mt-2 {{ $lulus ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $lulus ? 'Lulus' : 'Belum Lulus' }}
                </div>
                <div class="text-xs text-slate-500 mt-1">
                    {{ $lulus ? 'Nilai memenuhi batas minimum' : 'Nilai masih di bawah passing' }}
                </div>
            </div>
        </div>

        {{-- Perkembangan dari Pre-Test --}}
        <div class="p-4 rounded-xl border border-slate-100 bg-white mb-6">
            <div class="text-sm font-semibold text-slate-800 mb-3">
                Perkembangan dari Pre-Test
            </div>

            @if($preScore === null)
                <div class="text-sm text-slate-500">
                    Nilai Pre-Test belum ditemukan, jadi perkembangan belum bisa dihitung.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <div class="text-xs text-slate-500 uppercase font-semibold">Skor Pre-Test</div>
                        <div class="text-lg font-bold text-blue-700 mt-1">
                            {{ rtrim(rtrim(number_format((float)$preScore, 2), '0'), '.') }}
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <div class="text-xs text-slate-500 uppercase font-semibold">Peningkatan Nilai</div>
                        <div class="text-lg font-bold mt-1 {{ $improveClass }}">
                            {{ $improvementPoints >= 0 ? '+' : '' }}
                            {{ rtrim(rtrim(number_format($improvementPoints, 2), '0'), '.') }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Selisih Post vs Pre
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <div class="text-xs text-slate-500 uppercase font-semibold">Persentase</div>
                        <div class="text-lg font-bold mt-1 {{ $improveClass }}">
                            @if($improvementPercent !== null)
                                {{ $improvementPercent >= 0 ? '+' : '' }}{{ $improvementPercent }}%
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Relatif ke Pre-Test
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Detail waktu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-white rounded-xl border border-slate-100">
                <div class="text-xs font-semibold text-slate-500">Waktu Mulai</div>
                <div class="text-sm text-slate-800 mt-1">
                    {{ $percobaan->waktu_mulai?->format('d M Y, H:i') ?? '-' }}
                </div>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-100">
                <div class="text-xs font-semibold text-slate-500">Waktu Selesai</div>
                <div class="text-sm text-slate-800 mt-1">
                    {{ $percobaan->waktu_selesai?->format('d M Y, H:i') ?? 'Belum selesai' }}
                </div>
            </div>
        </div>

        {{-- Pesan & Kesan --}}
        @if(!empty($percobaan->pesan_kesan))
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 mb-6">
                <div class="text-sm font-semibold text-slate-800 mb-1">Pesan & Kesan</div>
                <div class="text-sm text-slate-600 italic">
                    “{{ $percobaan->pesan_kesan }}”
                </div>
            </div>
        @endif

        {{-- CTA --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dashboard.home') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Dashboard
            </a>

            <a href="{{ route('dashboard.materi.index') }}"
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                Lanjut ke Materi →
            </a>
        </div>

    @else
        <div class="py-10 text-center text-slate-500">
            Hasil percobaan tidak ditemukan.
        </div>
        <div class="text-center">
            <a href="{{ route('dashboard.home') }}"
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Dashboard
            </a>
        </div>
    @endif

</div>
@endsection
