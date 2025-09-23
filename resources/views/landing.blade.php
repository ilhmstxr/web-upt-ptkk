{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UPT PTKK Dinas Pendidikan Jawa Timur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-upt-ptkk.jpg') }} " class="w-4">
    <style>
        /* ====== CSS dari desain kamu ====== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1e6091 0%, #4a9eff 100%); min-height: 100vh; }
        .header { background: white; padding: 15px 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 15px; }
        .logo { width: 60px; height: 60px; border-radius: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); }
        .logo img { width: 100%; height: 100%; object-fit: contain; }
        .logo-fallback { width: 100%; height: 100%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; text-align: center; line-height: 1.2; }
        .header-title { color: #333; font-size: 20px; font-weight: 600; }
        .main-container { padding: 40px 20px; max-width: 1400px; margin: 0 auto; }
        .hero-section { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-bottom: 60px; }
        .hero-content { color: white; }
        .hero-title { font-size: 48px; font-weight: 700; margin-bottom: 20px; line-height: 1.2; }
        .hero-description { font-size: 16px; line-height: 1.6; opacity: 0.95; }
        .hero-image-container { position: relative; display: flex; justify-content: center; align-items: center; }
        .hero-image { width: 350px; height: 350px; border-radius: 50%; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); border: 5px solid rgba(255, 255, 255, 0.2); }
        .hero-image img { width: 100%; height: 100%; object-fit: cover; }
        .programs-section { display: flex; justify-content: center; gap: 30px; margin-top: 40px; flex-wrap: nowrap; overflow-x: auto; padding: 0 10px; }
        .program-card { background: white; border-radius: 25px; padding: 25px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; cursor: pointer; text-decoration: none; color: inherit; display: block; border: 3px solid #e6f3ff; width: 280px; height: 320px; position: relative; flex-shrink: 0; }
        .program-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15); border-color: #4a9eff; }
        .program-card:hover .arrow-icon { transform: translateX(5px); }
        .program-image { width: 100%; height: 180px; border-radius: 15px; margin: 0 0 20px 0; overflow: hidden; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
        .program-image img { width: 100%; height: 100%; object-fit: cover; }
        .program-content { display: flex; flex-direction: column; align-items: flex-start; text-align: left; height: calc(100% - 200px); justify-content: space-between; }
        .program-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px; line-height: 1.2; text-align: left; }
        .program-date { color: #666; font-size: 14px; font-weight: 500; text-align: left; }
        .arrow-icon { position: absolute; bottom: 25px; right: 25px; width: 24px; height: 24px; background: #4a9eff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; transition: transform 0.3s ease; }
        .floating-elements { position: absolute; width: 100%; height: 100%; overflow: hidden; pointer-events: none; }
        .floating-circle { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.1); animation: float 6s ease-in-out infinite; }
        .circle-1 { width: 100px; height: 100px; top: 10%; left: 10%; animation-delay: 0s; }
        .circle-2 { width: 60px; height: 60px; top: 60%; right: 20%; animation-delay: 2s; }
        .circle-3 { width: 80px; height: 80px; bottom: 20%; left: 20%; animation-delay: 4s; }
        @keyframes float { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(180deg); } }
        @media (max-width: 768px) { .hero-section { grid-template-columns: 1fr; text-align: center; } .hero-title { font-size: 36px; } .programs-section { flex-wrap: wrap; justify-content: center; } .main-container { padding: 20px 15px; } }
        @media (max-width: 1200px) { .programs-section { gap: 20px; } .program-card { width: 260px; } }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-circle circle-1"></div>
        <div class="floating-circle circle-2"></div>
        <div class="floating-circle circle-3"></div>
    </div>

    <header class="header">
        <div class="logo">
            <img src="{{ asset('images/logo-upt-ptkk.jpg') }}" alt="Logo UPT PTKK" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
            <div class="logo-fallback" style="display: none">UPT<br>PTKK</div>
        </div>
        <h1 class="header-title">UPT PTKK Dinas Pendidikan Jawa Timur</h1>
    </header>

    <main class="main-container">
        <section class="hero-section">
            <div class="hero-content">
                <h2 class="hero-title">UPT PTKK</h2>
                <p class="hero-description">
                    UPT Pengembangan Teknis Dan Keterampilan Kejuruan sebagai salah satu Unit Pelaksana Teknis dari Dinas Pendidikan Provinsi Jawa Timur, bertugas dalam kegiatan dan pengembangan teknis dan keterampilan kejuruan, ketatausahaan, dan pelayanan masyarakat.
                </p>
            </div>
            <div class="hero-image-container">
                <div class="hero-image">
                    <img src="{{ asset('images/pelatihan.jpg') }}" alt="Professional Team" onerror="this.style.display='none'" />
                </div>
            <div class="lg:w-1/2 mt-8 lg:mt-0 flex justify-center">
                <img src="{{ asset('images/pelatihan.jpg') }}"
                    alt="Pelatihan"
                    class="rounded-lg shadow-lg w-full h-auto">
            </div>
        </section>

        <section class="programs-section">
            <a href="{{ route('detail-pelatihan', 'tata-boga') }}" class="program-card">
                <div class="arrow-icon">→</div>
                <div class="program-image">
                    <img src="{{ asset('images/tata-boga.jpg') }}" alt="Tata Boga" onerror="this.src='{{ asset('images/placeholder.jpg') }}'" />
                </div>
                <div class="program-content">
                    <h3 class="program-title">Tata Boga</h3>
                    <span class="program-date">25 - 30 Agustus 2025</span>
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

            <a href="{{ route('detail-pelatihan', 'tata-busana') }}" class="program-card">
                <div class="arrow-icon">→</div>
                <div class="program-image">
                    <img src="{{ asset('images/tata-busana.jpg') }}" alt="Tata Busana" onerror="this.src='{{ asset('images/placeholder.jpg') }}'" />
                </div>
                <div class="program-content">
                    <h3 class="program-title">Tata Busana</h3>
                    <span class="program-date">25 - 30 Agustus 2025</span>
                </div>
            </a>

            <a href="{{ route('detail-pelatihan', 'tata-kecantikan') }}" class="program-card">
                <div class="arrow-icon">→</div>
                <div class="program-image">
                    <img src="{{ asset('images/tata-kecantikan.jpg') }}" alt="Tata Kecantikan" onerror="this.src='{{ asset('images/placeholder.jpg') }}'" />
                </div>
                <div class="program-content">
                    <h3 class="program-title">Tata Kecantikan</h3>
                    <span class="program-date">25 - 30 Agustus 2025</span>
                </div>
            </a>

            <a href="{{ route('detail-pelatihan', 'teknik-pendingin-dan-tata-udara') }}" class="program-card">
                <div class="arrow-icon">→</div>
                <div class="program-image">
                    <img src="{{ asset('images/tata-udara.jpg') }}" alt="Teknik Pendingin dan Tata Udara" onerror="this.src='{{ asset('images/placeholder.jpg') }}'" />
                </div>
                <div class="program-content">
                    <h3 class="program-title">Teknik Pendingin dan Tata Udara</h3>
                    <span class="program-date">25 - 30 Agustus 2025</span>
                </div>
            </a>
        </section>
    </main>
</body>
</html>
