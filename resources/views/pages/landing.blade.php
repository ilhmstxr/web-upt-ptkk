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
  color: #1524AF;                    /* isi teks biru */
  -webkit-text-fill-color: #1524AF; /* khusus WebKit—pastikan fill tetap biru */
  text-shadow:
    -1px -1px 0 #FFDE59,
     1px -1px 0 #FFDE59,
    -1px  1px 0 #FFDE59,
     1px  1px 0 #FFDE59;             /* efek stroke kuning via text-shadow */
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

<body class="bg-[#F1F9FC] antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

{{-- HERO: Slider dengan infinite loop dan scale effect --}}
<header class="w-full bg-[#F1F9FC]">
  <div class="w-full px-6 md:px-12 lg:px-[80px] py-4 md:py-6">

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

  const ANIM = 300, BUF = 40; // durasi + buffer
  let currentIndex = FIRST_REAL;
  let isTransitioning = false;

  // ===== Util dasar =====
  const realOf = (idx) => (idx >= FIRST_REAL && idx <= LAST_REAL)
    ? (idx - FIRST_REAL)
    : parseInt(slides[idx].dataset.real, 10);

  const setDots = (r) => dots.forEach((d,i)=>{
    d.className = 'w-2.5 h-2.5 rounded-full transition-colors ' + (i===r?'bg-[#1524AF]':'bg-gray-300');
  });

  const setActive = (idx) => slides.forEach((s,i)=> s.classList.toggle('active', i===idx));

  const centerOffset = (idx) =>
    slides[idx].offsetLeft - (track.clientWidth - slides[idx].clientWidth)/2;

  // Scroll ke index tertentu (center) dengan behavior pilihan
  const scrollToIndex = (idx, smooth=true) =>
    track.scrollTo({ left: centerOffset(idx), behavior: smooth ? 'smooth' : 'auto' });

  // Menunggu sampai posisi scroll benar-benar mencapai target (lebih stabil dari setTimeout)
  function smoothScrollToIndex(idx, cb){
    const prevSnap = track.style.scrollSnapType;
    track.style.scrollSnapType = 'none';            // hindari snap melawan arah
    const target = centerOffset(idx);
    track.scrollTo({ left: target, behavior: 'smooth' });

    const t0 = performance.now(), MAX = ANIM + 300, EPS = 1;
    function tick(){
      const atTarget = Math.abs(track.scrollLeft - target) <= EPS;
      const overtime = (performance.now() - t0) > MAX;
      if (atTarget || overtime) {
        track.style.scrollSnapType = prevSnap;
        cb && cb();
        return;
      }
      requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  // rAF ganda untuk memastikan eksekusi setelah browser menyelesaikan paint terakhir
  function rafSwap(fn){ requestAnimationFrame(()=>requestAnimationFrame(fn)); }

  // Tukar posisi clone→real secara relatif (delta) tanpa gerakan tambahan (side-peek tetap)
  function seamlessSwapByDelta(fromCloneIdx, toRealIdx){
    const prevBehavior = track.style.scrollBehavior;
    const prevSnap = track.style.scrollSnapType;
    track.style.scrollBehavior = 'auto';
    track.style.scrollSnapType = 'none';

    const delta = centerOffset(toRealIdx) - centerOffset(fromCloneIdx);
    track.scrollLeft += delta;                        // geser relatif → tidak terlihat loncat

    track.style.scrollBehavior = prevBehavior || '';
    track.style.scrollSnapType = prevSnap || '';
    currentIndex = toRealIdx;
    setActive(currentIndex);
    setDots(realOf(currentIndex));
  }

  // ===== Panah NEXT: kanan satu langkah; 3→1 halus (via clone kanan) =====
  function goNext() {
    if (isTransitioning) return;
    isTransitioning = true;

    if (currentIndex === LAST_REAL) {
      // target akhir = real0; kunci visual aktif ke tujuan supaya scale/opacity konsisten
      setActive(FIRST_REAL);
      setDots(0);

      const cloneIdx = RIGHT_CLONE_AFTER_LAST; // index clone kanan
      smoothScrollToIndex(cloneIdx, () => {
        // setelah benar-benar di clone, swap delta ke real tanpa gerakan tambahan
        rafSwap(() => {
          seamlessSwapByDelta(cloneIdx, FIRST_REAL);
          isTransitioning = false;
        });
      });
    } else {
      const target = currentIndex + 1;
      setActive(target);
      setDots(realOf(target));
      smoothScrollToIndex(target, () => {
        currentIndex = target;
        isTransitioning = false;
      });
    }
  }

  // ===== Panah PREV: kiri satu langkah; 1→3 halus (via clone kiri) =====
  function goPrev() {
    if (isTransitioning) return;
    isTransitioning = true;

    if (currentIndex === FIRST_REAL) {
      // target akhir = real2
      setActive(LAST_REAL);
      setDots(2);

      const cloneIdx = LEFT_CLONE_BEFORE_FIRST; // index clone kiri
      smoothScrollToIndex(cloneIdx, () => {
        rafSwap(() => {
          seamlessSwapByDelta(cloneIdx, LAST_REAL);
          isTransitioning = false;
        });
      });
    } else {
      const target = currentIndex - 1;
      setActive(target);
      setDots(realOf(target));
      smoothScrollToIndex(target, () => {
        currentIndex = target;
        isTransitioning = false;
      });
    }
  }

  // ===== Dots: pilih jarak terdekat, tetap step 1 berulang (arah konsisten) =====
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

  // ===== Sinkron ketika user swipe manual; normalisasi bila mendarat di clone =====
  let debounce = null;
  track.addEventListener('scroll', () => {
    if (isTransitioning) return;
    clearTimeout(debounce);
    debounce = setTimeout(() => {
      const mid = track.scrollLeft + track.clientWidth/2;
      let nearest = currentIndex, best = Infinity;
      for (let i=0;i<slides.length;i++) {
        const center = slides[i].offsetLeft + slides[i].clientWidth/2;
        const d = Math.abs(center - mid);
        if (d < best) { best = d; nearest = i; }
      }
      // Jika user berhenti di clone, swap delta ke real padanannya agar loop mulus
      if (nearest < FIRST_REAL) {
        // clone kiri → real terakhir
        rafSwap(() => seamlessSwapByDelta(nearest, LAST_REAL));
      } else if (nearest > LAST_REAL) {
        // clone kanan → real pertama
        rafSwap(() => seamlessSwapByDelta(nearest, FIRST_REAL));
      } else {
        currentIndex = nearest;
        setActive(currentIndex);
        setDots(realOf(currentIndex));
      }
    }, 80);
  }, { passive:true });

  // ===== Init awal: center di real0, set indikator =====
  scrollToIndex(FIRST_REAL, false);
  setActive(FIRST_REAL);
  setDots(0);

  // ===== Event tombol =====
  next.addEventListener('click', goNext);
  prev.addEventListener('click', goPrev);
})();
</script>

  {{-- SECTION: Cerita Kami --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6 px-6 md:px-12 lg:px-[80px]">
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

  <!-- Badge -->
  <div class="inline-flex self-start items-center justify-center mb-[20px] px-1 py-2 bg-[#F3E8E9] rounded-md">
    <span class="font-['Volkhov'] font-bold text-[#861D23] text-[24px] leading-none">Cerita Kami</span>
  </div>

  <!-- Heading -->
  <h2 class="mb-[20px] md:mb-[24px] font-['Volkhov'] font-bold text-[24px] md:text-[28px] lg:text-[32px]
             leading-tight text-[#1524AF] heading-stroke max-w-[36ch]">
    UPT Pengembangan Teknis Dan<br class="hidden md:block" />
    Keterampilan Kejuruan
  </h2>

  <!-- Paragraf -->
  <p class="mb-[24px] md:mb-[28px] font-['Montserrat'] font-medium text-[#081526]
         leading-7 text-[14px] md:text-[14px] lg:text-[16px] text-justify max-w-prose">
    Adalah salah satu Unit Pelaksana Teknis dari Dinas Pendidikan Provinsi Jawa Timur
    yang mempunyai tugas dan fungsi memberikan fasilitas melalui pelatihan berbasis kompetensi
    dengan dilengkapi Tempat Uji Kompetensi (TUK) yang didukung oleh Lembaga Sertifikasi Kompetensi (LSK)
    di beberapa bidang keahlian strategis. Sebagai pelopor pelatihan vokasi, UPT PTKK terus memperkuat posisinya
    dengan menghadirkan program yang relevan, progresif, dan berdampak nyata.
    Melalui upaya tersebut, UPT PTKK berkomitmen mencetak lulusan yang terampil
    sehingga mampu berkontribusi pada kemajuan pendidikan di Jawa Timur.
  </p>

  <!-- Button -->
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

{{-- SECTION: Jatim Bangkit (oval slim, bigger icons, tighter gap) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <style>
    @keyframes jatim-scroll-x {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
      .jatim-marquee { animation: none !important; }
    }
  </style>

  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="relative">

      {{-- BG oval lebih tinggi sedikit --}}
      <div class="relative bg-[#DBE7F7] rounded-full overflow-hidden
                  h-[54px] md:h-[58px] lg:h-[62px] flex items-center">

        {{-- Viewport --}}
        <div class="relative w-full overflow-hidden">

          {{-- TRACK --}}
          <div class="jatim-marquee flex w-[200%] items-center
                      animate-[jatim-scroll-x_linear_infinite] [animation-duration:24s]">

            {{-- Bagian 1 --}}
            <div class="flex w-1/2 items-center justify-between
                        px-8 md:px-12 lg:px-16
                        gap-5 md:gap-7 lg:gap-8">

              <img src="{{ asset('images/icons/cetar.svg') }}"     class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/dindik.svg') }}"    class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/jatim.svg') }}"     class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/berakhlak.svg') }}" class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/optimis.svg') }}"   class="h-[34px] md:h-[38px] lg:h-[42px]">

            </div>

            {{-- Bagian 2 (duplikat) --}}
            <div class="flex w-1/2 items-center justify-between
                        px-8 md:px-12 lg:px-16
                        gap-5 md:gap-7 lg:gap-8"
                 aria-hidden="true">

              <img src="{{ asset('images/icons/cetar.svg') }}"     class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/dindik.svg') }}"    class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/jatim.svg') }}"     class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/berakhlak.svg') }}" class="h-[34px] md:h-[38px] lg:h-[42px]">
              <img src="{{ asset('images/icons/optimis.svg') }}"   class="h-[34px] md:h-[38px] lg:h-[42px]">

            </div>

          </div>

          {{-- Fade kiri–kanan --}}
          <div class="pointer-events-none absolute inset-0
                      [mask-image:linear-gradient(to_right,transparent,black_15%,black_85%,transparent)]">
          </div>

        </div>

      </div>

    </div>
  </div>
