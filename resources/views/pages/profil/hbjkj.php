masukkan ini <!-- Ganti blok hero-track dengan ini -->
<div id="hero-track" class="flex items-center gap-4 md:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar select-none py-8">
  <div aria-hidden="true" class="shrink-0 snap-none pointer-events-none w-[15%] md:w-[12%] lg:w-[10%]"></div>

  @if($banners->isNotEmpty())
    @foreach($banners as $b)
      <div class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300">
        <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
          <img src="{{ $b->image ? asset('storage/' . $b->image) : asset('images/beranda/slide1.jpg') }}"
               alt="{{ $b->title ?? 'Banner' }}"
               class="w-full h-full object-cover select-none" draggable="false">
        </div>
      </div>
    @endforeach
  @else
    <!-- fallback 3 static slides kalau tidak ada banner -->
    @for($i=1;$i<=3;$i++)
      <div class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300">
        <div class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden">
          <img src="{{ asset('images/beranda/slide'.$i.'.jpg') }}" alt="Slide {{ $i }}" class="w-full h-full object-cover select-none">
        </div>
      </div>
    @endfor
  @endif

  <div aria-hidden="true" class="shrink-0 snap-none pointer-events-none w-[15%] md:w-[12%] lg:w-[10%]"></div>
</div>

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

 | - cek storage (public) dulu (storage/app/public/<path>)

 | - jika ada -> Storage::url($path) (biasanya '/storage/<path>')

 | - jika tidak -> asset('images/<path>')

 | Catatan: $path dapat berupa 'profil/xxx.svg' atau 'beranda/slide1.jpg'

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

        // jika path sudah berisi folder (mis. 'beranda/slide1.jpg'), gunakan images/<path>

        if (strpos($path, '/') !== false) {

            return asset("images/{$path}");

        }

        return asset("{$fallbackPrefix}/{$path}");

    }

}

