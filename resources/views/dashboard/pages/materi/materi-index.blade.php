{{-- resources/views/dashboard/pages/materi/materi-index.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Materi')
@section('page-title', 'Daftar Materi Pelatihan')

@section('content')
@php
    /**
     * ==========================================================
     * 1) DATA ASLI dari controller
     * ==========================================================
     */
    $materis = $materiList ?? collect();

    /**
     * ==========================================================
     * 2) DUMMY FALLBACK
     * - aktif kalau data kosong / belum ada
     * - bentuk object supaya kompatibel dengan materi-card
     * ==========================================================
     */
    $isDummy = false;

    if ($materis->isEmpty()) {
        $isDummy = true;

        $materis = collect([
            (object)[
                'id' => 'dummy-1',
                'judul' => 'Pengenalan Keselamatan Kerja',
                'deskripsi' => 'Materi dasar mengenai aturan keselamatan kerja di workshop.',
                'tipe' => 'teks',
                'estimasi_menit' => 15,
                'urutan' => 1,
                'kategori' => 'Dasar',
                'file_path' => null,
                'video_url' => null,
                'link_url' => null,
                'teks' => '<p>Contoh isi materi dummy...</p>',
                'is_published' => true,
                'pelatihan_id' => null,
                'is_done' => false,
            ],
            (object)[
                'id' => 'dummy-2',
                'judul' => 'Video Teknik Dasar Pengelasan',
                'deskripsi' => 'Video praktik teknik pengelasan untuk pemula.',
                'tipe' => 'video',
                'estimasi_menit' => 20,
                'urutan' => 2,
                'kategori' => 'Praktik',
                'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'link_url' => null,
                'teks' => null,
                'is_published' => true,
                'pelatihan_id' => null,
                'is_done' => false,
            ],
            (object)[
                'id' => 'dummy-3',
                'judul' => 'Modul Mesin Bubut (PDF)',
                'deskripsi' => 'Dokumen modul lengkap tentang pengoperasian mesin bubut.',
                'tipe' => 'file',
                'estimasi_menit' => 30,
                'urutan' => 3,
                'kategori' => 'Modul',
                'file_path' => 'materi/dummy-modul-mesin-bubut.pdf',
                'video_url' => null,
                'link_url' => null,
                'teks' => null,
                'is_published' => true,
                'pelatihan_id' => null,
                'is_done' => false,
            ],
            (object)[
                'id' => 'dummy-4',
                'judul' => 'Referensi External CNC',
                'deskripsi' => 'Link referensi pembelajaran CNC resmi.',
                'tipe' => 'link',
                'estimasi_menit' => 10,
                'urutan' => 4,
                'kategori' => 'Referensi',
                'file_path' => null,
                'video_url' => null,
                'link_url' => 'https://example.com/referensi-cnc',
                'teks' => null,
                'is_published' => true,
                'pelatihan_id' => null,
                'is_done' => false,
            ],
        ]);
    }

    /**
     * ==========================================================
     * 3) KATEGORI untuk dropdown filter
     * ==========================================================
     */
    $kategoriList = $materis
        ->pluck('kategori')
        ->filter()
        ->unique()
        ->values();
@endphp

{{-- Notice jika dummy aktif --}}
@if($isDummy)
    <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg text-sm">
        Data materi belum tersedia. Menampilkan contoh materi sementara.
    </div>
@endif

{{-- Bagian Pencarian & Filter --}}
<div class="mb-6 bg-white p-4 rounded-xl shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">

        <input type="text"
               placeholder="Cari materi berdasarkan judul atau kategori..."
               class="w-full md:w-2/3 p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
               onkeyup="filterMateri(this.value)">

        {{-- Filter kategori (dinamis) --}}
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
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($materis as $materi)
        @include('dashboard.pages.materi.materi-card', [
            'materi' => $materi,
        ])
    @endforeach
</div>

{{-- Pagination hanya kalau data asli dan paginator --}}
@if(!$isDummy && $materis instanceof \Illuminate\Pagination\AbstractPaginator)
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
                    $link = route('dashboard.materi.show', $m->id);
                @endphp
                <a href="{{ $link }}"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 transition">
                    <div class="w-8 h-8 rounded-md flex items-center justify-center text-xs font-semibold
                                {{ ($m->is_done ?? false) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-medium truncate">{{ $m->judul ?? '-' }}</div>
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
