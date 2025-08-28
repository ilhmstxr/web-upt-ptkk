@extends('dashboard.layout.main')
@section('title', 'Feedback')
@section('page-title', 'Feedback')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-semibold text-xl mb-4">Feedback</h2>
    <p class="text-gray-600 mb-4">Beri masukan Anda agar pelatihan semakin baik.</p>

    @if(Route::has('dashboard.feedback.submit'))
    <form action="{{ route('dashboard.feedback.submit') }}" method="POST" class="space-y-4">
        @csrf
        <textarea name="feedback" rows="4" class="w-full p-3 border rounded-lg" placeholder="Tulis feedback Anda..." required></textarea>
        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Kirim Feedback</button>
    </form>
    @else
        <span class="text-red-500">Route feedback belum tersedia.</span>
    @endif
</div>
@endsection
