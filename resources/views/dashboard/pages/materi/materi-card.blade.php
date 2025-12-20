{{-- resources/views/dashboard/pages/materi/materi-card.blade.php --}}
@php
    /** @var \App\Models\MateriPelatihan|object $materi */
    $progress = $progress ?? null;

    // kategori hasil derive dari index
    $kategoriText = $kategori ?? ($materi->kategori ?? null);

    $isDone  = (bool) ($progress->is_completed ?? ($materi->is_done ?? false));
    $percent = (int)  ($progress->percent ?? 0);

    $tipe = strtolower($materi->tipe ?? 'teks');

    $tipeBadge = match ($tipe) {
        'video' => ['label' => 'Video', 'bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'icon' => 'play'],
        'file', 'pdf' => ['label' => 'Modul', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => 'file'],
        'link' => ['label' => 'Link', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => 'link'],
        default => ['label' => 'Materi', 'bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'icon' => 'book'],
    };

    $accentClass = match ($tipe) {
        'video' => 'from-rose-500 via-pink-500 to-fuchsia-500',
        'file', 'pdf' => 'from-amber-500 via-orange-500 to-yellow-500',
        'link' => 'from-emerald-500 via-teal-500 to-cyan-500',
        default => 'from-blue-500 via-indigo-500 to-violet-500',
    };

    $kategoriKey = strtolower($kategoriText ?? '');
@endphp

<div
    class="materi-card-container group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden flex flex-col"
    data-kategori="{{ $kategoriKey }}"
>
    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $accentClass }}"></div>

    <div class="p-5 flex flex-col gap-3 grow">
        <div class="flex items-center justify-between gap-2">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-full {{ $tipeBadge['bg'] }} {{ $tipeBadge['text'] }}">
                @if($tipeBadge['icon'] === 'play')
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M6 4.5a1 1 0 011.53-.85l7 4.5a1 1 0 010 1.7l-7 4.5A1 1 0 016 13.5v-9z"/></svg>
                @elseif($tipeBadge['icon'] === 'file')
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0013.414 6L10 2.586A2 2 0 008.586 2H4z"/></svg>
                @elseif($tipeBadge['icon'] === 'link')
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M12.586 7.414a2 2 0 010 2.828l-3.172 3.172a2 2 0 11-2.828-2.828l1.586-1.586a1 1 0 10-1.414-1.414l-1.586 1.586a4 4 0 105.656 5.656l3.172-3.172a4 4 0 00-5.656-5.656 1 1 0 001.242 1.57z"/></svg>
                @else
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/></svg>
                @endif
                {{ $tipeBadge['label'] }}
            </span>

            @if(!empty($materi->estimasi_hari))
                <span class="text-[11px] px-2 py-1 rounded-full bg-gray-50 text-gray-600 font-medium">
                    {{ $materi->estimasi_hari }} hari
                </span>
            @endif
        </div>

        <h3 data-title class="text-base font-extrabold text-gray-900 leading-snug line-clamp-2 min-h-[44px]">
            {{ $materi->judul ?? '-' }}
        </h3>

        @if(!empty($kategoriText))
            <div class="flex flex-wrap gap-2">
                <span class="px-2.5 py-1 text-[11px] font-semibold bg-indigo-50 text-indigo-700 rounded-full">
                    {{ $kategoriText }}
                </span>
                @if($isDone)
                    <span class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 rounded-full">
                        Selesai
                    </span>
                @endif
            </div>
        @endif

        <p data-desc class="text-sm text-gray-600 leading-relaxed line-clamp-3 min-h-[54px]">
            {{ \Illuminate\Support\Str::limit($materi->deskripsi ?? $materi->excerpt ?? '', 110) }}
        </p>

        @if(!$isDone && $percent > 0)
            <div class="mt-1">
                <div class="flex items-center justify-between text-[11px] text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ $percent }}%</span>
                </div>
                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        @endif
    </div>

    <div class="p-4 pt-0">
        @if($isDone)
            <div class="w-full text-center px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold">
                Sudah Selesai
            </div>
        @else
            <a
                href="{{ route('dashboard.materi.show', $materi->slug ?? $materi->id) }}"
                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-sm"
            >
                Mulai
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6 4.5a1 1 0 011.53-.85l7 4.5a1 1 0 010 1.7l-7 4.5A1 1 0 016 13.5v-9z"/>
                </svg>
            </a>
        @endif
    </div>
</div>
