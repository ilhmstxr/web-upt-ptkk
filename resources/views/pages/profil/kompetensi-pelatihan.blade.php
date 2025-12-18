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

<section class="w-full bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- ✅ STICKY TAB KHUSUS HP (tetap di atas saat scroll) --}}
    {{-- Kalau navbar kamu fixed & nutup, ubah top-0 jadi top-[80px] misal --}}
    <div class="md:hidden sticky top-0 z-50 bg-[#F1F9FC] pt-4 pb-3">
      <div class="inline-flex px-4 py-2 rounded-md bg-[#F3E8E9] shadow-sm">
        <span class="font-[Volkhov] font-bold text-[15px] leading-none" style="color:#861D23;">
          Kompetensi Pelatihan
        </span>
      </div>

      {{-- Tabs HP: vertikal + underline seragam (panjang sama) --}}
      <nav class="mt-4 space-y-4">
        @foreach ($tabs as $key => $label)
          @php $isActive = $activeTab === $key; @endphp

          <a href="{{ route('kompetensi', ['tab' => $key]) }}" class="group block w-full">
            <div class="flex items-start gap-3">

              <span class="mt-3 h-2.5 w-2.5 rounded-full
                {{ $isActive ? 'bg-[#1524AF]' : 'bg-[#1524AF]/50 group-hover:bg-[#1524AF]' }}">
              </span>

              <div class="w-full">
                <span
                  class="block font-[Volkhov] font-bold text-[22px] tracking-tight stroke-yellow
                    {{ $isActive ? 'text-[#1524AF]' : 'text-[#1524AF]/75 group-hover:text-[#1524AF]' }}"
                  style="
                    -webkit-text-stroke: {{ $isActive ? '0.75px' : '0.5px' }} #FFDE59;
                    text-stroke: {{ $isActive ? '0.75px' : '0.5px' }} #FFDE59;
                  "
                >
                  {{ $label }}
                </span>

                <div class="mt-2 h-[2px] w-full
                  {{ $isActive ? 'bg-[#1524AF]' : 'bg-[#1524AF]/50 group-hover:bg-[#1524AF]' }}">
                </div>
              </div>

            </div>
          </a>
        @endforeach
      </nav>

      <div class="mt-4 h-px bg-[#1524AF]/10"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

      {{-- ✅ KIRI (Tablet & Desktop): sticky + underline seragam --}}
      <aside class="hidden md:block lg:col-span-4 lg:sticky lg:top-[140px] self-start">
        <div class="inline-flex px-4 py-2 rounded-md bg-[#F3E8E9] mb-4 shadow-sm">
          <span class="font-[Volkhov] font-bold text-[15px] md:text-[16px] leading-none" style="color:#861D23;">
            Kompetensi Pelatihan
          </span>
        </div>

        <nav class="space-y-4">
          @foreach ($tabs as $key => $label)
            @php $isActive = $activeTab === $key; @endphp

            <a href="{{ route('kompetensi', ['tab' => $key]) }}" class="group block w-full max-w-md">
              <div class="flex items-start gap-3">

                <span class="mt-3 h-2.5 w-2.5 rounded-full
                  {{ $isActive ? 'bg-[#1524AF]' : 'bg-[#1524AF]/50 group-hover:bg-[#1524AF]' }}">
                </span>

                <div class="w-full">
                  <span
                    class="block font-[Volkhov] font-bold text-[25px] md:text-[21px] tracking-tight stroke-yellow
                      {{ $isActive ? 'text-[#1524AF]' : 'text-[#1524AF]/75 group-hover:text-[#1524AF]' }}"
                    style="
                      -webkit-text-stroke: {{ $isActive ? '0.8px' : '0.55px' }} #FFDE59;
                      text-stroke: {{ $isActive ? '0.8px' : '0.55px' }} #FFDE59;
                    "
                  >
                    {{ $label }}
                  </span>

                  <div class="mt-2 h-[2px] w-full
                    {{ $isActive ? 'bg-[#1524AF]' : 'bg-[#1524AF]/50 group-hover:bg-[#1524AF]' }}">
                  </div>
                </div>

              </div>
            </a>
          @endforeach
        </nav>
      </aside>

      {{-- ✅ KANAN – LIST KOMPETENSI --}}
      <div class="lg:col-span-8">
        <div id="scroll-pane" class="space-y-8 snap-y snap-mandatory">

          @forelse ($listKompetensi as $kompetensi)
            @php
              $judul   = $kompetensi->nama_kompetensi;
              $desc    = $kompetensi->deskripsi;
              $imgPath = $kompetensi->gambar
                ? asset('storage/' . $kompetensi->gambar)
                : asset('images/profil/default-bidang.svg');
            @endphp

           <article class="relative overflow-hidden rounded-2xl shadow-md border border-[#1524AF]
                h-72 md:h-80 lg:h-96 snap-start">

              {{-- ✅ GAMBAR BISA DIKLIK (LIGHTBOX) --}}
              <button
                type="button"
                class="block w-full h-full"
                onclick="openImgModal('{{ $imgPath }}', @js($judul))"
                aria-label="Perbesar gambar {{ $judul }}"
              >
                <img
                  src="{{ $imgPath }}"
                  alt="{{ $judul }}"
                  class="w-full h-full object-cover object-center cursor-zoom-in"
                  loading="lazy"
                >
              </button>

              {{-- Overlay gradient --}}
              <div class="absolute inset-0 pointer-events-none" style="
                background: linear-gradient(
                  to top,
                  rgba(21,36,175,0.70) 12%,
                  rgba(21,36,175,0.35) 28%,
                  rgba(0,0,0,0) 72%
                );
              "></div>

              {{-- ✅ HANYA JUDUL BAWAH (tidak ada judul di tengah/atas) --}}
              <div class="absolute inset-0 flex flex-col justify-end p-5 md:p-6 space-y-2 text-white pointer-events-none">
                <h3
                  class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#FFDE59] tracking-tight"
                  style="-webkit-text-stroke:0.85px #1524AF; text-stroke:0.85px #1524AF;"
                >
                  {{ $judul }}
                </h3>

                {{-- ✅ DESKRIPSI JUSTIFY --}}
               <p class="text-[12px] md:text-[14px] font-[Montserrat] font-medium leading-relaxed opacity-95
          text-justify hyphens-auto md:line-clamp-5">
  {{ $desc }}
