{{-- resources/views/dashboard/pages/tes/result.blade.php --}}
@extends('dashboard.layouts.main')

@php
    /** @var string $mode */
    $isMonev = $mode === 'monev' || $mode === 'survey';
    $pageTitle = match($mode) {
        'pre-test'  => 'Hasil Pre-Test',
        'post-test' => 'Hasil Post-Test',
        default     => 'Hasil Monev / Survey',
    };

    $cheated = session('cheat_'.$percobaan->id, false);

    $score = $percobaan->skor ?? 0;
@endphp

@section('title', $pageTitle)
@section('page-title', $isMonev ? '📊 '.$pageTitle : $pageTitle)

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in max-w-2xl mx-auto">
    <h2 class="font-bold text-xl mb-4">{{ $pageTitle }}</h2>

    @if (!empty($percobaan))
        <div class="mb-4">
            <h3 class="font-semibold text-sm text-slate-700 mb-1">Nama Peserta</h3>
            <p class="text-slate-900 text-lg font-bold">
                {{ $percobaan->pesertaSurvei->nama
                    ?? $percobaan->peserta->nama
                    ?? 'Tidak ada nama' }}
            </p>
        </div>

        {{-- Peringatan kecurangan --}}
        @if($cheated)
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs text-red-800">
                ⚠️ Sistem mendeteksi aktivitas yang melanggar tata tertib (pindah tab/jendela atau mengecilkan layar).
                Sesuai aturan, nilai akhir untuk percobaan ini <strong>tidak ditampilkan</strong>.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-slate-50 rounded-lg shadow-sm">
                <h3 class="font-semibold text-xs text-slate-600">Skor</h3>
                <p class="text-2xl font-bold text-slate-900 mt-1">
                    @if($cheated)
                        —
                    @else
                        {{ $score }}
                    @endif
                </p>
            </div>

            <div class="p-4 bg-slate-50 rounded-lg shadow-sm">
                <h3 class="font-semibold text-xs text-slate-600">Waktu Mulai</h3>
                <p class="text-xs text-slate-800 mt-1">
                    {{ $percobaan->waktu_mulai ?? '-' }}
                </p>
            </div>

            <div class="p-4 bg-slate-50 rounded-lg shadow-sm">
                <h3 class="font-semibold text-xs text-slate-600">Waktu Selesai</h3>
                <p class="text-xs text-slate-800 mt-1">
                    {{ $percobaan->waktu_selesai ?? 'Belum selesai' }}
                </p>
            </div>
        </div>

        {{-- KOMENTAR KHUSUS PRE-TEST --}}
        @if(!$cheated && $mode === 'pre-test')
            @php
                if ($score >= 75) {
                    $labelPre   = 'di atas rata-rata, sangat baik 🎉';
                    $extraPre   = 'Pertahankan ya! Kamu sudah punya bekal yang kuat sebelum mengikuti materi.';
                    $badgeClass = 'bg-emerald-50 text-emerald-700';
                } elseif ($score >= 50) {
                    $labelPre   = 'cukup, masih bisa ditingkatkan 👍';
                    $extraPre   = 'Kamu sudah punya dasar yang lumayan, materi pelatihan akan membantu menguatkan konsep.';
                    $badgeClass = 'bg-amber-50 text-amber-700';
                } else {
                    $labelPre   = 'masih perlu banyak belajar 💪';
                    $extraPre   = 'Tidak apa-apa, justru ini kesempatan bagus buat belajar dari awal. Semangat, kamu pasti bisa!';
                    $badgeClass = 'bg-red-50 text-red-700';
                }
            @endphp

            <div class="mb-6 p-4 rounded-lg border text-xs md:text-sm {{ $badgeClass }}">
                <div class="font-semibold mb-1">
                    Selamat, pre-test kamu selesai! Nilai kamu: <span class="underline">{{ $score }}</span>,
                    yang berarti kamu <span class="font-bold">{{ $labelPre }}</span>
                </div>
                <p>{{ $extraPre }}</p>
            </div>
        @endif

        {{-- STATUS KELULUSAN (untuk pre/post, bukan monev) --}}
        @if(!$isMonev && !$cheated)
            <div class="mb-6">
                <h3 class="font-semibold text-xs text-slate-600 mb-1">Status Kelulusan</h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs
                             {{ ($percobaan->lulus ?? false) ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                    {{ ($percobaan->lulus ?? false) ? '✅ Lulus' : '❌ Tidak Lulus' }}
                </span>
            </div>
        @elseif($isMonev)
            <p class="text-xs text-slate-600 mb-6">
                Terima kasih telah mengisi monev 📊. Masukan Anda membantu peningkatan program.
            </p>
        @endif

        {{-- PERBANDINGAN PRE vs POST (KHUSUS POST-TEST) --}}
        @if(!$cheated && $mode === 'post-test')
            @php
                // variabel dari controller, kasih default supaya tidak error
                $preScore           = $preScore           ?? null;
                $improvementPoints  = $improvementPoints  ?? null;
                $improvementPercent = $improvementPercent ?? null;
            @endphp

            @if(!is_null($preScore))
                <div class="mt-6 p-4 rounded-lg bg-slate-50 border border-slate-200 text-xs md:text-sm">
                    <h3 class="font-semibold text-slate-800 mb-2">
                        📈 Perbandingan Pre-Test dan Post-Test
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-slate-500">Nilai Pre-Test</p>
                            <p class="text-lg font-bold text-slate-900">{{ $preScore }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-slate-500">Nilai Post-Test</p>
                            <p class="text-lg font-bold text-slate-900">{{ $score }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-slate-500">Kenaikan (poin)</p>
                            <p class="text-lg font-bold text-emerald-700">
                                {{ $improvementPoints >= 0 ? '+' : '' }}{{ $improvementPoints }}
                            </p>
                        </div>
                    </div>

                    @if(!is_null($improvementPercent))
                        <p class="mb-1">
                            Kenaikan relatif terhadap nilai pre-test:
                            <span class="font-semibold text-emerald-700">
                                {{ $improvementPercent >= 0 ? '+' : '' }}{{ $improvementPercent }}%
                            </span>
                        </p>
                        <p class="text-[11px] text-slate-500">
                            Rumus yang digunakan:
                            <code class="bg-slate-100 px-1 py-[2px] rounded">
                                ((Nilai Post-Test - Nilai Pre-Test) / Nilai Pre-Test) × 100%
                            </code>
                        </p>
                    @else
                        <p class="text-[11px] text-slate-500">
                            Catatan: nilai pre-test adalah 0, sehingga persentase kenaikan relatif
                            tidak dihitung. Namun, selisih poin tetap menunjukkan peningkatan dari
                            <strong>0</strong> ke <strong>{{ $score }}</strong>.
                        </p>
                    @endif
                </div>
            @else
                <p class="mt-4 text-[11px] text-slate-500">
                    Belum ditemukan data pre-test untuk peserta ini, sehingga kenaikan nilai tidak dapat dihitung.
                </p>
            @endif
        @endif

        <a href="{{ route('dashboard.home') }}"
           class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs md:text-sm">
            Kembali ke Dashboard
        </a>
    @else
        <p class="text-slate-500 text-sm">Hasil percobaan tidak ditemukan.</p>
        <a href="{{ route('dashboard.home') }}"
           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs md:text-sm">
            Kembali ke Dashboard
        </a>
    @endif
</div>
@endsection
