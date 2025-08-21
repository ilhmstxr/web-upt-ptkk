@php
    // $getState() adalah fungsi helper dari Filament untuk mengambil path file dari database
    $filePath = $getState();
    // $getLabel() adalah fungsi helper untuk mengambil label yang didefinisikan di Infolist
    $label = $getLabel();
    $fileName = null;
    $isImage = false;

    if ($filePath) {
        $fileName = basename($filePath); // Mengambil nama file dari path
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }
@endphp

<div class="space-y-1">
    {{-- Menampilkan label dari kolom --}}
    <h4 class="text-sm font-medium text-gray-700">{{ $label }}</h4>

    @if ($filePath)
        @if ($isImage)
            <div class="space-y-2 pt-2">
                {{-- Preview Gambar --}}
                <img src="{{ Storage::url($filePath) }}" 
                     alt="Preview untuk {{ $label }}" 
                     class="border rounded-md w-full max-w-xs h-auto">
                
                {{-- Nama File --}}
                <p class="text-sm text-gray-500 truncate" title="{{ $fileName }}">
                    {{ $fileName }}
                </p>
            </div>
        @else
            <div class="flex items-center space-x-3 pt-2">
                {{-- Ikon Download --}}
                <a href="{{ Storage::url($filePath) }}" 
                   target="_blank" 
                   class="text-primary-600 hover:text-primary-500">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
                
                {{-- Link dengan Nama File --}}
                <a href="{{ Storage::url($filePath) }}" 
                   target="_blank" 
                   class="text-primary-600 hover:underline font-medium text-sm truncate"
                   title="{{ $fileName }}">
                   {{ $fileName }}
                </a>
            </div>
        @endif
    @else
        <span class="text-sm text-gray-400">Tidak ada file</span>
    @endif
</div>
    