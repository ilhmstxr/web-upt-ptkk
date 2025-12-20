@extends('dashboard.layouts.main')
@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')
@php
    $pesertaAktif     = $pesertaAktif ?? null;

    $preTestDone      = $preTestDone      ?? false;
    $postTestDone     = $postTestDone     ?? false;
    $monevTestDone    = $moneTestDone        ?? false;

    $materiDoneCount  = $materiDoneCount  ?? 0;
    $totalMateri      = $totalMateri      ?? 0;

    $materiProgress   = $totalMateri > 0
        ? (int) floor(($materiDoneCount / $totalMateri) * 100)
        : 0;

    $preTestScore     = $preTestScore     ?? null;
    $postTestScore    = $postTestScore    ?? null;
    $monevTestScore       = $monevTestScore       ?? null;

    $preTestAttempts  = $preTestAttempts  ?? 0;
    $postTestAttempts = $postTestAttempts ?? 0;
    $monevTestAttempts    = $monevTestAttempts    ?? 0;

    $materiList = collect($materiList ?? []);

    $today        = \Carbon\Carbon::today();
    $monthStart   = $today->copy()->firstOfMonth();
    $daysInMonth  = $today->daysInMonth;
    $startWeekday = (int) $monthStart->format('N');

    $namaLengkap =
        $pesertaAktif?->nama
        ?? session('peserta_nama')
        ?? session('pesertaSurvei_nama')
        ?? 'Peserta';

    $namaDepan = explode(' ', trim($namaLengkap))[0] ?? 'Peserta';
@endphp

