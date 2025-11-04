{{-- resources/views/pages/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Font Volkhov --}}
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">

  {{-- Font Montserrat --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    {{-- Style kustom untuk efek stroke merah --}}
    .upt-stroke {
      text-shadow:
        -1px -1px 0 #861D23,
         1px -1px 0 #861D23,
        -1px  1px 0 #861D23,
         1px  1px 0 #861D23;
    }
    {{-- Custom Style untuk efek stroke kuning --}}
    .heading-stroke {
      text-shadow:
        -1px -1px 0 #FFDE59,
         1px -1px 0 #FFDE59,
        -1px  1px 0 #FFDE59,
         1px  1px 0 #FFDE59;
    }

    .tujuan-card{
      background:#FEFEFE;
      box-shadow:
        0 2px 4px rgba(0,0,0,.06),
        0 12px 24px rgba(0,0,0,.08),
        0 40px 80px rgba(0,0,0,.08);
    }

    .hero-card {
      background: linear-gradient(135deg,rgba(67,56,202,.75) 0%,rgba(79,70,229,.65) 100%);
      backdrop-filter: blur(15px);
    }

    .bg-blur { backdrop-filter: blur(8px); }
    .glass-nav { background: rgba(255,255,255,.95); backdrop-filter: blur(12px); }
    .soft-shadow {
      box-shadow: 0 10px 25px -3px rgba(0,0,0,.1),
                  0 4px 6px -2px rgba(0,0,0,.05);
    }
    .blue-gradient-bg {
      background: linear-gradient(135deg,#f0f4ff 0%,#e0e7ff 50%,#c7d2fe 100%);
    }

    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(20px) scale(0.95); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-badge { animation: fadeInUp 0.8s ease-out forwards; }
  </style>
</head>

<body class="blue-gradient-bg antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

{{-- HERO: Slider dengan infinite loop dan scale effect --}}
<header class="w-full bg-[#F5FBFF]">
  <div class="w-full px-6 md:px-12 lg:px-[80px] py-6 md:py-8">

    <div id="hero" class="relative">
      {{-- TRACK: beri padding horizontal supaya slide next/prev kelihatan (peek) --}}
      <div id="hero-track"
           class="flex items-center gap-4 md:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar select-none py-8"
           style="scrollbar-width:none;-ms-overflow-style:none;">

        <!-- LEFT GUTTER (untuk centering) -->
        <div aria-hidden="true"
             class="shrink-0 snap-none pointer-events-none
                    w-[15%] md:w-[12%] lg:w-[10%]"></div>

        <!-- CLONES KIRI (untuk unlimited scroll kiri) -->
        <div class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300" data-real="1">
          <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px]
                      rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
            <img src="{{ asset('images/beranda/slide2.jpg') }}"
                 alt="Slide 2" class="w-full h-full object-cover select-none" draggable="false">
          </div>
        </div>

        <div class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300" data-real="2">
          <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px]
                      rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
            <img src="{{ asset('images/beranda/slide3.jpg') }}"
                 alt="Slide 3" class="w-full h-full object-cover select-none" draggable="false">
          </div>
        </div>

        <!-- SLIDE ASLI -->
        <div class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300" data-real="0">
          <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px]
                      rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
            <img src="{{ asset('images/beranda/slide1.jpg') }}"
                 alt="Slide 1" class="w-full h-full object-cover select-none" draggable="false">
          </div>
        </div>

        <div class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300" data-real="1">
          <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px]
                      rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
            <img src="{{ asset('images/beranda/slide2.jpg') }}"
                 alt="Slide 2" class="w-full h-full object-cover select-none" draggable="false">
          </div>
        </div>

        <div class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300" data-real="2">
          <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px]
                      rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
            <img src="{{ asset('images/beranda/slide3.jpg') }}"
                 alt="Slide 3" class="w-full h-full object-cover select-none" draggable="false">
          </div>
        </div>

        <!-- CLONES KANAN (untuk unlimited scroll kanan) -->
        <div class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300" data-real="0">
          <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px]
                      rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
            <img src="{{ asset('images/beranda/slide1.jpg') }}"
                 alt="Slide 1" class="w-full h-full object-cover select-none" draggable="false">
          </div>
        </div>

        <div class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300" data-real="1">
          <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px]
                      rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
            <img src="{{ asset('images/beranda/slide2.jpg') }}"
                 alt="Slide 2" class="w-full h-full object-cover select-none" draggable="false">
          </div>
        </div>

        <!-- RIGHT GUTTER (untuk centering) -->
        <div aria-hidden="true"
             class="shrink-0 snap-none pointer-events-none
                    w-[15%] md:w-[12%] lg:w-[10%]"></div>
      </div>

      {{-- Controls + dots --}}
      <div class="mt-4 flex items-center justify-center gap-4">
        <button id="hero-prev"
                class="w-9 h-9 grid place-items-center rounded-full border border-gray-300 text-gray-600 hover:bg-white/60 transition-colors"
                aria-label="Sebelumnya">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </button>

        <div id="hero-dots" class="flex items-center gap-3">
          <button class="w-2.5 h-2.5 rounded-full bg-[#1524AF] transition-colors" aria-label="Slide 1"></button>
          <button class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-colors" aria-label="Slide 2"></button>
          <button class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-colors" aria-label="Slide 3"></button>
        </div>

        <button id="hero-next"
                class="w-9 h-9 grid place-items-center rounded-full border border-gray-300 text-gray-600 hover:bg-white/60 transition-colors"
                aria-label="Berikutnya">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="m8.59 16.59 1.41 1.41 6-6-6-6L8.59 6.41 13.17 11z"/></svg>
        </button>
      </div>
    </div>

  </div>
