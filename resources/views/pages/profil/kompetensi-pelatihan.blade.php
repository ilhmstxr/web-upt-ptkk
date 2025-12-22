{{-- resources/views/pages/profil/bidang-kompetensi.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kompetensi Pelatihan - UPT PTKK Jawa Timur</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    #scroll-pane::-webkit-scrollbar { display: none; }
    .stroke-yellow { paint-order: stroke fill; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>

<body class="bg-[#F1F9FC] antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO --}}
  <x-layouts.app.profile-hero
    title="Bidang Kompetensi"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Profil'],
      ['label' => 'Bidang Kompetensi '],
    ]"
    image="images/profil/profil-upt.JPG"
    height="h-[368px]"
  />

  @php
  $listKompetensi = $listKompetensi ?? collect();

  // split jadi 2 kolom berdasarkan urutan (id sudah urut dari controller)
  $leftCol  = $listKompetensi->filter(fn ($_, $i) => $i % 2 === 0)->values(); // index 0,2,4...
  $rightCol = $listKompetensi->filter(fn ($_, $i) => $i % 2 === 1)->values(); // index 1,3,5...
@endphp

<section class="w-full bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- MOBILE: 1 kolom --}}
    <div class="md:hidden space-y-6">
      @forelse ($listKompetensi as $kompetensi)
        @php
          $judul = $kompetensi->nama_kompetensi;
          $desc  = $kompetensi->deskripsi;
          $img   = $kompetensi->gambar
            ? asset('storage/'.$kompetensi->gambar)
            : asset('images/profil/default-bidang.svg');
        @endphp

        <article class="relative overflow-hidden rounded-2xl shadow-md border border-[#1524AF] h-72">
          <button type="button" onclick="openImgModal('{{ $img }}', @js($judul))" class="block w-full h-full">
            <img src="{{ $img }}" alt="{{ $judul }}" class="w-full h-full object-cover cursor-zoom-in" loading="lazy">
          </button>

          <div class="absolute inset-0 pointer-events-none"
               style="background:linear-gradient(to top, rgba(21,36,175,.7) 12%, rgba(21,36,175,.35) 28%, rgba(0,0,0,0) 72%);"></div>

          <div class="absolute inset-0 flex flex-col justify-end p-5 space-y-2 text-white">
            <h3 class="font-[Volkhov] font-bold text-[20px] text-[#FFDE59]"
                style="-webkit-text-stroke:.85px #1524AF;">
              {{ $judul }}
            </h3>
            <p class="text-[11px] font-[Montserrat] leading-tight text-justify opacity-95">
              {{ $desc }}
            </p>
          </div>
        </article>
      @empty
        <div class="bg-white p-6 rounded-2xl shadow-sm border">
          <p class="font-[Montserrat] text-[#081526]">Belum ada data kompetensi.</p>
        </div>
      @endforelse
    </div>

    {{-- TABLET & DESKTOP: 2 kolom --}}
    <div class="hidden md:grid grid-cols-2 gap-8">

      {{-- KOLOM KIRI --}}
      <div class="space-y-8">
        @forelse ($leftCol as $kompetensi)
          @php
            $judul = $kompetensi->nama_kompetensi;
            $desc  = $kompetensi->deskripsi;
            $img   = $kompetensi->gambar
              ? asset('storage/'.$kompetensi->gambar)
              : asset('images/profil/default-bidang.svg');
          @endphp

          <article class="relative overflow-hidden rounded-2xl shadow-md border border-[#1524AF] md:h-80 lg:h-96">
            <button type="button" onclick="openImgModal('{{ $img }}', @js($judul))" class="block w-full h-full">
              <img src="{{ $img }}" alt="{{ $judul }}" class="w-full h-full object-cover cursor-zoom-in" loading="lazy">
            </button>

            <div class="absolute inset-0 pointer-events-none"
                 style="background:linear-gradient(to top, rgba(21,36,175,.7) 12%, rgba(21,36,175,.35) 28%, rgba(0,0,0,0) 72%);"></div>

            <div class="absolute inset-0 flex flex-col justify-end p-6 space-y-2 text-white">
              <h3 class="font-[Volkhov] font-bold text-[22px] text-[#FFDE59]"
                  style="-webkit-text-stroke:.85px #1524AF;">
                {{ $judul }}
              </h3>
              <p class="text-[12px] font-[Montserrat] leading-tight text-justify opacity-95 md:line-clamp-5">
                {{ $desc }}
              </p>
            </div>
          </article>
        @empty
          <div class="bg-white p-6 rounded-2xl shadow-sm border">
            <p class="font-[Montserrat] text-[#081526]">Belum ada data kompetensi.</p>
          </div>
        @endforelse
      </div>

      {{-- KOLOM KANAN --}}
      <div class="space-y-8">
        @forelse ($rightCol as $kompetensi)
          @php
            $judul = $kompetensi->nama_kompetensi;
            $desc  = $kompetensi->deskripsi;
            $img   = $kompetensi->gambar
              ? asset('storage/'.$kompetensi->gambar)
              : asset('images/profil/default-bidang.svg');
          @endphp

          <article class="relative overflow-hidden rounded-2xl shadow-md border border-[#1524AF] md:h-80 lg:h-96">
            <button type="button" onclick="openImgModal('{{ $img }}', @js($judul))" class="block w-full h-full">
              <img src="{{ $img }}" alt="{{ $judul }}" class="w-full h-full object-cover cursor-zoom-in" loading="lazy">
            </button>

            <div class="absolute inset-0 pointer-events-none"
                 style="background:linear-gradient(to top, rgba(21,36,175,.7) 12%, rgba(21,36,175,.35) 28%, rgba(0,0,0,0) 72%);"></div>

            <div class="absolute inset-0 flex flex-col justify-end p-6 space-y-2 text-white">
              <h3 class="font-[Volkhov] font-bold text-[22px] text-[#FFDE59]"
                  style="-webkit-text-stroke:.85px #1524AF;">
                {{ $judul }}
              </h3>
              <p class="text-[12px] font-[Montserrat] leading-tight text-justify opacity-95 md:line-clamp-5">
                {{ $desc }}
              </p>
            </div>
          </article>
        @empty
          <div class="bg-white p-6 rounded-2xl shadow-sm border">
            <p class="font-[Montserrat] text-[#081526]">Belum ada data kompetensi.</p>
          </div>
        @endforelse
      </div>

    </div>

  </div>
</section>


@include('components.layouts.app.footer')

@stack('scripts')
</body>
</html>
