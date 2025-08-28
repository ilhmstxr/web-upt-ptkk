<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - UPT PTKK Jatim</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Animasi fade in */
        .fade-in { animation: fadeIn 0.8s ease forwards; opacity: 0; }
        @keyframes fadeIn { to { opacity: 1; } }

        /* Hover cards */
        .card-hover:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        /* Sidebar active link */
        .active-link { background-color: #5c76c1; color: white; }

        /* Pendaftaran Stepper */
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
<body class="bg-gray-100 min-h-screen flex">

    {{-- Jika halaman pendaftaran --}}
    @isset($isPendaftaran)
        <div class="container mx-auto p-4 sm:p-6 lg:p-8">
            <header class="flex items-center justify-between bg-white p-4 rounded-xl shadow-sm mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 overflow-hidden rounded-full flex-shrink-0">
                        <img src="{{ asset('../images/logo-upt-ptkk.png') }}" alt="Logo UPT PTKK"
                            class="w-full h-full object-cover"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full bg-blue-600 text-white font-bold text-sm items-center justify-center text-center leading-tight hidden">
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

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- SIDEBAR STEP --}}
                <aside class="lg:col-span-1">
                    <div class="bg-white border border-slate-200 p-6 rounded-xl h-fit sticky top-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">Langkah Pendaftaran</h2>
                        @php
                            $allowedStep = $allowedStep ?? 1;
                            $currentStep = $currentStep ?? 1;
                        @endphp
                        <div class="relative space-y-4">
                            <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-sky-200"></div>

                            @foreach ([
                                1 => 'Biodata Diri',
                                2 => 'Biodata Sekolah',
                                3 => 'Lampiran'
                            ] as $step => $label)
                                <a href="{{ $allowedStep >= $step ? route('pendaftaran.create', ['step' => $step]) : '#' }}" 
                                   class="flex items-center gap-4 relative {{ $allowedStep < $step ? 'pointer-events-none opacity-50' : '' }}">
                                    <div class="z-10 flex items-center justify-center w-8 h-8 rounded-full font-bold transition-colors {{ $currentStep >= $step ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                        {!! $allowedStep > $step ? '&#10003;' : $step !!}
                                    </div>
                                    <span class="font-semibold transition-colors {{ $currentStep == $step ? 'text-blue-700' : 'text-slate-800' }}">
                                        {{ $label }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>

                {{-- FORM CONTENT --}}
                <main class="lg:col-span-3">
                    @yield('content')
                </main>
            </div>
        </div>

    {{-- Jika halaman dashboard --}}
    @else
        {{-- SIDEBAR DASHBOARD --}}
        <aside class="w-64 bg-white shadow-md h-screen sticky top-0 flex flex-col">
            <div class="p-6 flex flex-col items-center border-b border-gray-200">
                <img src="https://placehold.co/80x80/5c76c1/fff?text=Logo" class="rounded-full mb-2">
                <h2 class="font-bold text-lg text-gray-800">Peserta Dashboard</h2>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard.home') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.home') ? 'active-link' : '' }}">Home</a>
                <a href="{{ route('dashboard.profile') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.profile') ? 'active-link' : '' }}">Profile</a>
                <a href="{{ route('dashboard.materi') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.materi*') ? 'active-link' : '' }}">Materi</a>
                <a href="{{ route('dashboard.pretest.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.pretest.*') ? 'active-link' : '' }}">Pre-Test</a>
                <a href="{{ route('dashboard.posttest.index') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.posttest.*') ? 'active-link' : '' }}">Post-Test</a>
                <a href="{{ route('dashboard.feedback') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.feedback') ? 'active-link' : '' }}">Feedback</a>
            </nav>
        </aside>

        {{-- MAIN DASHBOARD CONTENT --}}
        <main class="flex-1 p-6 overflow-auto">
            <header class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">@yield('page-title')</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">
                        Hai, {{ Auth::check() ? Auth::user()->name : 'Peserta' }}
                    </span>
                    @if(Auth::check())
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Logout</button>
                        </form>
                    @endif
                </div>
            </header>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6 fade-in">
                @yield('content')
            </div>
        </main>
    @endisset

</body>
</html>
