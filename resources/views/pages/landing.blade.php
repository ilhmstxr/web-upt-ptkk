<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    /* gaya singkat */
    .upt-stroke { text-shadow:-1px -1px 0 #861D23,1px -1px 0 #861D23,-1px 1px 0 #861D23,1px 1px 0 #861D23; }
    .heading-stroke { color:#1524AF; -webkit-text-fill-color:#1524AF; text-shadow:-1px -1px 0 #FFDE59,1px -1px 0 #FFDE59,-1px 1px 0 #FFDE59,1px 1px 0 #FFDE59; }

    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px) scale(0.95); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
    .animate-badge { animation: fadeInUp 0.8s ease-out forwards; }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
  @stack('styles')
</head>
<body class="bg-[#F1F9FC] antialiased">

@php
use Illuminate\Support\Facades\Storage;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\Kompetensi;
use Illuminate\Support\Str;

/* Ambil 3 berita terbaru */
$latestBeritas = Berita::query()
    ->where('is_published', true)
    ->whereNotNull('published_at')
    ->orderBy('published_at', 'desc')
    ->take(3)
    ->get();
@endphp

{{-- TOPBAR --}}
@include('components.layouts.app.topbar')

{{-- NAVBAR --}}
@include('components.layouts.app.navbarlanding')

{{-- ================== HERO: Slider Dinamis (tabel banners) ================== --}}
@php
  // 1. Gabungkan Featured dan Others menjadi satu koleksi
    $allBanners = collect();
    if ($featured) {
        $allBanners->push($featured);
    }
    // Gabung dengan banner lainnya
    $allBanners = $allBanners->merge($others);
    $count = $allBanners->count();
@endphp

@if($count > 0)
<header class="w-full bg-[#F1F9FC]">
  <div class="w-full py-2">
    {{-- ========================================== --}}
    {{-- KONDISI 1: JIKA GAMBAR HANYA SATU (STATIC) --}}
    {{-- ========================================== --}}
    @if($count === 1)
        <div class="relative w-full flex justify-center">
             {{-- Langsung tampilkan ukuran penuh (mirip state .active) --}}
             <div class="w-[87%] md:w-[87%] lg:w-[87%]">
                <div class="w-full h-[40vw] md:h-[340px] lg:h-[450px] max-h-[480px] min-h-[200px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden shadow-lg">
                    <img
                      src="{{ Storage::url($allBanners->first()->image) }}"
                      alt="Featured Banner"
                      class="w-full h-full object-cover select-none"
                    >
                </div>
             </div>
        </div>

    {{-- ========================================== --}}
    {{-- KONDISI 2: JIKA GAMBAR BANYAK (SLIDER)     --}}
    {{-- ========================================== --}}
    @else
        <div id="hero" class="relative">
          {{-- TRACK --}}
          <div
            id="hero-track"
            class="flex items-center overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar select-none py-2"
            style="scrollbar-width:none;-ms-overflow-style:none;"
          >
            <div aria-hidden="true" class="shrink-0 snap-none pointer-events-none w-[10%] md:w-[12.5%] lg:w-[15%]"></div>

            {{-- CLONES KIRI --}}
            @if($count >= 2)
                @foreach($allBanners->slice(-2) as $index => $banner)
                    <div class="hero-slide clone shrink-0 snap-center w-[87%] md:w-[87%] lg:w-[87%] transition-transform duration-300" data-real="{{ $count - 2 + $loop->index }}">
                      <div class="w-full h-[40vw] md:h-[340px] lg:h-[450px] max-h-[480px] min-h-[200px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
                        <img src="{{ Storage::url($banner->image) }}" class="w-full h-full object-cover select-none" draggable="false">
                      </div>
                    </div>
                @endforeach
            @endif

            {{-- REAL SLIDES --}}
            @foreach($allBanners as $index => $banner)
                <div class="hero-slide shrink-0 snap-center w-[87%] md:w-[87%] lg:w-[87%] transition-transform duration-300" data-real="{{ $index }}">
                  <div class="w-full h-[40vw] md:h-[340px] lg:h-[450px] max-h-[480px] min-h-[200px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
                    <img src="{{ Storage::url($banner->image) }}" class="w-full h-full object-cover select-none" draggable="false">
                  </div>
                </div>
            @endforeach

            {{-- CLONES KANAN --}}
            @if($count >= 2)
                @foreach($allBanners->slice(0, 2) as $index => $banner)
                    <div class="hero-slide clone shrink-0 snap-center w-[87%] md:w-[87%] lg:w-[87%] transition-transform duration-300" data-real="{{ $index }}">
                      <div class="w-full h-[40vw] md:h-[340px] lg:h-[450px] max-h-[480px] min-h-[200px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
                        <img src="{{ Storage::url($banner->image) }}" class="w-full h-full object-cover select-none" draggable="false">
                      </div>
                    </div>
                @endforeach
            @endif

            <div aria-hidden="true" class="shrink-0 snap-none pointer-events-none w-[15%] md:w-[12%] lg:w-[10%]"></div>
          </div>

          {{-- CONTROLS --}}
          <div class="mt-4 flex items-center justify-center gap-8 md:gap-12">
            <button id="hero-prev" class="w-9 h-9 grid place-items-center rounded-full border-2 border-gray-300 text-gray-600 hover:[bg-white/60] hover:border-[#1524AF] hover:text-[#1524AF] transition-colors">
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z" /></svg>
            </button>
            <div id="hero-dots" class="flex items-center gap-3">
                @foreach($allBanners as $index => $banner)
                  <button class="w-2.5 h-2.5 rounded-full {{ $index === 0 ? 'bg-[#1524AF]' : 'bg-gray-300' }} transition-colors"></button>
                @endforeach
            </div>
            <button id="hero-next" class="w-9 h-9 grid place-items-center rounded-full border-2 border-gray-300 text-gray-600 hover:bg-white/60 hover:border-[#1524AF] hover:text-[#1524AF] transition-colors">
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="m8.59 16.59 1.41 1.41 6-6-6-6L8.59 6.41 13.17 11z" /></svg>
            </button>
          </div>
        </div>
    @endif
  </div>
</header>
@endif

<style>
  .no-scrollbar::-webkit-scrollbar {
    display: none;
  }

  .hero-slide {
    transform: scale(0.90);
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

    // Jika tidak ada track (misal data kosong), hentikan script
    if (!track) return;

    const slides = Array.from(track.querySelectorAll('.hero-slide'));
    const dots = Array.from(document.getElementById('hero-dots').children);
    const prev = document.getElementById('hero-prev');
    const next = document.getElementById('hero-next');

    // KONFIGURASI DINAMIS DARI BLADE
    const REAL_COUNT = {{ $count }};
    // Kita menambahkan 2 clone di kiri, jadi slide "Real" pertama ada di index 2
    const CLONES_LEFT_COUNT = 2;

    const FIRST_REAL = CLONES_LEFT_COUNT;
    const LAST_REAL = FIRST_REAL + REAL_COUNT - 1;

    // Identifikasi index clone untuk swapping
    // Clone kiri (sebelum real pertama) adalah clone dari index terakhir
    const LEFT_CLONE_BEFORE_FIRST = FIRST_REAL - 1;
    // Clone kanan (setelah real terakhir) adalah clone dari index 0
    const RIGHT_CLONE_AFTER_LAST = LAST_REAL + 1;

    const ANIM = 300,
      BUF = 40;

    let currentIndex = FIRST_REAL;
    let isTransitioning = false;

    // ===== Util dasar =====
    const realOf = (idx) => {
        // Jika index berada di dalam range Real, kembalikan index relatif terhadap 0
        if (idx >= FIRST_REAL && idx <= LAST_REAL) {
            return idx - FIRST_REAL;
        }
        // Jika clone, ambil dari data-real attribute
        return parseInt(slides[idx].dataset.real, 10);
    };

    const setDots = (r) =>
      dots.forEach((d, i) => {
        d.className =
          'w-2.5 h-2.5 rounded-full transition-colors ' +
          (i === r ? 'bg-[#1524AF]' : 'bg-gray-300');
      });

    const setActive = (idx) =>
      slides.forEach((s, i) => s.classList.toggle('active', i === idx));

    const centerOffset = (idx) =>
      slides[idx].offsetLeft -
      (track.clientWidth - slides[idx].clientWidth) / 2;

    const scrollToIndex = (idx, smooth = true) =>
      track.scrollTo({
        left: centerOffset(idx),
        behavior: smooth ? 'smooth' : 'auto',
      });

    function smoothScrollToIndex(idx, cb) {
      const prevSnap = track.style.scrollSnapType;
      track.style.scrollSnapType = 'none';

      const target = centerOffset(idx);

      track.scrollTo({ left: target, behavior: 'smooth' });

      const t0 = performance.now(),
        MAX = ANIM + 300,
        EPS = 1;

      function tick() {
        const atTarget = Math.abs(track.scrollLeft - target) <= EPS;
        const overtime = performance.now() - t0 > MAX;

        if (atTarget || overtime) {
          track.style.scrollSnapType = prevSnap;
          cb && cb();
          return;
        }

        requestAnimationFrame(tick);
      }

      requestAnimationFrame(tick);
    }

    function rafSwap(fn) {
      requestAnimationFrame(() => requestAnimationFrame(fn));
    }

    function seamlessSwapByDelta(fromCloneIdx, toRealIdx) {
      const prevBehavior = track.style.scrollBehavior;
      const prevSnap = track.style.scrollSnapType;

      track.style.scrollBehavior = 'auto';
      track.style.scrollSnapType = 'none';

      const delta =
        centerOffset(toRealIdx) - centerOffset(fromCloneIdx);

      track.scrollLeft += delta;

      track.style.scrollBehavior = prevBehavior || '';
      track.style.scrollSnapType = prevSnap || '';

      currentIndex = toRealIdx;
      setActive(currentIndex);
      setDots(realOf(currentIndex));
    }

    // ===== Panah NEXT =====
    function goNext() {
      if (isTransitioning) return;
      isTransitioning = true;

      if (currentIndex === LAST_REAL) {
        // Loncat ke clone kanan (yang merepresentasikan Real 0)
        setActive(FIRST_REAL); // Set visual aktif ke tujuan agar smooth
        setDots(0);

        const cloneIdx = RIGHT_CLONE_AFTER_LAST;

        smoothScrollToIndex(cloneIdx, () => {
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

    // ===== Panah PREV =====
    function goPrev() {
      if (isTransitioning) return;
      isTransitioning = true;

      if (currentIndex === FIRST_REAL) {
        // Loncat ke clone kiri (yang merepresentasikan Real Terakhir)
        setActive(LAST_REAL);
        setDots(REAL_COUNT - 1); // Dot terakhir

        const cloneIdx = LEFT_CLONE_BEFORE_FIRST;

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

    function step(dir, times) {
      if (times <= 0) return;

      const tick = () => {
        dir > 0 ? goNext() : goPrev();
        times--;

        if (times > 0) setTimeout(tick, ANIM + BUF + 10);
      };

      tick();
    }

    // Event Listener untuk Dots
    dots.forEach((d, targetReal) => {
      d.addEventListener('click', () => {
        if (isTransitioning) return;

        const curReal = realOf(currentIndex);
        if (targetReal === curReal) return;

        // Logika jarak terpendek (wrap around)
        const r = (targetReal - curReal + REAL_COUNT) % REAL_COUNT;
        const l = (curReal - targetReal + REAL_COUNT) % REAL_COUNT;

        if (r <= l) step(1, r);
        else step(-1, l);
      });
    });

    // ===== Swipe Manual Handling =====
    let debounce = null;

    track.addEventListener(
      'scroll',
      () => {
        if (isTransitioning) return;

        clearTimeout(debounce);

        debounce = setTimeout(() => {
          const mid = track.scrollLeft + track.clientWidth / 2;
          let nearest = currentIndex,
            best = Infinity;

          for (let i = 0; i < slides.length; i++) {
            const center =
              slides[i].offsetLeft + slides[i].clientWidth / 2;
            const d = Math.abs(center - mid);

            if (d < best) {
              best = d;
              nearest = i;
            }
          }

          if (nearest < FIRST_REAL) {
            // Jika user scroll mentok kiri ke clone -> swap ke real kanan
            // Kita harus mencari index REAL yang sesuai dengan clone ini
            // Clone kiri index X merepresentasikan REAL index Y
            const realIdx = parseInt(slides[nearest].dataset.real, 10);
            const targetIndexInDOM = FIRST_REAL + realIdx;

            rafSwap(() => seamlessSwapByDelta(nearest, targetIndexInDOM));
          } else if (nearest > LAST_REAL) {
            // Jika user scroll mentok kanan ke clone -> swap ke real kiri
             const realIdx = parseInt(slides[nearest].dataset.real, 10);
             const targetIndexInDOM = FIRST_REAL + realIdx;

            rafSwap(() => seamlessSwapByDelta(nearest, targetIndexInDOM));
          } else {
            currentIndex = nearest;
            setActive(currentIndex);
            setDots(realOf(currentIndex));
          }
        }, 80);
      },
      { passive: true }
    );

    // Init awal
    scrollToIndex(FIRST_REAL, false);
    setActive(FIRST_REAL);
    setDots(0);

    // Event tombol
    next.addEventListener('click', goNext);
    prev.addEventListener('click', goPrev);

    // ==========================================
    // FITUR AUTO PLAY (Ganti Otomatis)
    // ==========================================
    const AUTO_PLAY_DELAY = 8000;
    let autoPlayTimer;

    function startAutoPlay() {
      // Hapus timer lama biar gak numpuk
      stopAutoPlay();

      // Jalankan timer baru
      autoPlayTimer = setInterval(() => {
        // Hanya pindah jika sedang tidak ada animasi transisi
        if (!isTransitioning) {
            goNext();
        }
      }, AUTO_PLAY_DELAY);
    }

    function stopAutoPlay() {
      clearInterval(autoPlayTimer);
    }

    // 1. Jalankan auto play saat halaman dimuat
    startAutoPlay();

    // (Opsional) Untuk HP: berhenti saat disentuh
    track.addEventListener('touchstart', stopAutoPlay, { passive: true });
    track.addEventListener('touchend', startAutoPlay, { passive: true });
  })();
</script>

{{-- SECTION: Cerita Kami (DINAMIS + FALLBACK DEFAULT) --}}
@php
    // Anggap "ada data" kalau minimal salah satu terisi
    $hasCerita = !empty($cerita)
        && (filled($cerita->title) || filled($cerita->excerpt) || filled($cerita->content) || filled($cerita->image_url));

    // ===== IMAGE URL =====
    if ($hasCerita && filled($cerita->image_url)) {
        $imgSrc = Str::startsWith($cerita->image_url, ['http://', 'https://', '/'])
            ? $cerita->image_url
            : Storage::url($cerita->image_url);
    } else {
        $imgSrc = asset('images/cerita-kami.jpg'); // fallback
    }

    // ===== TITLE =====
    $title = ($hasCerita && filled($cerita->title))
        ? $cerita->title
        : 'UPT Pengembangan Teknis Dan Keterampilan Kejuruan';

    // ===== CONTENT (HTML dari rich editor) =====
    $contentHtml = ($hasCerita && filled($cerita->content)) ? $cerita->content : null;

    if ($contentHtml) {
        $contentHtml = preg_replace('/(&nbsp;)+/i', ' ', $contentHtml);
    }

    $fallbackHtml = '
      <p>UPT PTKK merupakan salah satu Unit Pelaksana Teknis di bawah Dinas Pendidikan Provinsi Jawa Timur yang memiliki tugas dan fungsi dalam menyediakan fasilitas pelatihan berbasis kompetensi. Sebagai pelopor pelatihan vokasi, lembaga ini terus memperkuat perannya melalui penyelenggaraan program-program yang relevan, progresif, dan berdampak nyata.</p>
      <p>Selain itu UPT PTKK diberi kepercayaan oleh Lembaga Sertifikasi Kompetensi (LSK) berbasis KKNI di bawah naungan KEMENDIKBUD Vokasi sebagai Tempat Uji Kompetensi (TUK) bidang keahlian sebagai berikut:</p>
      <ol>
        <li>Tata Boga</li>
        <li>Tata Busana</li>
        <li>Tata Kecantikan</li>
        <li>Teknik Elektronika</li>
        <li>Teknik Otomotif</li>
        <li>Fotografi</li>
        <li>Teknik Informasi Komunikasi (Web Desain/RPL, Desain Grafis, Animasi, Konten Kreator/Videografi)</li>
      </ol>
    ';

    $bodyHtml = $contentHtml ?: $fallbackHtml;
    $ceritaUrl = url('/cerita-kami');
@endphp

<section class="relative bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="flex flex-col md:flex-row gap-8 md:gap-12 lg:gap-16">

      {{-- KIRI: gambar (CENTER terhadap tinggi konten kanan) --}}
      <div class="shrink-0 md:w-[420px] flex justify-center md:justify-start md:items-center">
        <div class="relative rounded-2xl overflow-hidden shadow-xl ring-[2.5px] ring-[#1524AF]
                    w-[300px] md:w-[380px] lg:w-[420px] aspect-[3/2] bg-slate-200">
          <img
            src="{{ $imgSrc }}"
            alt="{{ $title }}"
            class="absolute inset-0 w-full h-full object-cover"
            loading="lazy"
          />
        </div>
      </div>

      {{-- KANAN: konten --}}
      <div class="flex-1 flex flex-col w-full items-center md:items-start">
        {{-- Badge Cerita Kami --}}
        <div class="w-full flex mb-[15px] justify-center md:justify-start">
          <span class="inline-flex items-center
                    px-4 md:px-0 py-1 rounded-md bg-[#F3E8E9] text-[#861D23]
                    font-bold text-base md:text-lg lg:text-[20px] font-[Volkhov] shadow-sm leading-tight">
            Cerita Kami
          </span>
        </div>

        {{-- Title --}}
        <h2 class="mb-[15px] font-['Volkhov'] font-bold text-[22px] md:text-[26px] leading-tight text-[#1524AF] heading-stroke max-w-[32ch] md:max-w-[28ch] lg:max-w-[32ch] text-center md:text-left">
          {{ $title }}
        </h2>

        {{-- Body: render HTML (dengan styling prose biar list rapi) --}}
        <div class="prose max-w-none text-justify prose-p:my-3 prose-ol:my-3 prose-li:my-1
                    [&_p]:!leading-tight [&_li]:!leading-tight
                    prose-p:text-[#081526] prose-li:text-[#081526]
                    prose-p:font-[Montserrat] prose-li:font-[Montserrat]
                    prose-p:font-medium prose-li:font-medium
                    prose-p:text-[14px] md:prose-p:text-[15px] lg:prose-p:text-[16px] ">
          {!! $bodyHtml !!}
        </div>

        {{-- Button --}}
        <a href="{{ $ceritaUrl }}"
           class="mt-4 inline-flex items-center justify-center gap-2 w-max
                  px-4 py-1
                  rounded-lg bg-[#1524AF] text-white font-['Montserrat'] font-medium
                  text-[14px] md:text-[15px] lg:text-[16px]
                  shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
          <span class="leading-none">Cari tahu lebih</span>
          <svg xmlns="http://www.w3.org/2000/svg"
               class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
          </svg>
        </a>

      </div>
    </div>
  </div>
</section>
{{-- /SECTION: Cerita Kami --}}

{{-- SECTION: Jatim Bangkit (oval slim, bigger icons, tighter gap) --}}
<section class="relative bg-[#F1F9FC]">
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
          <div class="jatim-marquee flex w-max items-center flex-nowrap
                      animate-[jatim-scroll-x_linear_infinite] [animation-duration:24s]">

            {{-- Bagian 1 --}}
            <div class="flex items-center flex-nowrap
                        px-8 md:px-12
                        gap-8 md:gap-12 lg:gap-16">
              <img src="{{ asset('images/icons/cetar.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="Cetar">
              <img src="{{ asset('images/icons/dindik.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="Dindik">
              <img src="{{ asset('images/icons/jatim.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="Jatim">
              <img src="{{ asset('images/icons/berakhlak.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="Berakhlak">
              <img src="{{ asset('images/icons/optimis.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="Optimis">
            </div>

            {{-- Bagian 2 (duplikat) --}}
            <div class="flex items-center flex-nowrap
                        px-8 md:px-12
                        gap-8 md:gap-12 lg:gap-16" aria-hidden="true">
              <img src="{{ asset('images/icons/cetar.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/dindik.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/jatim.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/berakhlak.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/optimis.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] w-auto max-w-none flex-shrink-0" alt="">
            </div>
          </div>

          {{-- Fade kiri-kanan --}}
          <div class="pointer-events-none absolute inset-0
                      [mask-image:linear-gradient(to_right,transparent,black_15%,black_85%,transparent)]">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- SECTION: Berita Terbaru (DINAMIS) --}}
<section class="relative bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- HEADER (Menggunakan style terbaru Tuan Putri) --}}
    <div class="grid gap-y-2 mb-6">
      {{-- Badge Berita Terbaru --}}
      <div class="w-full flex mb-[15px] justify-center md:justify-start">
        <span class="inline-flex items-center
                     px-4 md:px-0 py-1 rounded-md bg-[#F3E8E9] text-[#861D23]
                     font-bold text-base md:text-lg lg:text-[20px] font-[Volkhov] shadow-sm leading-tight">
          Berita Terbaru
        </span>
      </div>

      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <h2 class="mb-[15px] md:mb-0 mx-auto md:mx-0
                   font-['Volkhov'] font-bold text-[22px] md:text-[26px] leading-tight
                   text-[#1524AF] heading-stroke
                   max-w-[38ch] md:max-w-[24ch] lg:max-w-[38ch]
                   text-center md:text-left">
          Jangan lewatkan kabar terbaru dari UPT PTKK
        </h2>

        <a href="{{ route('berita.index') ?? '#' }}"
           class="mt-4 md:mt-0 self-center md:self-auto inline-flex items-center justify-center gap-2 w-max
                  px-4 py-1
                  rounded-lg bg-[#1524AF] text-white font-['Montserrat'] font-medium
                  text-[14px] md:text-[15px] lg:text-[16px]
                  shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
          <span class="leading-none">Lihat Semua Berita</span>
          <svg xmlns="http://www.w3.org/2000/svg"
               class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
          </svg>
        </a>
      </div>
    </div>

    {{-- GRID CARD (Style Baru: Rounded-26px, Shadow Glow) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @if($latestBeritas->isEmpty())
        {{-- FALLBACK: Belum ada berita --}}
        @for($i=1;$i<=3;$i++)
          <article class="group rounded-[26px] border border-[#D0D5DD] bg-white p-4 sm:p-5 shadow-sm transition-all duration-300 hover:border-[#1524AF] hover:shadow-[0_10px_30px_rgba(21,36,175,0.18)] hover:bg-gradient-to-br hover:from-[#EFF5FF] hover:to-[#F8FBFF]">
             <div class="aspect-[16/11] w-full rounded-[20px] overflow-hidden border border-[#E2E8F0] mb-3 bg-gray-100 flex items-center justify-center text-gray-400">
               Belum ada gambar
             </div>
             <div class="flex items-center gap-2 text-[#6B7280] text-xs mb-1">
               <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect><line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line><line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line><line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line></svg>
               <span>-</span>
             </div>
             <h3 class="font-[Volkhov] text-[16px] sm:text-[18px] leading-snug mb-2 text-[#081526]">Belum ada berita</h3>
             <p class="font-[Montserrat] text-[13px] sm:text-[14px] text-[#374151] mb-3 leading-relaxed">Silakan tambahkan berita dari panel admin.</p>
          </article>
        @endfor
      @else
        {{-- BERITA TERSEDIA --}}
        @foreach($latestBeritas as $b)
          @php
            $imgUrl = $b->image ? Storage::url($b->image) : asset('images/berita/placeholder.jpg');
            $pubDate = optional($b->published_at ?? $b->created_at)->format('d F Y');
            $excerpt = Str::limit(strip_tags($b->content ?? ''), 120);
            $slugOrUrl = route('berita.show', $b->slug ?? $b->id);
          @endphp

          <article
            class="group rounded-[26px] border border-[#D0D5DD] bg-white
                   p-4 sm:p-5 shadow-sm
                   transition-all duration-300
                   hover:border-[#1524AF]
                   hover:shadow-[0_10px_30px_rgba(21,36,175,0.18)]
                   hover:bg-gradient-to-br hover:from-[#EFF5FF] hover:to-[#F8FBFF]">

            {{-- Gambar --}}
            <a href="{{ $slugOrUrl }}" class="block mb-3">
              <div class="aspect-[16/11] w-full rounded-[20px] overflow-hidden border border-[#E2E8F0] transition-colors group-hover:border-[#1524AF]">
                <img
                  src="{{ $imgUrl }}"
                  alt="{{ $b->title }}"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                  loading="lazy"
                  onerror="this.onerror=null;this.src='{{ asset('images/berita/placeholder.jpg') }}'">
              </div>
            </a>

            {{-- Tanggal --}}
            <div class="flex items-center gap-2 text-[#6B7280] text-xs mb-1">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
              </svg>
              <span>{{ $pubDate }}</span>
            </div>

            {{-- Judul --}}
            <h3 class="font-[Volkhov] text-[16px] sm:text-[18px] leading-snug mb-2
                       text-[#081526] transition-colors duration-200
                       group-hover:text-[#1524AF]">
              <a href="{{ $slugOrUrl }}" class="block">
                {{-- Judul dipotong biar rapi di HP --}}
                <span class="block md:hidden">{{ Str::words($b->title, 6, '...') }}</span>
                <span class="hidden md:block">{{ $b->title }}</span>
              </a>
            </h3>

            {{-- Excerpt --}}
            <p class="font-[Montserrat]
                      text-[13px] sm:text-[14px]
                      text-[#374151]
                      mb-3
                      leading-relaxed
                      break-words
                      overflow-hidden
                      line-clamp-3
                      text-justify">
              {{ $excerpt }}
            </p>

            {{-- Baca Selengkapnya --}}
            <a href="{{ $slugOrUrl }}"
               class="inline-flex items-center gap-2 font-[Montserrat]
                      text-[13px] sm:text-[14px]
                      text-[#595959]
                      transition-colors duration-200
                      group-hover:text-[#1524AF] group-hover:underline underline-offset-2">
              Baca Selengkapnya
              <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>
          </article>
        @endforeach
      @endif
    </div>
  </div>
</section>
{{-- /SECTION: Berita Terbaru --}}

{{-- SECTION: Sorotan Pelatihan (FULL DINAMIS) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <style>
    @keyframes sorotan-scroll-x {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
      .sorotan-marquee { animation: none !important; }
    }
  </style>

  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] text-center flex flex-col items-center">

    {{-- BADGE --}}
    <div class="inline-block bg-[#F3E8E9] text-[#861D23]
                font-[Volkhov] font-bold
                text-base md:text-lg lg:text-[20px]
                leading-tight shadow-sm
                px-4 py-1 rounded-md mb-4">
      Sorotan Pelatihan
    </div>

    {{-- JUDUL UTAMA --}}
    <h2 class="heading-stroke text-[20px] md:text-[24px] lg:text-[26px]
               font-[Volkhov] font-bold text-[#0E2A7B] leading-snug relative inline-block mt-2
               mb-4 md:mb-6 lg:mb-8">
      <span class="relative z-10">
        Mengasah Potensi dan Mencetak Tenaga Kerja yang Kompeten
      </span>
      <span class="absolute inset-0 text-transparent [-webkit-text-stroke:2px_#FFDE59] pointer-events-none">
        Mengasah Potensi dan Mencetak Tenaga Kerja yang Kompeten
      </span>
    </h2>

    {{-- PHP LOGIC: FIX JSON & STORAGE --}}
    @php
      // 1. Ambil data dari DB
      $collection = \App\Models\SorotanPelatihan::query()
          ->where('is_published', true)
          ->latest()
          ->get();

      // 2. Mapping Data
      $sorotanData = $collection->map(function ($item) {


        // Ambil data JSON photos
        $rawPhotos = $item->photos ?? [];
        if (is_string($rawPhotos)) {
            $rawPhotos = json_decode($rawPhotos, true) ?? [];
        }
        if (!is_array($rawPhotos)) $rawPhotos = [];

        // Convert ke URL Lengkap
        $files = collect($rawPhotos)->map(function ($path) {
            return \Illuminate\Support\Facades\Storage::url($path);
        })->toArray();

        return [
          'key'   => 'spotlight-' . $item->id,
          'label' => $item->title ?? 'Sorotan',
          'desc'  => $item->description,
          'files' => $files,
        ];
      })->values(); // Reset index array biar bisa dipanggil [0]
    @endphp

    {{-- PEMBUKA IF --}}
    @if($sorotanData->isNotEmpty())

      {{-- NAMA PELATIHAN + DESKRIPSI (Default ambil index 0) --}}
      <div id="sorotan-top" class="w-full mb-6 md:mb-8 flex flex-col items-center md:flex-row md:items-center md:justify-start md:gap-6 text-center md:text-left">
      <div id="sorotan-top" class="w-full mb-6 md:mb-8 flex flex-col items-center md:flex-row md:items-center md:justify-start md:gap-6 text-center md:text-left">
        <div class="shrink-0">
          <button type="button"
                  class="sorotan-label bg-[#DBE7F7] text-[#1524AF]
                         font-[Volkhov] font-bold 
                         text-base md:text-lg lg:text-[20px] 
                         rounded-md px-4 py-1 leading-tight whitespace-nowrap">
                         font-[Volkhov] font-bold
                         text-base md:text-lg lg:text-[20px]
                         rounded-md px-4 py-1 leading-tight whitespace-nowrap">
            {{ $sorotanData[0]['label'] }}
          </button>
        </div>

        <p id="sorotan-desc"
           class="mt-2 md:mt-0 text-sm md:text-base lg:text-[17px]
                  font-[Montserrat] font-medium text-[#000000] leading-relaxed md:max-w-[75%]">
          {{ $sorotanData[0]['desc'] }}
        </p>
      </div>

      {{-- SLIDER FOTO --}}
      <div class="w-full mb-8 md:mb-10 lg:mb-12">
        @foreach($sorotanData as $i => $cat)
          @php $files = $cat['files']; @endphp

          <div class="sorotan-pane {{ $i===0 ? '' : 'hidden' }}" data-pane="{{ $cat['key'] }}">
            <div class="relative">
              <div class="overflow-hidden">
                <div class="sorotan-track flex items-center gap-4 md:gap-5 lg:gap-6 [will-change:transform]" data-key="{{ $cat['key'] }}">
                  @for($loopIdx = 0; $loopIdx < 2; $loopIdx++)
                    @foreach($files as $fname)
                      <div class="relative h-[130px] md:h-[150px] lg:h-[170px] w-[220px] md:w-[260px] lg:w-[280px] rounded-2xl overflow-hidden shrink-0">
                        {{-- FIX: Hapus asset(), langsung $fname --}}
                        <img src="{{ $fname }}" alt="{{ $cat['label'] }}" loading="lazy" class="w-full h-full object-cover">
                      </div>
                    @endforeach
                  @endfor
                </div>
                <div class="pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]"></div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- KONTROL KATEGORI --}}
      <div class="mt-2 flex items-center justify-center gap-8 md:gap-12">
        <button id="tabPrev" class="w-8 h-8 flex items-center justify-center rounded-full border border-[#B6BBE6] text-[#0E2A7B] hover:bg-[#1524AF] hover:text-white transition" aria-label="Kategori sebelumnya" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>

        <div id="tabDots" class="flex items-center gap-2" aria-label="Indikator kategori"></div>

        <button id="tabNext" class="w-8 h-8 flex items-center justify-center rounded-full border border-[#B6BBE6] text-[#0E2A7B] hover:bg-[#1524AF] hover:text-white transition" aria-label="Kategori selanjutnya" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>

      {{-- SCRIPT --}}
      <script>
        (function(){
          const tabOrder = @json($sorotanData->pluck('key'));
          const panes = document.querySelectorAll('.sorotan-pane');
          const label = document.querySelector('.sorotan-label');
          const desc  = document.getElementById('sorotan-desc');
          const meta = @json(
            $sorotanData->mapWithKeys(fn($s) => [
              $s['key'] => ['label' => $s['label'], 'desc' => $s['desc']]
            ])
          );

          function currentKey(){
            const active = Array.from(panes).find(p=>!p.classList.contains('hidden'));
            return active ? active.dataset.pane : tabOrder[0];
          }
          function currentIndex(){ return tabOrder.indexOf(currentKey()); }

          const prev = document.getElementById('tabPrev');
          const next = document.getElementById('tabNext');
          const tabDots = document.getElementById('tabDots');

          if(prev) prev.addEventListener('click', ()=>showByIndex(currentIndex()-1));
          if(next) next.addEventListener('click', ()=>showByIndex(currentIndex()+1));

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


          if(tabOrder.length > 0) setActive(tabOrder[0]);
        })();

        (function(){
          const tracks = document.querySelectorAll('.sorotan-track');
          const SPEED = 0.8;

          tracks.forEach((track) => {
            let offset = 0;
            function animate() {
              const halfWidth = track.scrollWidth / 2;
              offset -= SPEED;
              if (Math.abs(offset) >= halfWidth) {
                offset += halfWidth;
              }
              track.style.transform = `translateX(${offset}px)`;
              requestAnimationFrame(animate);
            }
            requestAnimationFrame(animate);
          });
        })();
      </script>

    @else
      <p class="text-center text-gray-500 py-10">Belum ada sorotan pelatihan yang dipublikasikan.</p>
    @endif

  </div>
</section>

{{-- SECTION: Kompetensi Pelatihan (gambar dari DB Bidang) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- HEADER --}}
    <div class="text-center mb-6">
      {{-- Badge --}}
      <div class="inline-block bg-[#F3E8E9] text-[#861D23]
                font-[Volkhov] font-bold 
                text-base md:text-lg lg:text-[20px]
                leading-tight shadow-sm
                px-4 py-1 rounded-md mb-4">
                font-[Volkhov] font-bold
                text-base md:text-lg lg:text-[20px]
                leading-tight shadow-sm
                px-4 py-1 rounded-md mb-4">
        Kompetensi Pelatihan
      </div>

      {{-- Judul utama --}}
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
@php
    // Ambil dari DB:
    // 1 = Kelas Keterampilan & Teknik
    // 0 = MJC
    $kompetensiKeterampilan = Kompetensi::where('kelas_keterampilan', 1)
        ->orderBy('nama_kompetensi')
        ->get();

    $kompetensiMjc = Kompetensi::where('kelas_keterampilan', 0)
        ->orderBy('nama_kompetensi')
        ->get();

    // Gabungkan: Keterampilan dulu, baru MJC
    $kompetensiItems = $kompetensiKeterampilan->concat($kompetensiMjc);
@endphp


    <div class="relative">
      <div id="kompetensi-track"
           class="flex gap-4 md:gap-5 lg:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar py-1"
           style="scrollbar-width:none;-ms-overflow-style:none;">

       @forelse($kompetensiItems as $komp)
  @php
      $nama = $komp->nama_kompetensi ?? 'Kompetensi';

      // Ambil URL gambar:
      if (!empty($komp->gambar)) {
          if (Str::startsWith($komp->gambar, ['http://', 'https://'])) {
              $imgUrl = $komp->gambar; // sudah full URL
          } else {
              // diasumsikan disimpan di storage public (storage/kompetensi/xxx)
              $imgUrl = Storage::url($komp->gambar);
          }
      } else {
          // fallback ke gambar default
          $imgUrl = asset('images/profil/default-bidang.svg');
      }
  @endphp

  <div class="shrink-0 snap-start relative w-[260px] h-[180px] rounded-lg overflow-hidden group
              transition-all duration-300">
    <img src="{{ $imgUrl }}" alt="{{ $nama }}" class="w-full h-full object-cover">
    <div class="absolute inset-0 pointer-events-none transition-opacity duration-300 opacity-100 group-hover:opacity-0"
         style="background: linear-gradient(180deg,
                 rgba(219,231,247,0.5) 0%,
                 rgba(21,36,175,0.8) 100%);">
    </div>
    <div class="absolute bottom-3 left-0 right-0 z-10 text-center">
      <h3 class="text-white font-[Montserrat] font-medium text-[16px]">
        {{ $nama }}
      </h3>
    </div>
  </div>
@empty
  <div class="shrink-0 w-full text-center py-10 text-slate-600">
    Belum ada data kompetensi pelatihan yang tersimpan.
  </div>
@endforelse

      </div>

      {{-- BUTTONS & CTA sejajar di bawah slider --}}
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mt-6">

        {{-- Tombol navigasi (kiri) --}}
        <div class="flex gap-3 justify-center md:justify-start">
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
        <a href="{{ route('kompetensi') }}"
           class="mt-4 md:mt-0 self-center md:self-auto inline-flex items-center justify-center gap-2 w-max
                  px-4 py-1 
                  rounded-lg bg-[#1524AF] text-white font-['Montserrat'] font-medium 
                  text-[14px] md:text-[15px] lg:text-[16px] 
                  shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
           class="mt-4 md:mt-0 self-center md:self-auto inline-flex items-center justify-center gap-2 w-max
                  px-4 py-1
                  rounded-lg bg-[#1524AF] text-white font-['Montserrat'] font-medium
                  text-[14px] md:text-[15px] lg:text-[16px]
                  shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
          Lihat Semua Kompetensi
          <svg xmlns="http://www.w3.org/2000/svg" 
               class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" 
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
          <svg xmlns="http://www.w3.org/2000/svg"
               class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
          </svg>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- Script: geser 1 kartu per klik (masih sama) --}}
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


<!-- ========================= -->
<!-- SECTION: DATA STATISTIK   -->
<!-- ========================= -->
<section class="relative bg-[#F1F9FC] py-4 md:py-6" id="section-data-statistik">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

      <!-- Left Column -->
      <div class="lg:col-span-4 flex flex-col justify-between">
        <div>
          <div class="w-full flex mb-[15px] justify-center md:justify-start">
            <span class="inline-flex items-center
                      px-4 md:px-0 py-1 rounded-md bg-[#F3E8E9] text-[#861D23]
                      font-bold text-base md:text-lg lg:text-[20px] font-[Volkhov] shadow-sm leading-tight">
              Data Statistik
            </span>
          </div>

          <div class="flex flex-col items-center md:items-start w-full">
            {{-- JUDUL: Disamakan style-nya (Volkhov, Blue, Size 22px/26px) --}}
            <h2 class="heading-stroke font-[Volkhov] font-bold
                      text-[22px] md:text-[26px] leading-tight
                      text-[#1524AF] mb-3
                      text-center md:text-left">
              Rekapitulasi Rata-Rata<br/>Program Pelatihan
            </h2>

            {{-- TEKS: Disamakan style-nya (Montserrat, Ukuran pas, Justify di HP) --}}
            <p class="font-['Montserrat'] font-medium
                      text-[14px] md:text-[15px]
                      text-[#374151] leading-relaxed mb-5
                      text-justify">
              Hasil menunjukkan bahwa program pelatihan kami efektif meningkatkan pemahaman dan keterampilan peserta,
              terbukti dari kenaikan nilai rata-rata pre-test ke post-test.
            </p>
          </div>

          <!-- List Pelatihan (dibangun JS agar sinkron) -->
          <ul id="listPelatihan" class="space-y-2">
            <li>
              <button type="button" class="pel-btn w-full flex items-start gap-2 py-1.5 text-left" data-index="0">
                <span class="dot w-2 h-2 rounded-full mt-1 bg-[#1524AF]"></span>
                <span class="label flex-1 text-[13px] font-[Montserrat] font-medium leading-snug text-[#1524AF]">
                  Loading...
                </span>
              </button>
              <div class="divider h-[1px] bg-[#1524AF]"></div>
            </li>
          </ul>

          <div id="dummyNotice"
               class="hidden mt-4 text-[12px] text-slate-600 bg-white/70 border border-slate-200 rounded-lg p-3">
            Data Statistik.
          </div>
        </div>

        <a href="{{ route('statistik.index') }}"
           class="mt-8 self-center md:self-auto inline-flex items-center justify-center gap-2 w-max
                       px-3 py-1.5 md:px-4 md:py-1
                       rounded-lg bg-[#1524AF] text-white font-['Montserrat'] font-medium
                       text-[12px] md:text-[15px] lg:text-[16px]
                       shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
          <span class="leading-none">Cari Tahu Lebih</span>
          <svg xmlns="http://www.w3.org/2000/svg"
               class="w-3.5 h-3.5 md:w-5 md:h-5"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
          </svg>
        </a>
      </div>

      <!-- Right -->
      <div class="lg:col-span-8">
        <!-- Summary cards -->
        <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-4">
          <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-3 sm:p-4 text-center">
            <div id="preAvgCard" class="text-[18px] sm:text-[22px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">0</div>
            <div class="text-[10px] sm:text-xs font-[Montserrat] font-medium text-[#081526]">
              Rata-Rata Pre-Test
            </div>
          </div>

          <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-3 sm:p-4 text-center">
            <div id="praktekAvgCard" class="text-[18px] sm:text-[22px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">0</div>
            <div class="text-[10px] sm:text-xs font-[Montserrat] font-medium text-[#081526]">
              Praktek
            </div>
          </div>

          <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-3 sm:p-4 text-center">
            <div id="postAvgCard" class="text-[18px] sm:text-[22px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">0</div>
            <div class="text-[10px] sm:text-xs font-[Montserrat] font-medium text-[#081526]">
              Rata-Rata Post-Test
            </div>
          </div>
        </div>

        <!-- Chart box -->
        <div class="rounded-2xl bg-white border-2 border-[#1524AF] p-4 md:p-5">
          <div class="relative w-full h-[320px]">
            <canvas id="statistikChart"></canvas>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Chart.js (PASTIKAN cuma sekali di halaman, jangan dobel) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('listPelatihan');
  const canvasEl = document.getElementById('statistikChart');
  const dummyNote = document.getElementById('dummyNotice');
  const preCard = document.getElementById('preAvgCard');
  const praktekCard = document.getElementById('praktekAvgCard');
  const postCard = document.getElementById('postAvgCard');

  if (!list || !canvasEl) return;
  if (!window.Chart) {
    console.error('[Statistik] Chart.js belum keload (window.Chart undefined)');
    return;
  }

  const toNum = (v) => {
    if (v === null || v === undefined) return 0;
    if (typeof v === 'number') return v;
    const s = String(v).trim().replace(/\./g, '').replace(',', '.');
    const n = parseFloat(s);
    return Number.isFinite(n) ? n : 0;
  };
  const round2 = (n) => Math.round(n * 100) / 100;

  function wrap2Lines(text, maxCharsPerLine = 28) {
    const words = String(text).trim().split(/\s+/);
    const lines = [];
    let line = '';

    for (const w of words) {
      const test = (line ? line + ' ' : '') + w;
      if (test.length <= maxCharsPerLine) {
        line = test;
      } else {
        if (line) lines.push(line);
        line = w;
        if (lines.length === 1) break;
      }
    }
    if (line && lines.length < 2) lines.push(line);

    if (lines[1] && lines[1].length > maxCharsPerLine) {
      lines[1] = lines[1].slice(0, maxCharsPerLine - 3) + '...';
    }
    return lines;
  }

  const dummyData = {
    pelatihans: [
      { id: 1, nama: 'Akselerasi Kelas MJC Guru Angkatan I 2025', warna: '#1524AF', warna_inactive: '#000000' },
      { id: 2, nama: 'Mobile Training Unit (MTU) Angkatan II 2025', warna: '#1524AF', warna_inactive: '#000000' },
      { id: 3, nama: 'MILEA Kelas MJC Angkatan II 2025', warna: '#1524AF', warna_inactive: '#000000' },
      { id: 4, nama: 'Akselerasi TUK Kelas Keterampilan 2025', warna: '#1524AF', warna_inactive: '#000000' },
    ],
    labels: [
      'Akselerasi MJC Guru I',
      'MTU Angkatan II',
      'MILEA MJC II',
      'TUK Kelas Keterampilan',
    ],
    datasets: {
      pre: [49.87, 70.87, 62.2, 68.57],
      post: [67.6, 82.67, 78.8, 91.07],
      praktek: [86.21, 90.46, 88.24, 89.6],
      rata: [80.72, 87.08, 84.69, 86.55],
    },
  };

  const bladeData = {
    pelatihans: {!! json_encode(
      collect($pelatihans ?? [])->map(fn($p) => [
        'id' => $p->id,
        'nama' => $p->nama_pelatihan ?? 'Pelatihan',
        'warna' => $p->warna ?? '#1524AF',
        'warna_inactive' => $p->warna_inactive ?? '#000000',
      ])->values()
    ) !!},
    labels: {!! json_encode($labels ?? []) !!},
    datasets: {
      pre: {!! json_encode($pre ?? []) !!},
      post: {!! json_encode($post ?? []) !!},
      praktek: {!! json_encode($prak ?? []) !!},
      rata: {!! json_encode($rata ?? []) !!},
    },
  };

  const hasBladeData =
    Array.isArray(bladeData.labels) &&
    bladeData.labels.length > 0 &&
    bladeData.datasets &&
    Array.isArray(bladeData.datasets.pre) &&
    bladeData.datasets.pre.length > 0;

  const data = hasBladeData ? bladeData : dummyData;

  if (data === dummyData && dummyNote) {
    dummyNote.classList.remove('hidden');
  }

  if (!Array.isArray(data.pelatihans) || data.pelatihans.length === 0) {
    data.pelatihans = (data.labels || []).map((label, i) => ({
      id: i + 1,
      nama: label,
      warna: '#1524AF',
      warna_inactive: '#000000',
    }));
  }

  if (!Array.isArray(data.labels) || data.labels.length === 0) {
    data.labels = data.pelatihans.map((p) => p.nama || 'Pelatihan');
  }

  const chartData = {
    pre: (data.datasets?.pre || []).map(toNum),
    post: (data.datasets?.post || []).map(toNum),
    praktek: (data.datasets?.praktek || []).map(toNum),
    rata: (data.datasets?.rata || []).map(toNum),
  };

  list.innerHTML = data.pelatihans
    .map((p, i) => {
      const isFirst = i === 0;
      const active = p.warna || '#1524AF';
      const inactive = p.warna_inactive || '#000000';
      return `
        <li>
          <button type="button"
            class="pel-btn w-full flex items-start gap-2 py-1.5 text-left"
            data-index="${i}"
            data-color-active="${active}"
            data-color-inactive="${inactive}">
            <span class="dot w-2 h-2 rounded-full mt-1"
                  style="background-color:${isFirst ? active : inactive};"></span>

            <span class="label flex-1 text-[13px] font-[Montserrat] font-medium leading-snug"
                  style="color:${isFirst ? active : inactive};
                         display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
                         overflow:hidden;">
              ${p.nama}
            </span>
          </button>
          <div class="divider h-[1px]" style="background-color:${isFirst ? active : inactive};"></div>
        </li>
      `;
    })
    .join('');

  function setActive(idx) {
    list.querySelectorAll('li').forEach((li, i) => {
      const btn = li.querySelector('.pel-btn');
      const label = li.querySelector('.label');
      const dot = li.querySelector('.dot');
      const div = li.querySelector('.divider');
      const active = btn.dataset.colorActive || '#1524AF';
      const inactive = btn.dataset.colorInactive || '#000000';
      if (i === idx) {
        label.style.color = active;
        dot.style.backgroundColor = active;
        div.style.backgroundColor = active;
      } else {
        label.style.color = inactive;
        dot.style.backgroundColor = inactive;
        div.style.backgroundColor = inactive;
      }
    });
  }

  function setSummary(idx) {
    if (!preCard || !praktekCard || !postCard) return;
    preCard.textContent = round2(toNum(chartData.pre[idx]));
    praktekCard.textContent = round2(toNum(chartData.praktek[idx]));
    postCard.textContent = round2(toNum(chartData.post[idx]));
  }

  const ctx = canvasEl.getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: data.labels,
      datasets: [
        { label: 'Pre-Test', data: chartData.pre, borderColor: '#FF6107', pointBackgroundColor: '#FF6107', pointBorderColor: '#FF6107', borderWidth: 2, tension: 0.35, pointRadius: 4, pointHoverRadius: 5, fill: false },
        { label: 'Praktek', data: chartData.praktek, borderColor: '#6B2C47', pointBackgroundColor: '#6B2C47', pointBorderColor: '#6B2C47', borderWidth: 2, tension: 0.35, pointRadius: 4, pointHoverRadius: 5, fill: false },
        { label: 'Post-Test', data: chartData.post, borderColor: '#2F4BFF', pointBackgroundColor: '#2F4BFF', pointBorderColor: '#2F4BFF', borderWidth: 2, tension: 0.35, pointRadius: 4, pointHoverRadius: 5, fill: false },
        { label: 'Rata-Rata', data: chartData.rata, borderColor: '#DBCC8F', pointBackgroundColor: '#DBCC8F', pointBorderColor: '#DBCC8F', borderWidth: 2, tension: 0.35, pointRadius: 4, pointHoverRadius: 5, fill: false },
      ],
    },
    options: {
      maintainAspectRatio: false,
      layout: { padding: { top: 6, right: 14, left: 14, bottom: 6 } },
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
            font: { size: 12, weight: '600' },
          },
        },
        tooltip: {
          callbacks: { label: (c) => `${c.dataset.label}: ${c.formattedValue}` },
        },
      },
      scales: {
        x: {
          ticks: {
            font: { size: 11 },
            color: '#8787A3',
            maxRotation: 0,
            minRotation: 0,
            autoSkip: false,
            padding: 10,
            callback: function (value) {
              const label = this.getLabelForValue(value);
              return wrap2Lines(label, 18);
            },
          },
          grid: { display: true, drawTicks: false, color: 'rgba(135,135,163,0.25)', lineWidth: 1 },
          border: { display: true, color: 'rgba(135,135,163,0.55)', width: 1.2 },
          afterFit: (scale) => { scale.height = Math.max(scale.height, 56); },
        },
        y: {
          beginAtZero: true,
          min: 0,
          max: 100,
          ticks: { stepSize: 20, font: { size: 11 }, color: '#8787A3' },
          grid: { color: 'rgba(135,135,163,0.25)', drawTicks: false },
          border: { display: true, color: 'rgba(135,135,163,0.55)', width: 1.2 },
        },
      },
    },
  });

  setActive(0);
  setSummary(0);

  list.addEventListener('click', (e) => {
    const btn = e.target.closest('.pel-btn');
    if (!btn) return;
    const idx = parseInt(btn.dataset.index, 10) || 0;

    setActive(idx);
    setSummary(idx);
  });
});
</script>






{{-- SECTION: Panduan Pelatihan (Full-width image + gradient overlay) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="relative w-full rounded-2xl overflow-hidden shadow-sm">

      {{-- BG foto full --}}
      <div class="absolute inset-0 bg-cover bg-center z-0"
           style="background-image: url('{{ asset('images/bgvideo.svg') }}');">
        {{-- Overlay Gradient --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(270deg,
             rgba(21,36,175,1) 29%,
             rgba(21,36,175,0.34) 66%,
             rgba(21,36,175,0) 100%);">
        </div>
      </div>

      {{-- Overlay grid konten --}}
      <div class="relative z-10 grid grid-cols-2
                  gap-x-3 md:gap-x-4 lg:gap-x-8
                  px-3 sm:px-5 md:px-8 lg:px-10
                  py-5 md:py-6 lg:py-0
                  min-h-[220px] sm:min-h-[240px] md:min-h-[300px] lg:min-h-[380px]">

        {{-- KIRI: Tombol Play - posisi tengah area kiri --}}
        <div class="relative flex items-center justify-center">
          <a href="https://www.youtube.com/watch?v=JZXEx5i6U9o"
             target="_blank" rel="noopener"
             class="flex items-center justify-center
                    translate-x-2 sm:translate-x-4 md:translate-x-6 lg:translate-x-0">
            <img src="{{ asset('images/icons/play.svg') }}"
                 alt="Play Icon"
                 class="w-9 h-9 sm:w-11 sm:h-11 md:w-14 md:h-14 lg:w-20 lg:h-20
                        object-contain select-none">
          </a>
        </div>

        {{-- KANAN: Card panduan --}}
        <div class="relative flex items-center justify-end w-full h-full">
          <div
            class="bg-[#DBE7F7]/95 text-[#0E2A7B] rounded-xl shadow-md
                   w-full
                   max-w-[200px] sm:max-w-[260px] md:max-w-[360px] lg:max-w-[540px]
                   p-3 sm:p-4 md:p-5 lg:p-10
                   backdrop-blur-sm
                   flex flex-col items-center justify-center">
            <h2 class="heading-stroke text-[18px] md:text-[22px] lg:text-[24px]
                        font-[Volkhov] font-bold text-[#0E2A7B] leading-snug relative inline-block mt-1
                        mb-3 md:mb-6 lg:mb-8
                        text-center">
              <span class="relative z-10">
                Bersama, Kita Cetak Pendidikan Vokasi yang Unggul
              </span>
              <span class="absolute inset-0 text-transparent [-webkit-text-stroke:2px_#FFDE59] pointer-events-none">
                Bersama, Kita Cetak Pendidikan Vokasi yang Unggul
              </span>
            </h2>

            <p class="font-['Montserrat'] font-medium
                        text-[14px] md:text-[15px]
                        text-[#374151] leading-tight mb-5
                        text-justify">
              Pahami alur permohonan peserta untuk mengikuti program pelatihan di UPT PTKK
              guna memastikan kelancaran proses.
            </p>

            <a href="{{ route('panduan') }}"
               class="mt-8 self-center inline-flex items-center justify-center gap-2 w-max
                      px-3 py-1.5 md:px-4 md:py-1
                      rounded-lg bg-[#1524AF] text-white font-['Montserrat'] font-medium
                      text-[12px] md:text-[15px] lg:text-[16px]
                      shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
              <span class="leading-none">Lihat Panduan</span>
              <svg xmlns="http://www.w3.org/2000/svg"
                   class="w-3.5 h-3.5 md:w-5 md:h-5"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
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
