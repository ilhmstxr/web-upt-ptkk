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
    /* Style kustom untuk efek stroke merah */
    .upt-stroke {
      text-shadow:
        -1px -1px 0 #861D23,
         1px -1px 0 #861D23,
        -1px  1px 0 #861D23,
         1px  1px 0 #861D23;
    }

    /* Custom Style untuk efek stroke kuning */
    .heading-stroke {
      color: #1524AF;
      -webkit-text-fill-color: #1524AF;
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

    /* hide scrollbar helper (webkit) */
    .no-scrollbar::-webkit-scrollbar { display: none; }
  </style>

  @stack('styles')
</head>

<body class="bg-[#F1F9FC] antialiased">

@php
use Illuminate\Support\Facades\Storage;

/*
 | Helper: uploaded_or_asset($path, $fallbackPrefix = 'images')
 */
if (! function_exists('uploaded_or_asset')) {
    function uploaded_or_asset($path, $fallbackPrefix = 'images') {
        if (! $path) {
            return asset("{$fallbackPrefix}/placeholder.png");
        }
        if (preg_match('/^https?:\\/\\//', $path)) {
            return $path;
        }
        $storagePath = "public/{$path}";
        if (Storage::exists($storagePath)) {
            return Storage::url($path);
        }
        if (strpos($path, '/') !== false) {
            return asset("images/{$path}");
        }
        return asset("{$fallbackPrefix}/{$path}");
    }
}

/* fallback variabel supaya view aman jika controller lupa kirim */
$banners = $banners ?? collect();
$beritas = $beritas ?? collect();
$profil = $profil ?? null;
@endphp

{{-- TOPBAR --}}
@include('components.layouts.app.topbar')

{{-- NAVBAR --}}
@include('components.layouts.app.navbarlanding')

{{-- HERO --}}
<header class="w-full bg-[#F1F9FC]">
  <div class="w-full px-6 md:px-12 lg:px-[80px] py-4 md:py-6">
    <div id="hero" class="relative">

      {{-- Ganti blok hero-track with dynamic banners (this is the block you asked to insert) --}}
      <!-- Ganti blok hero-track dengan ini -->
      <div id="hero-track" class="flex items-center gap-4 md:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar select-none py-8">
        <div aria-hidden="true" class="shrink-0 snap-none pointer-events-none w-[15%] md:w-[12%] lg:w-[10%]"></div>

        @if($banners->isNotEmpty())
          @foreach($banners as $b)
            <div class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300">
              <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
                <img src="{{ $b->image ? uploaded_or_asset('banners/'.$b->image) : uploaded_or_asset('beranda/slide1.jpg') }}"
                     alt="{{ $b->title ?? 'Banner' }}"
                     loading="lazy"
                     class="w-full h-full object-cover select-none" draggable="false">
              </div>
            </div>
          @endforeach
        @else
          {{-- fallback 3 static slides kalau tidak ada banner --}}
          @for($i=1;$i<=3;$i++)
            <div class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300">
              <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
                <img src="{{ uploaded_or_asset('beranda/slide'.$i.'.jpg') }}" alt="Slide {{ $i }}" loading="lazy" class="w-full h-full object-cover select-none">
              </div>
            </div>
          @endfor
        @endif

        <div aria-hidden="true" class="shrink-0 snap-none pointer-events-none w-[15%] md:w-[12%] lg:w-[10%]"></div>
      </div>
      <!-- end hero-track -->

      {{-- Controls + dots --}}
      <div class="mt-4 flex items-center justify-center gap-4">
        <button id="hero-prev" class="w-9 h-9 grid place-items-center rounded-full border border-gray-300 text-gray-600 hover:bg-white/60 transition-colors" aria-label="Sebelumnya">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z" /></svg>
        </button>

        <div id="hero-dots" class="flex items-center gap-3">
          <button class="w-2.5 h-2.5 rounded-full bg-[#1524AF] transition-colors" aria-label="Slide 1"></button>
          <button class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-colors" aria-label="Slide 2"></button>
          <button class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-colors" aria-label="Slide 3"></button>
        </div>

        <button id="hero-next" class="w-9 h-9 grid place-items-center rounded-full border border-gray-300 text-gray-600 hover:bg-white/60 transition-colors" aria-label="Berikutnya">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="m8.59 16.59 1.41 1.41 6-6-6-6L8.59 6.41 13.17 11z" /></svg>
        </button>
      </div>

    </div>
  </div>
</header>

<style>
  .hero-slide { transform: scale(0.85); opacity: 0.5; transition: transform 0.3s ease, opacity 0.3s ease; }
  .hero-slide.active { transform: scale(1); opacity: 1; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
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

  const FIRST_REAL = 0; // simpler: when data dynamic, assume first real at index 0 (we don't use clones here)
  let currentIndex = FIRST_REAL;
  let isTransitioning = false;

  const setDots = (r) => {
    if (!dots) return;
    dots.forEach((d, i) => d.className = 'w-2.5 h-2.5 rounded-full transition-colors ' + (i === r ? 'bg-[#1524AF]' : 'bg-gray-300'));
  };

  const setActive = (idx) => slides.forEach((s, i) => s.classList.toggle('active', i === idx));

  const centerOffset = (idx) => slides[idx].offsetLeft - (track.clientWidth - slides[idx].clientWidth) / 2;

  const scrollToIndex = (idx, smooth = true) => track.scrollTo({ left: centerOffset(idx), behavior: smooth ? 'smooth' : 'auto' });

  const goTo = (idx) => {
    if (isTransitioning) return;
    isTransitioning = true;
    const target = Math.max(0, Math.min(idx, slides.length - 1));
    setActive(target);
    setDots(target % (dots.length || 1));
    scrollToIndex(target, true);
    setTimeout(()=> { currentIndex = target; isTransitioning = false; }, 340);
  };

  prev && prev.addEventListener('click', () => goTo(currentIndex - 1));
  next && next.addEventListener('click', () => goTo(currentIndex + 1));

  dots.forEach((d, i) => d.addEventListener('click', () => goTo(i)));

  // init
  setActive(currentIndex);
  setDots(0);
  // center first slide after paint
  requestAnimationFrame(()=> requestAnimationFrame(()=> scrollToIndex(currentIndex, false)));
})();
</script>

