<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - UPT PTKK Jatim</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md h-screen sticky top-0 flex flex-col">
        <div class="p-6 flex flex-col items-center border-b border-gray-200">
            <img src="https://placehold.co/80x80/5c76c1/fff?text=Logo" class="rounded-full mb-2">
            <h2 class="font-bold text-lg text-gray-800">Peserta Dashboard</h2>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard.home') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.home') ? 'active-link' : '' }}">Home</a>
            <a href="{{ route('dashboard.profile') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.profile') ? 'active-link' : '' }}">Profile</a>
            <a href="{{ route('dashboard.materi') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.materi') ? 'active-link' : '' }}">Materi</a>
            <a href="{{ route('dashboard.pretest') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.pretest') ? 'active-link' : '' }}">Pre-Test</a>
            <a href="{{ route('dashboard.posttest') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.posttest') ? 'active-link' : '' }}">Post-Test</a>
            <a href="{{ route('dashboard.feedback') }}" class="block px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white {{ request()->routeIs('dashboard.feedback') ? 'active-link' : '' }}">Feedback</a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 overflow-auto">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">@yield('page-title')</h1>
            <div>
                <span class="text-gray-600 mr-4">
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
        <div class="space-y-6 fade-in">
            @yield('content')
        </div>
    </main>

</body>
</html>
