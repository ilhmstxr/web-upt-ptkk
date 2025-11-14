{{-- resources/views/pages/berita.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cerita Kami - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    :root{
      --biru-brand: #1524AF;
      --kuning-stroke: #FFDE59;
      --merah-stroke: #861D23;
      --card-manfaat: #DBE7F7;
    }

    /* Stroke teks utilitas */
    .stroke-yellow{
      text-shadow:
        -1px -1px 0 var(--kuning-stroke),
         1px -1px 0 var(--kuning-stroke),
        -1px  1px 0 var(--kuning-stroke),
         1px  1px 0 var(--kuning-stroke);
    }
    .stroke-red{
      text-shadow:
        -1px -1px 0 var(--merah-stroke),
         1px -1px 0 var(--merah-stroke),
        -1px  1px 0 var(--merah-stroke),
         1px  1px 0 var(--merah-stroke);
    }

    /* Shadow bertingkat (5 lapis) */
    .shadow-5x{
      box-shadow:
        0 1px 3px rgba(0,0,0,0.05),
        0 2px 6px rgba(0,0,0,0.07),
        0 4px 10px rgba(0,0,0,0.09),
        0 6px 14px rgba(0,0,0,0.11),
        0 8px 20px rgba(0,0,0,0.13);
    }

    /* Kartu Tujuan & Manfaat */
    .tujuan-card{
      background: #FEFEFE;
      box-shadow:
        0 2px 4px rgba(0,0,0,.06),
        0 12px 24px rgba(0,0,0,.08),
        0 40px 80px rgba(0,0,0,.08);
      border-radius: 1rem; /* rounded-2xl */
    }
    .card-manfaat{
      background: var(--card-manfaat);
      height: 300px;
      border-radius: 1rem; /* rounded-2xl */
    }
    /* =======================================================
   GLOBAL SECTION LAYOUT CONSISTENCY
   ======================================================= */

/* Padding horizontal sama untuk semua section */
.section-container {
  max-width: 1280px; /* setara max-w-7xl */
  margin-left: auto;
  margin-right: auto;
  padding-left: 1.5rem;   /* px-6 */
  padding-right: 1.5rem;  /* px-6 */
}

@media (min-width: 768px) {
  .section-container {
    padding-left: 3rem;   /* md:px-12 */
    padding-right: 3rem;
  }
}

@media (min-width: 1024px) {
  .section-container {
    padding-left: 80px;   /* lg:px-[80px] */
    padding-right: 80px;
  }
}

/* === Baru: kompak 30px vertikal untuk semua section === */
.section-compact {
  padding-top: 30px !important;
  padding-bottom: 30px !important;
}

/* === Baru: jarak antar section 30px === */
section + section {
  margin-top: 30px !important;
}

  </style>
</head>

<body class="bg-[#FEFEFE] antialiased">
  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO (komponen reusable) --}}
  <x-layouts.app.profile-hero
    title="Berita"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Berita',  'route' => 'news'],
    ]"
    height="h-[320px]"   {{-- opsional: samakan tinggi hero --}}
    {{-- image="images/berita/hero-berita.jpg"  --}} {{-- opsional kalau kamu punya gambar khusus --}}
  />
  {{-- /HERO --}}

{{-- SECTION: Daftar Berita --}}
<section class="section-container py-8 md:py-10">
  @php
    // ====== Dummy data (ganti dengan data dari DB/Eloquent) ======
    $posts = collect(range(1,10))->map(function($i){
      return [
        'title'   => "Judul Berita {$i}",
        'date'    => '22 Oktober 2024',
        'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ornare ligula...',
        'url'     => url("/berita/{$i}"),
        'thumb'   => null, // ganti ke path gambar jika ada
      ];
    });
    $featured = $posts->first();
    $others   = $posts->slice(1);
  @endphp


