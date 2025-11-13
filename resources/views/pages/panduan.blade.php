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

{{-- SECTION: Panduan Alur Permohonan Peserta (Zig-Zag, Badge Angka 100% Bulat) --}}
<section class="relative bg-[#FFFFFF] py-16 md:py-24">
  <style>
    /* Badge angka: paksa benar-benar bulat di semua environment */
    .badge-circle{
      width:72px; height:72px; border-radius:50% !important;
      display:grid; place-items:center; padding:0 !important; margin:0;
      background: linear-gradient(145deg, #0F5DC7 0%, #5A8BE8 100%);
      border:4px solid #FFFFFF; box-shadow:0 6px 12px rgba(21,36,175,0.25);
      line-height:1;
    }
    .badge-circle > span{ color:#FFFFFF; font-weight:700; font-size:20px; font-family:Montserrat, Arial, sans-serif; }
  </style>

  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] text-center">

    {{-- JUDUL UTAMA --}}
    <div class="mb-14">
      <div class="inline-block bg-[#FDECEC] text-[#861D23] text-sm px-4 py-1.5 rounded-md mb-3 font-[Volkhov] font-bold">
        Alur Permohonan Peserta
      </div>
      <h2 class="text-[22px] md:text-[26px] lg:text-[30px] font-[Volkhov] font-bold text-[#0E2A7B] leading-snug">
        Proses Permohonan Peserta Untuk Mengikuti Program Pelatihan di<br class="hidden md:block">
        UPT PTKK
      </h2>
    </div>

    {{-- GRID 2x2 (zig-zag) + ruang panah --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-[120px] md:gap-x-[40px] place-items-center">

      <!-- STEP 1 (kiri atas) -->
      <div class="order-1">
        <div class="w-[260px] h-[260px] bg-[#DBE7F7] border-[3px] border-[#1524AF] rounded-full
                    flex flex-col items-center justify-center p-8 mx-auto">
          <div class="badge-circle"><span>1</span></div>
          <h3 class="font-[Volkhov] font-bold text-[#1524AF] mt-[24px] mb-2">Lembaga/Sekolah</h3>
          <p class="font-[Montserrat] font-medium text-[14px] text-[#4B5563] leading-relaxed px-4">
            Mengajukan Surat Permohonan yang ditujukan untuk Kepala Dinas Pendidikan Prov. Jatim.
          </p>
        </div>
      </div>

      <!-- STEP 2 (kanan atas) -->
      <div class="order-2">
        <div class="w-[260px] h-[260px] bg-[#DBE7F7] border-[3px] border-[#1524AF] rounded-full
                    flex flex-col items-center justify-center p-8 mx-auto">
          <div class="badge-circle"><span>2</span></div>
          <h3 class="font-[Volkhov] font-bold text-[#1524AF] mt-[24px] mb-2">Dinas Pendidikan Prov. Jatim</h3>
          <p class="font-[Montserrat] font-medium text-[14px] text-[#4B5563] leading-relaxed px-4">
            Mendisposisi Surat Permohonan ke Kepala UPT PTKK untuk ditindaklanjuti.
          </p>
        </div>
      </div>

      <!-- STEP 4 (kiri bawah) -->
      <div class="order-4 md:order-3">
        <div class="w-[260px] h-[260px] bg-[#DBE7F7] border-[3px] border-[#1524AF] rounded-full
                    flex flex-col items-center justify-center p-8 mx-auto">
          <div class="badge-circle"><span>4</span></div>
          <h3 class="font-[Volkhov] font-bold text-[#1524AF] mt-[24px] mb-2">Cabang Dinas</h3>
          <p class="font-[Montserrat] font-medium text-[14px] text-[#4B5563] leading-relaxed px-4">
            Menindaklanjuti Nota Dinas ke Lembaga/Sekolah terkait.
          </p>
        </div>
      </div>

      <!-- STEP 3 (kanan bawah) -->
      <div class="order-3 md:order-4">
        <div class="w-[260px] h-[260px] bg-[#DBE7F7] border-[3px] border-[#1524AF] rounded-full
                    flex flex-col items-center justify-center p-8 mx-auto">
          <div class="badge-circle"><span>3</span></div>
          <h3 class="font-[Volkhov] font-bold text-[#1524AF] mt-[24px] mb-2">UPT PTKK</h3>
          <p class="font-[Montserrat] font-medium text-[14px] text-[#4B5563] leading-relaxed px-4">
            Membuat Nota Dinas permohonan peserta dan ditujukan ke Cabang Dinas di masing-masing daerah.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>



  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