<link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen text-slate-900 antialiased space-y-6">

    {{-- ================= HERO ================= --}}
    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold uppercase text-blue-600 tracking-wide">
                    Dashboard Pelatihan
                </div>

                <h1 class="mt-2 text-2xl md:text-3xl font-bold leading-tight" style="font-family: 'Volkhov', serif;">
                    Hai, <span class="text-blue-600">{{ $namaDepan }}</span> 👋
                </h1>

                <p class="mt-2 text-sm text-slate-600 max-w-xl">
                    Selesaikan materi, lalu kerjakan Pre-Test, Post-Test dan Monev.
                    Pantau progres & nilainya di sini.
                </p>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.materi.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg shadow-sm text-sm bg-blue-600 hover:bg-blue-700 text-white">
                        📚 Lanjut Materi
                    </a>

                    <a href="{{ route('dashboard.pretest.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm border bg-white hover:bg-slate-50 text-slate-700 border-slate-200">
                        📝 Pre-Test
                    </a>

                    <a href="{{ route('dashboard.posttest.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm border bg-white hover:bg-slate-50 text-slate-700 border-slate-200">
                        📝 Post-Test
                    </a>

                    <a href="{{ route('dashboard.monev.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm border bg-white hover:bg-slate-50 text-slate-700 border-slate-200">
                        📊 Monev
                    </a>
                </div>
            </div>

            {{-- Progress card --}}
            <div class="w-full lg:w-64">
                <div class="bg-gradient-to-br from-indigo-600 to-sky-500 text-white rounded-xl p-4 shadow-md">
                    <div class="text-xs uppercase opacity-90">Materi Progress</div>

                    <div class="mt-3 flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold">{{ $materiProgress }}%</div>
                            <div class="text-xs opacity-90">{{ $materiDoneCount }} / {{ $totalMateri }} selesai</div>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold">
                            {{ $materiProgress }}
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="w-full h-2 bg-white/25 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded" style="width: {{ $materiProgress }}%"></div>
                        </div>
                    </div>

                    <div class="text-xs mt-3 opacity-90">Keep going 💪</div>
                </div>
            </div>
        </div>

        {{-- Media for lessons (take 4) --}}
        <div class="mt-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Media for lessons</h3>
                <a href="{{ route('dashboard.materi.index') }}" class="text-sm text-blue-600 font-semibold">
                    View all →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($materiList->take(4) as $m)
                    @php
                        $tipe = $m->tipe ?? 'teks';
                        $icon = match($tipe) {
                            'video' => '▶',
                            'file'  => '📄',
                            default => '✍',
                        };
                        $materiLink = route('dashboard.materi.show', $m->slug ?? $m->id);
                    @endphp

                    <a href="{{ $materiLink }}"
                       class="flex items-center gap-3 bg-slate-50 border border-slate-100 rounded-lg p-3 hover:bg-slate-100 transition">
                        <div class="h-12 w-12 rounded-lg bg-white flex items-center justify-center border text-lg">
                            {{ $icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-slate-800 truncate">{{ $m->judul ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ \Illuminate\Support\Str::limit($m->deskripsi ?? '', 60) }}
                            </div>
                        </div>
                        <div class="text-xs text-slate-400">{{ $m->estimasi_hari ? $m->estimasi_hari . 'm' : '-' }}</div>
                    </a>
                @empty
                    <div class="text-sm text-slate-400 col-span-full">Belum ada materi yang tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ================= MAIN GRID ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-8 gap-6">

        {{-- LEFT --}}
        <main class="lg:col-span-5 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- PRE --}}
                <div class="bg-white border rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400">Pre-Test</div>
                            <div class="text-xl font-bold mt-2 text-amber-600">
                                {{ $preTestDone ? ($preTestScore ?? '-') : '-' }}
                            </div>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-lg text-amber-600 text-lg">📝</div>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">Attempts: {{ $preTestAttempts }}</div>
                </div>

                {{-- POST --}}
                <div class="bg-white border rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400">Post-Test</div>
                            <div class="text-xl font-bold mt-2 text-emerald-600">
                                {{ $postTestDone ? ($postTestScore ?? '-') : '-' }}
                            </div>
                        </div>
                        <div class="bg-emerald-50 p-3 rounded-lg text-emerald-600 text-lg">✅</div>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">Attempts: {{ $postTestAttempts }}</div>
                </div>

                {{-- MONEV --}}
                <div class="bg-white border rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400">Monev</div>
                            <div class="text-xl font-bold mt-2 text-indigo-600">
                                {{ ($monevTestAttempts ?? 0) >= 1 ? 'Selesai' : '-' }}
                            </div>

                        </div>
                        <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600 text-lg">📊</div>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">Attempts: {{ $monevTestAttempts }}</div>
                </div>
            </div>

            {{-- Notes --}}
            <section class="bg-white rounded-2xl border p-5 shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">💡</div>
                    <h4 class="font-semibold text-slate-800">Catatan Penting</h4>
                </div>

                <ul class="mt-3 space-y-2 text-sm text-slate-600 list-disc pl-5">
                    <li>Kerjakan materi secara berurutan sampai statusnya <b>selesai</b>.</li>
                    <li>Pre-Test sebaiknya dikerjakan sebelum mulai materi utama.</li>
                    <li>Post-Test hanya bisa dikerjakan <b>sekali</b> setelah materi selesai.</li>
                    <li>Monev/Survei wajib diisi untuk rekap evaluasi pelatihan.</li>
                </ul>
            </section>
        </main>

        {{-- RIGHT --}}
        <aside class="lg:col-span-3">
            <div class="sticky top-6 space-y-6">

                <div class="bg-gradient-to-br from-indigo-600 to-sky-500 text-white rounded-2xl p-5 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs opacity-90">Welcome back</div>
                            <div class="text-xl font-bold">{{ $namaDepan }}</div>
                        </div>
                        <div class="text-sm text-white/80">{{ \Carbon\Carbon::now()->format('D, d M') }}</div>
                    </div>

                    {{-- Calendar --}}
                    <div class="mt-4 bg-white/10 rounded-lg p-3">
                        <div class="grid grid-cols-7 gap-1 text-xs text-white/80">
                            @foreach(['M','T','W','T','F','S','S'] as $d)
                                <div class="text-center">{{ $d }}</div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7 gap-1 mt-2 text-sm">
                            @for($i = 1; $i < $startWeekday; $i++)
                                <div class="h-8"></div>
                            @endfor
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php $isToday = $d === (int) $today->format('j'); @endphp
                                <div class="h-8 flex items-center justify-center rounded {{ $isToday ? 'bg-white text-indigo-700 font-semibold' : 'text-white/90' }}">
                                    {{ $d }}
                                </div>
                            @endfor
                        </div>
                    </div>

                {{-- Reminder --}}
                <div class="bg-white rounded-2xl border p-4 shadow-sm">
                    <h4 class="font-semibold text-slate-800">Reminder</h4>
                    <p class="text-sm text-slate-500 mt-2">
                        Jika ada materi/tes yang tidak muncul, hubungi admin pelatihan.
                        Pastikan koneksi stabil saat mengerjakan tes.
                    </p>
                </div>

            </div>
        </aside>
    </div>

</div>
@endsection
