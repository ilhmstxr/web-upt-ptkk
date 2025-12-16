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
      max-width: 80rem;
      margin-left: auto;
      margin-right: auto;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
      padding-top: 2.5rem;
      padding-bottom: 2.5rem;
    }

    @media (min-width: 768px) {
      .section-container {
        padding-left: 3rem;
        padding-right: 3rem;
        padding-top: 3rem;
        padding-bottom: 3rem;
      }
    }

    @media (min-width: 1024px) {
      .section-container {
        padding-left: 80px;
        padding-right: 80px;
        padding-top: 4rem;
        padding-bottom: 4rem;
      }
    }

    .yellow-stroke {
      text-shadow:
        -1px -1px 0 #FFDE59,
         1px -1px 0 #FFDE59,
        -1px  1px 0 #FFDE59,
         1px  1px 0 #FFDE59,
         0    0   1px #FFDE59;
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
    title="Program Pelatihan"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Profil'],
      ['label' => 'Program Pelatihan'],
    ]"
    image="images/profil/profil-upt.JPG"
    height="h-[368px]"
  />

  {{-- CONTENT DINAMIS --}}
  <section class="relative bg-[#F1F9FC]">
    <div class="section-container">

      @if(!isset($items) || $items->count() === 0)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <p class="font-[Montserrat] text-[#081526]">
            Belum ada data Program Pelatihan. Silakan tambah data melalui Admin.
          </p>
        </div>
      @endif

    </div>
  </section>

  @foreach($items as $i => $item)
    @php
      // karena di model sudah casts array, biasanya langsung array
      $galeri = is_array($item->galeri)
        ? $item->galeri
        : (json_decode($item->galeri ?? '[]', true) ?? []);

      // tampilkan max 3 foto (sesuai layout)
      $galeri3 = array_slice($galeri, 0, 3);

      // selang-seling desktop
      $reverse = $i % 2 === 1;
    @endphp

    <section class="relative bg-[#F1F9FC]">
      <div class="section-container">

        {{-- ========== MOBILE (HP) ========== --}}
        <div class="block md:hidden mb-10">
          <h2 class="font-['Volkhov'] font-bold text-[24px] text-[#1524AF] yellow-stroke mb-3 text-center">
            {{ $item->judul }}
          </h2>

          @if($item->hero_image)
            <div class="flex justify-center mb-3">
              <img
                src="{{ asset('storage/' . $item->hero_image) }}"
                alt="{{ $item->judul }}"
                class="w-full h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                loading="lazy"
              >
            </div>
          @endif

          @if($item->deskripsi)
            <p class="font-[Montserrat] text-[15px] text-[#081526] leading-relaxed text-justify">
              {{ $item->deskripsi }}
            </p>
          @endif

          @if(!empty($galeri3))
            <div class="grid grid-cols-1 gap-6 mt-6">
              @foreach($galeri3 as $foto)
                <img
                  src="{{ asset('storage/' . $foto) }}"
                  alt="Foto {{ $item->judul }}"
                  class="w-full h-[220px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                  loading="lazy"
                >
              @endforeach
            </div>
          @endif
        </div>

        {{-- ========== TABLET & DESKTOP (MD+) ========== --}}
        <div class="hidden md:block">

          {{-- 2 kolom: teks + hero (selang-seling) --}}
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-14 items-center mb-8">

            {{-- TEKS --}}
            <div class="{{ $reverse ? 'order-2' : 'order-1' }}">
              <h2 class="font-['Volkhov'] font-bold md:text-[24px] lg:text-[26px] text-[#1524AF] yellow-stroke mb-3">
                {{ $item->judul }}
              </h2>

              @if($item->deskripsi)
                <p class="font-[Montserrat] md:text-[15px] lg:text-[17px] text-[#081526] leading-relaxed text-justify">
                  {{ $item->deskripsi }}
                </p>
              @endif
            </div>

            {{-- HERO IMAGE --}}
            <div class="flex {{ $reverse ? 'justify-start order-1' : 'justify-end order-2' }}">
              @if($item->hero_image)
                <img
                  src="{{ asset('storage/' . $item->hero_image) }}"
                  alt="{{ $item->judul }}"
                  class="w-full h-[330px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                  loading="lazy"
                >
              @else
                <div class="w-full h-[330px] border-[3px] border-dashed border-[#1524AF] rounded-xl flex items-center justify-center">
                  <span class="font-[Montserrat] text-[#081526] text-sm">Belum ada foto hero</span>
                </div>
              @endif
            </div>

          </div>

          {{-- 3 foto bawah --}}
          @if(!empty($galeri3))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
              @foreach($galeri3 as $foto)
                <img
                  src="{{ asset('storage/' . $foto) }}"
                  alt="Foto {{ $item->judul }}"
                  class="w-full h-[220px] object-cover border-[3px] border-[#1524AF] rounded-xl"
                  loading="lazy"
                >
              @endforeach
            </div>
          @endif

        </div>

      </div>
    </section>
  @endforeach

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
