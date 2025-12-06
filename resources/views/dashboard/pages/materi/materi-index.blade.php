{{-- resources/views/dashboard/pages/materi/materi-index.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Materi')
@section('page-title', 'Daftar Materi Pelatihan')

@section('content')
@php
    // Variabel dikirim dari controller sebagai $materiList.
    // Kita gunakan $materiList langsung untuk konsistensi.
    $materis = $materiList ?? collect();
@endphp

{{-- Bagian Pencarian & Filter --}}
<div class="mb-6 bg-white p-4 rounded-xl shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
        <input type="text" placeholder="Cari materi berdasarkan judul atau kategori..."
               class="w-full md:w-2/3 p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
               onkeyup="filterMateri(this.value)">
        <select class="w-full md:w-1/4 p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">Semua Kategori</option>
            {{-- jika punya daftar kategori, loop di sini --}}
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    
    {{-- 🛑 Perbaikan Utama: Menggunakan @each untuk memanggil partial view --}}
    @if ($materis->isNotEmpty())
        @each('dashboard.pages.materi.materi-card', $materis, 'materi', 'dashboard.pages.materi.empty-materi-list')
    @else
        {{-- Tampilkan pesan kosong jika $materis kosong (handle-nya mirip seperti forelse empty) --}}
        <div class="col-span-full bg-white p-8 rounded-xl shadow-md text-center border-2 border-dashed border-gray-300">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.75 17L9 20l-1 1h8l-1-1v-3m2 0H9.75M17 12h.01M17 16h.01M16 12h1M16 16h1M9 16h.01M9 12h.01M12 12h.01M12 16h.01M5 16h.01M5 12h.01M19 8V5a2 2 0 00-2-2H7a2 2 0 00-2 2v3m14 0h-1m-4 0h-4m-4 0H3m14 0c-.552 0-1 .448-1 1s.448 1 1 1h.01c.552 0 1-.448 1-1s-.448-1-1-1zm-10 0c-.552 0-1 .448-1 1s.448 1 1 1h.01c.552 0 1-.448 1-1s-.448-1-1-1z"></path>
            </svg>
            <p class="text-gray-600 font-semibold text-lg">Oops! Belum Ada Materi Pelatihan.</p>
            <p class="text-gray-500 mt-1">Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
        </div>
    @endif
    
</div>

{{-- Pagination --}}
@if(method_exists($materis, 'links'))
    <div class="mt-8">
        {{ $materis->links() }}
    </div>
@endif

{{-- Simple frontend filter script (opsional) --}}
@push('scripts')
<script>
function filterMateri(query) {
    // Perhatikan: sekarang card harus memiliki class 'materi-card-container' di dalamnya
    const cards = document.querySelectorAll('.materi-card-container');
    query = query.toLowerCase();
    
    let found = false;
    
    cards.forEach(card => {
        // Cek jika elemen ada sebelum mencoba mengakses properti
        const title = (card.querySelector('h3')?.textContent || '').toLowerCase();
        const description = (card.querySelector('p')?.textContent || '').toLowerCase();
        
        if (title.includes(query) || description.includes(query)) {
            card.style.display = 'block';
            found = true;
        } else {
            card.style.display = 'none';
        }
    });

    // Anda mungkin ingin menampilkan pesan 'tidak ditemukan' di sini jika filter terlalu ketat
}
</script>
@endpush

@endsection