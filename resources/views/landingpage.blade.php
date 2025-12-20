<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UPT PTKK Dinas Pendidikan Jawa Timur</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #1e6091 0%, #4a9eff 100%);
        min-height: 100vh;
      }

      .header {
        background: white;
        padding: 15px 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 15px;
      }

      .logo {
        width: 60px;
        height: 60px;
        border-radius: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      .logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
      }

      .logo-fallback {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        text-align: center;
        line-height: 1.2;
      }

      .header-title {
        color: #333;
        font-size: 20px;
        font-weight: 600;
      }

      .main-container {
        padding: 40px 20px;
        max-width: 1400px;
        margin: 0 auto;
      }

      .hero-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
        margin-bottom: 60px;
      }

      .hero-content {
        color: white;
      }

      .hero-title {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.2;
      }

      .hero-description {
        font-size: 16px;
        line-height: 1.6;
        opacity: 0.95;
      }

      .hero-image-container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .hero-image {
        width: 350px;
        height: 350px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        border: 5px solid rgba(255, 255, 255, 0.2);
      }

      .hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .programs-section {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 40px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding: 0 10px;
      }

      .program-card {
        background: white;
        border-radius: 25px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 3px solid #e6f3ff;
        width: 280px;
        height: 320px;
        position: relative;
        flex-shrink: 0;
      }

      .program-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        border-color: #4a9eff;
      }

      .program-card:hover .arrow-icon {
        transform: translateX(5px);
      }

      .program-image {
        width: 100%;
        height: 180px;
        border-radius: 15px;
        margin: 0 0 20px 0;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .program-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .program-content {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        height: calc(100% - 200px);
        justify-content: space-between;
      }

      .program-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.2;
        text-align: left;
      }

      .program-date {
        color: #666;
        font-size: 14px;
        font-weight: 500;
        text-align: left;
      }

      .arrow-icon {
        position: absolute;
        bottom: 25px;
        right: 25px;
        width: 24px;
        height: 24px;
        background: #4a9eff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        transition: transform 0.3s ease;
      }

      .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
      }

      .floating-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
      }

      .circle-1 {
        width: 100px;
        height: 100px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
      }

      .circle-2 {
        width: 60px;
        height: 60px;
        top: 60%;
        right: 20%;
        animation-delay: 2s;
      }

      .circle-3 {
        width: 80px;
        height: 80px;
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
      }

      @keyframes float {
        0%,
        100% {
          transform: translateY(0px) rotate(0deg);
        }
        50% {
          transform: translateY(-20px) rotate(180deg);
        }
      }

      @media (max-width: 768px) {
        .hero-section {
          grid-template-columns: 1fr;
          text-align: center;
        }

        .hero-title {
          font-size: 36px;
        }

        .programs-section {
          flex-wrap: wrap;
          justify-content: center;
        }

        .main-container {
          padding: 20px 15px;
        }
      }

      @media (max-width: 1200px) {
        .programs-section {
          gap: 20px;
        }

        .program-card {
          width: 260px;
        }
      }
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
        <!-- Ganti src dengan URL logo UPT PTKK Anda -->
        <img src="./logo-upt-ptkk.jpg" alt="Logo UPT PTKK" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
        <div class="logo-fallback" style="display: none">UPT<br />PTKK</div>
      </div>
      <h1 class="header-title">UPT PTKK Dinas Pendidikan Jawa Timur</h1>
    </header>

    <main class="main-container">
      <section class="hero-section">
        <div class="hero-content">
          <h2 class="hero-title">UPT PTKK</h2>
          <p class="hero-description">
            UPT Pengembangan Teknis Dan Keterampilan Kejuruan sebagai salah satu Unit Pelaksana Teknis dari Dinas Pendidikan Propinsi Jawa timur UPT PTKK bertugas dalam kegiatan dan pengembangan teknis dan keterampilan kejuruan,
            ketatausahaan, dan pelayanan masyarakat.
          </p>
        </div>
        <div class="hero-image-container">
          <div class="hero-image">
            <img src="images/pelatihan.jpg" alt="Professional Team" onerror="this.style.display='none'" />
          </div>
        </div>
      </section>

      <section class="programs-section">
        @php
          $statPelatihans = collect($pelatihans ?? [])
            ->sortByDesc(function ($p) {
              return data_get($p, 'tanggal_selesai')
                ?? data_get($p, 'tanggal_mulai')
                ?? data_get($p, 'id', 0);
            })
            ->take(4);
        @endphp

        @if($statPelatihans->isNotEmpty())
          @foreach($statPelatihans as $pel)
            @php
              $name = data_get($pel, 'nama') ?? data_get($pel, 'nama_pelatihan') ?? 'Pelatihan';
              $img = data_get($pel, 'fotos.0') ?? data_get($pel, 'image') ?? 'images/placeholder.jpg';
              $start = data_get($pel, 'tanggal_mulai');
              $end = data_get($pel, 'tanggal_selesai');
              $dateText = ($start && $end) ? "{$start} - {$end}" : ($start ?? '-');
            @endphp
            <a href="#" class="program-card">
              <div class="arrow-icon">→</div>
              <div class="program-image">
                <img src="{{ $img }}" alt="{{ $name }}" onerror="this.src='images/placeholder.jpg'" />
              </div>
              <div class="program-content">
                <h3 class="program-title">{{ $name }}</h3>
                <span class="program-date">{{ $dateText }}</span>
              </div>
            </a>
          @endforeach
        @else
          <a href="#" class="program-card" onclick="handleCardClick('tata-boga')">
            <div class="arrow-icon">→</div>
            <div class="program-image">
              <img src="images/tata-boga.jpg" alt="Tata Boga" onerror="this.src='images/placeholder.jpg'" />
            </div>
            <div class="program-content">
              <h3 class="program-title">Tata Boga</h3>
              <span class="program-date">25 - 30 Agustus 2025</span>
            </div>
          </a>

          <a href="#" class="program-card" onclick="handleCardClick('tata-busana')">
            <div class="arrow-icon">→</div>
            <div class="program-image">
              <img src="images/tata-busana.jpg" alt="Tata Busana" onerror="this.src='images/placeholder.jpg'" />
            </div>
            <div class="program-content">
              <h3 class="program-title">Tata Busana</h3>
              <span class="program-date">25 - 30 Agustus 2025</span>
            </div>
          </a>

          <a href="#" class="program-card" onclick="handleCardClick('tata-kecantikan')">
            <div class="arrow-icon">→</div>
            <div class="program-image">
              <img src="images/tata-kecantikan.jpg" alt="Tata Kecantikan" onerror="this.src='images/placeholder.jpg'" />
            </div>
            <div class="program-content">
              <h3 class="program-title">Tata Kecantikan</h3>
              <span class="program-date">25 - 30 Agustus 2025</span>
            </div>
          </a>

          <a href="#" class="program-card" onclick="handleCardClick('teknik-pendingin-tata-udara')">
            <div class="arrow-icon">→</div>
            <div class="program-image">
              <img src="images/tata-udara.jpg" alt="Teknik Pendingin dan Tata Udara" onerror="this.src='images/placeholder.jpg'" />
            </div>
            <div class="program-content">
              <h3 class="program-title">Teknik Pendingin dan Tata Udara</h3>
              <span class="program-date">25 - 30 Agustus 2025</span>
            </div>
          </a>
        @endif
      </section>
    </main>

    <script>
      function handleCardClick(programType) {
        alert(`Mengarahkan ke form pendaftaran untuk program: ${programType.replace(/-/g, " ")}`);
        window.location.href = `index.html?program=${programType}`;
      }

      // Animasi smooth scroll / fade-in untuk kartu
      document.addEventListener("DOMContentLoaded", function () {
        const cards = document.querySelectorAll(".program-card");

        cards.forEach((card, index) => {
          card.style.opacity = "0";
          card.style.transform = "translateY(20px)";

          setTimeout(() => {
            card.style.transition = "all 0.6s ease";
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
          }, index * 200);
        });
      });
    </script>
  </body>
</html>
