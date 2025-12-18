{{-- resources/views/pages/berita.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Berita - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

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

    .judul-stroke {
      color: #1524AF !important;
      text-shadow:
        -1px -1px 0 #FFDE59,
         1px -1px 0 #FFDE59,
        -1px  1px 0 #FFDE59,
         1px  1px 0 #FFDE59;
    }

    .section-container {
      max-width: 1280px;
      margin-left: auto;
      margin-right: auto;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    @media (min-width: 768px) {
      .section-container { padding-left: 3rem; padding-right: 3rem; }
    }

    @media (min-width: 1024px) {
      .section-container { padding-left: 80px; padding-right: 80px; }
    }

    section + section { margin-top: 30px !important; }
  </style>
</head>

<body class="bg-[#F1F9FC] antialiased">
  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO --}}
  <x-layouts.app.profile-hero
    title="Berita"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Berita',  'route' => 'berita.index'],
    ]"
    height="h-[320px]"
  />
  {{-- /HERO --}}

@php
  use Illuminate\Support\Facades\Storage;
  use Illuminate\Support\Str;
  use Illuminate\Support\Carbon;

  // normalisasi input dari controller
  $postsPaginator = $postsPaginator ?? ($posts ?? null);
  $featured = $featured ?? null;
  $others = $others ?? null;

  if (empty($postsPaginator) && ! empty($posts) && is_object($posts) && method_exists($posts, 'items')) {
    $postsPaginator = $posts;
  }

  if ($postsPaginator && empty($featured)) {
    $items = collect($postsPaginator->items());
    $featured = $items->first() ?: null;
    $others = $items->slice(1);
  }

  if (empty($postsPaginator) && ! empty($posts) && ($posts instanceof \Illuminate\Support\Collection)) {
    $items = $posts;
    $featured = $featured ?? $items->first();
    $others = $others ?? $items->slice(1);
  }

  if ($others && ! ($others instanceof \Illuminate\Support\Collection)) {
    $others = collect($others);
  }

  $tz = config('app.timezone') ?: 'UTC';
