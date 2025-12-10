<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftaran Belum Dibuka - UPT PTKK Dinas Pendidikan Jawa Timur</title>
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
        display: flex;
        flex-direction: column;
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
        text-decoration: none;
      }

      .main-container {
        padding: 40px 20px;
        max-width: 1400px;
        margin: 0 auto;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
      }

      .info-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        width: 100%;
        text-align: center;
      }

      .info-icon {
        width: 80px;
        height: 80px;
        background: #e6f3ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: #1e6091;
      }

      .info-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e6091;
        margin-bottom: 15px;
      }

      .info-text {
        color: #555;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 30px;
      }

      .btn-home {
        display: inline-flex;
        align-items: center;
        padding: 12px 30px;
        background: #1e6091;
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(30, 96, 145, 0.3);
      }

      .btn-home:hover {
        background: #164a73;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 96, 145, 0.4);
      }

      /* Tailwind utility placeholder for SVG icon */
      .w-12 { width: 48px; }
      .h-12 { height: 48px; }
    </style>
</head>
<body>

    <header class="header">
      <div class="logo">
        <img src="{{ asset('logo-upt-ptkk.jpg') }}" alt="Logo UPT PTKK" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
        <div class="logo-fallback" style="display: none">UPT<br />PTKK</div>
      </div>
      <a href="{{ url('/') }}" class="header-title">UPT PTKK Dinas Pendidikan Jawa Timur</a>
    </header>

    <main class="main-container">
        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h2 class="info-title">Pendaftaran Belum Dibuka</h2>
            
            <p class="info-text">
                Mohon maaf, saat ini belum ada pelatihan yang berstatus <strong>Aktif</strong> atau pendaftaran sedang ditutup sementara.<br><br>
                Silakan cek kembali halaman ini secara berkala atau pantau media sosial resmi UPT PTKK untuk informasi terbaru.
            </p>
            
            <a href="{{ url('/') }}" class="btn-home">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </main>

</body>
</html>