<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Pendaftaran') - UPT PTKK Jatim</title>
    {{-- Menggunakan Tailwind CSS via CDN untuk kemudahan --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Menggunakan font Inter untuk tampilan yang lebih modern */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* {{-- CSS untuk animasi dan tampilan pop-up error --}} */
        .error-popup {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(10px);
            z-index: 10;
        }

        .error-popup.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        {{-- HEADER --}}
        <header class="flex items-center justify-between bg-white p-4 rounded-xl shadow-sm mb-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 overflow-hidden rounded-full flex-shrink-0">
                    {{-- Ganti src dengan path logo Anda yang benar --}}
                    <img src="{{ asset('../images/logo-upt-ptkk.png') }}" alt="Logo UPT PTKK"
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    {{-- Fallback jika logo gagal dimuat --}}
                    <div
                        class="w-full h-full bg-blue-600 text-white font-bold text-sm items-center justify-center text-center leading-tight hidden">
                        UPT<br>PTKK
                    </div>
                </div>
                <h1 class="text-lg md:text-xl font-bold text-slate-900">
                    UPT PTKK Dinas Pendidikan Jawa Timur
                </h1>
            </div>
            <a href="#" title="Tutup Pendaftaran"
                class="w-9 h-9 flex items-center justify-center bg-red-100 rounded-full text-red-600 font-bold text-2xl transition-all duration-300 hover:bg-red-200 hover:rotate-90">
                ×
            </a>
        </header>
  {{-- MAIN CONTENT GRID --}}
        {{-- Kondisi ini akan menampilkan layout yang berbeda untuk step 4 --}}
        @if ($currentStep != 4)
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- SIDEBAR --}}
                <aside class="lg:col-span-1">
                    <div class="bg-white border border-slate-200 p-6 rounded-xl h-fit sticky top-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">Langkah Pendaftaran</h2>

                        {{-- Stepper Navigation --}}
                        @php
                            // Memberikan nilai default untuk keamanan
                            $allowedStep = $allowedStep ?? 1;
                            $currentStep = $currentStep ?? 1;
                        @endphp
                        <div class="relative space-y-4">
                            <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-sky-200"></div>

                            <!-- STEP 1 -->
                            <a href="{{ $allowedStep >= 1 ? route('pendaftaran.create', ['step' => 1]) : '#' }}" 
                               class="flex items-center gap-4 relative {{ $allowedStep < 1 ? 'pointer-events-none' : '' }}">
                                <div class="z-10 flex items-center justify-center w-8 h-8 rounded-full font-bold transition-colors {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                    {!! $allowedStep > 1 ? '&#10003;' : '1' !!}
                                </div>
                                <span class="font-semibold transition-colors {{ $currentStep == 1 ? 'text-blue-700' : 'text-slate-800' }}">
                                    Biodata Diri
                                </span>
                            </a>

                            <!-- STEP 2 -->
                            <a href="{{ $allowedStep >= 2 ? route('pendaftaran.create', ['step' => 2]) : '#' }}" 
                               class="flex items-center gap-4 relative {{ $allowedStep < 2 ? 'pointer-events-none opacity-50' : '' }}">
                                <div class="z-10 flex items-center justify-center w-8 h-8 rounded-full font-bold transition-colors {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                    {!! $allowedStep > 2 ? '&#10003;' : '2' !!}
                                </div>
                                <span class="font-semibold transition-colors {{ $currentStep == 2 ? 'text-blue-700' : 'text-slate-800' }}">
                                    Biodata Sekolah
                                </span>
                            </a>

                            <!-- STEP 3 -->
                            <a href="{{ $allowedStep >= 3 ? route('pendaftaran.create', ['step' => 3]) : '#' }}" 
                               class="flex items-center gap-4 relative {{ $allowedStep < 3 ? 'pointer-events-none opacity-50' : '' }}">
                                <div class="z-10 flex items-center justify-center w-8 h-8 rounded-full font-bold transition-colors {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                    {!! $allowedStep > 3 ? '&#10003;' : '3' !!}
                                </div>
                                <span class="font-semibold transition-colors {{ $currentStep == 3 ? 'text-blue-700' : 'text-slate-800' }}">
                                    Lampiran
                                </span>
                            </a>
                        </div>
                    </div>
                </aside>

                {{-- FORM CONTENT --}}
                <main class="lg:col-span-3">
                    @yield('content')
                </main>
            </div>
        @else
            {{-- Tampilan untuk step 4 (Halaman Selesai) --}}
            <main class="w-full">
                @yield('content')
            </main>
        @endif
    </div>

</body>

</html>
