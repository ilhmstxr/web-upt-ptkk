{{-- 
    Komponen ini menampilkan satu kartu materi.
    Variabel yang dibutuhkan:
    - $materi: Objek materi (wajib)
    - $progress: Objek status progress materi (opsional, untuk mengecek is_completed)
--}}
<div {{ $attributes->merge(['class' => 'materi-card-container bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] flex flex-col justify-between border-t-4 border-blue-500']) }}>
    <div>
        {{-- Judul Materi --}}
        <h3 class="font-extrabold text-xl mb-2 text-gray-800 leading-snug">{{ $materi->judul }}</h3>
        
        {{-- Deskripsi --}}
        <p class="text-gray-600 mb-4 text-sm min-h-[48px] overflow-hidden">
            {{ Str::limit($materi->deskripsi, 100) }}
        </p>

        {{-- Detail/Tag --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @if(isset($materi->kategori))
                <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ $materi->kategori }}
                </span>
            @endif
            @if(isset($materi->durasi))
                <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $materi->durasi }} Min
                </span>
            @endif
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="mt-4 pt-4 border-t border-gray-100">
        @if(isset($progress) && $progress->is_completed)
            <span class="inline-block px-4 py-2 bg-green-500 text-white font-semibold rounded-lg text-sm w-full text-center">
                <i class="fas fa-check-circle mr-1"></i> Sudah Selesai
            </span>
        @else
            {{-- Menggunakan slug jika ada, fallback ke id --}}
            <a href="{{ route('dashboard.materi.show', $materi->slug ?? $materi->id) }}" 
               class="inline-block px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 w-full text-center">
                Mulai Pelatihan
            </a>
        @endif
    </div>
</div>