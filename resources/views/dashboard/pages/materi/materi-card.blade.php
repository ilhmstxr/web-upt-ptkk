{{-- resources/views/dashboard/pages/materi/materi-card.blade.php --}}
@php
  /** @var \App\Models\MateriPelatihan $materi */
  $progress = $progress ?? null;
@endphp

<div class="materi-card-container bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] flex flex-col justify-between border-t-4 border-blue-500">
  <div>
    <h3 class="font-extrabold text-xl mb-2 text-gray-800 leading-snug">{{ $materi->judul }}</h3>
    <p class="text-gray-600 mb-4 text-sm min-h-[48px] overflow-hidden">
      {{ \Illuminate\Support\Str::limit($materi->deskripsi ?? '', 100) }}
    </p>

    <div class="flex flex-wrap gap-2 mb-4">
      @if(!empty($materi->kategori))
        <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
          {{ $materi->kategori }}
        </span>
      @endif

      @if(!empty($materi->estimasi_menit))
        <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
          {{ $materi->estimasi_menit }} Min
        </span>
      @endif
    </div>
  </div>

  <div class="mt-4 pt-4 border-t border-gray-100">
    @if($progress && ($progress->is_completed ?? false))
      <span class="inline-block px-4 py-2 bg-green-500 text-white font-semibold rounded-lg text-sm w-full text-center">
        Sudah Selesai
      </span>
    @else
      <a href="{{ route('dashboard.materi.show', $materi->slug ?? $materi->id) }}"
         class="inline-block px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-md w-full text-center">
        Mulai Pelatihan
      </a>
    @endif
  </div>
</div>