</p>

              </div>
            </article>

          @empty
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
              <p class="font-[Montserrat] text-[#081526]">
                Belum ada data kompetensi untuk tab ini.
              </p>
            </div>
          @endforelse

        </div>
      </div>

    </div>
  </div>
</section>

{{-- ✅ IMAGE MODAL (LIGHTBOX) --}}
<div id="imgModal" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">
  <div class="absolute inset-0 bg-black/70" onclick="closeImgModal()"></div>

  <div class="relative w-full h-full flex items-center justify-center p-4">
    <div class="relative max-w-5xl w-full">
      <button
        type="button"
        onclick="closeImgModal()"
        class="absolute -top-3 -right-3 md:-top-4 md:-right-4 bg-white text-[#081526] rounded-full w-10 h-10 shadow flex items-center justify-center"
        aria-label="Tutup"
      >
        ✕
      </button>

      <img
        id="imgModalSrc"
        src=""
        alt=""
        class="w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl bg-white"
      >

      <p id="imgModalCaption" class="mt-3 text-center text-white font-[Montserrat] text-sm md:text-base"></p>
    </div>
  </div>
</div>

@include('components.layouts.app.footer')
@stack('scripts')

{{-- ✅ SCRIPT LIGHTBOX --}}
<script>
  const imgModal = document.getElementById('imgModal');
  const imgModalSrc = document.getElementById('imgModalSrc');
  const imgModalCaption = document.getElementById('imgModalCaption');

  function openImgModal(src, caption) {
    imgModalSrc.src = src;
    imgModalSrc.alt = caption || 'Gambar kompetensi';
    imgModalCaption.textContent = caption || '';
    imgModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeImgModal() {
    imgModal.classList.add('hidden');
    imgModalSrc.src = '';
    imgModalCaption.textContent = '';
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !imgModal.classList.contains('hidden')) {
      closeImgModal();
    }
  });
</script>

</body>
</html>
