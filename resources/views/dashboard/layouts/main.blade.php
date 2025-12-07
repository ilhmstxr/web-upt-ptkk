<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - UPT PTKK Jatim</title>

    {{-- Tailwind CDN (untuk production nanti idealnya pakai build Vite) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />

    @stack('styles')

    <style>
        body { font-family: 'Inter', sans-serif; }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
            opacity: 0;
            will-change: opacity, transform;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-hover {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        }

        .error-popup {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(10px);
            z-index: 50;
        }
        .error-popup.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
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
            <div id="sidebar-overlay"
                 class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

            {{-- Sidebar --}}
            <aside id="sidebar"
                   class="fixed inset-y-0 left-0 w-64 bg-white/95 backdrop-blur border-r border-slate-200 shadow-lg
                          transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40 flex flex-col"
                   aria-label="Sidebar">
                <div class="flex items-center justify-center p-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-upt-ptkk.jpg') }}"
                             onerror="this.onerror=null;this.src='https://placehold.co/60x60/3B82F6/FFFFFF?text=LOGO';"
                             alt="Logo UPT"
                             class="rounded-full w-12 h-12 object-cover border border-blue-100">
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

                <nav class="flex-1 p-4 space-y-4" aria-label="Sidebar Navigation">
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            Navigasi Utama
                        </p>
                        <ul class="space-y-1 text-sm">
                            {{-- Home --}}
                            <li>
                                <a href="{{ route('dashboard.home') }}"
                                   class="flex items-center p-2.5 rounded-lg font-medium
                                        {{ request()->routeIs('dashboard.home') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}"
                                   aria-current="{{ request()->routeIs('dashboard.home') ? 'page' : 'false' }}">
                                    <x-icon-home class="w-5 h-5 mr-3" />
                                    <span>Home</span>
                                </a>
                            </li>

                            {{-- Profil --}}
                            <li>
                                <a href="{{ route('dashboard.profile') }}"
                                   class="flex items-center p-2.5 rounded-lg font-medium
                                        {{ request()->routeIs('dashboard.profile') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}"
                                   aria-current="{{ request()->routeIs('dashboard.profile') ? 'page' : 'false' }}">
                                    <x-icon-user class="w-5 h-5 mr-3" />
                                    <span>Profil</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            Evaluasi & Monev
                        </p>
                        <ul class="space-y-1 text-sm">
                            {{-- Pre-Test --}}
                            <li>
                                <a href="{{ route('dashboard.pretest.index') }}"
                                   class="flex items-center p-2.5 rounded-lg font-medium
                                        {{ request()->routeIs('dashboard.pretest.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <x-icon-clipboard class="w-5 h-5 mr-3" />
                                    <span>Pre-Test</span>
                                </a>
                            </li>

                            {{-- Post-Test --}}
                            <li>
                                <a href="{{ route('dashboard.posttest.index') }}"
                                   class="flex items-center p-2.5 rounded-lg font-medium
                                        {{ request()->routeIs('dashboard.posttest.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <x-icon-check-circle class="w-5 h-5 mr-3" />
                                    <span>Post-Test</span>
                                </a>
                            </li>

                            {{-- Monev / Survey (pakai emot) --}}
                            <li>
                                <a href="{{ route('dashboard.survey') }}"
                                   class="flex items-center p-2.5 rounded-lg font-medium
                                        {{ request()->routeIs('dashboard.survey*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg leading-none">
                                        📊
                                    </span>
                                    <span>Monev / Survey</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </aside>

            {{-- Main content --}}
            <main class="flex-1 lg:ml-64 p-4 md:p-6 overflow-auto">
                <header class="flex justify-between items-center mb-6 bg-blue-100/80 border border-blue-200 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-3">
                        {{-- Toggle Sidebar on Mobile --}}
                        <button id="menu-toggle"
                                aria-label="Toggle Sidebar"
                                aria-controls="sidebar"
                                aria-expanded="false"
                                class="lg:hidden p-2 rounded-md border border-blue-300 hover:bg-blue-200/60 text-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-400">
                            ☰
                        </button>

                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-blue-900 leading-tight">
                                @yield('page-title', 'Dashboard Home')
                            </h1>
                            @hasSection('page-subtitle')
                                <p class="text-xs text-slate-500 mt-0.5">
                                    @yield('page-subtitle')
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @if(isset($pesertaAktif) || session()->has('pesertaAktif'))
                            <div class="hidden md:block text-right">
                                <p class="text-xs text-slate-500">Peserta aktif</p>
                                <p class="text-sm font-semibold text-slate-800 truncate max-w-[140px]">
                                    {{ $pesertaAktif->nama ?? session('pesertaAktif.nama') ?? 'Peserta' }}
                                </p>
                            </div>
                        @endif

                        @if(session()->has('pesertaAktif') || !empty($pesertaAktif))
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
                @if(session('success'))
                    <div class="mb-4 p-3 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-200 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-200 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Page content --}}
                <div class="space-y-6 fade-in max-w-7xl mx-auto">
                    @yield('content')
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
            if (sidebar) sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
        }

        function closeSidebar() {
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
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
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
</body>
</html>