</section>
{{-- /SECTION --}}



{{-- SECTION: Berita Terbaru (hover efek interaktif) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- HEADER --}}
    <div class="grid grid-rows-[auto_auto] md:[grid-template-columns:1fr_auto] gap-y-2 mb-10 items-center">

      {{-- Baris 1 kiri: Badge --}}
      <div class="row-start-1 col-start-1">
        <span class="inline-flex items-center justify-center bg-[#F3E8E9] text-[#861D23]
                     font-[Volkhov] font-bold text-sm rounded-md leading-none
                     px-[10px] py-[4px] whitespace-nowrap">
          Berita Terbaru
        </span>
      </div>

      {{-- Baris 2 kiri: Judul --}}
      <h2 class="row-start-2 col-start-1 heading-stroke font-[Volkhov] font-bold
                 text-[20px] md:text-[24px] lg:text-[28px] leading-snug">
        Jangan lewatkan kabar terbaru dari UPT PTKK
      </h2>

      {{-- Baris 2 kanan: Tombol CTA --}}
      <a href="#"
         class="row-start-2 md:col-start-2 justify-self-start md:justify-self-end
                inline-flex items-center gap-1.5 bg-[#1524AF] hover:bg-[#0E1E8B]
                text-[#FFFFFF] font-[Volkhov] font-bold text-[14px]
                px-[14px] md:px-[16px] py-[6px] md:py-[7px]
                rounded-md transition-all duration-200">
        Cari tahu lebih →
      </a>
    </div>

    {{-- GRID BERITA --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @for ($i = 0; $i < 3; $i++)
      <article
        class="group bg-white border border-[#B6BBE6] rounded-2xl shadow-sm p-4
               transition-all duration-300 hover:border-[#1524AF] hover:shadow-md">

        <div class="w-full h-[160px] bg-[#E7ECF3] rounded-lg mb-4"></div>

        <div class="flex items-center gap-2 text-[#727272] text-xs mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>22 Oktober 2024</span>
        </div>

        <h3 class="text-[#081526] group-hover:text-[#1524AF] transition-colors duration-300 font-semibold mb-2">
          Judul Berita...
        </h3>

        <p class="text-sm text-[#081526] mb-3 leading-relaxed">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ornare ligula...
        </p>

        <a href="#" class="text-[#595959] group-hover:text-[#1524AF] text-sm font-medium inline-flex items-center gap-1 transition-colors duration-300">
          Baca Selengkapnya →
        </a>
      </article>
      @endfor
    </div>

  </div>
</section>

{{-- SECTION: Sorotan Pelatihan --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <style>
    /* Gerakan halus horizontal seperti icons Jatim Bangkit */
    @keyframes sorotan-scroll-x {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); } /* konten digandakan, cukup geser 50% */
    }

    @media (prefers-reduced-motion: reduce) {
      .sorotan-marquee { animation: none !important; }
    }
  </style>

  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] text-center flex flex-col items-center">

    {{-- BADGE --}}
    <div class="inline-block bg-[#FDECEC] text-[#861D23]
                text-[18px] md:text-[20px] lg:text-[22px]
                font-[Volkhov] font-bold leading-none
                px-4 py-[6px] rounded-md mb-4">
      Sorotan Pelatihan
    </div>

    {{-- JUDUL UTAMA --}}
    <h2 class="heading-stroke text-[20px] md:text-[24px] lg:text-[26px]
               font-[Volkhov] font-bold text-[#0E2A7B] leading-snug relative inline-block mt-2
               mb-10 md:mb-12 lg:mb-14">
      <span class="relative z-10">
        Mengasah Potensi dan Mencetak Tenaga Kerja yang Kompeten
      </span>
      <span class="absolute inset-0 text-transparent [-webkit-text-stroke:2px_#FFDE59] pointer-events-none">
        Mengasah Potensi dan Mencetak Tenaga Kerja yang Kompeten
      </span>
    </h2>

    @php
      $sorotan = [
        [
          'key'   => 'mtu',
          'label' => 'Mobil Training Unit',
          'desc'  => 'Mobil Keliling UPT. PTKK Dindik Jatim adalah sebuah program unggulan yang dirancang khusus untuk menjangkau sekolah di pelosok-pelosok Jawa Timur.',
          'files' => ['mtu-1.jpg','mtu-2.jpg','mtu-3.jpg','mtu-4.jpg','mtu-5.jpg','mtu-6.jpg'],
        ],
        [
          'key'   => 'reguler',
          'label' => 'Pelatihan Reguler',
          'desc'  => 'Proses peningkatan kompetensi di UPT. PTKK dipandu oleh para asesor kompetensi profesional yang tersertifikasi.',
          'files' => ['reg-1.jpg','reg-2.jpg','reg-3.jpg','reg-4.jpg','reg-5.jpg','reg-6.jpg'],
        ],
        [
          'key'   => 'akselerasi',
          'label' => 'Pelatihan Akselerasi',
          'desc'  => 'UPT. PTKK memiliki 6 kompetensi yang tersertifikasi oleh Kemendikdasmen sebagai tempat uji kompetensi yang memiliki fasilitas mumpuni.',
          'files' => ['acc-1.jpg','acc-2.jpg','acc-3.jpg','acc-4.jpg','acc-5.jpg','acc-6.jpg'],
        ],
      ];
    @endphp

    {{-- NAMA PELATIHAN + DESKRIPSI --}}
    <div id="sorotan-top"
         class="w-full mb-6 md:mb-8 flex flex-col md:flex-row md:items-center md:justify-start md:gap-6 text-left">
      {{-- Nama Pelatihan --}}
      <div class="shrink-0">
        <button type="button"
                class="sorotan-label bg-[#DBE7F7] text-[#1524AF]
                       font-[Volkhov] font-bold text-[18px] md:text-[20px] lg:text-[22px]
                       rounded-md px-5 py-2.5 leading-tight whitespace-nowrap">
          {{ $sorotan[0]['label'] }}
        </button>
      </div>

      {{-- Deskripsi --}}
      <p id="sorotan-desc"
         class="mt-2 md:mt-0 text-sm md:text-base lg:text-[17px]
                font-[Montserrat] font-medium text-[#000000] leading-relaxed md:max-w-[75%]">
        {{ $sorotan[0]['desc'] }}
      </p>
    </div>

    {{-- SLIDER FOTO: auto jalan sendiri (marquee), tanpa panah --}}
    <div class="w-full mb-8 md:mb-10 lg:mb-12">
      @foreach($sorotan as $i => $cat)
        @php $files = $cat['files']; @endphp
        <div class="sorotan-pane {{ $i===0 ? '' : 'hidden' }}" data-pane="{{ $cat['key'] }}">
          <div class="relative">
            <div class="overflow-hidden">
              <div
                class="sorotan-track sorotan-marquee flex w-[200%]
                       animate-[sorotan-scroll-x_linear_infinite] [animation-duration:26s]"
                data-key="{{ $cat['key'] }}"
              >
                {{-- Bagian 1 --}}
                <div class="flex w-1/2 gap-4 md:gap-5 lg:gap-6">
                  @foreach($files as $fname)
                    <div
                      class="relative h-[130px] md:h-[150px] lg:h-[170px]
                             rounded-2xl overflow-hidden shrink-0 group
                             w-[220px] md:w-[260px] lg:w-[280px]">
                      <img src="{{ asset('images/sorotan/'.$cat['key'].'/'.$fname) }}"
                           alt="{{ $cat['label'] }}" loading="lazy"
                           class="w-full h-full object-cover">
                      <div class="absolute inset-0 pointer-events-none transition-opacity duration-300 opacity-100 group-hover:opacity-0"
                           style="background: linear-gradient(180deg, rgba(219,231,247,0.45) 0%, rgba(21,36,175,0.75) 100%);"></div>
                    </div>
                  @endforeach
                </div>

                {{-- Bagian 2 (duplikat, untuk loop tanpa putus) --}}
                <div class="flex w-1/2 gap-4 md:gap-5 lg:gap-6" aria-hidden="true">
                  @foreach($files as $fname)
                    <div
                      class="relative h-[130px] md:h-[150px] lg:h-[170px]
                             rounded-2xl overflow-hidden shrink-0 group
                             w-[220px] md:w-[260px] lg:w-[280px]">
                      <img src="{{ asset('images/sorotan/'.$cat['key'].'/'.$fname) }}"
                           alt="" loading="lazy"
                           class="w-full h-full object-cover">
                      <div class="absolute inset-0 pointer-events-none transition-opacity duration-300 opacity-100 group-hover:opacity-0"
                           style="background: linear-gradient(180deg, rgba(219,231,247,0.45) 0%, rgba(21,36,175,0.75) 100%);"></div>
                    </div>
                  @endforeach
                </div>
              </div>

              {{-- Fade kiri–kanan --}}
              <div class="pointer-events-none absolute inset-0
                          [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]"></div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- KONTROL KATEGORI (panah hanya pindah kategori, bukan foto) --}}
    <div class="mt-2 flex items-center justify-center gap-6">
      <button id="tabPrev"
              class="w-8 h-8 flex items-center justify-center rounded-full border border-[#B6BBE6] text-[#0E2A7B]
                     hover:bg-[#1524AF] hover:text-white transition"
              aria-label="Kategori sebelumnya" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <div id="tabDots" class="flex items-center gap-2" aria-label="Indikator kategori"></div>

      <button id="tabNext"
              class="w-8 h-8 flex items-center justify-center rounded-full border border-[#B6BBE6] text-[#0E2A7B]
                     hover:bg-[#1524AF] hover:text-white transition"
              aria-label="Kategori selanjutnya" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>

  </div>

  {{-- SCRIPT: hanya untuk ganti kategori + dots, tanpa slider foto JS --}}
  <script>
    (function(){
      const tabOrder = ['mtu','reguler','akselerasi'];

      const panes = document.querySelectorAll('.sorotan-pane');
      const label = document.querySelector('.sorotan-label');
      const desc  = document.getElementById('sorotan-desc');

      const meta = {
        mtu:       { label: 'Mobil Training Unit',   desc: @json($sorotan[0]['desc']) },
        reguler:   { label: 'Pelatihan Reguler',     desc: @json($sorotan[1]['desc']) },
        akselerasi:{ label: 'Pelatihan Akselerasi',  desc: @json($sorotan[2]['desc']) },
      };

      function currentKey(){
        const active = Array.from(panes).find(p=>!p.classList.contains('hidden'));
        return active ? active.dataset.pane : tabOrder[0];
      }
      function currentIndex(){ return tabOrder.indexOf(currentKey()); }

      const prev = document.getElementById('tabPrev');
      const next = document.getElementById('tabNext');
      const tabDots = document.getElementById('tabDots');

      prev.addEventListener('click', ()=>showByIndex(currentIndex()-1));
      next.addEventListener('click', ()=>showByIndex(currentIndex()+1));

      function paintTabDots(){
        if(!tabDots) return;
        const idx = currentIndex();
        tabDots.innerHTML = '';
        tabOrder.forEach((k,i)=>{
          const b=document.createElement('button');
          b.type='button';
          b.className='w-2.5 h-2.5 rounded-full transition ' + (i===idx ? 'bg-[#1524AF]' : 'bg-[#C7D3F5]');
          b.setAttribute('aria-label', meta[k].label);
          b.setAttribute('aria-current', i===idx ? 'true' : 'false');
          b.addEventListener('click', ()=>showByIndex(i));
          tabDots.appendChild(b);
        });
      }

      function showByIndex(i){
        const idx = (i < 0) ? tabOrder.length-1 : (i >= tabOrder.length ? 0 : i);
        setActive(tabOrder[idx]);
      }

      function setActive(key){
        panes.forEach(p => p.classList.toggle('hidden', p.dataset.pane !== key));
        if (label && meta[key]) label.textContent = meta[key].label;
        if (desc  && meta[key]) desc.textContent  = meta[key].desc;
        paintTabDots();
      }

      // Init pertama
      setActive(tabOrder[0]);
    })();
  </script>
</section>


{{-- SECTION: Kompetensi Pelatihan (gambar SVG lokal) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
{{-- HEADER --}}
<div class="text-center mb-6">
 <!-- Badge -->
<div class="inline-block bg-[#F3E8E9] text-[#861D23]
            text-[18px] md:text-[20px] lg:text-[22px]
            px-5 py-1.5 rounded-md
            font-[Volkhov] font-bold leading-none mb-6">
  Kompetensi Pelatihan
</div>

  <!-- Judul utama dengan stroke kuning -->
  <h2 class="heading-stroke text-[20px] md:text-[24px] lg:text-[26px]
             font-[Volkhov] font-bold text-[#0E2A7B] leading-snug relative inline-block mb-6">
    <span class="relative z-10">
      Belajar dengan didampingi oleh instruktur yang ahli di kompetensinya
    </span>
    <span class="absolute inset-0 text-transparent [-webkit-text-stroke:2px_#FFDE59] pointer-events-none">
      Belajar dengan didampingi oleh instruktur yang ahli di kompetensinya
    </span>
  </h2>
</div>


    {{-- SLIDER --}}
    <div class="relative">
      <div id="kompetensi-track"
     class="flex gap-4 md:gap-5 lg:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar py-1"
     style="scrollbar-width:none;-ms-overflow-style:none;">
        @php
          $items = [
            'Tata Busana'              => 'tata-busana.svg',
            'Tata Boga'                => 'tata-boga.svg',
            'Tata Kecantikan'          => 'tata-kecantikan.svg',
            'Teknik Pemesinan'         => 'teknik-pemesinan.svg',
            'Teknik Otomotif'          => 'teknik-otomotif.svg',
            'Teknik Pendingin'         => 'teknik-pendingin.svg',
            'Teknik Pengelasan'        => 'teknik-pengelasan.svg',
            'Desain Grafis'            => 'mjc-desain-grafis.svg',
            'Web Desain'               => 'mjc-web-desain.svg',
            'Animasi'                  => 'mjc-animasi.svg',
            'Fotografi'                => 'mjc-fotografi.svg',
            'Videografi'               => 'mjc-videografi.svg',
          ];
        @endphp
@foreach($items as $nama => $file)
  <div class="shrink-0 snap-start relative w-[260px] h-[180px] rounded-lg overflow-hidden group
              transition-all duration-300">
    <!-- Gambar -->
    <img src="{{ asset('images/profil/'.$file) }}" alt="{{ $nama }}"
         class="w-full h-full object-cover">

    <!-- Overlay gradient (hilang saat hover) -->
    <div class="absolute inset-0 pointer-events-none transition-opacity duration-300 opacity-100 group-hover:opacity-0"
         style="background: linear-gradient(180deg, rgba(219,231,247,0.5) 0%, rgba(21,36,175,0.8) 100%);">
    </div>

    <!-- Teks di tengah bawah -->
    <div class="absolute bottom-3 left-0 right-0 z-10 text-center">
      <h3 class="text-white font-[Montserrat] font-medium text-[16px]">
        {{ $nama }}
      </h3>
    </div>
  </div>
@endforeach

      </div>

     {{-- BUTTONS & CTA sejajar di bawah slider --}}
<div class="flex items-center justify-between mt-6">

  {{-- Tombol navigasi (kiri) --}}
  <div class="flex gap-3">
    <button id="prevBtn" type="button"
            class="w-8 h-8 flex items-center justify-center bg-white rounded-full border border-[#B6BBE6]
                   text-[#0E2A7B] hover:bg-[#1524AF] hover:text-white transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
    </button>
    <button id="nextBtn" type="button"
            class="w-8 h-8 flex items-center justify-center bg-white rounded-full border border-[#B6BBE6]
                   text-[#0E2A7B] hover:bg-[#1524AF] hover:text-white transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </button>
  </div>

  {{-- CTA (kanan) --}}
  <a href="#"
     class="inline-flex items-center gap-2 bg-[#1524AF] hover:bg-[#0E1F73]
            text-white text-sm font-medium px-5 py-2.5 rounded-md transition">
    Lihat Semua Kompetensi
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
  </a>
</div>
    </div>
  </div>

  {{-- Script: geser 1 kartu per klik (hitung lebar + gap dinamis) --}}
  <script>
    (function () {
      const track  = document.getElementById('kompetensi-track');
      const prev   = document.getElementById('prevBtn');
      const next   = document.getElementById('nextBtn');

      function getStep() {
        const first = track.querySelector(':scope > *');
        if (!first) return 280;
        const rect = first.getBoundingClientRect();
        const gap  = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap || '0');
        return Math.round(rect.width + gap);
      }

      next.addEventListener('click', () => {
        track.scrollBy({ left: getStep(), behavior: 'smooth' });
      });
      prev.addEventListener('click', () => {
        track.scrollBy({ left: -getStep(), behavior: 'smooth' });
      });
    })();
  </script>
</section>




<!-- SECTION: Data Statistik -->
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

      <!-- Left Column -->
      <div class="lg:col-span-4 flex flex-col justify-between">
        <div>
          <div class="inline-flex items-center px-3 py-1 rounded-md bg-[#F3E8E9] mb-3">
            <span class="text-[#861D23] font-[Volkhov] font-bold text-[16px]">Data Statistik</span>
          </div>
        <h2 class="heading-stroke font-[Volkhov] font-bold text-[24px] md:text-[28px] leading-snug mb-3">
  Rekapitulasi Rata-Rata<br/>Program Pelatihan
</h2>
          <p class="text-sm text-slate-700 leading-relaxed mb-5">
            Hasil menunjukkan bahwa program pelatihan kami efektif meningkatkan pemahaman dan keterampilan peserta,
            terbukti dari kenaikan nilai rata-rata pre-test ke post-test.
          </p>

         <!-- Bagian list judul pelatihan (ubah hanya bagian ini) -->
<ul id="listPelatihan" class="space-y-2">
  <li>
    <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="0">
      <span class="dot w-2 h-2 rounded-full bg-[#1524AF]"></span>
      <span class="label flex-1 text-[14px] font-[Montserrat] font-medium text-[#1524AF]">Judul Pelatihan 1</span>
    </button>
    <div class="divider h-[1px] bg-[#1524AF]"></div>
  </li>
  <li>
    <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="1">
      <span class="dot w-2 h-2 rounded-full bg-[#000000]"></span>
      <span class="label flex-1 text-[14px] font-[Montserrat] font-medium text-[#000000]">Judul Pelatihan 2</span>
    </button>
    <div class="divider h-[1px] bg-[#000000]"></div>
  </li>
  <li>
    <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="2">
      <span class="dot w-2 h-2 rounded-full bg-[#000000]"></span>
      <span class="label flex-1 text-[14px] font-[Montserrat] font-medium text-[#000000]">Judul Pelatihan 3</span>
    </button>
    <div class="divider h-[1px] bg-[#000000]"></div>
  </li>
  <li>
    <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="3">
      <span class="dot w-2 h-2 rounded-full bg-[#000000]"></span>
      <span class="label flex-1 text-[14px] font-[Montserrat] font-medium text-[#000000]">Judul Pelatihan 4</span>
    </button>
    <div class="divider h-[1px] bg-[#000000]"></div>
  </li>
</ul>
        </div>

        <a href="#" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-[#1524AF] text-white text-[14px] mt-6 shadow-sm hover:shadow transition-all duration-200 self-start">Cari Tahu Lebih
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
      </div>
<!-- Right Column (Chart) -->
<div class="lg:col-span-8">
  <div class="grid grid-cols-3 gap-4 mb-4">
    <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-4 text-center">
      <div class="text-[28px] font-[Volkhov] font-bold text-[#081526]">63.48</div>
      <div class="text-xs font-[Montserrat] font-medium text-[#081526]">Rata-Rata Pre-Test</div>
    </div>

    <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-4 text-center">
      <div class="text-[28px] font-[Volkhov] font-bold text-[#081526]">90</div>
      <div class="text-xs font-[Montserrat] font-medium text-[#081526]">Praktek</div>
    </div>

    <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-4 text-center">
      <div class="text-[28px] font-[Volkhov] font-bold text-[#081526]">80.76</div>
      <div class="text-xs font-[Montserrat] font-medium text-[#081526]">Rata-Rata Post-Test</div>
    </div>
  </div>

 <div class="rounded-2xl bg-white border-2 border-[#1524AF] p-4 md:p-5">
    <div class="relative w-full h-[320px]">
      <canvas id="statistikChart"></canvas>
    </div>
  </div>
</div>
    </div>
  </div>
</section>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const ctx = document.getElementById('statistikChart').getContext('2d');

  const pelatihanLabels = ['Teknik Pengelasan', 'Teknik Mesin Bubut', 'Teknik Mesin CNC', 'Teknik Elektro'];

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: pelatihanLabels,
      datasets: [
        {
          label: 'Pre-Test',
          data: [8, 22, 12, 29],
          borderColor: '#FF6107',        // oranye
          pointBackgroundColor: '#FF6107',
          pointBorderColor: '#FF6107',
          borderWidth: 2,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 5,
          fill: false
        },
        {
          label: 'Post-Test',
          data: [24, 53, 75, 94],
          borderColor: '#2F4BFF',        // biru
          pointBackgroundColor: '#2F4BFF',
          pointBorderColor: '#2F4BFF',
          borderWidth: 2,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 5,
          fill: false
        },
        {
          label: 'Praktek',
          data: [38, 70, 35, 60],
          borderColor: '#6B2C47',        // maroon/ungu tua
          pointBackgroundColor: '#6B2C47',
          pointBorderColor: '#6B2C47',
          borderWidth: 2,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 5,
          fill: false
        },
        {
          label: 'Rata-Rata',
          data: [52, 10, 26, 49],
          borderColor: '#DBCC8F',        // beige/kuning muda
          pointBackgroundColor: '#DBCC8F',
          pointBorderColor: '#DBCC8F',
          borderWidth: 2,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 5,
          fill: false
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      layout: {
        padding: { top: 10, right: 16, left: 16, bottom: 8 }
      },
      plugins: {
        legend: {
          position: 'top',
          align: 'center',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            boxWidth: 10,
            boxHeight: 10,
            padding: 12,
            color: '#081526',
            font: { size: 13, weight: '600' }
          }
        },
        tooltip: {
          callbacks: {
            // tooltip ringkas: “Label Dataset: Angka”
            label: (ctx) => `${ctx.dataset.label}: ${ctx.formattedValue}`
          }
        }
      },
      scales: {
        x: {
          offset: true,
          ticks: {
            font: { size: 12 },
            color: '#8787A3'
          },
          grid: {
            display: true,                 // garis vertikal seperti contoh
            drawTicks: false,
            color: '#8787A3',
            lineWidth: 1
          },
          border: {
            display: true,
            color: '#8787A3',
            width: 1.5
          }
        },
        y: {
          beginAtZero: true,
          min: 0,
          max: 100,
          ticks: {
            stepSize: 20,
            font: { size: 12 },
            color: '#8787A3'
          },
          grid: {
            // hanya garis 0 yang terlihat (baseline)
            color: (ctx) => (ctx.tick.value === 0 ? '#8787A3' : 'transparent'),
            lineWidth: (ctx) => (ctx.tick.value === 0 ? 1.5 : 0),
            drawTicks: false
          },
          border: {
            display: true,
            color: '#8787A3',
            width: 1.5
          }
        }
      }
    }
  });
</script>


<script>
  // Toggle active state (ubah bagian dot, label, divider warna)
  (function(){
    const list = document.getElementById('listPelatihan');
    if (!list || list.dataset.inited) return;
    list.dataset.inited = '1';

    const ACTIVE_TEXT = 'text-[#1524AF]';
    const INACTIVE_TEXT = 'text-[#000000]';
    const ACTIVE_DOT = 'bg-[#1524AF]';
    const INACTIVE_DOT = 'bg-[#000000]';
    const ACTIVE_DIV = 'bg-[#1524AF]';
    const INACTIVE_DIV = 'bg-[#000000]';

    function setActive(idx){
      list.querySelectorAll('li').forEach((li,i)=>{
        const label = li.querySelector('.label');
        const dot   = li.querySelector('.dot');
        const div   = li.querySelector('.divider');
        if (!label || !dot || !div) return;
        label.classList.remove(ACTIVE_TEXT); label.classList.add(INACTIVE_TEXT);
        dot.classList.remove(ACTIVE_DOT);     dot.classList.add(INACTIVE_DOT);
        div.classList.remove(ACTIVE_DIV);     div.classList.add(INACTIVE_DIV);
        if (i === idx){
          label.classList.remove(INACTIVE_TEXT); label.classList.add(ACTIVE_TEXT);
          dot.classList.remove(INACTIVE_DOT);     dot.classList.add(ACTIVE_DOT);
          div.classList.remove(INACTIVE_DIV);     div.classList.add(ACTIVE_DIV);
        }
      });
    }

    setActive(0);

    list.addEventListener('click',(e)=>{
      const btn = e.target.closest('.pel-btn');
      if (!btn) return;
      const idx = parseInt(btn.dataset.index,10) || 0;
      setActive(idx);
    });
  })();
</script>

{{-- SECTION: Panduan Pelatihan (Full-width image + gradient overlay) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="relative rounded-2xl overflow-hidden">

      <!-- BG foto full -->
      <div class="w-full h-[280px] md:h-[340px] lg:h-[380px] bg-cover bg-center relative"
           style="background-image: url('{{ asset('images/bgvideo.svg') }}');">
        <!-- Overlay gradient dari kanan ke kiri -->
<div class="absolute inset-0"
     style="background: linear-gradient(270deg,
        rgba(21,36,175,1) 29%,
        rgba(21,36,175,0.34) 66%,
        rgba(21,36,175,0) 100%);">
</div>

      </div>

      <!-- Overlay grid 2 kolom -->
      <div class="absolute inset-0 grid grid-cols-1 lg:grid-cols-2">
        <!-- KIRI: Tombol Play -->
        <div class="relative flex items-center justify-center p-6">
         <!-- Tombol Play: ikon saja, tanpa background -->
<!-- Tombol Play: hanya icon, diperbesar -->
<a href="https://www.youtube.com/watch?v=JZXEx5i6U9o" target="_blank" rel="noopener"
   class="absolute inset-0 flex items-center justify-center">
  <img src="{{ asset('images/icons/play.svg') }}"
       alt="Play Icon"
       class="w-16 h-16 md:w-20 md:h-20 object-contain select-none pointer-events-none">
</a>
        </div>

        <!-- KANAN: Shape persegi panjang warna DBE7F7 -->
        <div class="relative flex items-center justify-end p-6">
          <div class="bg-[#DBE7F7] text-[#0E2A7B] rounded-2xl shadow-md
                      w-full lg:max-w-[540px] p-6 md:p-8 lg:p-10">
           <h2 class="heading-stroke text-center text-[18px] md:text-[22px] lg:text-[24px]
           font-[Volkhov] font-bold leading-snug mb-3">
  Bersama, Kita Cetak Pendidikan<br>
   Vokasi yang Unggul
</h2>
            <p class="text-[15px] md:text-[16px] text-[#000000] leading-relaxed mb-6">
              Pahami alur permohonan peserta untuk mengikuti program pelatihan di UPT PTKK
              guna memastikan kelancaran proses.
            </p>
           <a href="#panduan"
   class="mx-auto flex items-center justify-center gap-2
          bg-[#1524AF] text-white px-8 md:px-10 py-2.5 rounded-md
          font-medium hover:opacity-90 transition w-fit">
  Lihat Panduan
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
       stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
  </svg>
</a>

          </div>
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
