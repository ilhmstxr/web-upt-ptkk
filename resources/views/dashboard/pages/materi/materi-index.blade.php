{{-- resources/views/dashboard/pages/materi/materi-index.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Materi')
@section('page-title', 'Daftar Materi Pelatihan')

@section('content')
@php
    /**
     * ==========================================================
     * DATA ASLI dari controller (TANPA DUMMY)
     * ==========================================================
     */
    $materis = $materiList ?? collect();

    /**
     * ==========================================================
     * KATEGORI untuk dropdown filter
     * ==========================================================
     */
    $kategoriList = $materis
        ->pluck('kategori')
        ->filter()
        ->unique()
        ->values();
@endphp

{{-- Notice jika belum ada materi --}}
@if($materis->isEmpty())
    <div class="mb-4 bg-slate-50 border border-slate-200 text-slate-600 px-4 py-3 rounded-lg text-sm">
        Materi pelatihan belum tersedia.
    </div>
@endif

{{-- Bagian Pencarian & Filter --}}
<div class="mb-6 bg-white p-4 rounded-xl shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">

        <input type="text"
               placeholder="Cari materi berdasarkan judul atau kategori..."
               class="w-full md:w-2/3 p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
               onkeyup="filterMateri(this.value)">

        {{-- Filter kategori --}}
        <select id="kategoriFilter"
                class="w-full md:w-1/4 p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                onchange="filterKategori(this.value)">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $kat)
                <option value="{{ strtolower($kat) }}">{{ $kat }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Grid Materi --}}
@if($materis->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($materis as $materi)
            @include('dashboard.pages.materi.materi-card', [
                'materi' => $materi,
            ])
        @endforeach
    </div>
@endif

{{-- Pagination --}}
@if($materis instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="mt-8">
        {{ $materis->links() }}
    </div>
@endif

@push('scripts')
<script>
function filterMateri(query) {
    const cards = document.querySelectorAll('.materi-card-container');
    query = (query || '').toLowerCase();

    cards.forEach(card => {
        const title = (card.querySelector('h3')?.textContent || '').toLowerCase();
        const desc  = (card.querySelector('p')?.textContent || '').toLowerCase();
        const kategori = (card.dataset.kategori || '').toLowerCase();

        const cocokJudulDesc = title.includes(query) || desc.includes(query);
        const cocokKategori  = !window.__kategoriFilter || kategori.includes(window.__kategoriFilter);

        card.style.display = (cocokJudulDesc && cocokKategori) ? 'block' : 'none';
    });
}

function filterKategori(value) {
    window.__kategoriFilter = (value || '').toLowerCase();
    const input = document.querySelector('input[onkeyup="filterMateri(this.value)"]');
    filterMateri(input?.value || '');
}
</script>
@endpush
@endsection


{{-- ===================== RIGHT SIDEBAR ===================== --}}
@section('right-sidebar')
@php
    $materisForSidebar = $materiList ?? collect();
    $doneCount = $materisForSidebar->where('is_done', true)->count();
    $total = $materisForSidebar->count();
    $progress = $total > 0 ? floor(($doneCount / $total) * 100) : 0;
@endphp

<div class="sticky top-6 space-y-4">
    {{-- Progress --}}
    <div class="bg-white rounded-2xl border p-4 shadow-sm">
        <div class="text-sm font-semibold text-slate-800">Progress Materi</div>
        <div class="mt-2 text-2xl font-bold text-blue-600">{{ $progress }}%</div>
        <div class="text-xs text-slate-500">{{ $doneCount }}/{{ $total }} selesai</div>

        <div class="mt-3 w-full h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded" style="width: {{ $progress }}%"></div>
        </div>
    </div>

    {{-- List materi singkat --}}
    <div class="bg-white rounded-2xl border p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="text-sm font-semibold">Daftar Materi</div>
            <div class="text-xs text-slate-500">{{ $doneCount }}/{{ $total }}</div>
        </div>

        <div class="space-y-2 max-h-[420px] overflow-auto pr-1">
            @forelse($materisForSidebar as $i => $m)
                @php
                    $link = route('dashboard.materi.show', $m->slug ?? $m->id);
                @endphp
                <a href="{{ $link }}"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 transition">
                    <div class="w-8 h-8 rounded-md flex items-center justify-center text-xs font-semibold
                                {{ ($m->is_done ?? false) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-medium truncate">{{ $m->judul }}</div>
                        <div class="text-[11px] text-slate-500">
                            {{ ($m->is_done ?? false) ? 'Selesai' : 'Belum' }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-sm text-slate-400">Belum ada materi</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
