{{-- resources/views/pages/berita.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Berita - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts (dari design estetik) --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    /* CSS Custom dari design estetik */
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
    
    /* Global Section Layout Consistency */
    .section-container {
      max-width: 1280px;
      margin-left: auto;
      margin-right: auto;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    @media (min-width: 768px) {
      .section-container {
        padding-left: 3rem;
        padding-right: 3rem;
      }
    }

    @media (min-width: 1024px) {
      .section-container {
        padding-left: 80px;
        padding-right: 80px;
      }
    }
    
    section + section {
      margin-top: 30px !important;
    }
  </style>
</head>

<body class="bg-[#F1F9FC] antialiased">
  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO (komponen reusable) --}}
  <x-layouts.app.profile-hero
    title="Berita"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Berita',  'route' => 'berita.index'], {{-- Menggunakan route fungsional --}}
    ]"
    height="h-[320px]"
  />
  {{-- /HERO --}}

  {{-- SECTION: Daftar Berita --}}
  <section class="section-container py-8 md:py-10">
    @php
      // ====== Logic Laravel Fungsional untuk mengambil data ======
      $postsCollection = $posts instanceof \Illuminate\Pagination\LengthAwarePaginator
                         ? $posts->items()
                         : ($posts ?? collect());

      if (!isset($featured)) {
          $featured = collect($postsCollection)->first();
      }

      // Filter posts, jika $featured ada, hilangkan dari $others
      $others = collect($postsCollection)->filter(fn($p) => !($featured && ($p->id ?? $p->slug) === ($featured->id ?? $featured->slug)));
    @endphp

    {{-- Jika tidak ada berita --}}
    @if(empty($postsCollection) || count($postsCollection) === 0)
      <div class="text-center py-16">
        <h3 class="text-2xl font-bold text-[#1524AF]">Belum ada berita</h3>
      </div>

    @else
      
      {{-- ===== KARTU UNGGULAN (FEATURED) ===== --}}
      @if($featured)
      <article class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 lg:mb-10">
        {{-- Gambar --}}
        <div class="lg:col-span-5">
          <a href="{{ route('berita.show', $featured->slug) }}" class="block group">
            <div class="aspect-[16/12] md:aspect-[16/11] w-full rounded-[18px] overflow-hidden shadow-md">
              {{-- Menggunakan data fungsional --}}
              @if($featured->image)
                <img src="{{ Storage::url($featured->image) }}" alt="{{ $featured->title }}"
                     class="w-full h-full object-cover transition group-hover:scale-[1.02]" loading="lazy">
              @else
                {{-- Placeholder jika tidak ada gambar --}}
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
              <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
              <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
            </svg>
            {{-- Menggunakan data fungsional --}}
            <span>{{ optional($featured->published_at ?? $featured->created_at)->format('d F Y') }}</span>
          </div>

          {{-- Judul --}}
          <h2 class="font-[Volkhov] font-bold text-[24px] md:text-[26px] leading-tight text-[#1524AF] mb-2">
            {{-- Menggunakan route fungsional --}}
            <a href="{{ route('berita.show', $featured->slug) }}" class="hover:opacity-90 transition">
              {{ $featured->title }}
            </a>
          </h2>

          {{-- Excerpt --}}
          <p class="font-[Montserrat] text-[14.5px] md:text-[15px] text-slate-800 leading-relaxed mb-3 max-w-[60ch]">
            {{-- Menggunakan data fungsional dan Str::limit untuk excerpt --}}
            {!! \Illuminate\Support\Str::limit(strip_tags($featured->content), 250) !!}
          </p>

          {{-- CTA --}}
          <a href="{{ route('berita.show', $featured->slug) }}"
             class="inline-flex items-center gap-2 text-[#1524AF] font-[Montserrat] underline underline-offset-2 hover:no-underline">
            Baca Selengkapnya
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
              <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>
      </article>
      @endif

      {{-- ===== GRID BERITA LAIN ===== --}}
      <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($others as $post)
          <article class="rounded-2xl border border-slate-200 bg-white p-3 sm:p-4 shadow-sm hover:shadow-md transition">
            {{-- Thumb --}}
            <a href="{{ route('berita.show', $post->slug) }}" class="block mb-3">
              <div class="aspect-[16/11] w-full rounded-xl border border-[#1524AF]/40 overflow-hidden">
                {{-- Menggunakan data fungsional --}}
                @if($post->image)
                  <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                       class="w-full h-full object-cover hover:scale-[1.02] transition" loading="lazy">
                @else
                  {{-- Placeholder jika tidak ada gambar --}}
                  <div class="w-full h-full bg-slate-200/70"></div>
                @endif
              </div>
            </a>

            {{-- Meta tanggal --}}
            <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
              </svg>
              {{-- Menggunakan data fungsional --}}
              <span>{{ optional($post->published_at ?? $post->created_at)->format('d F Y') }}</span>
            </div>

            {{-- Judul --}}
            <h3 class="font-[Volkhov] text-[16px] sm:text-[18px] leading-snug text-slate-900 mb-2">
              {{-- Menggunakan route fungsional --}}
              <a href="{{ route('berita.show', $post->slug) }}" class="hover:text-[#1524AF] transition">
                {{ $post->title }}
              </a>
            </h3>

            {{-- Excerpt --}}
            <p class="font-[Montserrat] text-[13px] sm:text-[14px] text-slate-700 mb-3 line-clamp-2">
              {{-- Menggunakan data fungsional dan Str::limit --}}
              {!! \Illuminate\Support\Str::limit(strip_tags($post->content), 80) !!}
            </p>

            {{-- Read more --}}
            <a href="{{ route('berita.show', $post->slug) }}"
               class="inline-flex items-center gap-2 text-[#1524AF] text-[13px] sm:text-[14px] hover:underline">
              Baca Selengkapnya
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </article>
        @endforeach
      </div>

      {{-- ===== PAGINATION (menggunakan Paginator Laravel) ===== --}}
      <nav class="mt-8 flex justify-center">
        {{-- Menggunakan helper Laravel untuk paginasi --}}
        {{ $posts->links('pagination::tailwind') }}
      </nav>

    @endif
  </section>

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>