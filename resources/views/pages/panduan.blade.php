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

    .section-container {
      max-width: 1280px;
      margin-left: auto;
      margin-right: auto;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
    @media (min-width: 768px) {
      .section-container { padding-left: 3rem; padding-right: 3rem; }
    }
    @media (min-width: 1024px) {
      .section-container { padding-left: 80px; padding-right: 80px; }
    }
  </style>
</head>

<body class="bg-[#FEFEFE] antialiased">

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
    {{-- image="images/panduan/hero-panduan.jpg" --}} {{-- opsional jika ada --}}
  />
  {{-- /HERO --}}



  
  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