</header>

<style>
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .hero-slide {
    transform: scale(0.85);
    opacity: 0.5;
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .hero-slide.active {
    transform: scale(1);
    opacity: 1;
  }
</style>

<script>
(function () {
  const track = document.getElementById('hero-track');
  const slides = Array.from(track.querySelectorAll('.hero-slide')); // hanya slide (tanpa gutter)
  const dots = Array.from(document.getElementById('hero-dots').children);
  const prev = document.getElementById('hero-prev');
  const next = document.getElementById('hero-next');

  // Urutan di DOM (tanpa gutter):
  // 0: clone(real1) | 1: clone(real2) | 2: real0 | 3: real1 | 4: real2 | 5: clone(real0) | 6: clone(real1)
  const FIRST_REAL = 2;            // real0
  const LAST_REAL  = 4;            // real2
  const LEFT_CLONE_BEFORE_FIRST  = 1; // clone real2
  const RIGHT_CLONE_AFTER_LAST   = 5; // clone real0
  const REAL_COUNT = 3;

  const ANIM = 300, BUF = 40;
  let currentIndex = FIRST_REAL;   // mulai di real0
  let isTransitioning = false;

  const realOf = (idx) => (idx >= FIRST_REAL && idx <= LAST_REAL) ? (idx - FIRST_REAL)
                            : parseInt(slides[idx].dataset.real, 10);

  const setDots = (r) => dots.forEach((d,i)=>{
    d.className = 'w-2.5 h-2.5 rounded-full transition-colors ' + (i===r?'bg-[#1524AF]':'bg-gray-300');
  });

  const setActive = (idx) => slides.forEach((s,i)=> s.classList.toggle('active', i===idx));

  const centerOffset = (idx) => slides[idx].offsetLeft - (track.clientWidth - slides[idx].clientWidth)/2;

  const scrollToIndex = (idx, smooth=true) =>
    track.scrollTo({ left: centerOffset(idx), behavior: smooth?'smooth':'auto' });

  // Normalisasi jika mendarat di clone (dipanggil setelah scroll manual)
  function normalizeIfClone() {
    if (currentIndex < FIRST_REAL) { currentIndex += REAL_COUNT; scrollToIndex(currentIndex, false); }
    else if (currentIndex > LAST_REAL) { currentIndex -= REAL_COUNT; scrollToIndex(currentIndex, false); }
    setActive(currentIndex); setDots(realOf(currentIndex));
  }

  // === NEXT: 1 langkah ke kanan. Dari real2(4) langsung ke real0(2) via clone(5) ===
  function goNext() {
    if (isTransitioning) return;
    isTransitioning = true;

    if (currentIndex === LAST_REAL) {
      // 4 -> 5 (clone real0) [kanan], lalu snap ke 2 (real0)
      setDots(0); setActive(RIGHT_CLONE_AFTER_LAST);
      scrollToIndex(RIGHT_CLONE_AFTER_LAST, true);
      currentIndex = RIGHT_CLONE_AFTER_LAST;
      setTimeout(() => {
        currentIndex = FIRST_REAL; // 2
        scrollToIndex(currentIndex, false);
        setActive(currentIndex); setDots(0);
        isTransitioning = false;
      }, ANIM + BUF);
    } else {
      // real0(2)->real1(3) atau real1(3)->real2(4)
      currentIndex += 1;
      scrollToIndex(currentIndex, true);
      setActive(currentIndex); setDots(realOf(currentIndex));
      setTimeout(()=>{ isTransitioning = false; }, ANIM + BUF);
    }
  }

  // === PREV: 1 langkah ke kiri. Dari real0(2) langsung ke real2(4) via clone(1) ===
  function goPrev() {
    if (isTransitioning) return;
    isTransitioning = true;

    if (currentIndex === FIRST_REAL) {
      // 2 -> 1 (clone real2) [kiri], lalu snap ke 4 (real2)
      setDots(2); setActive(LEFT_CLONE_BEFORE_FIRST);
      scrollToIndex(LEFT_CLONE_BEFORE_FIRST, true);
      currentIndex = LEFT_CLONE_BEFORE_FIRST;
      setTimeout(() => {
        currentIndex = LAST_REAL; // 4
        scrollToIndex(currentIndex, false);
        setActive(currentIndex); setDots(2);
        isTransitioning = false;
      }, ANIM + BUF);
    } else {
      // real2(4)->real1(3) atau real1(3)->real0(2)
      currentIndex -= 1;
      scrollToIndex(currentIndex, true);
      setActive(currentIndex); setDots(realOf(currentIndex));
      setTimeout(()=>{ isTransitioning = false; }, ANIM + BUF);
    }
  }

  // Dots: pilih jalur terpendek (seri -> kanan), tetap 1-langkah berulang (tidak melompati 2 langkah sekaligus)
  function step(dir, times) {
    if (times<=0) return;
    const tick = () => { (dir>0?goNext():goPrev()); times--; if (times>0) setTimeout(tick, ANIM+BUF+10); };
    tick();
  }
  dots.forEach((d, targetReal) => {
    d.addEventListener('click', () => {
      if (isTransitioning) return;
      const curReal = realOf(currentIndex);
      if (targetReal === curReal) return;
      const r = (targetReal - curReal + 3) % 3;
      const l = (curReal - targetReal + 3) % 3;
      if (r <= l) step(1, r); else step(-1, l);
    });
  });

  // Sync indikator ketika user swipe/scroll native
  let debounce = null;
  track.addEventListener('scroll', () => {
    if (isTransitioning) return;
    clearTimeout(debounce);
    debounce = setTimeout(() => {
      // cari index terdekat pusat viewport
      const mid = track.scrollLeft + track.clientWidth/2;
      let nearest = currentIndex, best = Infinity;
      for (let i=0;i<slides.length;i++) {
        const center = slides[i].offsetLeft + slides[i].clientWidth/2;
        const d = Math.abs(center - mid);
        if (d < best) { best = d; nearest = i; }
      }
      currentIndex = nearest;
      setActive(currentIndex); setDots(realOf(currentIndex));
      normalizeIfClone();
    }, 80);
  }, { passive:true });

  // Init awal
  scrollToIndex(FIRST_REAL, false);
  setActive(FIRST_REAL); setDots(0);

  // Tombol
  next.addEventListener('click', goNext);
  prev.addEventListener('click', goPrev);
})();
</script>



  {{-- SECTION: Cerita Kami --}}
  <section class="relative bg-white py-12 md:py-16 lg:py-20 px-6 md:px-12 lg:px-[80px]">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-[60px] items-center">

        {{-- Kolom Kiri: Foto + badges --}}
<div class="relative md:pl-[16px] lg:pl-[24px]">

  {{-- Wrapper frame + avatar (relative) --}}
  <div class="relative inline-block">
    {{-- Frame foto --}}
    <div class="rounded-2xl overflow-hidden shadow-xl ring-2 ring-[#1524AF]">
      <img src="{{ asset('images/cerita-kami.svg') }}"
           alt="Kegiatan UPT PTKK"
           class="w-full h-auto object-cover">
    </div>

    {{-- AVATAR: pojok kiri bawah, turun 0.5rem agar match -bottom-2 --}}
    <div class="hidden md:block absolute left-0 top-full translate-y-4">
      <img src="{{ asset('images/icons/avatar.svg') }}" alt="Avatar"
           class="w-[134px] h-[30px] object-contain drop-shadow-md">
    </div>
  </div>

          {{-- BADGES (desktop) --}}
          <div class="hidden md:block">
            {{-- Cabang Dinas (kiri atas) --}}
            <div class="absolute -top-7 -left-2">
              <div class="flex items-center bg-white rounded-xl h-[56px] pl-[10px] pr-[10px] shadow-lg ring-1 ring-black/5">
                <span class="inline-flex w-[30px] h-[30px] rounded-full bg-[#BCE9F5] items-center justify-center mr-[9px]">
                  <img src="{{ asset('images/icons/cabdin.svg') }}" alt="Cabang Dinas" class="w-[25px] h-[25px]">
                </span>
                <div class="font-[Montserrat] flex flex-col justify-center">
                  <div class="font-medium text-[#081526] px-[10px] py-[3px]" style="font-size:16px;">Cabang Dinas</div>
                  <div class="font-bold text-[#00A4F9] tracking-tight px-[10px] py-[3px]" style="font-size:20px;">24</div>
                </div>
              </div>
            </div>

            {{-- Alumni (kanan atas) --}}
            <div class="absolute top-5 -right-11">
              <div class="flex items-center bg-white rounded-xl w-[124px] h-[56px] pl-[10px] pr-[10px] shadow-lg ring-1 ring-black/5">
                <span class="inline-flex flex-none shrink-0 items-center justify-center
                             w-[30px] h-[30px] aspect-square rounded-full bg-[#BCE9F5] mr-[9px] overflow-hidden">
                  <img src="{{ asset('images/icons/alumni.svg') }}"
                       alt="Cabang Dinas"
                       class="w-[22px] h-[22px] object-contain">
                </span>
                <div class="font-[Montserrat] flex flex-col justify-center leading-tight">
                  <div class="font-medium text-[#081526] px-[10px] py-[3px]" style="font-size:16px;">Alumni</div>
                  <div class="font-bold text-[#00A4F9] tracking-tight px-[10px] py-[3px]" style="font-size:20px;">500+</div>
                </div>
              </div>
            </div>

            {{-- Bidang Pelatihan (kanan bawah) --}}
            <div class="absolute -bottom-2 -right-6">
              <div class="flex items-center bg-white rounded-xl w-[205px] h-[56px]
                          pl-[10px] pr-[10px] shadow-lg ring-1 ring-black/5">
                <span class="inline-flex flex-none shrink-0 items-center justify-center
                             w-[30px] h-[30px] aspect-square rounded-full bg-[#BCE9F5] mr-[9px] overflow-hidden">
                  <img src="{{ asset('images/icons/bidang-pelatihan.svg') }}"
                       alt="Bidang Pelatihan"
                       class="w-[22px] h-[22px] object-contain">
                </span>
                <div class="font-[Montserrat] flex flex-col justify-center leading-tight">
                  <div class="font-medium text-[#081526] px-[10px] py-[3px] whitespace-nowrap" style="font-size:16px;">
                    Bidang Pelatihan
                  </div>
                  <div class="font-bold text-[#00A4F9] tracking-tight px-[10px] py-[3px]" style="font-size:20px;">
                    10+
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- MOBILE badges --}}
          <div class="mt-5 md:hidden space-y-3">
            <div>
              <img src="{{ asset('images/icons/avatar.svg') }}" alt="Avatar"
                   class="w-[134px] h-[30px] object-contain drop-shadow-md">
            </div>
            <div class="flex flex-wrap gap-3">
              <div class="flex items-center bg-white rounded-xl h-[48px] pl-[10px] pr-[10px] shadow-md ring-1 ring-black/5">
                <span class="inline-flex w-[28px] h-[28px] rounded-full bg-[#BCE9F5] items-center justify-center mr-[9px]">
                  <img src="{{ asset('images/icons/cabdin.svg') }}" alt="Cabang Dinas" class="w-[22px] h-[22px]">
                </span>
                <div class="font-[Montserrat]">
                  <div class="font-medium text-[#081526] px-[8px] py-[2px]" style="font-size:14px;">Cabang Dinas</div>
                  <div class="font-bold text-[#00A4F9] tracking-tight px-[8px] py-[2px]" style="font-size:16px;">24</div>
                </div>
              </div>
              <div class="flex items-center bg-white rounded-xl h-[48px] pl-[10px] pr-[10px] shadow-md ring-1 ring-black/5">
                <span class="inline-flex w-[28px] h-[28px] rounded-full bg-[#BCE9F5] items-center justify-center mr-[9px]">
                  <img src="{{ asset('images/icons/cabdin.svg') }}" alt="Alumni" class="w-[22px] h-[22px]">
                </span>
                <div class="font-[Montserrat]">
                  <div class="font-medium text-[#081526] px-[8px] py-[2px]" style="font-size:14px;">Alumni</div>
                  <div class="font-bold text-[#00A4F9] tracking-tight px-[8px] py-[2px]" style="font-size:16px;">500+</div>
                </div>
              </div>
              <div class="flex items-center bg-white rounded-xl h-[48px] pl-[10px] pr-[10px] shadow-md ring-1 ring-black/5">
                <span class="inline-flex w-[28px] h-[28px] rounded-full bg-[#BCE9F5] items-center justify-center mr-[9px]">
                  <img src="{{ asset('images/icons/bidang-pelatihan.svg') }}" alt="Bidang Pelatihan" class="w-[22px] h-[22px]">
                </span>
                <div class="font-[Montserrat]">
                  <div class="font-medium text-[#081526] px-[8px] py-[2px]" style="font-size:14px;">Bidang Pelatihan</div>
                  <div class="font-bold text-[#00A4F9] tracking-tight px-[8px] py-[2px]" style="font-size:16px;">10+</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- /Kolom Kiri --}}

        {{-- Kolom Kanan --}}
        <div class="flex flex-col">
          <div class="inline-flex self-start items-center justify-center mb-[30px] px-1 py-2 bg-[#F3E8E9] rounded-md">
            <span class="font-['Volkhov'] font-bold text-[#861D23] text-[24px] leading-none">Cerita Kami</span>
          </div>

          <h2 class="mb-[20px] md:mb-[30px] font-['Volkhov'] font-bold text-[24px] md:text-[28px] lg:text-[32px] leading-tight text-[#1524AF] heading-stroke max-w-[36ch]">
            UPT Pengembangan Teknis Dan<br class="hidden md:block" />
            Keterampilan Kejuruan
          </h2>

          <p class="mb-[24px] md:mb-[30px] font-['Montserrat'] font-medium text-[#081526]
                 leading-7 text-[18px] md:text-[18px] lg:text-[20px] text-justify max-w-prose">
            adalah salah satu Unit Pelaksana Teknis dari Dinas Pendidikan Provinsi Jawa Timur
            yang mempunyai tugas dan fungsi memberikan fasilitas melalui pelatihan berbasis kompetensi
            dengan dilengkapi Tempat Uji Kompetensi (TUK) yang didukung oleh Lembaga Sertifikasi Kompetensi (LSK)
            di beberapa bidang keahlian strategis. Sebagai pelopor pelatihan vokasi, UPT PTKK terus memperkuat posisinya
            dengan menghadirkan program yang relevan, progresif, dan berdampak nyata.
            Melalui upaya tersebut, UPT PTKK berkomitmen mencetak lulusan yang terampil
            sehingga mampu berkontribusi pada kemajuan pendidikan di Jawa Timur.
          </p>

          <a href="#"
             class="inline-flex items-center justify-center gap-2 w-max px-8 py-3 rounded-xl bg-[#1524AF]
                    text-white font-['Montserrat'] font-medium text-[18px] tracking-tight shadow-md hover:bg-[#0F1D8F]
                    active:scale-[.99] transition-all duration-200 ease-out">
            <span class="leading-none">Cari tahu lebih</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
            </svg>
          </a>
        </div>
        {{-- /Kolom Kanan --}}
      </div>
    </div>
  </section>
  {{-- /SECTION: Cerita Kami --}}

{{-- SECTION: Jatim Bangkit (infinite marquee) --}}
<section class="relative bg-white">
  <style>
    /* Loop horizontal tak terputus */
    @keyframes jatim-scroll-x {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); } /* geser setengah lebar track (karena konten digandakan) */
    }

    /* Hormati preferensi user yang mengurangi animasi */
    @media (prefers-reduced-motion: reduce) {
      .jatim-marquee { animation: none !important; }
    }
  </style>

  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <!-- Viewport -->
    <div class="relative overflow-hidden">
      <!-- Track: lebar 200% karena konten digandakan -->
      <div
        class="jatim-marquee flex w-[200%] animate-[jatim-scroll-x_linear_infinite] [animation-duration:28s]"
      >
        <!-- Bagian 1 -->
        <div class="flex w-1/2 items-center">
          <img
            src="{{ asset('images/jatim-bangkit.svg') }}"
            alt="Ilustrasi kegiatan UPT PTKK"
            class="block h-auto w-full flex-none"
            loading="lazy"
          >
        </div>

        <!-- Bagian 2 (duplikat persis) -->
        <div class="flex w-1/2 items-center" aria-hidden="true">
          <img
            src="{{ asset('images/jatim-bangkit.svg') }}"
            alt=""
            class="block h-auto w-full flex-none"
            loading="lazy"
          >
        </div>
      </div>

      <!-- (Opsional) Mask tepi supaya fade di kiri-kanan -->
      <div class="pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]"></div>
    </div>
  </div>
