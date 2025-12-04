@extends('dashboard.layouts.main')

@section('title', 'Materi')
@section('page-title', 'Daftar Materi Pelatihan')

@section('content')

{{-- Bagian Pencarian & Filter (Placeholder) --}}

<div class="mb-6 bg-white p-4 rounded-xl shadow-md">
<div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
<input type="text" placeholder="Cari materi berdasarkan judul atau kategori..."
class="w-full md:w-2/3 p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
onkeyup="filterMateri(this.value)">
<select class="w-full md:w-1/4 p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
<option value="">Semua Kategori</option>
{{-- @foreach($categories as $category)
<option value="{{ $category }}">{{ $category }}</option>
@endforeach --}}
</select>
</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
@forelse($materis ?? [] as $materi)
{{-- Menggunakan Component Baru --}}
<x-materi-card :materi="$materi" :progress="$materi->progress_status ?? null" />
@empty
<div class="col-span-full bg-white p-8 rounded-xl shadow-md text-center border-2 border-dashed border-gray-300">
<svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1v-3m2 0H9.75M17 12h.01M17 16h.01M16 12h1M16 16h1M9 16h.01M9 12h.01M12 12h.01M12 16h.01M5 16h.01M5 12h.01M19 8V5a2 2 0 00-2-2H7a2 2 0 00-2 2v3m14 0h-1m-4 0h-4m-4 0H3m14 0c-.552 0-1 .448-1 1s.448 1 1 1h.01c.552 0 1-.448 1-1s-.448-1-1-1zm-10 0c-.552 0-1 .448-1 1s.448 1 1 1h.01c.552 0 1-.448 1-1s-.448-1-1-1z"></path></svg>
<p class="text-gray-600 font-semibold text-lg">Oops! Belum Ada Materi Pelatihan.</p>
<p class="text-gray-500 mt-1">Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
</div>
@endforelse
</div>

{{-- Script for basic filtering (optional, remove if using full server-side search) --}}
{{-- <script>
function filterMateri(query) {
const cards = document.querySelectorAll('.materi-card-container');
query = query.toLowerCase();
cards.forEach(card => {
const title = card.querySelector('h3').textContent.toLowerCase();
const description = card.querySelector('p').textContent.toLowerCase();
if (title.includes(query) || description.includes(query)) {
card.style.display = 'block';
} else {
card.style.display = 'none';
}
});
}
</script> --}}

@endsection