<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', 'Pendaftaran') - UPT PTKK Jatim</title>
    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* Style input, select, textarea agar lebih jelas */
        input[type="text"],
        input[type="email"],
        input[type="number"],
        select,
        textarea {
            @apply bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-blue-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition;
        }

        label {
            @apply block mb-1 font-semibold text-blue-900;
        }

        /* Error popup */
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

<body class="bg-blue-200 min-h-screen text-blue-900">

    <div class="container mx-auto p-4 sm:p-6 lg:p-8 bg-white rounded-xl shadow-lg">
        {{-- HEADER --}}
        <header
            class="flex items-center justify-between bg-blue-700 p-4 rounded-xl shadow-md mb-8 text-white select-none">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 overflow-hidden rounded-full flex-shrink-0 relative">
                    <img src="{{ asset('../images/logo-upt-ptkk.jpg') }}" alt="Logo UPT PTKK"
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                    <div
                        class="w-full h-full bg-blue-900 text-white font-bold text-sm flex items-center justify-center text-center leading-tight hidden absolute top-0 left-0">
                        UPT<br />PTKK
                    </div>
                </div>
                <h1 class="text-lg md:text-xl font-bold">
                    UPT PTKK Dinas Pendidikan Jawa Timur
                </h1>
            </div>
        </header>

        {{-- MAIN CONTENT GRID --}}
        @if ($currentStep != 4)
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- SIDEBAR --}}
                <aside class="lg:col-span-1">
                    <div
                        class="bg-white border border-blue-300 p-6 rounded-xl h-fit sticky top-8 shadow-md select-none">
                        <h2 class="text-xl font-bold mb-6 text-blue-900">Langkah Pendaftaran</h2>

                        @php
                            $allowedStep = $allowedStep ?? 1;
                            $currentStep = $currentStep ?? 1;
                        @endphp

                        <div class="relative space-y-4">
                            <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-blue-300"></div>

             {{-- STEP 1 --}}
<a href="{{ $allowedStep >= 1 ? route('pendaftaran.create', ['step' => 1]) : '#' }}"
    class="flex items-center gap-4 relative {{ $allowedStep < 1 ? 'pointer-events-none opacity-50' : '' }}">
    <div
        class="z-10 flex items-center justify-center w-8 h-8 rounded-full font-bold transition-colors duration-300
        {{ $currentStep > 1 ? 'bg-green-500 text-white' : ($currentStep == 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600') }}">
        {!! $currentStep > 1 ? '&#10003;' : '1' !!}
    </div>
    <span class="font-semibold transition-colors duration-300 {{ $currentStep == 1 ? 'text-blue-700' : 'text-blue-900' }}">
        Biodata Diri
    </span>
</a>

{{-- STEP 2 --}}
<a href="{{ $allowedStep >= 2 ? route('pendaftaran.create', ['step' => 2]) : '#' }}"
    class="flex items-center gap-4 relative {{ $allowedStep < 2 ? 'pointer-events-none opacity-50' : '' }}">
    <div
        class="z-10 flex items-center justify-center w-8 h-8 rounded-full font-bold transition-colors duration-300
        {{ $currentStep > 2 ? 'bg-green-500 text-white' : ($currentStep == 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600') }}">
        {!! $currentStep > 2 ? '&#10003;' : '2' !!}
    </div>
    <span class="font-semibold transition-colors duration-300 {{ $currentStep == 2 ? 'text-blue-700' : 'text-blue-900' }}">
        Biodata Sekolah
    </span>
</a>

{{-- STEP 3 --}}
<a href="{{ $allowedStep >= 3 ? route('pendaftaran.create', ['step' => 3]) : '#' }}"
    class="flex items-center gap-4 relative {{ $allowedStep < 3 ? 'pointer-events-none opacity-50' : '' }}">
    <div
        class="z-10 flex items-center justify-center w-8 h-8 rounded-full font-bold transition-colors duration-300
        {{ $currentStep > 3 ? 'bg-green-500 text-white' : ($currentStep == 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600') }}">
        {!! $currentStep > 3 ? '&#10003;' : '3' !!}
    </div>
    <span class="font-semibold transition-colors duration-300 {{ $currentStep == 3 ? 'text-blue-700' : 'text-blue-900' }}">
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
            <main class="w-full text-blue-900">
                @yield('content')
            </main>
        @endif
    </div>

</body>

</html>
