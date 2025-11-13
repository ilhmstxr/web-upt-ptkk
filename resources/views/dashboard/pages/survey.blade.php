@extends('dashboard.layouts.main')

@section('title', 'Survey')
@section('page-title', 'Daftar Survey Pelatihan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($surveys ?? [] as $survey)
        <div class="bg-white p-6 rounded-xl shadow-md card-hover fade-in flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-xl mb-2">{{ $survey->judul }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($survey->deskripsi, 120) }}</p>

                @if(isset($survey->kategori))
                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-200 rounded">{{ $survey->kategori }}</span>
                @endif
            </div>
            
            <div class="mt-4">
                @if(Route::has('dashboard.survey.show'))
                    <a href="{{ route('dashboard.survey.show', $survey->id) }}" 
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                       Buka Survey
                    </a>
                @else
                    <span class="text-red-500 font-semibold">Survey belum tersedia.</span>
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-500 col-span-full text-center">Belum ada survey tersedia saat ini.</p>
    @endforelse
</div>
@endsection
