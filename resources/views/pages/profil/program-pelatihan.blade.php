{{-- resources/views/pages/profil/program-pelatihan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Program Pelatihan - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Font Volkhov --}}
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">

  {{-- Font Montserrat --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    .section-container {
      max-width: 80rem; /* max-w-7xl */
      margin-left: auto;
      margin-right: auto;
      padding-left: 1.5rem;  /* px-6 */
      padding-right: 1.5rem;
      padding-top: 2.5rem;   /* py-10 */
      padding-bottom: 2.5rem;
    }

    @media (min-width: 768px) {
      .section-container {
        padding-left: 3rem;   /* md:px-12 */
        padding-right: 3rem;
        padding-top: 3rem;    /* md:py-12 */
        padding-bottom: 3rem;
      }
    }

    @media (min-width: 1024px) {
      .section-container {
        padding-left: 80px;   /* lg:px-[80px] */
        padding-right: 80px;
        padding-top: 4rem;    /* lg:py-16 */
        padding-bottom: 4rem;
      }
    }

    .upt-stroke {
      text-shadow:
        -1px -1px 0 #861D23,
         1px -1px 0 #861D23,
        -1px  1px 0 #861D23,
         1px  1px 0 #861D23;
    }

    .yellow-stroke {
      text-shadow:
        -1px -1px 0 #FFDE59,
         1px -1px 0 #FFDE59,
        -1px  1px 0 #FFDE59,
         1px  1px 0 #FFDE59,
         0    0   1px #FFDE59;
    }

    .tujuan-card {
      background: #FEFEFE;
      box-shadow:
        0 2px 4px rgba(0, 0, 0, .06),
        0 12px 24px rgba(0, 0, 0, .08),
        0 40px 80px rgba(0, 0, 0, .08);
    }
  </style>
</head>

<body class="bg-[#F1F9FC] antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO (komponen reusable) --}}
  <x-layouts.app.profile-hero
    title="Program Pelatihan"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Profil'],
      ['label' => 'Program Pelatihan'],
    ]"
    image="images/profil/profil-upt.JPG"
    height="h-[368px]"
  />
  {{-- /HERO --}}

  {{-- SECTION: Mobil Training Unit --}}
  @isset($mtu)
  <section id="mobil-training-unit" class="relative bg-[#F1F9FC]">
    <div class="section-container">

      @php
        // aman untuk cast array / JSON string
        $galeriMtu = is_array($mtu->galeri)
          ? $mtu->galeri
          : (json_decode($mtu->galeri ?? '[]', true) ?? []);
      @endphp

      {{-- ========== MOBILE (HP ONLY) ========== --}}
      <div class="block md:hidden">
        <div class="grid grid-cols-1 gap-[3px] sm:gap-4 items-center mb-8">

          {{-- JUDUL --}}
          <div class="flex justify-center">
            <h2 class="font-['Volkhov'] font-bold
                       text-[24px]
                       text-[#1524AF] yellow-stroke
                       mb-1 text-center">
              {{ $mtu->judul }}
            </h2>
          </div>

          {{-- FOTO UTAMA --}}
          <div class="flex justify-center">
            @if($mtu->hero_image)
              <img src="{{ asset('storage/' . $mtu->hero_image) }}"
                   alt="{{ $mtu->judul }}"
                   class="w-full h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                   loading="lazy">
            @endif
          </div>

          {{-- TEKS --}}
          <div>
            <p class="font-[Montserrat]
                      text-[16px]
                      text-[#081526]
                      leading-relaxed text-justify">
              {{ $mtu->deskripsi }}
            </p>
          </div>

        </div>

        {{-- 3 FOTO BAWAH (HP) --}}
        <div class="grid grid-cols-1 gap-6">
          @foreach($galeriMtu as $foto)
            <img src="{{ asset('storage/' . $foto) }}"
                 alt="Foto {{ $mtu->judul }}"
                 class="w-full h-[220px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                 loading="lazy">
          @endforeach
        </div>
      </div>

      {{-- ========== TABLET & DESKTOP (MD+) ========== --}}
      <div class="hidden md:block">
        {{-- 2 Kolom: Kiri (judul + teks), Kanan (foto utama) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-14 items-center mb-8">

          {{-- KIRI: Judul + Teks --}}
          <div>
            <h2 class="font-['Volkhov'] font-bold
                       text-[24px] md:text-[22px] lg:text-[26px]
                       text-[#1524AF] yellow-stroke
                       mb-3 text-left">
              {{ $mtu->judul }}
            </h2>

            <p class="font-[Montserrat]
                      text-[15px] md:text-[15px] lg:text-[17px]
                      text-[#081526]
                      leading-relaxed text-justify">
              {{ $mtu->deskripsi }}
            </p>
          </div>

          {{-- KANAN: Foto Utama --}}
          <div class="flex justify-center md:justify-end">
            @if($mtu->hero_image)
              <img src="{{ asset('storage/' . $mtu->hero_image) }}"
                   alt="{{ $mtu->judul }}"
                   class="w-full h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                   loading="lazy">
            @endif
          </div>

        </div>

        {{-- 3 FOTO BAWAH (MD+) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-6 lg:gap-8">
          @foreach($galeriMtu as $foto)
            <img src="{{ asset('storage/' . $foto) }}"
                 alt="Foto {{ $mtu->judul }}"
                 class="w-full h-[220px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                 loading="lazy">
          @endforeach
        </div>
      </div>

    </div>
  </section>
  @endisset

  {{-- SECTION: Diklat Peningkatan Kompetensi --}}
  @isset($program)
  <section id="diklat-peningkatan-kompetensi" class="relative bg-[#F1F9FC]">
    <div class="section-container">

      @php
        $galeriProgram = is_array($program->galeri)
          ? $program->galeri
          : (json_decode($program->galeri ?? '[]', true) ?? []);
      @endphp

      {{-- ========== MOBILE (HP) ========== --}}
      <div class="md:hidden mb-8">
        {{-- Judul --}}
        <h2 class="font-['Volkhov'] font-bold text-[24px]
                   text-[#1524AF] yellow-stroke mb-3 text-center">
          {{ $program->judul }}
        </h2>

        {{-- Foto Utama --}}
        @if($program->hero_image)
        <div class="flex justify-center mb-3">
          <img src="{{ asset('storage/' . $program->hero_image) }}"
               alt="{{ $program->judul }} - UPT PTKK"
               class="w-full h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
               loading="lazy">
        </div>
        @endif

        {{-- Teks --}}
        <p class="font-[Montserrat] text-[15px]
                  text-[#081526] leading-relaxed text-justify">
          {{ $program->deskripsi }}
        </p>
      </div>

      {{-- ========== TABLET & DESKTOP ========== --}}
      <div class="hidden md:grid md:grid-cols-2 gap-8 md:gap-14 items-center mb-8">

        {{-- Kolom KIRI: Gambar Utama --}}
        <div class="flex justify-center md:justify-start">
          @if($program->hero_image)
            <img src="{{ asset('storage/' . $program->hero_image) }}"
                 alt="{{ $program->judul }} - UPT PTKK"
                 class="w-full h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                 loading="lazy">
          @endif
        </div>

        {{-- Kolom KANAN: Judul + Deskripsi --}}
        <div>
          <h2 class="font-['Volkhov'] font-bold
                     md:text-[24px] lg:text-[26px]
                     text-[#1524AF] yellow-stroke mb-2">
            {{ $program->judul }}
          </h2>

          <p class="font-[Montserrat]
                    md:text-[15px] lg:text-[17px]
                    text-[#081526] leading-relaxed text-justify">
            {{ $program->deskripsi }}
          </p>
        </div>
      </div>

      {{-- 3 Foto Bawah --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
        @foreach($galeriProgram as $foto)
          <img src="{{ asset('storage/' . $foto) }}"
               alt="Foto {{ $program->judul }}"
               class="w-full h-[220px] object-cover border-[3px] border-[#1524AF] rounded-xl"
               loading="lazy">
        @endforeach
      </div>
    </div>
  </section>
  @endisset

  {{-- SECTION: Sertifikasi Berbasis KKNI Bertaraf Nasional --}}
  @isset($sertifikasi)
  <section id="sertifikasi-kkni" class="relative bg-[#F1F9FC]">
    <div class="section-container">

      @php
        $galeriSertifikasi = is_array($sertifikasi->galeri)
          ? $sertifikasi->galeri
          : (json_decode($sertifikasi->galeri ?? '[]', true) ?? []);
      @endphp

      {{-- MOBILE --}}
      <div class="block md:hidden mb-8">

        <h2 class="font-['Volkhov'] font-bold text-[24px]
                   text-[#1524AF] yellow-stroke leading-tight mb-3 text-center">
          {{ $sertifikasi->judul }}
        </h2>

        <div class="flex justify-center mb-4">
          @if($sertifikasi->hero_image)
            <img src="{{ asset('storage/' . $sertifikasi->hero_image) }}"
                 alt="{{ $sertifikasi->judul }}"
                 class="w-full h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                 loading="lazy">
          @endif
        </div>

        <div class="flex gap-4 items-center">
          <img src="{{ asset('images/profil/logosertifikasi.svg') }}"
               alt="Logo Sertifikasi KKNI"
               class="w-[40px] h-auto object-contain shrink-0"
               loading="lazy">
          <p class="font-[Montserrat] text-[16px]
                    text-[#081526] leading-relaxed text-justify">
            {{ $sertifikasi->deskripsi }}
          </p>
        </div>
      </div>

      {{-- TABLET & DESKTOP --}}
      <div class="hidden md:grid grid-cols-12 gap-6 items-start mb-10">

        {{-- LOGO --}}
        <div class="flex justify-center md:justify-start md:col-span-1">
          <img src="{{ asset('images/profil/logosertifikasi.svg') }}"
               alt="Logo Sertifikasi KKNI"
               class="w-fit md:w-[100px] md:max-h-[330px] object-contain"
               loading="lazy">
        </div>

        {{-- TEKS --}}
        <div class="md:col-span-5 md:pl-4 lg:pl-6 md:pr-6">
          <h2 class="font-['Volkhov'] font-bold text-[24px] lg:text-[26px]
                     text-[#1524AF] yellow-stroke leading-tight mb-3">
            {{ $sertifikasi->judul }}
          </h2>
          <p class="font-[Montserrat] text-[16px] lg:text-[17px]
                    text-[#081526] leading-relaxed text-justify">
            {{ $sertifikasi->deskripsi }}
          </p>
        </div>

        {{-- GAMBAR UTAMA --}}
        <div class="flex justify-center md:justify-end md:col-span-6">
          @if($sertifikasi->hero_image)
            <img src="{{ asset('storage/' . $sertifikasi->hero_image) }}"
                 alt="{{ $sertifikasi->judul }}"
                 class="w-full max-h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                 loading="lazy">
          @endif
        </div>

      </div>

      {{-- FOTO BAWAH --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
        @foreach($galeriSertifikasi as $foto)
          <img src="{{ asset('storage/' . $foto) }}"
               alt="Foto {{ $sertifikasi->judul }}"
               class="w-full h-[220px] object-cover border-[3px] border-[#1524AF] rounded-xl"
               loading="lazy">
        @endforeach
      </div>
    </div>
  </section>
  @endisset

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
