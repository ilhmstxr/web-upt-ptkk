@props(['filePath'])

@if ($filePath)
    @php
        // Dapatkan URL publik dari path file
        $fileUrl = Illuminate\Support\Facades\Storage::disk('public')->url($filePath);
        // Cek ekstensi file untuk menentukan apakah ini gambar
        $isImage = in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), [
            'jpg',
            'jpeg',
            'png',
            'gif',
            'svg',
            'webp',
        ]);
    @endphp

    <div class="border rounded-lg p-4 space-y-2 bg-gray-50 dark:bg-gray-800/50">
        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Pratinjau Saat Ini:</span>

        @if ($isImage)
            <img src="{{ $fileUrl }}" alt="Pratinjau" class="rounded-lg shadow-md max-h-48">
        @else
            <div class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ basename($filePath) }}</span>
            </div>
        @endif

        <a href="{{ route('pendaftaran.download_file', ['path' => $filePath]) }}"
            class="inline-flex items-center px-3 py-1 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 dark:text-primary-400 dark:bg-primary-500/10 dark:hover:bg-primary-500/20">
            Unduh Berkas <svg xmlns="http://www.w.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
        </a>
    </div>
@else
    <div class="border rounded-lg p-4 text-sm text-gray-500 bg-gray-50 dark:bg-gray-800/50">
        Tidak ada berkas yang diunggah.
    </div>
@endif
