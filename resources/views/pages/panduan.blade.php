{{-- resources/views/pages/panduan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panduan - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    :root {
      --biru-brand: #1524AF;
      --kuning-stroke: #FFDE59;
      --merah-stroke: #861D23;
    }

    .yellow-stroke {
      text-shadow:
        -1px -1px 0 var(--kuning-stroke),
         1px -1px 0 var(--kuning-stroke),
        -1px  1px 0 var(--kuning-stroke),
         1px  1px 0 var(--kuning-stroke);
    }

    .oval-flow {
      width: 330px;
      height: 140px;
      background: #DBE7F7;
      border: 2px solid var(--biru-brand);
      border-radius: 50%;
    }

    /* LINGKARAN ANGKA STEP */
    .step-badge {
      width: 36px;
      height: 36px;
      border-radius: 9999px;
      background: linear-gradient(
        135deg,
        #0F5DC7 0%,     /* Biru tua full */
        #3F87DA 55%,    /* Biru mid (sesuai 55% di Figma) */
        #A9D4F7 100%    /* Biru muda cerah */
      );
      border: 3px solid #FFFFFF;
    }
  </style>
</head>

<body class="bg-[#F1F9FC] antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO --}}
  <x-layouts.app.profile-hero
    title="Panduan"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Panduan', 'route' => 'panduan'],
    ]"
    height="h-[320px]"
  />
  {{-- /HERO --}}

  {{-- =========================== --}}
  {{--   SECTION: ALUR PERMOHONAN  --}}
  {{-- =========================== --}}
  <section class="relative bg-white py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] text-center">

      {{-- BADGE --}}
      <div class="inline-block bg-[#FDECEC] text-[#861D23] text-sm px-4 py-1.5 rounded-md mb-3 font-[Volkhov] font-bold">
        Alur Permohonan Peserta
      </div>

      {{-- TITLE --}}
      <h2 class="text-[18px] md:text-[22px] lg:text-[24px] font-[Volkhov] font-bold
                 text-[#1524AF] yellow-stroke leading-snug mb-10 md:mb-12">
        Proses Permohonan Peserta Untuk Mengikuti Program Pelatihan di<br class="hidden md:block">
        UPT PTKK Dindik Jatim
      </h2>

      {{-- GAMBAR ALUR --}}
      <div class="max-w-5xl mx-auto">
        <img
          src="{{ asset('images/panduan.svg') }}"
          alt="Alur permohonan peserta pelatihan UPT PTKK"
          class="w-full h-auto"
          loading="lazy"
        >
      </div>

    </div>
  </section>

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
