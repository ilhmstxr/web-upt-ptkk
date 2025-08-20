<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPT PTKK Dinas Pendidikan Jawa Timur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-upt-ptkk.png') }} " class="w-4">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            /* bg-gray-100 */
        }

        .fade-in-up {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover-effect:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .animate-pulse-once {
            animation: pulse-once 1.5s ease-in-out;
        }

        @keyframes pulse-once {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm p-4 sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo-upt-ptkk.png') }}" alt="Logo" class="w-8">
                <h1 class="text-xl font-bold text-gray-800">UPT PTKK Dinas Pendidikan Jawa Timur</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto p-4 md:p-8">
        <!-- Hero Section -->
        <section class="flex flex-col lg:flex-row items-center lg:space-x-12 mt-8 md:mt-12 fade-in-up">
            <div class="lg:w-1/2">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800">UPT PTKK</h2>
                <p class="mt-4 text-gray-600 leading-relaxed text-lg">
                    UPT Pengembangan Teknis Dan Keterampilan Kejuruan sebagai salah satu Unit Pelaksana Teknis dari
                    Dinas Pendidikan Propinsi Jawa Timur UPT PTKK bertugas dalam kegiatan dan pengembangan teknis dan
                    keterampilan kejuruan, ketatausahaan, dan pelayanan masyarakat.
                </p>
                <a href="/pendaftaran"
                    class="mt-6 inline-block px-8 py-3 bg-[#5c76c1] text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition-transform transform hover:scale-105 animate-pulse-once">
                    Daftar Sekarang
                </a>
            </div>
            <div class="lg:w-1/2 mt-8 lg:mt-0 flex justify-center">
                <img src="{{ asset('images/pelatihan.jpg') }}" 
                    alt="Pelatihan"
                    class="rounded-lg shadow-lg w-full h-auto">
            </div>
        </section>

        <!-- Pelatihan Tersedia Section -->
        <section class="mt-16 text-center">
            <h3 class="text-3xl font-bold text-gray-800">Pelatihan Tersedia</h3>
            <p class="mt-2 text-gray-600">Pilih salah satu dari pelatihan berikut.</p>
        </section>

        <!-- Cards Container -->
        <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <a href="/pelatihan/tata-boga"
                class="block bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 card-hover-effect fade-in-up">
                <img src="{{ asset('images/tata-boga.jpg') }}" 
                    alt="Tata Boga"
                    class="w-full h-48 object-cover rounded-lg shadow-md">
                <div class="p-4">
                    <h4 class="font-semibold text-lg text-gray-800">Tata Boga</h4>
                    <p class="text-sm text-gray-500 mt-1">25 - 30 Agustus 2025</p>
                    <button class="mt-3 px-4 py-2 bg-[#5c76c1] text-white rounded-lg hover:bg-blue-600 transition">
                        Lihat Detail
                    </button>
                </div>
            </a>

            <!-- Card 2 -->
            <a href="/pelatihan/tata-busana"
                class="block bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 card-hover-effect fade-in-up"
                style="animation-delay: 0.1s;">
                <img src="{{ asset('images/tata-busana.jpg') }}" 
                    alt="Tata Busana"
                    class="w-full h-48 object-cover rounded-lg shadow-md">
                <div class="p-4">
                    <h4 class="font-semibold text-lg text-gray-800">Tata Busana</h4>
                    <p class="text-sm text-gray-500 mt-1">25 - 30 Agustus 2025</p>
                    <button class="mt-3 px-4 py-2 bg-[#5c76c1] text-white rounded-lg hover:bg-blue-600 transition">
                        Lihat Detail
                    </button>
                </div>
            </a>

            <!-- Card 3 -->
            <a href="/pelatihan/tata-kecantikan"
                class="block bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 card-hover-effect fade-in-up"
                style="animation-delay: 0.2s;">
                <img src="{{ asset('images/tata-kecantikan.jpg') }}" 
                    alt="Tata Kecantikan"
                    class="w-full h-48 object-cover rounded-lg shadow-md">
                <div class="p-4">
                    <h4 class="font-semibold text-lg text-gray-800">Tata Kecantikan</h4>
                    <p class="text-sm text-gray-500 mt-1">25 - 30 Agustus 2025</p>
                    <button class="mt-3 px-4 py-2 bg-[#5c76c1] text-white rounded-lg hover:bg-blue-600 transition">
                        Lihat Detail
                    </button>
                </div>
            </a>

            <!-- Card 4 -->
            <a href="/pelatihan/teknik-pendingin"
                class="block bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 card-hover-effect fade-in-up"
                style="animation-delay: 0.3s;">
                <img src="{{ asset('images/tata-udara.jpg') }}" 
                    alt="Teknik Pendingin dan Tata Udara"
                    class="w-full h-48 object-cover rounded-lg shadow-md">
                <div class="p-4">
                    <h4 class="font-semibold text-lg text-gray-800">Teknik Pendingin dan Tata Udara</h4>
                    <p class="text-sm text-gray-500 mt-1">25 - 30 Agustus 2025</p>
                    <button class="mt-3 px-4 py-2 bg-[#5c76c1] text-white rounded-lg hover:bg-blue-600 transition">
                        Lihat Detail
                    </button>
                </div>
            </a>
        </section>
    </main>

</body>

</html>