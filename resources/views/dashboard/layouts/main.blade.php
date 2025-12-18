<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - UPT PTKK Jatim</title>

    {{-- Tailwind CDN (production idealnya pakai Vite) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @stack('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
            opacity: 0;
            will-change: opacity, transform;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-sky-50 to-slate-50 min-h-screen flex">

    @isset($isPendaftaran)
        {{-- Mode khusus pendaftaran, konten langsung --}}
        @yield('content')
    @else
        <div class="flex flex-1 relative">

            {{-- Overlay mobile --}}
            <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden pointer-events-none"></div>

            {{-- ===================== SIDEBAR ===================== --}}
            <aside id="sidebar"
                class="fixed inset-y-0 left-0 w-64 bg-white/95 backdrop-blur border-r border-slate-200 shadow-lg
                      transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40 flex flex-col"
                aria-label="Sidebar">

                {{-- Brand / Header --}}
                <div class="flex items-center justify-center p-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-upt-ptkk.jpg') }}"
                            onerror="this.onerror=null;this.src='https://placehold.co/60x60/3B82F6/FFFFFF?text=LOGO';"
                            alt="Logo UPT" class="rounded-full w-12 h-12 object-cover border border-blue-100">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                Dashboard Peserta
                            </p>
                            <p class="text-xs text-slate-500">
                                UPT PTKK Jatim
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Nav --}}
                <nav class="flex-1 p-4 space-y-4" aria-label="Sidebar Navigation">

                    {{-- ===== Navigasi Utama ===== --}}
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            Navigasi Utama
                        </p>

                        <ul class="space-y-1 text-sm">

                            {{-- Home --}}
                            <li>
                                <a href="{{ route('dashboard.home') }}"
                                    class="flex items-center p-2.5 rounded-lg font-medium
                                    {{ request()->routeIs('dashboard.home') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <x-icon-home class="w-5 h-5 mr-3" />
                                    <span>Home</span>
                                </a>
                            </li>

                            {{-- Profil --}}
                            <li>
                                <a href="{{ route('dashboard.profile') }}"
                                    class="flex items-center p-2.5 rounded-lg font-medium
                                    {{ request()->routeIs('dashboard.profile') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <x-icon-user class="w-5 h-5 mr-3" />
                                    <span>Profil</span>
                                </a>
                            </li>


                            {{-- Materi (✅ Ditambah sesuai Home) --}}
                            <li>
                                <a href="{{ route('dashboard.materi.index') }}"
                                    class="flex items-center p-2.5 rounded-lg font-medium
                                    {{ request()->routeIs('dashboard.materi.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg leading-none">
                                        📚
                                    </span>
                                    <span>Materi</span>
                                </a>
                            </li>

                        </ul>
                    </div>

                    {{-- ===== Evaluasi & Monev ===== --}}
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            Evaluasi & Monev
                        </p>

                        <ul class="space-y-1 text-sm">

                            {{-- Pre-Test --}}
                            <li>
                                <a href="{{ route('dashboard.pretest.index') }}"
                                    class="flex items-center gap-3 p-2.5 rounded-lg font-medium transition
            {{ request()->routeIs('dashboard.pretest.*')
                ? 'bg-blue-600 text-white shadow-md'
                : 'text-slate-700 hover:bg-slate-100' }}">
                                    <span class="text-base leading-none">📝</span>
                                    <span>Pre-Test</span>
                                </a>
                            </li>


                            {{-- Post-Test --}}
                            <li>
                                <a href="{{ route('dashboard.posttest.index') }}"
                                    class="flex items-center gap-3 p-2.5 rounded-lg font-medium transition
            {{ request()->routeIs('dashboard.posttest.*')
                ? 'bg-blue-600 text-white shadow-md'
                : 'text-slate-700 hover:bg-slate-100' }}">
                                    <span class="text-base leading-none">📝</span>
                                    <span>Post-Test</span>
                                </a>
                            </li>


                            {{-- Monev / Survei (✅ route diperbaiki jadi monev.index) --}}
                            <li>
                                <a href="{{ route('dashboard.monev.index') }}"
                                    class="flex items-center p-2.5 rounded-lg font-medium
                                    {{ request()->routeIs('dashboard.monev.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg leading-none">
                                        📊
                                    </span>
                                    <span>Monev / Survei</span>
                                </a>
                            </li>

                        </ul>
                    </div>

                </nav>
            </aside>


            {{-- ===================== MAIN WRAPPER ===================== --}}
            <main class="flex-1 lg:ml-64 p-4 md:p-6 overflow-auto">

                {{-- Header top --}}
                <header
                    class="flex justify-between items-center mb-6 bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm">

                    <div class="flex items-center gap-3">
                        {{-- Toggle Sidebar (mobile) --}}
                        <button id="menu-toggle" aria-label="Toggle Sidebar" aria-controls="sidebar" aria-expanded="false"
                            class="lg:hidden p-2 rounded-md border border-slate-200 hover:bg-slate-100 text-slate-700 transition focus:outline-none focus:ring-2 focus:ring-blue-400">
                            ☰
                        </button>

                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-slate-900 leading-tight">
                                @yield('page-title', 'Dashboard Home')
                            </h1>
                            @hasSection('page-subtitle')
                                <p class="text-xs text-slate-500 mt-0.5">
                                    @yield('page-subtitle')
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Right header info --}}
                    <div class="flex items-center gap-3">

                        @php
                            $nama =
                                $pesertaAktif->nama ??
                                (session('peserta_nama') ??
                                    (session('pesertaSurvei_nama') ?? (session('pesertaAktif.nama') ?? null)));
                        @endphp

                        @if ($nama)
                            <div class="hidden md:block text-right">
                                <p class="text-xs text-slate-500">Peserta aktif</p>
                                <p class="text-sm font-semibold text-slate-800 truncate max-w-[160px]">
                                    {{ $nama }}
                                </p>
                            </div>
                        @endif

                        {{-- Logout --}}
                        @if (session()->has('peserta_id') || session()->has('pesertaSurvei_id') || !empty($pesertaAktif))
                            <form method="POST" action="{{ route('dashboard.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 bg-red-500 text-white text-xs md:text-sm rounded-lg hover:bg-red-600 transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-600">
                                    Logout
                                </button>
                            </form>
                        @endif
                    </div>
                </header>

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="mb-4 p-3 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-200 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-200 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- ===================== CONTENT + RIGHT SIDEBAR SLOT ===================== --}}
                <div class="max-w-7xl mx-auto fade-in">
                    @hasSection('right-sidebar')
                        <div class="grid grid-cols-1 lg:grid-cols-8 gap-6">
                            <div class="lg:col-span-5 space-y-6">
                                @yield('content')
                            </div>
                            <aside class="lg:col-span-3">
                                @yield('right-sidebar')
                            </aside>
                        </div>
                    @else
                        <div class="space-y-6">
                            @yield('content')
                        </div>
                    @endif
                </div>

            </main>
        </div>
    @endisset


    @stack('modals')
    @stack('scripts')

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('menu-toggle');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
        sidebar?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
        overlay?.classList.remove('pointer-events-none');
        toggleBtn?.setAttribute('aria-expanded', 'true');
        }

        function closeSidebar() {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
        overlay?.classList.add('pointer-events-none');
        toggleBtn?.setAttribute('aria-expanded', 'false');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                if (sidebar && sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });
        }
        if (overlay) overlay.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
</body>

</html>