{{-- ===== KARTU UNGGULAN (refined) ===== --}}
<article class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 lg:mb-10">
  {{-- Gambar --}}
  <div class="lg:col-span-5">
    <a href="{{ $featured['url'] }}" class="block group">
      <div class="aspect-[16/12] md:aspect-[16/11] w-full rounded-[18px] overflow-hidden">
        @if($featured['thumb'])
          <img src="{{ $featured['thumb'] }}" alt="{{ $featured['title'] }}"
               class="w-full h-full object-cover transition group-hover:scale-[1.02]" loading="lazy">
        @else
          <div class="w-full h-full bg-slate-300/60"></div>
        @endif
      </div>
    </a>
  </div>

  {{-- Teks --}}
  <div class="lg:col-span-7">
    {{-- Badge --}}
    <div class="mb-2">
      <span class="inline-flex items-center px-3 py-1 rounded-md bg-[#F3E8E9] text-[#861D23]
                   font-[Volkhov] text-[15px] leading-none shadow-sm">
        Berita Baru
      </span>
    </div>

    {{-- Tanggal --}}
    <div class="flex items-center gap-2 text-slate-500 text-[13px] mb-1">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
        <line x1="8"  y1="2" x2="8"  y2="6" stroke-width="2"></line>
        <line x1="3"  y1="10" x2="21" y2="10" stroke-width="2"></line>
      </svg>
      <span>{{ $featured['date'] }}</span>
    </div>

    {{-- Judul --}}
    <h2 class="font-[Volkhov] font-bold text-[24px] md:text-[26px] leading-tight text-[#1524AF] mb-2">
      <a href="{{ $featured['url'] }}" class="hover:opacity-90 transition">{{ $featured['title'] }}</a>
    </h2>

    {{-- Excerpt --}}
    <p class="font-[Montserrat] text-[14.5px] md:text-[15px] text-slate-800 leading-relaxed mb-3 max-w-[60ch]">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam condimentum libero ut nibh fermentum,
      non sollicitudin dolor rutrum. Cras ultricies nec enim ut malesuada. Duis rhoncus dignissim feugiat.
      Duis laoreet egestas dolor sit amet bibendum. Maecenas egestas porttitor mattis. Quisque orci est,
      faucibus nec fermentum vitae, interdum faucibus nisl. Vivamus ornare porttitor nisl eget venenatis …
    </p>

    {{-- CTA --}}
    <a href="{{ $featured['url'] }}"
       class="inline-flex items-center gap-2 text-[#1524AF] font-[Montserrat] underline underline-offset-2 hover:no-underline">
      Baca Selengkapnya
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>
  </div>
</article>


  {{-- ===== GRID BERITA LAIN ===== --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($others as $post)
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
        {{-- Thumb --}}
        <a href="{{ $post['url'] }}" class="block mb-3">
          <div class="aspect-[16/11] w-full rounded-xl border border-[#1524AF]/40 overflow-hidden">
            @if($post['thumb'])
              <img src="{{ $post['thumb'] }}" alt="{{ $post['title'] }}"
                   class="w-full h-full object-cover hover:scale-[1.02] transition" loading="lazy">
            @else
              <div class="w-full h-full bg-slate-200/70"></div>
            @endif
          </div>
        </a>

        {{-- Meta tanggal --}}
        <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
            <line x1="8"  y1="2" x2="8"  y2="6" stroke-width="2"></line>
            <line x1="3"  y1="10" x2="21" y2="10" stroke-width="2"></line>
          </svg>
          <span>{{ $post['date'] }}</span>
        </div>

        {{-- Judul --}}
        <h3 class="font-[Volkhov] text-[18px] leading-snug text-slate-900 mb-2">
          <a href="{{ $post['url'] }}" class="hover:text-[#1524AF] transition">{{ $post['title'] }}</a>
        </h3>

        {{-- Excerpt --}}
        <p class="font-[Montserrat] text-[14px] text-slate-700 mb-3">
          {{ $post['excerpt'] }}
        </p>

        {{-- Read more --}}
        <a href="{{ $post['url'] }}"
           class="inline-flex items-center gap-2 text-[#1524AF] text-[14px] hover:underline">
          Baca Selengkapnya
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </article>
    @endforeach
  </div>

  {{-- ===== PAGINATION (dummy) ===== --}}
  <nav class="mt-8 flex justify-center">
    <ul class="inline-flex items-center gap-1">
      <li><a class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50" href="#">&laquo;</a></li>
      @foreach (range(1,7) as $p)
        <li>
          <a href="#"
             class="px-3 py-1.5 rounded-lg border {{ $p===3 ? 'border-[#1524AF] text-[#1524AF] bg-[#F5FBFF]' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            {{ $p }}
          </a>
        </li>
      @endforeach
      <li><a class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50" href="#">&raquo;</a></li>
    </ul>
  </nav>
</section>


  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