@endphp

  <section class="section-container py-8 md:py-10">
    @if( ! $postsPaginator || ($postsPaginator->count() === 0 && (empty($featured) && ($others ? $others->isEmpty() : true))) )
      <div class="text-center py-16">
        <h3 class="text-2xl font-bold text-[#1524AF]">Belum ada berita</h3>
      </div>
    @else

      {{-- FEATURED --}}
      @if($featured)
        @php
          $fIsModel = is_object($featured);
          $fTitle = $fIsModel ? ($featured->title ?? '—') : ($featured['title'] ?? '—');
          $fSlug = $fIsModel ? ($featured->slug ?? '#') : ($featured['url'] ?? '#');

          $rawImg = $fIsModel ? ($featured->image ?? null) : ($featured['thumb'] ?? null);

          if ($rawImg) {
              $fImgUrl = Storage::url($rawImg);
          } else {
              $fImgUrl = asset('images/beranda/slide1.jpg');
          }

          $fDate = $fIsModel ? ($featured->published_at ?? $featured->created_at) : ($featured['date'] ?? null);
          if ($fDate && is_object($fDate) && method_exists($fDate, 'setTimezone')) {
            $fDateForDisplay = $fDate->setTimezone($tz)->translatedFormat('d F Y H:i');
          } elseif (!empty($fDate)) {
            $fDateForDisplay = Carbon::parse($fDate)->setTimezone($tz)->translatedFormat('d F Y H:i');
          } else {
            $fDateForDisplay = '-';
          }
        @endphp

        <article class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 lg:mb-10">
          {{-- KIRI: GAMBAR --}}
          <div class="lg:col-span-5">
            <a href="{{ $fIsModel ? route('berita.show', $fSlug) : ($fSlug) }}" class="block group">
              <div class="aspect-[16/12] md:aspect-[16/11] w-full rounded-[18px] overflow-hidden">
                <img src="{{ $fImgUrl }}" alt="{{ $fTitle }}"
                     class="w-full h-full object-cover transition group-hover:scale-[1.02]" loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('images/beranda/slide1.jpg') }}'">
              </div>
            </a>
          </div>

          {{-- KANAN: BADGE + TANGGAL + JUDUL + EXCERPT --}}
          <div class="lg:col-span-7">
            <div class="mb-3">
              <span class="inline-flex items-center
                           px-4 py-1.5
                           rounded-lg
                           bg-[#F6D8DC]
                           text-[#861D23]
                           font-[Volkhov]
                           text-[17px] md:text-[18px]
                           font-semibold
                           leading-none
                           shadow-sm
                           whitespace-nowrap">
                Berita Baru
              </span>
            </div>

            <div class="flex items-center gap-2 text-slate-500 text-[13px] mb-1">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                <line x1="8"  y1="2" x2="8"  y2="6" stroke-width="2"></line>
                <line x1="3"  y1="10" x2="21" y2="10" stroke-width="2"></line>
              </svg>
              <span>{{ $fDateForDisplay }}</span>
            </div>

            <h2 class="font-[Volkhov] font-bold text-[24px] md:text-[26px] leading-tight text-[#1524AF] mb-2 judul-stroke">
              <a href="{{ $fIsModel ? route('berita.show', $fSlug) : ($fSlug) }}" class="hover:opacity-90 transition">
                {{ $fTitle }}
              </a>
            </h2>

         <p class="font-[Montserrat] text-[14.5px] md:text-[15px]
      text-slate-800 leading-relaxed mb-3
      max-w-[60ch] break-words overflow-hidden
      text-justify">

              @if($fIsModel)
                {{ Str::limit(strip_tags($featured->content ?? ''), 320) }}
              @else
                {{ $featured['excerpt'] ?? '' }}
              @endif
            </p>

            <a href="{{ $fIsModel ? route('berita.show', $fSlug) : ($fSlug) }}"
               class="inline-flex items-center gap-2 text-[#1524AF] font-[Montserrat] underline underline-offset-2 hover:no-underline">
              Baca Selengkapnya
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </div>
        </article>
      @endif

      {{-- GRID OTHER POSTS --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($others as $post)
          @php
            $isModel   = is_object($post);
            $title     = $isModel ? ($post->title ?? '—') : ($post['title'] ?? '—');
            $slugOrUrl = $isModel ? route('berita.show', $post->slug) : ($post['url'] ?? '#');

            $rawImg = $isModel ? ($post->image ?? null) : ($post['thumb'] ?? null);
            if ($rawImg) {
              $imgUrl = Storage::url($rawImg);
            } else {
              $imgUrl = asset('images/beranda/slide1.jpg');
            }

            $date = $isModel ? ($post->published_at ?? $post->created_at) : ($post['date'] ?? null);
            if ($date && is_object($date) && method_exists($date, 'setTimezone')) {
              $dateForDisplay = $date->setTimezone($tz)->translatedFormat('d F Y');
            } elseif (!empty($date)) {
              $dateForDisplay = Carbon::parse($date)->setTimezone($tz)->translatedFormat('d F Y');
            } else {
              $dateForDisplay = '-';
            }

            $excerpt = $isModel
              ? Str::limit(strip_tags($post->content ?? ''), 120)
              : ($post['excerpt'] ?? '');
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
                  alt="{{ $title }}"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                  loading="lazy"
                  onerror="this.onerror=null;this.src='{{ asset('images/beranda/slide1.jpg') }}'">
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
              <span>{{ $dateForDisplay }}</span>
            </div>

            {{-- Judul --}}
           <h3 class="font-[Volkhov] text-[16px] sm:text-[18px] leading-snug mb-2
           text-[#081526] transition-colors duration-200
           group-hover:text-[#1524AF]">
  <a href="{{ $slugOrUrl }}" class="block">

    {{-- MOBILE (≤ md): max 6 kata --}}
    <span class="block md:hidden">
      {{ Str::words($title, 6, '...') }}
    </span>

    {{-- DESKTOP (≥ md): judul full --}}
    <span class="hidden md:block">
      {{ $title }}
    </span>

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
      </div>

      {{-- PAGINATION --}}
      <div class="mt-8 flex justify-center">
        @if($postsPaginator)
          @php
            $p       = $postsPaginator;
            $current = $p->currentPage();
            $last    = $p->lastPage();
            $start   = max(1, $current - 3);
            $end     = min($last, $current + 3);
          @endphp

          <nav class="inline-flex items-center gap-1" aria-label="Pagination">
            @if($p->onFirstPage())
              <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">&laquo;</span>
            @else
              <a href="{{ $p->url($current - 1) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50">&laquo;</a>
            @endif

            @for($i = $start; $i <= $end; $i++)
              @if($i === $current)
                <span class="px-3 py-1.5 rounded-lg border border-[#1524AF] text-[#1524AF] bg-[#F5FBFF]">{{ $i }}</span>
              @else
                <a href="{{ $p->url($i) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50">{{ $i }}</a>
              @endif
            @endfor

            @if($current >= $last)
              <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">&raquo;</span>
            @else
              <a href="{{ $p->url($current + 1) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50">&raquo;</a>
            @endif
          </nav>
        @endif
      </div>

    @endif
  </section>

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
