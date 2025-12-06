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
use App\Models\ProfilUPT;
use Illuminate\Support\Str;

/* Ambil banners */
$banners = Banner::query()
    ->where('is_active', true)
    ->orderBy('sort_order', 'asc')
    ->get();
$realCount = $banners->count() > 0 ? $banners->count() : 3;
$cloneCount = min(2, $realCount);

// Ambil Profil UPT
$profil = ProfilUPT::first();
// Teks Default jika database kosong
$defaultSejarah = "Adalah salah satu Unit Pelaksana Teknis dari Dinas Pendidikan Provinsi Jawa Timur yang mempunyai tugas dan fungsi memberikan fasilitas melalui pelatihan berbasis kompetensi dengan dilengkapi Tempat Uji Kompetensi (TUK) yang didukung oleh Lembaga Sertifikasi Kompetensi (LSK) di beberapa kompetensi keahlian strategis.";

// Definisikan variabel $teksCerita
$teksCerita = $profil && !empty($profil->sejarah) ? $profil->sejarah : $defaultSejarah;

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

{{-- HERO (UKURAN DIPERBAIKI) --}}
<header class="w-full bg-[#F1F9FC]">
  {{-- Padding disesuaikan agar banner terlihat penuh namun tetap rapi --}}
  <div class="w-full px-4 md:px-8 lg:px-[80px] py-6 md:py-8">
    <div id="hero" class="relative group">

      {{-- Track Slider: Menggunakan w-full untuk container dan child --}}
      <div id="hero-track" class="flex items-center overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar select-none rounded-[20px] shadow-lg border border-white/50" style="scrollbar-width:none;-ms-overflow-style:none;">

        {{-- Clones Kiri --}}
        @if($banners->isNotEmpty())
          @for($i = $cloneCount; $i > 0; $i--)
            @php
                $idx = ($realCount - $i) % $realCount;
                $b = $banners->values()[$idx];
                $bannerSrc = $b->image ? Storage::url($b->image) : asset('images/beranda/slide1.jpg');
            @endphp
            {{-- Slide Item: w-full (100%) --}}
            <div class="hero-slide clone shrink-0 snap-center w-full relative" data-real="{{ $idx }}">
              {{-- Tinggi Banner Responsif: HP(220px) -> Tablet(400px) -> Desktop(550px) --}}
              <div class="w-full h-[220px] sm:h-[350px] md:h-[450px] lg:h-[550px] overflow-hidden bg-gray-200">
                <img src="{{ $bannerSrc }}" alt="{{ $b->title ?? 'Banner' }}" class="w-full h-full object-cover select-none" draggable="false">
              </div>
            </div>
          @endfor

          {{-- Slides Asli --}}
          @foreach($banners as $i => $b)
            @php
              $bannerSrc = $b->image ? Storage::url($b->image) : asset('images/beranda/slide1.jpg');
            @endphp
            <div class="hero-slide shrink-0 snap-center w-full relative" data-real="{{ $i }}">
              <div class="w-full h-[220px] sm:h-[350px] md:h-[450px] lg:h-[550px] overflow-hidden bg-gray-200">
                <img src="{{ $bannerSrc }}" alt="{{ $b->title ?? 'Banner' }}" class="w-full h-full object-cover select-none" draggable="false">
              </div>
            </div>
          @endforeach

          {{-- Clones Kanan --}}
          @for($i = 0; $i < $cloneCount; $i++)
            @php
              $idx = $i % $realCount;
              $b = $banners->values()[$idx];
              $bannerSrc = $b->image ? Storage::url($b->image) : asset('images/beranda/slide1.jpg');
            @endphp
            <div class="hero-slide clone shrink-0 snap-center w-full relative" data-real="{{ $idx }}">
              <div class="w-full h-[220px] sm:h-[350px] md:h-[450px] lg:h-[550px] overflow-hidden bg-gray-200">
                <img src="{{ $bannerSrc }}" alt="{{ $b->title ?? 'Banner' }}" class="w-full h-full object-cover select-none" draggable="false">
              </div>
            </div>
          @endfor
        @else
          {{-- Fallback: 3 static slides --}}
          @for($i=1;$i<=3;$i++)
            <div class="hero-slide shrink-0 snap-center w-full relative" data-real="{{ $i-1 }}">
              <div class="w-full h-[220px] sm:h-[350px] md:h-[450px] lg:h-[550px] overflow-hidden bg-gray-200">
                <img src="{{ asset('images/beranda/slide'.$i.'.jpg') }}" alt="Slide {{ $i }}" class="w-full h-full object-cover select-none">
              </div>
            </div>
          @endfor
        @endif

      </div>

      {{-- Controls + dots --}}
      <div class="absolute bottom-4 md:bottom-6 left-0 right-0 flex items-center justify-center gap-4 z-10 pointer-events-none">
        <div class="pointer-events-auto flex items-center gap-4">
            <button id="hero-prev" class="w-9 h-9 md:w-10 md:h-10 grid place-items-center rounded-full bg-white/40 backdrop-blur-md text-white hover:bg-white/70 hover:text-[#1524AF] transition-all border border-white/30 shadow-md" aria-label="Sebelumnya">
            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z" /></svg>
            </button>

            <div id="hero-dots" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/30 backdrop-blur-md border border-white/10">
            @php $dotsToShow = $realCount; @endphp
            @for($i=0;$i<$dotsToShow;$i++)
                <button class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-[#1524AF] w-6 md:w-8' : 'bg-white/80 hover:bg-white' }}" aria-label="Slide {{ $i+1 }}"></button>
            @endfor
            </div>

            <button id="hero-next" class="w-9 h-9 md:w-10 md:h-10 grid place-items-center rounded-full bg-white/40 backdrop-blur-md text-white hover:bg-white/70 hover:text-[#1524AF] transition-all border border-white/30 shadow-md" aria-label="Berikutnya">
            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="m8.59 16.59 1.41 1.41 6-6-6-6L8.59 6.41 13.17 11z" /></svg>
            </button>
        </div>
      </div>

    </div>
  </div>
</header>

<style>
  /* Menghilangkan efek scale/kecil agar banner terlihat solid dan penuh */
  .hero-slide { opacity: 1; transition: opacity 0.3s ease; }
</style>

<script>
(function () {
  const track = document.getElementById('hero-track');
  if (!track) return;
  const slides = Array.from(track.querySelectorAll('.hero-slide'));
  const dotsEl = document.getElementById('hero-dots');
  const dots = dotsEl ? Array.from(dotsEl.children) : [];
  const prev = document.getElementById('hero-prev');
  const next = document.getElementById('hero-next');

  if (slides.length === 0) return;

  const realIndices = slides.map(s => parseInt(s.dataset.real, 10));
  const uniqueReals = Array.from(new Set(realIndices));
  const realCount = uniqueReals.length;

  let FIRST_REAL = 0;
  function findFirstReal() {
    for (let start = 0; start < slides.length; start++) {
      let ok = true;
      for (let k = 1; k < realCount; k++) {
        const prev = parseInt(slides[(start + k - 1) % slides.length].dataset.real, 10);
        const cur = parseInt(slides[(start + k) % slides.length].dataset.real, 10);
        if (cur !== (prev + 1) % realCount && !(realCount === 1)) { ok = false; break; }
      }
      if (ok) return start;
    }
    return 0;
  }
  FIRST_REAL = findFirstReal();
  const LAST_REAL = FIRST_REAL + realCount - 1;

  if (dots.length !== realCount && dotsEl) {
    dotsEl.innerHTML = '';
    for (let i = 0; i < realCount; i++) {
      const btn = document.createElement('button');
      // Update styling dots untuk transisi lebar
      btn.className = 'w-2 h-2 md:w-2.5 md:h-2.5 rounded-full transition-all duration-300 ' + (i === 0 ? 'bg-[#1524AF] w-6 md:w-8' : 'bg-white/80 hover:bg-white');
      btn.setAttribute('aria-label', 'Slide ' + (i+1));
      dotsEl.appendChild(btn);
    }
  }

  const updatedDots = dotsEl ? Array.from(dotsEl.children) : [];
  const ANIM = 500, BUF = 40;
  let currentIndex = FIRST_REAL;
  let isTransitioning = false;

  const realOf = (idx) => parseInt(slides[idx].dataset.real, 10);

  const setDots = (r) => {
    if (!updatedDots) return;
    updatedDots.forEach((d, i) => {
      d.className = 'w-2 h-2 md:w-2.5 md:h-2.5 rounded-full transition-all duration-300 ' + (i === r ? 'bg-[#1524AF] w-6 md:w-8' : 'bg-white/80 hover:bg-white');
    });
  };

  const centerOffset = (idx) => {
    return slides[idx].offsetLeft;
  };

  const scrollToIndex = (idx, smooth = true) =>
    track.scrollTo({ left: centerOffset(idx), behavior: smooth ? 'smooth' : 'auto' });

  function smoothScrollToIndex(idx, cb) {
    const prevSnap = track.style.scrollSnapType;
    track.style.scrollSnapType = 'none';
    const target = centerOffset(idx);
    track.scrollTo({ left: target, behavior: 'smooth' });

    const t0 = performance.now(), MAX = ANIM + 300, EPS = 2;
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

  function rafSwap(fn) { requestAnimationFrame(() => requestAnimationFrame(fn)); }

  function seamlessSwapByDelta(fromCloneIdx, toRealIdx) {
    const prevBehavior = track.style.scrollBehavior;
    const prevSnap = track.style.scrollSnapType;
    track.style.scrollBehavior = 'auto';
    track.style.scrollSnapType = 'none';
    const delta = centerOffset(toRealIdx) - centerOffset(fromCloneIdx);
    track.scrollLeft += delta;

    // Force layout update fix untuk browser tertentu
    track.scrollTop;

    track.style.scrollBehavior = prevBehavior || '';
    track.style.scrollSnapType = prevSnap || '';
    currentIndex = toRealIdx;
    setDots(realOf(currentIndex));
  }

  function goNext() {
    if (isTransitioning) return;
    isTransitioning = true;
    if (currentIndex === LAST_REAL) {
      setDots(realOf(FIRST_REAL)); // update dots duluan biar responsif
      const cloneIdx = LAST_REAL + 1;
      smoothScrollToIndex(cloneIdx, () => {
        rafSwap(() => {
          seamlessSwapByDelta(cloneIdx, FIRST_REAL);
          isTransitioning = false;
        });
      });
    } else {
      const target = currentIndex + 1;
      setDots(realOf(target));
      smoothScrollToIndex(target, () => {
        currentIndex = target;
        isTransitioning = false;
      });
    }
  }

  function goPrev() {
    if (isTransitioning) return;
    isTransitioning = true;
    if (currentIndex === FIRST_REAL) {
      setDots(realOf(LAST_REAL));
      const cloneIdx = FIRST_REAL - 1;
      smoothScrollToIndex(cloneIdx, () => {
        rafSwap(() => {
          seamlessSwapByDelta(cloneIdx, LAST_REAL);
          isTransitioning = false;
        });
      });
    } else {
      const target = currentIndex - 1;
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

  updatedDots.forEach((d, targetReal) => {
    d.addEventListener('click', () => {
      if (isTransitioning) return;
      const curReal = realOf(currentIndex);
      if (targetReal === curReal) return;
      const r = (targetReal - curReal + realCount) % realCount;
      const l = (curReal - targetReal + realCount) % realCount;
      if (r <= l) step(1, r);
      else step(-1, l);
    });
  });

  // Autoplay
  let autoplay = setInterval(goNext, 5000);
  track.addEventListener('mouseenter', () => clearInterval(autoplay));
  track.addEventListener('mouseleave', () => autoplay = setInterval(goNext, 5000));

  // Init
  scrollToIndex(FIRST_REAL, false);
  setDots(realOf(FIRST_REAL));

  if (next) next.addEventListener('click', goNext);
  if (prev) prev.addEventListener('click', goPrev);
})();
</script>

{{-- SECTION: Cerita Kami --}}
<section class="relative bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16 items-start md:items-center">

      {{-- Kolom Kiri: Foto --}}
      <div class="w-full flex justify-center md:justify-start md:pl-2 lg:pl-4">
        <div class="rounded-2xl overflow-hidden shadow-xl ring-2 ring-[#1524AF] max-w-[420px] md:max-w-[480px] lg:max-w-[520px]">
          @php
            $ceritaFilename = 'profil/cerita-kami.svg';
            $ceritaSrc = Storage::disk('public')->exists($ceritaFilename)
                         ? Storage::url($ceritaFilename)
                         : asset('images/cerita-kami.svg');
          @endphp
          <img src="{{ $ceritaSrc }}" alt="Kegiatan UPT PTKK" class="w-full h-auto object-cover">
        </div>
      </div>

      {{-- Kolom Kanan: Teks --}}
      <div class="flex flex-col">
        {{-- Badge Cerita Kami --}}
        <div class="inline-flex self-start items-center justify-center mb-[20px] px-2 py-2 bg-[#F3E8E9] rounded-md">
          <span class="font-['Volkhov'] font-bold text-[#861D23] text-[22px] md:text-[24px] leading-none">Cerita Kami</span>
        </div>

        {{-- Heading --}}
        <h2 class="mb-[20px] md:mb-[24px] font-['Volkhov'] font-bold text-[24px] md:text-[30px] lg:text-[34px] leading-tight text-[#1524AF] heading-stroke max-w-[32ch] md:max-w-[28ch] lg:max-w-[32ch]">
          UPT Pengembangan Teknis <br class="hidden lg:block" /> Dan Keterampilan Kejuruan
        </h2>

        {{-- Paragraf DINAMIS (Dari Model ProfilUPT) --}}
        <p class="mb-[24px] md:mb-[28px] font-['Montserrat'] font-medium text-[#081526] leading-7 text-[14px] md:text-[15px] lg:text-[16px] text-justify">
          {!! nl2br(e($teksCerita)) !!}
        </p>

        {{-- Button --}}
        <a href="#" class="inline-flex items-center justify-center gap-2 w-max px-5 py-2 sm:px-6 sm:py-2.5 md:px-8 md:py-3 rounded-xl bg-[#1524AF] text-white font-['Montserrat'] font-medium text-[14px] sm:text-[16px] md:text-[18px] shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
          <span class="leading-none">Cari tahu lebih</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M19 12l-4-4m0 8l4-4" /></svg>
        </a>
      </div>
    </div>
  </div>
</section>
{{-- /SECTION: Cerita Kami --}}

{{-- SECTION: Jatim Bangkit --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <style>
    @keyframes jatim-scroll-x {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }
  </style>

  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="relative">

      <div class="relative bg-[#DBE7F7] rounded-full overflow-hidden h-[54px] md:h-[58px] lg:h-[62px] flex items-center">

        <div class="relative w-full overflow-hidden">

          <div class="jatim-marquee flex w-[200%] items-center animate-[jatim-scroll-x_linear_infinite] [animation-duration:24s]">

            {{-- Loop Pertama --}}
            <div class="flex w-1/2 items-center justify-between px-6 md:px-10 lg:px-16 gap-4 md:gap-6 lg:gap-8">
              @foreach(['cetar', 'dindik', 'jatim', 'berakhlak', 'optimis'] as $iconName)
                @php
                    $path = 'icons/' . $iconName . '.svg';
                    $src = Illuminate\Support\Facades\Storage::disk('public')->exists($path)
                            ? Illuminate\Support\Facades\Storage::url($path)
                            : asset('images/icons/' . $iconName . '.svg');
                @endphp
                <img src="{{ $src }}" class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="{{ ucfirst($iconName) }}">
              @endforeach
            </div>

            {{-- Loop Kedua (Duplikat) --}}
            <div class="flex w-1/2 items-center justify-between px-6 md:px-10 lg:px-16 gap-4 md:gap-6 lg:gap-8" aria-hidden="true">
              @foreach(['cetar', 'dindik', 'jatim', 'berakhlak', 'optimis'] as $iconName)
                @php
                    $path = 'icons/' . $iconName . '.svg';
                    $src = Illuminate\Support\Facades\Storage::disk('public')->exists($path)
                            ? Illuminate\Support\Facades\Storage::url($path)
                            : asset('images/icons/' . $iconName . '.svg');
                @endphp
                <img src="{{ $src }}" class="h-[26px] md:h-[32px] lg:h-[42px] flex-shrink-0" alt="">
              @endforeach
            </div>

          </div>

          <div class="pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_right,transparent,black_15%,black_85%,transparent)]"></div>

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
            $imgUrl = $b->image ? Storage::url($b->image) : asset('images/berita/placeholder.jpg');
            $excerpt = Str::limit(strip_tags($b->content ?? ''), 120);
            $pubDate = optional($b->published_at ?? $b->created_at)->format('d F Y');
          @endphp
          <article class="group bg-white border border-[#B6BBE6] rounded-2xl shadow-sm p-4 transition-all duration-300 hover:border-[#1524AF] hover:shadow-md">
            <div class="w-full h-[160px] rounded-lg mb-4 overflow-hidden">
               <img src="{{ $imgUrl }}" alt="{{ $b->title }}" class="w-full h-full object-cover rounded-lg shadow-md" loading="lazy">
            </div>
            <div class="flex items-center gap-2 text-[#727272] text-xs mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              <span>{{ $pubDate }}</span>
            </div>
            <h3 class="text-[#081526] group-hover:text-[#1524AF] transition-colors duration-300 font-semibold mb-2">{{ $b->title }}</h3>
            <p class="text-sm text-[#081526] mb-3 leading-relaxed">{!! e($excerpt) !!}</p>
            <a href="{{ route('berita.show', $b->slug ?? $b->id) ?? '#' }}" class="text-[#595959] group-hover:text-[#1524AF] text-sm font-medium inline-flex items-center gap-1 transition-colors duration-300">Baca Selengkapnya →</a>
          </article>
        @endforeach
      @endif
    </div>
  </div>
</section>
{{-- /SECTION: Berita Terbaru --}}

 {{-- SECTION: Sorotan Pelatihan --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <style>
    @keyframes sorotan-scroll-x {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); } /* karena konten digandakan 2x */
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
          // MTU di images/profil
          'files' => [
            'profil/MTU1.svg',
            'profil/MTU2.svg',
            'profil/MTU3.svg',
            'profil/MTU4.svg',
            'profil/MTU5.svg',
            'profil/MTU6.svg',
          ],
        ],
        [
          'key'   => 'reguler',
          'label' => 'Pelatihan Reguler',
          'desc'  => 'Proses peningkatan kompetensi di UPT. PTKK dipandu oleh para asesor kompetensi profesional yang tersertifikasi.',
          // reguler di images/sorotan/reguler
          'files' => [
            'sorotan/reguler/reg-1.jpg',
            'sorotan/reguler/reg-2.jpg',
            'sorotan/reguler/reg-3.jpg',
            'sorotan/reguler/reg-4.jpg',
            'sorotan/reguler/reg-5.jpg',
            'sorotan/reguler/reg-6.jpg',
          ],
        ],
        [
          'key'   => 'akselerasi',
          'label' => 'Pelatihan Akselerasi',
          'desc'  => 'UPT. PTKK memiliki 6 kompetensi yang tersertifikasi oleh Kemendikdasmen sebagai tempat uji kompetensi yang memiliki fasilitas mumpuni.',
          // akselerasi di images/sorotan/akselerasi
          'files' => [
            'sorotan/akselerasi/acc-1.jpg',
            'sorotan/akselerasi/acc-2.jpg',
            'sorotan/akselerasi/acc-3.jpg',
            'sorotan/akselerasi/acc-4.jpg',
            'sorotan/akselerasi/acc-5.jpg',
            'sorotan/akselerasi/acc-6.jpg',
          ],
        ],
      ];
    @endphp

    {{-- NAMA PELATIHAN + DESKRIPSI --}}
    <div id="sorotan-top"
         class="w-full mb-6 md:mb-8 flex flex-col md:flex-row md:items-center md:justify-start md:gap-6 text-left">
      <div class="shrink-0">
        <button type="button"
                class="sorotan-label bg-[#DBE7F7] text-[#1524AF]
                       font-[Volkhov] font-bold text-[18px] md:text-[20px] lg:text-[22px]
                       rounded-md px-5 py-2.5 leading-tight whitespace-nowrap">
          {{ $sorotan[0]['label'] }}
        </button>
      </div>

      <p id="sorotan-desc"
         class="mt-2 md:mt-0 text-sm md:text-base lg:text-[17px]
                font-[Montserrat] font-medium text-[#000000] leading-relaxed md:max-w-[75%]">
        {{ $sorotan[0]['desc'] }}
      </p>
    </div>

  {{-- SLIDER FOTO: auto jalan sendiri, 6 foto rapi --}}
<div class="w-full mb-8 md:mb-10 lg:mb-12">
  @foreach($sorotan as $i => $cat)
    @php $files = $cat['files']; @endphp

    <div class="sorotan-pane {{ $i===0 ? '' : 'hidden' }}" data-pane="{{ $cat['key'] }}">
      <div class="relative">
        <div class="overflow-hidden">
          {{-- Track: isi digandakan 2x untuk loop mulus --}}
          <div
            class="sorotan-track flex items-center gap-4 md:gap-5 lg:gap-6 [will-change:transform]"
            data-key="{{ $cat['key'] }}"
          >
            @for($loopIdx = 0; $loopIdx < 2; $loopIdx++)
              @foreach($files as $fname)
                <div
                  class="relative h-[130px] md:h-[150px] lg:h-[170px]
                         w-[220px] md:w-[260px] lg:w-[280px]
                         rounded-2xl overflow-hidden shrink-0">
                  <img src="{{ asset('images/'.$fname) }}"
                       alt="{{ $cat['label'] }}" loading="lazy"
                       class="w-full h-full object-cover">
                </div>
              @endforeach
            @endfor
          </div>

          {{-- Fade kiri–kanan --}}
          <div class="pointer-events-none absolute inset-0
                      [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]"></div>
        </div>
      </div>
    </div>
  @endforeach
</div>

    {{-- KONTROL KATEGORI (panah hanya pindah kategori) --}}
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

  {{-- SCRIPT: hanya untuk ganti kategori + dots --}}
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


  // Auto-scroll untuk setiap sorotan-track (infinite loop halus)
  (function(){
    const tracks = document.querySelectorAll('.sorotan-track');
    const SPEED = 0.8; // px per frame (ubah kalau mau lebih cepat / pelan)

    tracks.forEach((track) => {
      let offset = 0;

      function animate() {
        const halfWidth = track.scrollWidth / 2; // lebar 6 foto pertama

        // Geser ke kiri
        offset -= SPEED;

        // Kalau sudah lewat satu deret (6 foto), reset supaya loop mulus
        if (Math.abs(offset) >= halfWidth) {
          offset += halfWidth;
        }

        track.style.transform = `translateX(${offset}px)`;
        requestAnimationFrame(animate);
      }

      // Mulai animasi
      requestAnimationFrame(animate);
    });
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
  <a href="#"
     class="inline-flex items-center gap-2 bg-[#1524AF] hover:bg-[#0E1F73]
            text-white text-sm font-medium px-5 py-2.5 rounded-md transition
            self-center md:self-auto">
    Lihat Semua Kompetensi
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
  </a>
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
<div class="lg:col-span-8 mt-6 lg:mt-0">
  <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-4">
    <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-3 sm:p-4 text-center">
      <div class="text-[18px] sm:text-[22px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">
        63.48
      </div>
      <div class="text-[10px] sm:text-xs font-[Montserrat] font-medium text-[#081526]">
        Rata-Rata Pre-Test
      </div>
    </div>

    <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-3 sm:p-4 text-center">
      <div class="text-[18px] sm:text-[22px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">
        90
      </div>
      <div class="text-[10px] sm:text-xs font-[Montserrat] font-medium text-[#081526]">
        Praktek
      </div>
    </div>

    <div class="rounded-xl bg-[#DBE7F7] shadow-sm border border-slate-200 p-3 sm:p-4 text-center">
      <div class="text-[18px] sm:text-[22px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">
        80.76
      </div>
      <div class="text-[10px] sm:text-xs font-[Montserrat] font-medium text-[#081526]">
        Rata-Rata Post-Test
      </div>
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

         <a href="#panduan"
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
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> bb957f848c51108415c7a5beee75061bfb673daf