{{-- -----------------------
   Sisa halaman: Cerita Kami, Jatim Bangkit, Berita, Sorotan, Kompetensi, Statistik, Panduan, Footer
   Saya sertakan semua section lengkap (tetap memakai helper uploaded_or_asset untuk gambar).
   ----------------------- --}}

{{-- SECTION: Cerita Kami --}}
<section class="relative bg-[#F1F9FC] py-6 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16 items-start md:items-center">
      <div class="w-full flex justify-center md:justify-start md:pl-2 lg:pl-4">
        <div class="rounded-2xl overflow-hidden shadow-xl ring-2 ring-[#1524AF] max-w-[420px] md:max-w-[480px] lg:max-w-[520px]">
          <img src="{{ uploaded_or_asset('profil/cerita-kami.svg') }}" alt="Kegiatan UPT PTKK" class="w-full h-auto object-cover">
        </div>
      </div>

      <div class="flex flex-col">
        <div class="inline-flex self-start items-center justify-center mb-[20px] px-2 py-2 bg-[#F3E8E9] rounded-md">
          <span class="font-['Volkhov'] font-bold text-[#861D23] text-[22px] md:text-[24px] leading-none">Cerita Kami</span>
        </div>

        <h2 class="mb-[20px] md:mb-[24px] font-['Volkhov'] font-bold text-[24px] md:text-[30px] lg:text-[34px] leading-tight text-[#1524AF] heading-stroke max-w-[32ch] md:max-w-[28ch] lg:max-w-[32ch]">
          UPT Pengembangan Teknis<br class="hidden lg:block" />Dan Keterampilan Kejuruan
        </h2>

        <p class="mb-[24px] md:mb-[28px] font-['Montserrat'] font-medium text-[#081526] leading-7 text-[14px] md:text-[15px] lg:text-[16px] text-justify">
          Adalah salah satu Unit Pelaksana Teknis dari Dinas Pendidikan Provinsi Jawa Timur yang mempunyai tugas
          dan fungsi memberikan fasilitas melalui pelatihan berbasis kompetensi...
        </p>

        <a href="#" class="inline-flex items-center justify-center gap-2 w-max px-5 py-2 rounded-xl bg-[#1524AF] text-white font-['Montserrat'] font-medium text-[14px] shadow-md hover:bg-[#0F1D8F] active:scale-[.99] transition-all duration-200 ease-out">
          <span class="leading-none">Cari tahu lebih</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
          </svg>
        </a>
      </div>

    </div>
  </div>