@endphp



  {{-- TOPBAR --}}

  @include('components.layouts.app.topbar')



  {{-- NAVBAR --}}

  @include('components.layouts.app.navbarlanding')



  {{-- HERO: Slider dengan infinite loop dan scale effect --}}

  <header class="w-full bg-[#F1F9FC]">

    <div class="w-full px-6 md:px-12 lg:px-[80px] py-4 md:py-6">

      <div id="hero" class="relative">



        {{-- TRACK: beri padding horizontal supaya slide next/prev kelihatan (peek) --}}

        <div

          id="hero-track"

          class="flex items-center gap-4 md:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar select-none py-8"

          style="scrollbar-width:none;-ms-overflow-style:none;"

        >



          <!-- LEFT GUTTER (untuk centering) -->

          <div

            aria-hidden="true"

            class="shrink-0 snap-none pointer-events-none w-[15%] md:w-[12%] lg:w-[10%]"

          ></div>



          <!-- CLONES KIRI (untuk unlimited scroll kiri) -->

          <div

            class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300"

            data-real="1"

          >

            <div

              class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden"

            >

              <img

                src="{{ uploaded_or_asset('beranda/slide2.jpg') }}"

                alt="Slide 2"

                class="w-full h-full object-cover select-none"

                draggable="false"

              >

            </div>

          </div>



          <div

            class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300"

            data-real="2"

          >

            <div

              class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden"

            >

              <img

                src="{{ uploaded_or_asset('beranda/slide3.jpg') }}"

                alt="Slide 3"

                class="w-full h-full object-cover select-none"

                draggable="false"

              >

            </div>

          </div>



          <!-- SLIDE ASLI -->

          <div

            class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300"

            data-real="0"

          >

            <div

              class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden"

            >

              <img

                src="{{ uploaded_or_asset('beranda/slide1.jpg') }}"

                alt="Slide 1"

                class="w-full h-full object-cover select-none"

                draggable="false"

              >

            </div>

          </div>



          <div

            class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300"

            data-real="1"

          >

            <div

              class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden"

            >

              <img

                src="{{ uploaded_or_asset('beranda/slide2.jpg') }}"

                alt="Slide 2"

                class="w-full h-full object-cover select-none"

                draggable="false"

              >

            </div>

          </div>



          <div

            class="hero-slide shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300"

            data-real="2"

          >

            <div

              class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden"

            >

              <img

                src="{{ uploaded_or_asset('beranda/slide3.jpg') }}"

                alt="Slide 3"

                class="w-full h-full object-cover select-none"

                draggable="false"

              >

            </div>

          </div>



          <!-- CLONES KANAN (untuk unlimited scroll kanan) -->

          <div

            class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300"

            data-real="0"

          >

            <div

              class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden"

            >

              <img

                src="{{ uploaded_or_asset('beranda/slide1.jpg') }}"

                alt="Slide 1"

                class="w-full h-full object-cover select-none"

                draggable="false"

              >

            </div>

          </div>



          <div

            class="hero-slide clone shrink-0 snap-center w-[70%] md:w-[76%] lg:w-[80%] transition-transform duration-300"

            data-real="1"

          >

            <div

              class="w-full h-[46vw] md:h-[420px] lg:h-[520px] max-h-[560px] min-h-[220px] rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF] overflow-hidden"

            >

              <img

                src="{{ uploaded_or_asset('beranda/slide2.jpg') }}"

                alt="Slide 2"

                class="w-full h-full object-cover select-none"

                draggable="false"

              >

            </div>

          </div>



          <!-- RIGHT GUTTER (untuk centering) -->

          <div

            aria-hidden="true"

            class="shrink-0 snap-none pointer-events-none w-[15%] md:w-[12%] lg:w-[10%]"

          ></div>

        </div>



        {{-- Controls + dots --}}

        <div class="mt-4 flex items-center justify-center gap-4">

          <button

            id="hero-prev"

            class="w-9 h-9 grid place-items-center rounded-full border border-gray-300 text-gray-600 hover:bg-white/60 transition-colors"

            aria-label="Sebelumnya"

          >

            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor">

              <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z" />

            </svg>

          </button>



          <div id="hero-dots" class="flex items-center gap-3">

            <button

              class="w-2.5 h-2.5 rounded-full bg-[#1524AF] transition-colors"

              aria-label="Slide 1"

            ></button>

            <button

              class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-colors"

              aria-label="Slide 2"

            ></button>

            <button

              class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-colors"

              aria-label="Slide 3"

            ></button>

          </div>



          <button

            id="hero-next"

            class="w-9 h-9 grid place-items-center rounded-full border border-gray-300 text-gray-600 hover:bg-white/60 transition-colors"

            aria-label="Berikutnya"

          >

            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor">

              <path d="m8.59 16.59 1.41 1.41 6-6-6-6L8.59 6.41 13.17 11z" />

            </svg>

          </button>

        </div>



      </div>

    </div>

  </header>



  <style>

    .hero-slide {

      transform: scale(0.85);

      opacity: 0.5;

      transition: transform 0.3s ease, opacity 0.3s ease;

    }



    .hero-slide.active {

      transform: scale(1);

      opacity: 1;

    }



    /* small helper to hide native scrollbar on non-webkit too */

    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

  </style>



  <script>

    (function () {

      const track = document.getElementById('hero-track');

      if (!track) return;

      const slides = Array.from(track.querySelectorAll('.hero-slide')); // hanya slide (tanpa gutter)

      const dotsEl = document.getElementById('hero-dots');

      const dots = dotsEl ? Array.from(dotsEl.children) : [];

      const prev = document.getElementById('hero-prev');

      const next = document.getElementById('hero-next');



      if (slides.length === 0) return;



      // Urutan di DOM (tanpa gutter):

      // 0: clone(real1) | 1: clone(real2) | 2: real0 | 3: real1 | 4: real2 | 5: clone(real0) | 6: clone(real1)

      const FIRST_REAL = 2; // real0

      const LAST_REAL = 4; // real2

      const LEFT_CLONE_BEFORE_FIRST = 1; // clone real2

      const RIGHT_CLONE_AFTER_LAST = 5; // clone real0

      const REAL_COUNT = 3;

      const ANIM = 300,

        BUF = 40; // durasi + buffer



      let currentIndex = FIRST_REAL;

      let isTransitioning = false;



      // ===== Util dasar =====

      const realOf = (idx) =>

        idx >= FIRST_REAL && idx <= LAST_REAL

          ? idx - FIRST_REAL

          : parseInt(slides[idx].dataset.real, 10);



      const setDots = (r) => {

        if (!dots) return;

        dots.forEach((d, i) => {

          d.className =

            'w-2.5 h-2.5 rounded-full transition-colors ' +

            (i === r ? 'bg-[#1524AF]' : 'bg-gray-300');

        });

      };



      const setActive = (idx) =>

        slides.forEach((s, i) => s.classList.toggle('active', i === idx));



      const centerOffset = (idx) =>

        slides[idx].offsetLeft -

        (track.clientWidth - slides[idx].clientWidth) / 2;



      // Scroll ke index tertentu (center) dengan behavior pilihan

      const scrollToIndex = (idx, smooth = true) =>

        track.scrollTo({

          left: centerOffset(idx),

          behavior: smooth ? 'smooth' : 'auto',

        });



      // Menunggu sampai posisi scroll benar-benar mencapai target (lebih stabil dari setTimeout)

      function smoothScrollToIndex(idx, cb) {

        const prevSnap = track.style.scrollSnapType;

        track.style.scrollSnapType = 'none'; // hindari snap melawan arah



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



      // rAF ganda untuk memastikan eksekusi setelah browser menyelesaikan paint terakhir

      function rafSwap(fn) {

        requestAnimationFrame(() => requestAnimationFrame(fn));

      }



      // Tukar posisi clone→real secara relatif (delta) tanpa gerakan tambahan (side-peek tetap)

      function seamlessSwapByDelta(fromCloneIdx, toRealIdx) {

        const prevBehavior = track.style.scrollBehavior;

        const prevSnap = track.style.scrollSnapType;



        track.style.scrollBehavior = 'auto';

        track.style.scrollSnapType = 'none';



        const delta =

          centerOffset(toRealIdx) - centerOffset(fromCloneIdx);



        track.scrollLeft += delta; // geser relatif → tidak terlihat loncat



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

        if (times <= 0) return;



        const tick = () => {

          dir > 0 ? goNext() : goPrev();

          times--;



          if (times > 0) setTimeout(tick, ANIM + BUF + 10);

        };



        tick();

      }



      dots.forEach((d, targetReal) => {

        d.addEventListener('click', () => {

          if (isTransitioning) return;



          const curReal = realOf(currentIndex);

          if (targetReal === curReal) return;



          const r = (targetReal - curReal + 3) % 3;

          const l = (curReal - targetReal + 3) % 3;



          if (r <= l) step(1, r);

          else step(-1, l);

        });

      });

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

        },

        { passive: true }

      );


      scrollToIndex(FIRST_REAL, false);

      setActive(FIRST_REAL);

      setDots(0);


      if (next) next.addEventListener('click', goNext);

      if (prev) prev.addEventListener('click', goPrev);

    })();

  </script>

