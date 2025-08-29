@extends('dashboard.layouts.main')

@section('title', 'Feedback')
@section('page-title', 'Feedback Materi')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-bold text-xl mb-4">Berikan Feedback</h2>
    <p class="text-gray-600 mb-6">Bantu kami meningkatkan kualitas materi dengan memberikan masukanmu.</p>

    <form action="{{ route('dashboard.feedback.submit') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-2">Materi yang diikuti</label>
            <select name="materi_id" class="w-full p-2 border rounded">
                @foreach($materis as $materi)
                    <option value="{{ $materi->id }}">{{ $materi->judul }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Masukan / Saran</label>
            <textarea name="feedback" rows="5" class="w-full p-2 border rounded" placeholder="Tulis masukanmu..."></textarea>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Kirim Feedback
        </button>
    </form>
</div>
@endsection
