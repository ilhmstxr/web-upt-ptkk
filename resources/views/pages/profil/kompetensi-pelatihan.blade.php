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
  $listKompetensi = $listKompetensi ?? [];
@endphp


<section class="w-full bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- ===== STICKY JUDUL (HP) ===== --}}
    <div class="md:hidden sticky top-0 z-50 bg-[#F1F9FC] pt-4 pb-3">
      <div class="inline-flex px-4 py-2 rounded-md bg-[#F3E8E9] shadow-sm">
        <span class="font-[Volkhov] font-bold text-[15px]" style="color:#861D23;">
          Kompetensi Pelatihan
        </span>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

      {{-- ===== KIRI (DESKTOP & TABLET) ===== --}}
      <aside class="hidden md:block lg:col-span-4 lg:sticky lg:top-[140px] self-start">
        <div class="inline-flex px-4 py-2 rounded-md bg-[#F3E8E9] shadow-sm">
          <span class="font-[Volkhov] font-bold text-[16px]" style="color:#861D23;">
            Kompetensi Pelatihan
          </span>
        </div>
      </aside>

      {{-- ===== KANAN: LIST KOMPETENSI ===== --}}
      <div class="lg:col-span-8">
        <div id="scroll-pane" class="space-y-8 snap-y snap-mandatory">

          @forelse ($listKompetensi as $kompetensi)
            @php
              $judul = $kompetensi->nama_kompetensi;
              $desc  = $kompetensi->deskripsi;
              $img   = $kompetensi->gambar
                ? asset('storage/'.$kompetensi->gambar)
                : asset('images/profil/default-bidang.svg');
            @endphp

            <article
              class="relative overflow-hidden rounded-2xl shadow-md border border-[#1524AF]
                     h-72 md:h-80 lg:h-96 snap-start">

              {{-- IMAGE --}}
              <button type="button"
                onclick="openImgModal('{{ $img }}', @js($judul))"
                class="block w-full h-full">
                <img src="{{ $img }}" alt="{{ $judul }}"
                     class="w-full h-full object-cover cursor-zoom-in"
                     loading="lazy">
              </button>

              {{-- OVERLAY --}}
              <div class="absolute inset-0 pointer-events-none"
                style="background:linear-gradient(
                  to top,
                  rgba(21,36,175,.7) 12%,
                  rgba(21,36,175,.35) 28%,
                  rgba(0,0,0,0) 72%
                );">
              </div>

              {{-- TEXT --}}
              <div class="absolute inset-0 flex flex-col justify-end p-5 md:p-6 space-y-2 text-white">
                <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#FFDE59]"
                    style="-webkit-text-stroke:.85px #1524AF;">
                  {{ $judul }}
                </h3>

                <p class="text-[12px] md:text-[14px] font-[Montserrat] leading-relaxed text-justify opacity-95 md:line-clamp-5">
                  {{ $desc }}
                </p>
              </div>
            </article>

          @empty
            <div class="bg-white p-6 rounded-2xl shadow-sm border">
              <p class="font-[Montserrat] text-[#081526]">
                Belum ada data kompetensi.
              </p>
            </div>
          @endforelse

        </div>
      </div>

    </div>
  </div>
</section>


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
