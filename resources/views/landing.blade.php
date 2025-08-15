{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UPT PTKK Dinas Pendidikan Jawa Timur</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1e6091 0%, #4a9eff 100%); min-height: 100vh; }

        /* Header */
        .header { background: white; padding: 15px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 15px; position: relative; }
        .logo { width: 60px; height: 60px; border-radius: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .logo img { width: 100%; height: 100%; object-fit: contain; }
        .logo-fallback { width: 100%; height: 100%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; text-align: center; line-height: 1.2; }
        .header-title { color: #333; font-size: 20px; font-weight: 600; }
        .otp-login-btn { margin-left:auto; padding:8px 15px; border:none; border-radius:8px; background:#4a9eff; color:white; font-weight:600; cursor:pointer; }

        /* Main Container */
        .main-container { padding: 40px 20px; max-width: 1400px; margin: 0 auto; }

        /* Hero Section */
        .hero-section { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-bottom: 60px; }
        .hero-content { color: white; }
        .hero-title { font-size: 48px; font-weight: 700; margin-bottom: 20px; line-height: 1.2; }
        .hero-description { font-size: 16px; line-height: 1.6; opacity: 0.95; }
        .hero-image-container { position: relative; display: flex; justify-content: center; align-items: center; }
        .hero-image { width: 350px; height: 350px; border-radius: 50%; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border:5px solid rgba(255,255,255,0.2); }
        .hero-image img { width: 100%; height: 100%; object-fit: cover; }

        /* Programs Section */
        .programs-section { display: flex; justify-content: center; gap: 30px; margin-top: 40px; flex-wrap: nowrap; overflow-x: auto; padding: 0 10px; }
        .program-card { background: white; border-radius: 25px; padding: 25px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: all 0.3s ease; cursor: pointer; text-decoration: none; color: inherit; display: block; border: 3px solid #e6f3ff; width: 280px; height: 320px; position: relative; flex-shrink: 0; }
        .program-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.15); border-color: #4a9eff; }
        .program-card:hover .arrow-icon { transform: translateX(5px); }
        .program-image { width: 100%; height: 180px; border-radius: 15px; margin: 0 0 20px 0; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .program-image img { width: 100%; height: 100%; object-fit: cover; }
        .program-content { display: flex; flex-direction: column; align-items: flex-start; text-align: left; height: calc(100% - 200px); justify-content: space-between; }
        .program-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px; line-height: 1.2; text-align: left; }
        .program-date { color: #666; font-size: 14px; font-weight: 500; text-align: left; }
        .arrow-icon { position: absolute; bottom: 25px; right: 25px; width: 24px; height: 24px; background: #4a9eff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; transition: transform 0.3s ease; }

        /* Floating Circles */
        .floating-elements { position: absolute; width: 100%; height: 100%; overflow: hidden; pointer-events: none; }
        .floating-circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.1); animation: float 6s ease-in-out infinite; }
        .circle-1 { width: 100px; height: 100px; top: 10%; left: 10%; animation-delay: 0s; }
        .circle-2 { width: 60px; height: 60px; top: 60%; right: 20%; animation-delay: 2s; }
        .circle-3 { width: 80px; height: 80px; bottom: 20%; left: 20%; animation-delay: 4s; }
        @keyframes float { 0%,100%{transform:translateY(0px) rotate(0deg);}50%{transform:translateY(-20px) rotate(180deg);} }

        /* Modal OTP */
        #otp-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999; }
        #otp-modal .modal-content { background:white; padding:30px; border-radius:12px; max-width:400px; width:90%; }
        #otp-modal input { width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc; }
        #otp-modal button { width:100%; padding:10px; border:none; border-radius:8px; cursor:pointer; }
        #otp-modal .submit-btn { background:#4a9eff; color:white; margin-bottom:10px; }
        #otp-modal .cancel-btn { background:#ccc; color:#333; }

        @media (max-width: 768px) { 
            .hero-section { grid-template-columns: 1fr; text-align:center; } 
            .hero-title { font-size:36px; } 
            .programs-section { flex-wrap: wrap; justify-content:center; } 
            .main-container { padding:20px 15px; } 
        }
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
            <img src="{{ asset('images/logo-upt-ptkk.png') }}" alt="Logo UPT PTKK" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
            <div class="logo-fallback" style="display:none">UPT<br>PTKK</div>
        </div>
        <h1 class="header-title">UPT PTKK Dinas Pendidikan Jawa Timur</h1>
        <button class="otp-login-btn" id="otp-login-btn">Login / OTP</button>
    </header>

    <main class="main-container">
        {{-- Hero Section --}}
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
            </div>
        </section>

        {{-- Programs Section --}}
        <section id="programs-section" class="programs-section">
            @foreach($pelatihans as $pelatihan)
                <a href="{{ route('detail-pelatihan', $pelatihan->slug) }}" class="program-card">
                    <div class="arrow-icon">→</div>
                    <div class="program-image">
                        <img src="{{ asset('storage/'.$pelatihan->gambar) }}" 
                             alt="{{ $pelatihan->judul }}" 
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'" />
                    </div>
                    <div class="program-content">
                        <h3 class="program-title">{{ $pelatihan->judul }}</h3>
                        <span class="program-date">
                            {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d M Y') }} 
                            - 
                            {{ \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d M Y') }}
                        </span>
                    </div>
                </a>
            @endforeach
        </section>
    </main>

    {{-- Modal OTP --}}
    <div id="otp-modal">
        <div class="modal-content">
            <h3>Login dengan OTP</h3>
            <form action="{{ route('otp.form') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email / No. HP" required />
                <button type="submit" class="submit-btn">Kirim OTP</button>
            </form>
            <button onclick="document.getElementById('otp-modal').style.display='none';" class="cancel-btn">Batal</button>
        </div>
    </div>

    <script>
        document.getElementById('otp-login-btn').addEventListener('click', function(){
            document.getElementById('otp-modal').style.display = 'flex';
        });
    </script>
</body>
</html>