</section>
{{-- /SECTION: Jatim Bangkit --}}



  {{-- SECTION: Tujuan --}}
  <section id="tujuan" class="w-full bg-white py-12 md:py-16 lg:py-20">
    <!-- Badge -->
   <div class="max-w-7xl mx-auto px-6 md:px-12 lg:pl-[80px] lg:pr-[calc(80px+24px)]">
      <div class="w-full flex justify-center">
        <span class="inline-flex items-center justify-center
                    w-[126px] h-[41px] rounded-lg bg-[#F3E8E9] text-[#861D23]
                    font-bold text-base md:text-lg lg:text-[24px] font-[Volkhov] shadow-sm leading-tight">
          Tujuan
        </span>
      </div>
    </div>

    <!-- Judul -->
   <div class="max-w-7xl mx-auto px-6 md:px-12 lg:pl-[80px] lg:pr-[calc(80px+24px)]">
      <h2 class="text-center text-3xl sm:text-4xl lg:text-[32px] font-bold font-[Volkhov] text-[#1524AF]
                 [-webkit-text-stroke:1px_#FFDE59] my-8 sm:my-[30px] leading-tight">
        Komitmen Kami Untuk Masyarakat Jawa Timur
      </h2>
    </div>

    <!-- Grid cards -->
   <div class="max-w-7xl mx-auto px-6 md:px-12 lg:pl-[80px] lg:pr-[calc(80px+24px)]">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-x-[72px] lg:gap-y-[72px]">

        <!-- 01 -->
        <div class="tujuan-card relative rounded-2xl w-full min-h-[236px] p-6">
          <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2
                       flex items-center justify-center w-[45px] h-[45px] rounded-full
                       text-[#FEFEFE] font-[Montserrat] font-medium text-[16px]"
                style="background:linear-gradient(90deg,#0E65CC 0%,#01A0F6 49%,#0C69CF 100%);">01</span>
          <div class="flex flex-col gap-4 h-full justify-center">
            <img src="{{ asset('images/icons/tujuan-1.svg') }}" alt="Ikon Tujuan 1" class="w-[50px] h-[50px]" loading="lazy" decoding="async" />
            <p class="font-[Montserrat] font-medium text-[20px] leading-snug text-gray-800">
              Membangun Sumber Daya Manusia (SDM) yang kreatif, kompetitif, dan adaptif.
            </p>
          </div>
        </div>

        <!-- 02 -->
        <div class="tujuan-card relative rounded-2xl w-full min-h-[236px] p-6">
          <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 flex items-center justify-center w-[45px] h-[45px] rounded-full text-[#FEFEFE] font-[Montserrat] font-medium text-[16px]" style="background:linear-gradient(90deg,#0E65CC 0%,#01A0F6 49%,#0C69CF 100%);">02</span>
          <div class="flex flex-col gap-4 h-full justify-center">
            <img src="{{ asset('images/icons/tujuan-2.svg') }}" alt="Ikon Tujuan 2" class="w-[50px] h-[50px]" loading="lazy" decoding="async" />
            <p class="font-[Montserrat] font-medium text-[20px] leading-snug text-gray-800">Membentuk mindset tangguh berbasis literasi dan kolaborasi.</p>
          </div>
        </div>

        <!-- 03 -->
        <div class="tujuan-card relative rounded-2xl w-full min-h-[236px] p-6">
          <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 flex items-center justify-center w-[45px] h-[45px] rounded-full text-[#FEFEFE] font-[Montserrat] font-medium text-[16px]" style="background:linear-gradient(90deg,#0E65CC 0%,#01A0F6 49%,#0C69CF 100%);">03</span>
          <div class="flex flex-col gap-4 h-full justify-center">
            <img src="{{ asset('images/icons/tujuan-3.svg') }}" alt="Ikon Tujuan 3" class="w-[50px] h-[50px]" loading="lazy" decoding="async" />
            <p class="font-[Montserrat] font-medium text-[20px] leading-snug text-gray-800">Mendorong transformasi edukasi vokasi yang relevan dan inklusif.</p>
          </div>
        </div>

        <!-- 04 -->
        <div class="tujuan-card relative rounded-2xl w-full min-h-[236px] p-6">
          <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 flex items-center justify-center w-[45px] h-[45px] rounded-full text-[#FEFEFE] font-[Montserrat] font-medium text-[16px]" style="background:linear-gradient(90deg,#0E65CC 0%,#01A0F6 49%,#0C69CF 100%);">04</span>
          <div class="flex flex-col gap-4 h-full justify-center">
            <img src="{{ asset('images/icons/tujuan-4.svg') }}" alt="Ikon Tujuan 4" class="w-[50px] h-[50px]" loading="lazy" decoding="async" />
            <p class="font-[Montserrat] font-medium text-[20px] leading-snug text-gray-800">Meningkatkan pengakuan kompetensi melalui sertifikasi nasional.</p>
          </div>
        </div>

        <!-- 05 -->
        <div class="tujuan-card relative rounded-2xl w-full min-h-[236px] p-6">
          <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 flex items-center justify-center w-[45px] h-[45px] rounded-full text-[#FEFEFE] font-[Montserrat] font-medium text-[16px]" style="background:linear-gradient(90deg,#0E65CC 0%,#01A0F6 49%,#0C69CF 100%);">05</span>
          <div class="flex flex-col gap-4 h-full justify-center">
            <img src="{{ asset('images/icons/tujuan-5.svg') }}" alt="Ikon Tujuan 5" class="w-[50px] h-[50px]" loading="lazy" decoding="async" />
            <p class="font-[Montserrat] font-medium text-[20px] leading-snug text-gray-800">Memperkuat konektivitas antara dunia pendidikan dan dunia industri.</p>
          </div>
        </div>

        <!-- 06 -->
        <div class="tujuan-card relative rounded-2xl w-full min-h-[236px] p-6">
          <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 flex items-center justify-center w-[45px] h-[45px] rounded-full text-[#FEFEFE] font-[Montserrat] font-medium text-[16px]" style="background:linear-gradient(90deg,#0E65CC 0%,#01A0F6 49%,#0C69CF 100%);">06</span>
          <div class="flex flex-col gap-4 h-full justify-center">
            <img src="{{ asset('images/icons/tujuan-6.svg') }}" alt="Ikon Tujuan 6" class="w-[50px] h-[50px]" loading="lazy" decoding="async" />
            <p class="font-[Montserrat] font-medium text-[20px] leading-snug text-gray-800">Mewujudkan generasi muda yang mandiri dan berjiwa wirausaha.</p>
          </div>
        </div>

      </div>
    </div>
  </section>
  {{-- /Tujuan --}}

  {{-- SECTION: Program Pelatihan - MTU --}}
  <section class="relative bg-white py-12 md:py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

      <!-- Badge -->
      <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
        <div class="w-full flex justify-center">
          <span class="inline-flex items-center justify-center
                      w-[265px] h-[41px] rounded-lg bg-[#F3E8E9] text-[#861D23]
                      font-bold text-base md:text-lg lg:text-[24px] font-[Volkhov] shadow-sm leading-tight">
            Program Pelatihan
          </span>
        </div>
      </div>

      {{-- Judul utama --}}
      <h2 class="mt-[32px] text-center text-[22px] md:text-[28px] lg:text-[32px]
                 font-[Volkhov] font-bold text-[#1524AF]
                 leading-tight tracking-tight [-webkit-text-stroke:1px_#FFDE59] mb-6">
        Mobil Training Unit (MTU)
      </h2>

      {{-- shape MTU --}}
      <div class="relative w-full max-w-7xl h-[422px] rounded-[32px] overflow-hidden
                  bg-gradient-to-r from-[#DBE7F7] to-[#0F5DC7] mx-auto">
      </div>

    </div>
  </section>
  {{-- Program Pelatihan --}}

  {{-- SECTION: Pengumuman / Berita Terkini --}}
  <section class="relative bg-[#EDF4FF] py-12 md:py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

      {{-- Badge --}}
      <div class="w-full flex justify-center">
        <span class="inline-flex items-center justify-center px-4 py-1.5
                     rounded-lg border border-rose-300 bg-rose-100 text-rose-700
                     font-bold">
          Pengumuman
        </span>
      </div>

      {{-- Judul --}}
      <h2 class="mt-3 text-center text-[22px] md:text-[26px] lg:text-[28px] font-extrabold text-[#0E2A7B]">
        Berita / Artikel Terkini
      </h2>

      {{-- Grid kartu --}}
      <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach (range(1,4) as $i)
          <a href="#"
             class="block rounded-xl bg-[#DFEAFD] px-5 py-4 shadow-sm ring-1 ring-[#0E2A7B]/10
                    hover:bg-[#D7E3FB] transition">
            <h3 class="font-semibold text-[#0E2A7B] leading-snug">
              NOTDIN Mobile Training Unit (MTU) Angkatan 2 Tahun 2025_sign
            </h3>
            <p class="mt-2 text-xs text-slate-600">Kamis, 26 Maret 2025</p>
          </a>
        @endforeach
      </div>

    </div>
  </section>

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
