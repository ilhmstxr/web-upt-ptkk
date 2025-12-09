{{-- resources/views/pages/profil/kompetensi-pelatihan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kompetensi Pelatihan - UPT PTKK Jawa Timur</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    #scroll-pane::-webkit-scrollbar { display: none; }
   .stroke-yellow {
  /* soft smooth outline */
  paint-order: stroke fill;
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
    title="Kompetensi Pelatihan"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Profil'],
      ['label' => 'Kompetensi Pelatihan'],
    ]"
    image="images/profil/profil-upt.JPG"
    height="h-[368px]"
  />

@php
    $activeTab    = $activeTab    ?? 'keterampilan';
    $keterampilan = $keterampilan ?? [];
    $mjc          = $mjc          ?? [];

    $tabs = [
        'keterampilan' => 'Kelas Keterampilan dan Teknik',
        'mjc'          => 'Milenial Job Center',
    ];

    $listKompetensi = $activeTab === 'mjc' ? $mjc : $keterampilan;
@endphp


 <section class="w-full bg-[#F1F9FC] py-8 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

      {{-- KIRI (STICKY) --}}
      <aside class="lg:col-span-4 lg:sticky lg:top-[140px] self-start">
        {{-- Badge judul kiri --}}
        <div class="inline-flex px-4 py-2 rounded-md bg-[#F3E8E9] mb-4 shadow-sm">
          <span
            class="font-[Volkhov] font-bold text-[15px] md:text-[16px] leading-none"
            style="color:#861D23;"
          >
            Kompetensi Pelatihan
          </span>
        </div>

        {{-- Tabs kelompok bidang --}}
        <nav class="space-y-3">
          @foreach ($tabs as $key => $label)
            @php $isActive = $activeTab === $key; @endphp

            <a href="{{ route('kompetensi', ['tab' => $key]) }}" class="group block w-fit">
              <div class="inline-flex items-center gap-2 pb-1 border-b-2
                {{ $isActive ? 'border-[#1524AF]' : 'border-[#1524AF]/50 group-hover:border-[#1524AF]' }}">

                {{-- Bulatan biru --}}
                <span class="h-2.5 w-2.5 rounded-full
                  {{ $isActive ? 'bg-[#1524AF]' : 'bg-[#1524AF]/50 group-hover:bg-[#1524AF]' }}">
                </span>

                {{-- Teks Tab --}}
                <span
                  class="font-[Volkhov] font-bold text-[25px] md:text-[21px] tracking-tight stroke-yellow
                    {{ $isActive ? 'text-[#1524AF]' : 'text-[#1524AF]/75 group-hover:text-[#1524AF]' }}"
                  style="
                    -webkit-text-stroke: {{ $isActive ? '0.8px' : '0.55px' }} #FFDE59;
                    text-stroke: {{ $isActive ? '0.8px' : '0.55px' }} #FFDE59;
                  "
                >
                  {{ $label }}
                </span>
              </div>
            </a>
          @endforeach
        </nav>
      </aside>

      {{-- KANAN – LIST KOMPETENSI --}}
<div class="lg:col-span-8">
  <div id="scroll-pane" class="space-y-8 snap-y snap-mandatory">

    @foreach ($listKompetensi as $kompetensi)
        @php
          $judul   = $kompetensi->nama_kompetensi;
          $desc    = $kompetensi->deskripsi;
          $imgPath = $kompetensi->gambar
              ? asset('storage/' . $kompetensi->gambar)
              : asset('images/profil/default-bidang.svg');
        @endphp

        <article
          class="relative overflow-hidden rounded-2xl shadow-md border border-[#1524AF]
                 h-72 md:h-88 snap-start">
          <img
            src="{{ $imgPath }}"
            alt="{{ $judul }}"
            class="w-full h-full object-cover object-center"
          >

          <div
            class="absolute inset-0"
            style="
              background: linear-gradient(
                to top,
                rgba(21,36,175,0.70) 12%,
                rgba(21,36,175,0.35) 28%,
                rgba(0,0,0,0) 72%
              );
            "
          ></div>

          <div class="absolute inset-0 flex flex-col justify-end p-5 md:p-6 space-y-2 text-white">
            <h3
              class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#FFDE59] tracking-tight stroke-blue"
              style="
                -webkit-text-stroke: 0.85px #1524AF;
                text-stroke: 0.85px #1524AF;
              "
            >
              {{ $judul }}
            </h3>

            <p class="text-[12px] md:text-[14px] font-[Montserrat] font-medium leading-relaxed opacity-95 line-clamp-4 md:line-clamp-5">
              {{ $desc }}
            </p>
          </div>
        </article>
    @endforeach

  </div>
</div>

    </div>
  </div>
</section>


  @include('components.layouts.app.footer')
  @stack('scripts')

</body>
</html>



