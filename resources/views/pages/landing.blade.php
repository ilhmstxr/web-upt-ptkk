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

{{-- SECTION: Cerita Kami (DINAMIS) --}}
<section class="relative bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 lg:gap-16">
      {{-- Kolom Kiri: Foto --}}
      <div class="shrink-0 flex justify-center md:justify-start">
        <div class="relative rounded-2xl overflow-hidden shadow-xl ring-[2.5px] ring-[#1524AF] 
                    w-[300px] md:w-[360px] lg:w-[400px] aspect-[3/2] bg-slate-200">
          @if(!empty($cerita) && $cerita->image_url)
            <img src="{{ $cerita->image_url }}" alt="{{ $cerita->title ?? 'Cerita Kami' }}" class="absolute inset-0 w-full h-full object-cover" />
          @else
            {{-- fallback static --}}
            <img src="{{ asset('images/cerita-kami.svg') }}" alt="Kegiatan UPT PTKK" class="w-full h-auto object-cover" />
          @endif
        </div>
      </div>

      {{-- Kolom Kanan: Teks --}}
      <div class="flex-1 flex flex-col w-full items-center md:items-start">
        {{-- Badge Cerita Kami --}}
        <div class="w-full flex mb-[15px] justify-center md:justify-start">
          <span class="inline-flex items-center
                    px-0 py-1 rounded-lg bg-[#F3E8E9] text-[#861D23]
                    font-bold text-base md:text-lg lg:text-[20px] font-[Volkhov] shadow-sm leading-tight">
                    Cerita Kami</span>
        </div>

       {{-- Heading (tetap / statis) --}}
          <h2 class="mb-[15px] font-['Volkhov'] font-bold text-[22px] md:text-[26px] leading-tight text-[#1524AF] heading-stroke max-w-[32ch] md:max-w-[28ch] lg:max-w-[32ch] text-center md:text-left">
            UPT Pengembangan Teknis Dan Keterampilan Kejuruan
          </h2>


                  {{-- Excerpt / Content ringkas --}}
                <p class="mb-[15px] md:mb-[28px] font-['Montserrat'] font-medium text-[#081526] leading-snug text-[14px] md:text-[15px] lg:text-[16px] text-justify">
            @if(!empty($cerita) && !empty($cerita->excerpt))
              {{ $cerita->excerpt }}
            @elseif(!empty($cerita) && !empty($cerita->content))
              {{ strip_tags($cerita->content) }}
            @else
              Adalah salah satu Unit Pelaksana Teknis dari Dinas Pendidikan Provinsi Jawa Timur yang mempunyai tugas dan fungsi
              memberikan fasilitas melalui pelatihan berbasis kompetensi dengan dilengkapi Tempat Uji Kompetensi (TUK) yang didukung
              oleh Lembaga Sertifikasi Kompetensi (LSK) di beberapa kompetensi keahlian strategis. Sebagai pelopor pelatihan vokasi,
              UPT PTKK terus memperkuat posisinya dengan menghadirkan program yang relevan, progresif, dan berdampak nyata.
              Melalui upaya tersebut, UPT PTKK berkomitmen mencetak lulusan yang terampil sehingga mampu berkontribusi pada
              kemajuan pendidikan di Jawa Timur.
            @endif
          </p>

        {{-- Tombol: ke halaman cerita lengkap (jika ada slug / route) --}}
        @php
          $ceritaUrl = '#';
          if(!empty($cerita)) {
              if(!empty($cerita->slug) && \Illuminate\Support\Facades\Route::has('cerita-kami.show')) {
                  $ceritaUrl = route('cerita-kami.show', $cerita->slug);
              } elseif(\Illuminate\Support\Facades\Route::has('cerita-kami')) {
                  $ceritaUrl = route('cerita-kami');
              } elseif(\Illuminate\Support\Facades\Route::has('story')) {
                  $ceritaUrl = route('story');
              } else {
                  $ceritaUrl = url('/cerita-kami');
              }
          } else {
              $ceritaUrl = \Illuminate\Support\Facades\Route::has('cerita-kami') ? route('cerita-kami') : '#';
          }
        @endphp

        <a href="{{ $ceritaUrl }}" 
          class=" inline-flex items-center justify-center gap-2 w-max 
                  px-4 py-1 
                  rounded-lg bg-[#1524AF] text-white font-['Montserrat'] font-medium 
                  text-[14px] md:text-[15px] lg:text-[16px] 
                  shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
          
          <span class="leading-none">Cari tahu lebih</span>
          
          {{-- Ikon diperbesar responsif (w-4 sm:w-5 md:w-6) --}}
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
          <div class="jatim-marquee flex w-[200%] items-center
                      animate-[jatim-scroll-x_linear_infinite] [animation-duration:24s]">
            {{-- Bagian 1 --}}
            <div class="flex w-1/2 items-center justify-between
                        px-6 md:px-10 lg:px-16
                        gap-4 md:gap-6 lg:gap-8">
              <img src="{{ asset('images/icons/cetar.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="Cetar">
              <img src="{{ asset('images/icons/dindik.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="Dindik">
              <img src="{{ asset('images/icons/jatim.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="Jatim">
              <img src="{{ asset('images/icons/berakhlak.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="Berakhlak">
              <img src="{{ asset('images/icons/optimis.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="Optimis">
            </div>
            {{-- Bagian 2 (duplikat) --}}
            <div class="flex w-1/2 items-center justify-between
                        px-6 md:px-10 lg:px-16
                        gap-4 md:gap-6 lg:gap-8"
                 aria-hidden="true">
              <img src="{{ asset('images/icons/cetar.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/dindik.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/jatim.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/berakhlak.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="">
              <img src="{{ asset('images/icons/optimis.svg') }}"
                   class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="">
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

{{-- SECTION: Berita Terbaru (DINAMIS) --}}
<section class="relative bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    <div class="grid gap-y-2 mb-6">
      <span class="inline-flex items-center justify-center bg-[#F3E8E9] text-[#861D23] font-[Volkhov] font-bold text-[15px] md:text-[16px] rounded-md leading-none px-3 py-1 shadow-sm w-fit">Berita Terbaru</span>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <h2 class="heading-stroke font-[Volkhov] font-bold text-[18px] sm:text-[20px] md:text-[24px] lg:text-[28px] leading-snug">Jangan lewatkan kabar terbaru dari UPT PTKK</h2>
        <a href="{{ route('berita.index') ?? '#' }}" class="inline-flex items-center justify-center gap-2 bg-[#1524AF] hover:bg-[#0E1E8B] text-white font-['Montserrat'] font-medium text-[12px] sm:text-[13px] md:text-[14px] px-[12px] py-[6px] sm:px-[14px] sm:py-[7px] md:px-[16px] md:py-[8px] rounded-xl shadow-md transition-all duration-200 self-start md:self-center active:scale-[.98]">
          <span class="leading-none">Lihat Semua Berita</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px] sm:w-[18px] sm:h-[18px] md:w-[20px] md:h-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M19 12l-4-4m0 8l4-4" /></svg>
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @if($latestBeritas->isEmpty())
        {{-- fallback ketika belum ada berita --}}
        @for($i=1;$i<=3;$i++)
          <article class="group bg-white border border-[#B6BBE6] rounded-2xl shadow-sm p-4 transition-all duration-300 hover:border-[#1524AF] hover:shadow-md">
            <div class="w-full h-[160px] bg-[#E7ECF3] rounded-lg mb-4 flex items-center justify-center text-sm text-[#727272]">Belum ada berita</div>
            <div class="flex items-center gap-2 text-[#727272] text-xs mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              <span>{{ now()->format('d F Y') }}</span>
            </div>
            <h3 class="text-[#081526] group-hover:text-[#1524AF] transition-colors duration-300 font-semibold mb-2">Belum ada berita tersedia</h3>
            <p class="text-sm text-[#081526] mb-3 leading-relaxed">Silakan tambahkan berita melalui panel admin.</p>
            <a href="#" class="text-[#595959] group-hover:text-[#1524AF] text-sm font-medium inline-flex items-center gap-1 transition-colors duration-300">Baca Selengkapnya →</a>
          </article>
        @endfor
      @else
        @foreach($latestBeritas as $b)
          @php
            // --- LOGIKA COPY PASTE DARI BERITA SHOW ---
            // Cukup gunakan Storage::url jika ada image, fallback ke asset jika tidak
            $imgUrl = $b->image ? Storage::url($b->image) : asset('images/berita/placeholder.jpg');

            $excerpt = Str::limit(strip_tags($b->content ?? ''), 120);

            // Format tanggal meniru berita_show (menggunakan optional)
            $pubDate = optional($b->published_at ?? $b->created_at)->format('d F Y');
          @endphp

          <article class="group bg-white border border-[#B6BBE6] rounded-2xl shadow-sm p-4 transition-all duration-300 hover:border-[#1524AF] hover:shadow-md">

            {{-- MARKUP GAMBAR --}}
            <div class="w-full h-[160px] rounded-lg mb-4 overflow-hidden">
               <img src="{{ $imgUrl }}" alt="{{ $b->title }}" class="w-full h-full object-cover rounded-lg shadow-md" loading="lazy">
            </div>

            <div class="flex items-center gap-2 text-[#727272] text-xs mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              <span>{{ $pubDate }}</span>
            </div>

            <h3 class="text-[#081526] group-hover:text-[#1524AF] transition-colors duration-300 font-semibold mb-2">
              {{ $b->title }}
            </h3>

            <p class="text-sm text-[#081526] mb-3 leading-relaxed">
              {!! e($excerpt) !!}
            </p>

            <a href="{{ route('berita.show', $b->slug ?? $b->id) ?? '#' }}" class="text-[#595959] group-hover:text-[#1524AF] text-sm font-medium inline-flex items-center gap-1 transition-colors duration-300">
              Baca Selengkapnya →
            </a>
          </article>
        @endforeach
      @endif
    </div>
  </div>
</section>
{{-- /SECTION: Berita Terbaru --}}

{{-- SECTION: Sorotan Pelatihan (FULL DINAMIS DARI DB) --}}
@php

    // Ambil data dari controller (bisa kosong)
    $collection = ($sorotans ?? collect())
        ->where('is_published', true);

    // Urutan kelas yang diizinkan
    $order = ['mtu', 'reguler', 'akselerasi'];

    // Bangun array hanya dari data yang BENAR-BENAR ada di DB
    $sorotanData = collect($order)
        ->map(function ($kelas) use ($collection) {
            $row = $collection->firstWhere('kelas', $kelas);
            if (!$row) {
                return null; // kalau belum ada row kelas itu, skip
            }

            $files = $row->photo_urls ?: [];   // dari accessor model

            return [
                'key'   => $kelas,
                'label' => $row->title ?? Str::headline($kelas),
                'desc'  => $row->description ?? '',
                'files' => $files,
            ];
        })
        ->filter()   // buang yang null (kelas yang belum ada di DB)
        ->values();
@endphp

@if($sorotanData->isNotEmpty())
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

    {{-- HEADER NAMA + DESKRIPSI --}}
    <div id="sorotan-top"
         class="w-full mb-6 md:mb-8 flex flex-col md:flex-row md:items-center md:justify-start md:gap-6 text-left">
      <div class="shrink-0">
        <button type="button"
                class="sorotan-label bg-[#DBE7F7] text-[#1524AF]
                       font-[Volkhov] font-bold text-[18px] md:text-[20px] lg:text-[22px]
                       rounded-md px-5 py-2.5 leading-tight whitespace-nowrap">
          {{ $sorotanData[0]['label'] }}
        </button>
      </div>

      <p id="sorotan-desc"
         class="mt-2 md:mt-0 text-sm md:text-base lg:text-[17px]
                font-[Montserrat] font-medium text-[#000000] leading-relaxed md:max-w-[75%]">
        {{ $sorotanData[0]['desc'] }}
      </p>
    </div>

    {{-- SLIDER FOTO PER KATEGORI --}}
    <div class="w-full mb-8 md:mb-10 lg:mb-12">
      @foreach($sorotanData as $i => $cat)
        @php
          // pastikan files array
          $files = is_array($cat['files']) ? $cat['files'] : (array) $cat['files'];
        @endphp
        <div class="sorotan-pane {{ $i === 0 ? '' : 'hidden' }}" data-pane="{{ $cat['key'] }}">
          <div class="relative">
            <div class="overflow-hidden">
              <div class="sorotan-track flex items-center gap-4 md:gap-5 lg:gap-6 [will-change:transform]" data-key="{{ $cat['key'] }}">
                @for($loopIdx = 0; $loopIdx < 2; $loopIdx++)
                  @foreach($files as $img)
                    <div class="relative h-[130px] md:h-[150px] lg:h-[170px]
                                w-[220px] md:w-[260px] lg:w-[280px]
                                rounded-2xl overflow-hidden shrink-0">
                      <img src="{{ $img }}" alt="{{ $cat['label'] }}" loading="lazy"
                           class="w-full h-full object-cover">
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

    {{-- CONTROLS --}}
    <div class="mt-2 flex items-center justify-center gap-6">
      <button id="sorotan-prev"
              type="button"
              class="w-8 h-8 flex items-center justify-center rounded-full border border-[#B6BBE6]
                     text-[#1524AF] hover:bg-[#1524AF] hover:text-white transition">
        ‹
      </button>

      <div id="sorotan-dots" class="flex items-center gap-2"></div>

      <button id="sorotan-next"
              type="button"
              class="w-8 h-8 flex items-center justify-center rounded-full border border-[#B6BBE6]
                     text-[#1524AF] hover:bg-[#1524AF] hover:text-white transition">
        ›
      </button>
    </div>

  </div>
</section>

{{-- SCRIPT: TAB / DOT + AUTO-SCROLL --}}
<script>
  (function () {
    const tabOrder = @json($sorotanData->pluck('key'));
    if (!tabOrder.length) return;

    const meta = @json(
        $sorotanData->mapWithKeys(fn($s) => [
            $s['key'] => ['label' => $s['label'], 'desc' => $s['desc']],
        ])
    );

    const panes   = Array.from(document.querySelectorAll('.sorotan-pane'));
    const labelEl = document.querySelector('.sorotan-label');
    const descEl  = document.getElementById('sorotan-desc');
    const dotsWrap = document.getElementById('sorotan-dots');
    const prevBtn  = document.getElementById('sorotan-prev');
    const nextBtn  = document.getElementById('sorotan-next');

    function currentKey() {
      const active = panes.find(p => !p.classList.contains('hidden'));
      return active ? active.dataset.pane : tabOrder[0];
    }

    function currentIndex() {
      return tabOrder.indexOf(currentKey());
    }

    function setActive(key) {
      panes.forEach(p => p.classList.toggle('hidden', p.dataset.pane !== key));

      if (meta[key]) {
        if (labelEl) labelEl.textContent = meta[key].label;
        if (descEl)  descEl.textContent  = meta[key].desc;
      }

      paintDots();
    }

    function paintDots() {
      if (!dotsWrap) return;
      const idx = currentIndex();
      dotsWrap.innerHTML = '';
      tabOrder.forEach((k, i) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'w-2.5 h-2.5 rounded-full transition ' +
          (i === idx ? 'bg-[#1524AF]' : 'bg-[#C7D3F5]');
        b.setAttribute('aria-label', meta[k]?.label ?? k);
        b.setAttribute('aria-current', i === idx ? 'true' : 'false');
        b.addEventListener('click', () => setActive(k));
        dotsWrap.appendChild(b);
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        const idx = currentIndex();
        const nextIdx = idx <= 0 ? tabOrder.length - 1 : idx - 1;
        setActive(tabOrder[nextIdx]);
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        const idx = currentIndex();
        const nextIdx = (idx + 1) % tabOrder.length;
        setActive(tabOrder[nextIdx]);
      });
    }

    // init
    setActive(tabOrder[0]);

    // AUTO-SCROLL TRACK FOTO
    const tracks = document.querySelectorAll('.sorotan-track');
    const SPEED = 0.8;

    tracks.forEach(track => {
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
@endif
{{-- /SECTION: Sorotan Pelatihan --}}



{{-- SECTION: Kompetensi Pelatihan (gambar dari DB Bidang) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- HEADER --}}
    <div class="text-center mb-6">
      {{-- Badge --}}
      <div class="inline-block bg-[#F3E8E9] text-[#861D23]
                  text-[18px] md:text-[20px] lg:text-[22px]
                  px-5 py-1.5 rounded-md
                  font-[Volkhov] font-bold leading-none mb-6">
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
           class="inline-flex items-center gap-2 bg-[#1524AF] hover:bg-[#0E1F73]
                  text-white text-sm font-medium px-5 py-2.5 rounded-md transition
                  self-center md:self-auto">
          Lihat Semua Kompetensi
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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


<!-- SECTION: Data Statistik (API + Blade fallback + Dummy fallback) -->
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

          <!-- List Pelatihan
               - Blade sebagai fallback awal
               - Akan di-replace JS jika API/dummy dipakai -->
          <ul id="listPelatihan" class="space-y-2">
            @forelse($pelatihans ?? [] as $pel)
              @php
                $idx = $loop->index;
                $colorActive = $pel->warna ?? '#1524AF';
                $colorInactive = $pel->warna_inactive ?? '#000000';
                $isFirst = $loop->first;
              @endphp
              <li>
                <button
                  type="button"
                  class="pel-btn w-full flex items-center gap-2 py-1.5 text-left"
                  data-index="{{ $idx }}"
                  data-color-active="{{ $colorActive }}"
                  data-color-inactive="{{ $colorInactive }}"
                >
                  <span class="dot w-2 h-2 rounded-full"
                        style="background-color: {{ $isFirst ? $colorActive : $colorInactive }};"></span>

                  <span class="label flex-1 text-[14px] font-[Montserrat] font-medium"
                        style="color: {{ $isFirst ? $colorActive : $colorInactive }};">
                    {{ \Illuminate\Support\Str::limit($pel->nama_pelatihan ?? 'Pelatihan', 60) }}
                  </span>
                </button>

                <div class="divider h-[1px]"
                     style="background-color: {{ $isFirst ? $colorActive : $colorInactive }};"></div>
              </li>
            @empty
              <!-- Placeholder minimal (akan diganti JS kalau dummy/api) -->
              <li>
                <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="0">
                  <span class="dot w-2 h-2 rounded-full bg-[#1524AF]"></span>
                  <span class="label flex-1 text-[14px] font-[Montserrat] font-medium text-[#1524AF]">
                    Loading...
                  </span>
                </button>
                <div class="divider h-[1px] bg-[#1524AF]"></div>
              </li>
            @endforelse
          </ul>

          <!-- Badge dummy info (muncul jika dummy dipakai) -->
          <div id="dummyNotice"
               class="hidden mt-4 text-[12px] text-slate-600 bg-white/70 border border-slate-200 rounded-lg p-3">
            Data asli belum tersedia. Menampilkan contoh statistik sementara.
          </div>
        </div>

        {{-- Safe route untuk tombol --}}
        @php
          try { $pelatihanIndexUrl = route('pelatihan.index'); }
          catch (\Throwable $e) { $pelatihanIndexUrl = '/pelatihan'; }
        @endphp

        <a href="{{ $pelatihanIndexUrl }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-[#1524AF] text-white text-[14px] mt-6 shadow-sm hover:shadow transition-all duration-200 self-start">
          Cari Tahu Lebih
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </a>
      </div>

      <!-- Right Column (Chart) -->
      <div class="lg:col-span-8 mt-6 lg:mt-0">

        <!-- Summary Cards -->
        <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-4">
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

        <!-- Chart Wrapper -->
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
(async function () {
  // =========================
  // 1) DUMMY DATA (fallback paling akhir)
  // =========================
  const dummyData = {
    pelatihans: [
      { id: 1, nama: 'Tata Boga', warna: '#1524AF', warna_inactive: '#000000' },
      { id: 2, nama: 'Teknik Pendingin dan Tata Udara', warna: '#1524AF', warna_inactive: '#000000' },
      { id: 3, nama: 'Tata Busana', warna: '#1524AF', warna_inactive: '#000000' },
      { id: 4, nama: 'Kecantikan', warna: '#1524AF', warna_inactive: '#000000' },
    ],
    labels: ['Tata Boga', 'Teknik Pendingin dan Tata Udara', 'Tata Busana', 'Kecantikan'],
    datasets: {
      pre:     [8, 22, 12, 29],
      post:    [24, 53, 75, 94],
      praktek: [38, 70, 35, 60],
      rata:    [52, 10, 26, 49],
    },
  };

  // =========================
  // 2) DATA BLADE (fallback tengah)
  // =========================
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
      pre:     {!! json_encode($pre ?? []) !!},
      post:    {!! json_encode($post ?? []) !!},
      praktek: {!! json_encode($prak ?? []) !!},
      rata:    {!! json_encode($rata ?? []) !!},
    },
  };

  const list      = document.getElementById('listPelatihan');
  const canvasEl  = document.getElementById('statistikChart');
  const dummyNote = document.getElementById('dummyNotice');

  const preCard     = document.getElementById('preAvgCard');
  const praktekCard = document.getElementById('praktekAvgCard');
  const postCard    = document.getElementById('postAvgCard');

  if (!list || !canvasEl) return;

  // =========================
  // 3) Fetch API (prioritas utama)
  // =========================
  let apiData = null;
  let useDummy = false;
  let useBlade = false;

  try {
    const res = await fetch('/api/statistik-pelatihan');
    apiData = await res.json();
  } catch (e) {
    apiData = null;
  }

  // =========================
  // Helper cek data kosong
  // =========================
  function isEmptyData(d) {
    if (!d) return true;
    if (!Array.isArray(d.pelatihans) || d.pelatihans.length === 0) return true;

    const ds = d.datasets || {};
    const allVals = [
      ...(ds.pre || []),
      ...(ds.post || []),
      ...(ds.praktek || []),
      ...(ds.rata || []),
    ];

    if (allVals.length === 0) return true;
    if (allVals.every(v => Number(v) === 0)) return true;

    return false;
  }

  // =========================
  // Tentukan sumber data
  // =========================
  if (!isEmptyData(apiData)) {
    // pakai API
  } else if (!isEmptyData(bladeData)) {
    apiData = bladeData;
    useBlade = true;
  } else {
    apiData = dummyData;
    useDummy = true;
  }

  const data = apiData;

  if (useDummy && dummyNote) dummyNote.classList.remove('hidden');

  // =========================
  // Render list pelatihan (selalu dari data final)
  // =========================
  list.innerHTML = (data.pelatihans ?? []).map((p, i) => {
    const activeColor = p.warna || '#1524AF';
    const inactiveColor = p.warna_inactive || '#000000';
    const isFirst = i === 0;

    return `
      <li>
        <button type="button"
          class="pel-btn w-full flex items-center gap-2 py-1.5 text-left"
          data-index="${i}"
          data-color-active="${activeColor}"
          data-color-inactive="${inactiveColor}">
          <span class="dot w-2 h-2 rounded-full"
                style="background-color:${isFirst ? activeColor : inactiveColor};"></span>
          <span class="label flex-1 text-[14px] font-[Montserrat] font-medium"
                style="color:${isFirst ? activeColor : inactiveColor};">
            ${p.nama || 'Pelatihan'}
          </span>
        </button>
        <div class="divider h-[1px]"
             style="background-color:${isFirst ? activeColor : inactiveColor};"></div>
      </li>
    `;
  }).join('');

  // =========================
  // Active state helper (pakai inline color)
  // =========================
  function setActive(idx){
    list.querySelectorAll('li').forEach((li, i) => {
      const btn = li.querySelector('.pel-btn');
      const label = li.querySelector('.label');
      const dot   = li.querySelector('.dot');
      const div   = li.querySelector('.divider');
      if (!btn || !label || !dot || !div) return;

      const colorActive   = btn.dataset.colorActive || '#1524AF';
      const colorInactive = btn.dataset.colorInactive || '#000000';

      if (i === idx){
        label.style.color = colorActive;
        dot.style.backgroundColor = colorActive;
        div.style.backgroundColor = colorActive;
      } else {
        label.style.color = colorInactive;
        dot.style.backgroundColor = colorInactive;
        div.style.backgroundColor = colorInactive;
      }
    });
  }

  // =========================
  // Summary cards per index
  // =========================
  function setSummaryByIndex(idx){
    const preVal     = data.datasets?.pre?.[idx] ?? 0;
    const praktekVal = data.datasets?.praktek?.[idx] ?? 0;
    const postVal    = data.datasets?.post?.[idx] ?? 0;

    if (preCard) preCard.textContent = preVal;
    if (praktekCard) praktekCard.textContent = praktekVal;
    if (postCard) postCard.textContent = postVal;
  }

  setActive(0);
  setSummaryByIndex(0);

  // =========================
  // Render Chart
  // =========================
  const ctx = canvasEl.getContext('2d');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: data.labels ?? [],
      datasets: [
        {
          label: 'Pre-Test',
          data: data.datasets?.pre ?? [],
          borderColor: '#FF6107',
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
          data: data.datasets?.post ?? [],
          borderColor: '#2F4BFF',
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
          data: data.datasets?.praktek ?? [],
          borderColor: '#6B2C47',
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
          data: data.datasets?.rata ?? [],
          borderColor: '#DBCC8F',
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
      layout: { padding: { top: 10, right: 16, left: 16, bottom: 8 } },
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
          callbacks: { label: (ctx) => `${ctx.dataset.label}: ${ctx.formattedValue}` }
        }
      },
      scales: {
        x: {
          offset: true,
          ticks: { font: { size: 12 }, color: '#8787A3' },
          grid: { display: true, drawTicks: false, color: '#8787A3', lineWidth: 1 },
          border: { display: true, color: '#8787A3', width: 1.5 }
        },
        y: {
          beginAtZero: true,
          min: 0,
          max: 100,
          ticks: { stepSize: 20, font: { size: 12 }, color: '#8787A3' },
          grid: {
            color: (ctx) => (ctx.tick.value === 0 ? '#8787A3' : 'transparent'),
            lineWidth: (ctx) => (ctx.tick.value === 0 ? 1.5 : 0),
            drawTicks: false
          },
          border: { display: true, color: '#8787A3', width: 1.5 }
        }
      }
    }
  });

  // =========================
  // Click list => aktif + update summary
  // =========================
  list.addEventListener('click', (e) => {
    const btn = e.target.closest('.pel-btn');
    if (!btn) return;
    const idx = parseInt(btn.dataset.index, 10) || 0;
    setActive(idx);
    setSummaryByIndex(idx);
  });

})();
</script>



{{-- SECTION: Panduan Pelatihan (Full-width image + gradient overlay) --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="relative rounded-2xl overflow-hidden">

      {{-- BG foto full --}}
      <div
        class="w-full h-[220px] sm:h-[240px] md:h-[300px] lg:h-[380px] bg-cover bg-center relative"
        style="background-image: url('{{ asset('images/bgvideo.svg') }}');"
      >
       {{-- Overlay gradient: muncul di semua device (HP, tablet, desktop) --}}
<div class="absolute inset-0"
     style="background: linear-gradient(270deg,
        rgba(21,36,175,1) 29%,
        rgba(21,36,175,0.34) 66%,
        rgba(21,36,175,0) 100%);">
</div>
      </div>

      {{-- Overlay grid konten --}}
      <div
        class="absolute inset-0 grid grid-cols-2
               gap-x-3 md:gap-x-4 lg:gap-x-8
               px-3 sm:px-5 md:px-8 lg:px-10
               py-5 md:py-6 lg:py-0"
      >

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
        <div class="relative flex items-center justify-end">
          <div
            class="bg-[#DBE7F7]/95 text-[#0E2A7B] rounded-xl shadow-md
                   w-full
                   max-w-[200px] sm:max-w-[260px] md:max-w-[360px] lg:max-w-[540px]
                   p-3 sm:p-4 md:p-5 lg:p-10
                   backdrop-blur-sm
                   md:translate-y-0 lg:translate-y-0"
          >
            <h2
              class="heading-stroke
                     text-left
                     text-[12px] sm:text-[14px] md:text-[18px] lg:text-[24px]
                     font-[Volkhov] font-bold leading-snug
                     mb-2 sm:mb-2.5 md:mb-3"
            >
              Bersama, Kita Cetak Pendidikan
              <br class="hidden md:block">
              Vokasi yang Unggul
            </h2>

            <p
              class="text-[11px] sm:text-[12px] md:text-[13px] lg:text-[16px]
                     text-[#000000] leading-relaxed
                     mb-3 sm:mb-4 md:mb-5 lg:mb-6"
            >
              Pahami alur permohonan peserta untuk mengikuti program pelatihan di UPT PTKK
              guna memastikan kelancaran proses.
            </p>

        <a href="{{ route('panduan') }}"
   class="flex items-center justify-start gap-1.5
          bg-[#1524AF] text-white
          px-3 sm:px-4 md:px-6 lg:px-10
          py-1.5 sm:py-2 md:py-2.5
          rounded-md
          text-[11px] sm:text-[12px] md:text-[13px] lg:text-[14px]
          font-medium hover:opacity-90 transition w-fit
          ml-0">
              Lihat Panduan
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                   stroke-width="2" viewBox="0 0 24 24" class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-5 md:h-5">
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