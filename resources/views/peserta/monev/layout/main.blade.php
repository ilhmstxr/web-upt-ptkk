<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'MONEV Kegiatan Vokasi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-input { transition: all 0.3s; }
        .form-input:focus { box-shadow: 0 0 0 4px rgba(129,140,248,.3); }
        .btn-primary { transition: all .3s; background-image: linear-gradient(to right, #4f46e5, #7c3aed); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.2); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .rating-option { transition: all 0.3s; }
        .rating-option:hover { transform: translateY(-5px) scale(1.05); }
        .rating-option.selected { transform: scale(1.1); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5); border-color: #6366f1; }
        .emoji-rating { font-size: 28px; transition: all 0.3s; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 flex items-center justify-center">
        <div class="w-full max-w-4xl">

            <!-- Header Logo UPT -->
            <div class="flex items-center justify-center mb-6">
                <!-- Ganti src dengan asset() jika gambar ada di public/images -->
                <img src="/images/logo-upt.png" alt="Logo UPT" class="h-14 sm:h-16 md:h-20 mr-4" onerror="this.style.display='none'">
                <h2 class="text-base sm:text-lg md:text-xl font-bold text-indigo-900 whitespace-nowrap">
                    UPT. PENGEMBANGAN TEKNIS KETRAMPILAN DAN KEJURUAN
                </h2>
            </div>

            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            <div class="mt-8 text-center text-xs sm:text-sm text-gray-500">
                <p>© UPT. PENGEMBANGAN TEKNIS KETRAMPILAN DAN KEJURUAN</p>
                <p class="mt-1">Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi</p>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
