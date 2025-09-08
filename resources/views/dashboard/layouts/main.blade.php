<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard') - UPT PTKK Jatim</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Animasi fade in */
        .fade-in {
            animation: fadeIn 0.8s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        /* Hover cards */
        .card-hover:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Sidebar active link */
        .active-link {
            background-color: #2563eb; /* blue-600 */
            color: white;
        }

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
<body class="bg-blue-100 min-h-screen flex">

    @isset($isPendaftaran)
        <!-- Konten Pendaftaran (tidak diubah pada saran saat ini) -->
    @else
        <div class="flex flex-1 relative">
            <!-- Overlay untuk mobile sidebar -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

            {{-- Sidebar --}}
           <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40 flex flex-col border-r">
            <div class="flex items-center justify-center p-4 border-b">
                <img src="{{ asset('images/logo-upt-ptkk.png') }}" onerror="this.onerror=null;this.src='https://placehold.co/60x60/3B82F6/FFFFFF?text=LOGO';" alt="Logo UPT" class="rounded-full w-14 h-14 object-cover">
            </div>
            <nav class="flex-1 p-4" aria-label="Sidebar Navigation">
                 <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Peserta Dashboard</p>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard.home') }}" class="flex items-center p-3 rounded-lg font-medium {{ request()->routeIs('dashboard.home') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.profile') }}" class="flex items-center p-3 rounded-lg font-medium {{ request()->routeIs('dashboard.profile') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.pretest.index') }}" class="flex items-center p-3 rounded-lg font-medium {{ request()->routeIs('dashboard.pretest.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                           <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Pre-Test
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.posttest.index') }}" class="flex items-center p-3 rounded-lg font-medium {{ request()->routeIs('dashboard.posttest.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Post-Test
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.survey') }}" class="flex items-center p-3 rounded-lg font-medium {{ request()->routeIs('dashboard.survey') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Monev
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

            {{-- Main content --}}
            <main class="flex-1 lg:ml-64 p-6 overflow-auto">
                <header class="flex justify-between items-center mb-6 bg-blue-200 p-4 rounded-lg">
                    <div class="flex items-center gap-4">
                        {{-- Toggle Sidebar on Mobile --}}
                        <button id="menu-toggle" aria-label="Toggle Sidebar" class="lg:hidden p-2 rounded-md border border-blue-400 hover:bg-blue-200 text-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-400">
                            ☰
                        </button>
                        <h1 class="text-2xl font-bold text-blue-900">@yield('page-title', 'Dashboard Home')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-blue-800 font-medium">Hai, {{ Auth::check() ? Auth::user()->name : 'Peserta' }} 👋</span>
                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" role="button" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-600">Logout</button>
                            </form>
                        @endauth
                    </div>
                </header>

                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded shadow-sm">{{ session('error') }}</div>
                @endif

                {{-- Konten Dashboard --}}
                <div class="space-y-6 fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    @endisset

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('menu-toggle');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });
        }

        // Klik di overlay untuk menutup sidebar
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }
    </script>
</body>
</html>