</section>

{{-- SECTION: Jatim Bangkit --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="relative">
      <div class="relative bg-[#DBE7F7] rounded-full overflow-hidden h-[54px] md:h-[58px] lg:h-[62px] flex items-center">
        <div class="relative w-full overflow-hidden">
          <div class="jatim-marquee flex w-[200%] items-center animate-[jatim-scroll-x_linear_infinite] [animation-duration:24s]">
            <div class="flex w-1/2 items-center justify-between px-6 md:px-10 lg:px-16 gap-4 md:gap-6 lg:gap-8">
              <img src="{{ uploaded_or_asset('icons/cetar.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="Cetar">
              <img src="{{ uploaded_or_asset('icons/dindik.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="Dindik">
              <img src="{{ uploaded_or_asset('icons/jatim.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="Jatim">
              <img src="{{ uploaded_or_asset('icons/berakhlak.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="Berakhlak">
              <img src="{{ uploaded_or_asset('icons/optimis.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="Optimis">
            </div>

            <div class="flex w-1/2 items-center justify-between px-6 md:px-10 lg:px-16 gap-4 md:gap-6 lg:gap-8" aria-hidden="true">
              <img src="{{ uploaded_or_asset('icons/cetar.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="">
              <img src="{{ uploaded_or_asset('icons/dindik.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="">
              <img src="{{ uploaded_or_asset('icons/jatim.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="">
              <img src="{{ uploaded_or_asset('icons/berakhlak.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="">
              <img src="{{ uploaded_or_asset('icons/optimis.svg') }}" class="h-[26px] md:h-[32px] lg:h-[42px]" alt="">
            </div>
          </div>

          <div class="pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_right,transparent,black_15%,black_85%,transparent)]"></div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- SECTION: Berita Terbaru --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="grid gap-y-2 mb-10">
      <span class="inline-flex items-center justify-center bg-[#F3E8E9] text-[#861D23] font-[Volkhov] font-bold text-[15px] rounded-md px-3 py-1 shadow-sm w-fit">
        Berita Terbaru
      </span>

      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <h2 class="heading-stroke font-[Volkhov] font-bold text-[18px] sm:text-[20px] md:text-[24px] lg:text-[28px] leading-snug">
          Jangan lewatkan kabar terbaru dari UPT PTKK
        </h2>

        <a href="#" class="inline-flex items-center gap-2 bg-[#1524AF] hover:bg-[#0E1E8B] text-white font-['Montserrat'] font-medium text-[12px] px-[12px] py-[6px] rounded-xl shadow-md">
          <span class="leading-none">Cari tahu lebih</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M19 12l-4-4m0 8l4-4" />
          </svg>
        </a>
      </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse ($beritas->take(6) as $berita)
        <article class="group bg-white border border-[#B6BBE6] rounded-2xl shadow-sm p-4">
          <div class="w-full h-[160px] bg-[#E7ECF3] rounded-lg mb-4 overflow-hidden">
            <img src="{{ uploaded_or_asset($berita->thumbnail ?? 'beranda/slide1.jpg') }}" alt="{{ $berita->title }}" class="w-full h-full object-cover">
          </div>

          <div class="flex items-center gap-2 text-[#727272] text-xs mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ optional($berita->published_at)->format('d F Y') ?? '—' }}</span>
          </div>

          <h3 class="text-[#081526] group-hover:text-[#1524AF] transition-colors duration-300 font-semibold mb-2">
            {{ Str::limit($berita->title ?? 'Tidak ada judul', 80) }}
          </h3>

          <p class="text-sm text-[#081526] mb-3 leading-relaxed">
            {{ Str::limit(strip_tags($berita->ringkasan ?? $berita->konten ?? 'Ringkasan belum tersedia'), 120) }}
          </p>

          <a href="{{ route('berita.show', $berita->id ?? '#') }}" class="text-[#595959] group-hover:text-[#1524AF] text-sm font-medium inline-flex items-center gap-1 transition-colors duration-300">
            Baca Selengkapnya →
          </a>
        </article>
      @empty
        @for ($i=0;$i<3;$i++)
          <article class="group bg-white border border-[#B6BBE6] rounded-2xl shadow-sm p-4">
            <div class="w-full h-[160px] bg-[#E7ECF3] rounded-lg mb-4"></div>
            <div class="flex items-center gap-2 text-[#727272] text-xs mb-2"><span>22 Oktober 2024</span></div>
            <h3 class="text-[#081526] font-semibold mb-2">Judul Berita...</h3>
            <p class="text-sm text-[#081526] mb-3 leading-relaxed">Lorem ipsum dolor sit amet...</p>
            <a href="#" class="text-[#595959] text-sm font-medium inline-flex items-center gap-1">Baca Selengkapnya →</a>
          </article>
        @endfor
      @endforelse
    </div>

  </div>
</section>

{{-- SECTION: Sorotan Pelatihan --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] text-center flex flex-col items-center">
    <div class="inline-block bg-[#FDECEC] text-[#861D23] text-[18px] md:text-[20px] lg:text-[22px] font-[Volkhov] font-bold leading-none px-4 py-[6px] rounded-md mb-4">Sorotan Pelatihan</div>

    <h2 class="heading-stroke text-[20px] md:text-[24px] lg:text-[26px] font-[Volkhov] font-bold text-[#0E2A7B] leading-snug relative inline-block mt-2 mb-10 md:mb-12 lg:mb-14">
      <span class="relative z-10">Mengasah Potensi dan Mencetak Tenaga Kerja yang Kompeten</span>
    </h2>

    @php
      $sorotan = $sorotan ?? [
        ['key'=>'mtu','label'=>'Mobil Training Unit','desc'=>'Mobil Keliling UPT. PTKK ...','files'=>['profil/MTU1.svg','profil/MTU2.svg','profil/MTU3.svg','profil/MTU4.svg','profil/MTU5.svg','profil/MTU6.svg']],
        ['key'=>'reguler','label'=>'Pelatihan Reguler','desc'=>'Proses peningkatan kompetensi ...','files'=>['sorotan/reguler/reg-1.jpg','sorotan/reguler/reg-2.jpg','sorotan/reguler/reg-3.jpg','sorotan/reguler/reg-4.jpg','sorotan/reguler/reg-5.jpg','sorotan/reguler/reg-6.jpg']],
        ['key'=>'akselerasi','label'=>'Pelatihan Akselerasi','desc'=>'UPT. PTKK memiliki 6 kompetensi ...','files'=>['sorotan/akselerasi/acc-1.jpg','sorotan/akselerasi/acc-2.jpg','sorotan/akselerasi/acc-3.jpg','sorotan/akselerasi/acc-4.jpg','sorotan/akselerasi/acc-5.jpg','sorotan/akselerasi/acc-6.jpg']],
      ];
    @endphp

    <div id="sorotan-top" class="w-full mb-6 md:mb-8 flex flex-col md:flex-row md:items-center md:justify-start md:gap-6 text-left">
      <div class="shrink-0">
        <button type="button" class="sorotan-label bg-[#DBE7F7] text-[#1524AF] font-[Volkhov] font-bold text-[18px] md:text-[20px] rounded-md px-5 py-2.5 leading-tight whitespace-nowrap">
          {{ $sorotan[0]['label'] }}
        </button>
      </div>

      <p id="sorotan-desc" class="mt-2 md:mt-0 text-sm md:text-base lg:text-[17px] font-[Montserrat] font-medium text-[#000000] leading-relaxed md:max-w-[75%]">
        {{ $sorotan[0]['desc'] }}
      </p>
    </div>

    <div class="w-full mb-8 md:mb-10 lg:mb-12">
      @foreach($sorotan as $i => $cat)
        @php $files = $cat['files']; @endphp

        <div class="sorotan-pane {{ $i===0 ? '' : 'hidden' }}" data-pane="{{ $cat['key'] }}">
          <div class="relative">
            <div class="overflow-hidden">
              <div class="sorotan-track flex items-center gap-4 md:gap-5 lg:gap-6 [will-change:transform]" data-key="{{ $cat['key'] }}">
                @for($loopIdx = 0; $loopIdx < 2; $loopIdx++)
                  @foreach($files as $fname)
                    <div class="relative h-[130px] md:h-[150px] lg:h-[170px] w-[220px] md:w-[260px] lg:w-[280px] rounded-2xl overflow-hidden shrink-0">
                      <img src="{{ uploaded_or_asset($fname) }}" alt="{{ $cat['label'] }}" loading="lazy" class="w-full h-full object-cover">
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

    <div class="mt-2 flex items-center justify-center gap-6">
      <button id="tabPrev" class="w-8 h-8 flex items-center justify-center rounded-full border" aria-label="Kategori sebelumnya" type="button">‹</button>
      <div id="tabDots" class="flex items-center gap-2" aria-label="Indikator kategori"></div>
      <button id="tabNext" class="w-8 h-8 flex items-center justify-center rounded-full border" aria-label="Kategori selanjutnya" type="button">›</button>
    </div>

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

        if (prev) prev.addEventListener('click', ()=>showByIndex(currentIndex()-1));
        if (next) next.addEventListener('click', ()=>showByIndex(currentIndex()+1));

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

        setActive(tabOrder[0]);
      })();

      (function(){
        const tracks = document.querySelectorAll('.sorotan-track');
        const SPEED = 0.8;
        tracks.forEach((track) => {
          let offset = 0;
          function animate() {
            const halfWidth = track.scrollWidth / 2;
            offset -= SPEED;
            if (Math.abs(offset) >= halfWidth) offset += halfWidth;
            track.style.transform = `translateX(${offset}px)`;
            requestAnimationFrame(animate);
          }
          requestAnimationFrame(animate);
        });
      })();
    </script>

  </div>
</section>

{{-- SECTION: Kompetensi Pelatihan --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="text-center mb-6">
      <div class="inline-block bg-[#F3E8E9] text-[#861D23] text-[18px] md:text-[20px] lg:text-[22px] px-5 py-1.5 rounded-md font-[Volkhov] font-bold mb-6">Kompetensi Pelatihan</div>
      <h2 class="heading-stroke text-[20px] md:text-[24px] lg:text-[26px] font-[Volkhov] font-bold text-[#0E2A7B] leading-snug mb-6">
        <span class="relative z-10">Belajar dengan didampingi oleh instruktur yang ahli</span>
      </h2>
    </div>

    <div class="relative">
      <div id="kompetensi-track" class="flex gap-4 md:gap-5 lg:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar py-1" style="scrollbar-width:none;-ms-overflow-style:none;">
        @php
          $items = [
            'Tata Busana' => 'tata-busana.svg',
            'Tata Boga' => 'tata-boga.svg',
            'Tata Kecantikan' => 'tata-kecantikan.svg',
            'Teknik Pemesinan' => 'teknik-pemesinan.svg',
            'Teknik Otomotif' => 'teknik-otomotif.svg',
            'Teknik Pendingin' => 'teknik-pendingin.svg',
            'Teknik Pengelasan' => 'teknik-pengelasan.svg',
            'Desain Grafis' => 'mjc-desain-grafis.svg',
            'Web Desain' => 'mjc-web-desain.svg',
            'Animasi' => 'mjc-animasi.svg',
            'Fotografi' => 'mjc-fotografi.svg',
            'Videografi' => 'mjc-videografi.svg',
          ];
        @endphp

        @foreach($items as $nama => $file)
          <div class="shrink-0 snap-start relative w-[260px] h-[180px] rounded-lg overflow-hidden group transition-all duration-300">
            <img src="{{ uploaded_or_asset('profil/'.$file) }}" alt="{{ $nama }}" class="w-full h-full object-cover">
            <div class="absolute bottom-3 left-0 right-0 z-10 text-center">
              <h3 class="text-white font-[Montserrat] font-medium text-[16px]">{{ $nama }}</h3>
            </div>
          </div>
        @endforeach
      </div>

      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mt-6">
        <div class="flex gap-3 justify-center md:justify-start">
          <button id="prevBtn" class="w-8 h-8 rounded-full border">‹</button>
          <button id="nextBtn" class="w-8 h-8 rounded-full border">›</button>
        </div>

        <a href="#" class="inline-flex items-center gap-2 bg-[#1524AF] hover:bg-[#0E1F73] text-white text-sm font-medium px-5 py-2.5 rounded-md">Lihat Semua Kompetensi</a>
      </div>

      <script>
        (function () {
          const track  = document.getElementById('kompetensi-track');
          const prev   = document.getElementById('prevBtn');
          const next   = document.getElementById('nextBtn');
          if (!track) return;
          function getStep() {
            const first = track.querySelector(':scope > *');
            if (!first) return 280;
            const rect = first.getBoundingClientRect();
            const gap  = parseFloat(getComputedStyle(track).gap || '0');
            return Math.round(rect.width + gap);
          }
          if (next) next.addEventListener('click', () => track.scrollBy({ left: getStep(), behavior: 'smooth' }));
          if (prev) prev.addEventListener('click', () => track.scrollBy({ left: -getStep(), behavior: 'smooth' }));
        })();
      </script>
    </div>
  </div>
</section>

{{-- SECTION: Data Statistik --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
      <div class="lg:col-span-4">
        <div>
          <div class="inline-flex items-center px-3 py-1 rounded-md bg-[#F3E8E9] mb-3">
            <span class="text-[#861D23] font-[Volkhov] font-bold text-[16px]">Data Statistik</span>
          </div>

          <h2 class="heading-stroke font-[Volkhov] font-bold text-[24px] md:text-[28px] leading-snug mb-3">Rekapitulasi Rata-Rata<br/>Program Pelatihan</h2>

          <p class="text-sm text-slate-700 leading-relaxed mb-5">Hasil menunjukkan bahwa program pelatihan kami efektif ...</p>

          <ul id="listPelatihan" class="space-y-2">
            <li>
              <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="0">
                <span class="dot w-2 h-2 rounded-full bg-[#1524AF]"></span>
                <span class="label flex-1 text-[14px] font-[Montserrat] font-medium text-[#1524AF]">Judul Pelatihan 1</span>
              </button>
            </li>
            <li>
              <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="1">
                <span class="dot w-2 h-2 rounded-full bg-[#000000]"></span>
                <span class="label flex-1 text-[14px] font-[Montserrat] font-medium text-[#000000]">Judul Pelatihan 2</span>
              </button>
            </li>
          </ul>
        </div>

        <a href="#" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-[#1524AF] text-white text-[14px] mt-6 shadow-sm">Cari Tahu Lebih</a>
      </div>

      <div class="lg:col-span-8 mt-6 lg:mt-0">
        <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-4">
          <div class="rounded-xl bg-[#DBE7F7] p-3 text-center">
            <div class="text-[18px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">63.48</div>
            <div class="text-[10px] font-[Montserrat] font-medium text-[#081526]">Rata-Rata Pre-Test</div>
          </div>
          <div class="rounded-xl bg-[#DBE7F7] p-3 text-center">
            <div class="text-[18px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">90</div>
            <div class="text-[10px] font-[Montserrat] font-medium text-[#081526]">Praktek</div>
          </div>
          <div class="rounded-xl bg-[#DBE7F7] p-3 text-center">
            <div class="text-[18px] md:text-[28px] font-[Volkhov] font-bold text-[#081526]">80.76</div>
            <div class="text-[10px] font-[Montserrat] font-medium text-[#081526]">Rata-Rata Post-Test</div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
  const canvas = document.getElementById('statistikChart');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  const pelatihanLabels = ['Teknik Pengelasan', 'Teknik Mesin Bubut', 'Teknik Mesin CNC', 'Teknik Elektro'];

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: pelatihanLabels,
      datasets: [
        { label: 'Pre-Test', data: [8,22,12,29], borderColor: '#FF6107', tension:0.35, pointRadius:4, fill:false },
        { label: 'Post-Test', data: [24,53,75,94], borderColor: '#2F4BFF', tension:0.35, pointRadius:4, fill:false },
        { label: 'Praktek', data: [38,70,35,60], borderColor: '#6B2C47', tension:0.35, pointRadius:4, fill:false },
        { label: 'Rata-Rata', data: [52,10,26,49], borderColor: '#DBCC8F', tension:0.35, pointRadius:4, fill:false },
      ]
    },
    options: { maintainAspectRatio:false }
  });
})();
</script>

{{-- SECTION: Panduan Pelatihan --}}
<section class="relative bg-[#F1F9FC] py-4 md:py-6">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="relative rounded-2xl overflow-hidden">
      <div class="w-full h-[220px] sm:h-[240px] md:h-[300px] lg:h-[380px] bg-cover bg-center relative" style="background-image: url('{{ uploaded_or_asset('bgvideo.svg') }}');">
        <div class="absolute inset-0" style="background: linear-gradient(270deg, rgba(21,36,175,1) 29%, rgba(21,36,175,0.34) 66%, rgba(21,36,175,0) 100%);"></div>
      </div>

      <div class="absolute inset-0 grid grid-cols-2 gap-x-3 md:gap-x-4 lg:gap-x-8 px-3 sm:px-5 md:px-8 lg:px-10 py-5 md:py-6 lg:py-0">
        <div class="relative flex items-center justify-center">
          <a href="https://www.youtube.com/watch?v=JZXEx5i6U9o" target="_blank" rel="noopener" class="flex items-center justify-center">
            <img src="{{ uploaded_or_asset('icons/play.svg') }}" alt="Play Icon" class="w-14 h-14 object-contain select-none">
          </a>
        </div>

        <div class="relative flex items-center justify-end">
          <div class="bg-[#DBE7F7]/95 text-[#0E2A7B] rounded-xl shadow-md w-full max-w-[360px] p-4 backdrop-blur-sm">
            <h2 class="heading-stroke text-left text-[18px] font-[Volkhov] font-bold mb-2">Bersama, Kita Cetak Pendidikan Vokasi yang Unggul</h2>
            <p class="text-[13px] text-[#000000] leading-relaxed mb-3">Pahami alur permohonan peserta ...</p>
            <a href="#panduan" class="flex items-center gap-2 bg-[#1524AF] text-white px-4 py-2 rounded-md">Lihat Panduan</a>
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
