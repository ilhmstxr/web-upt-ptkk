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
    .section-container { max-width:1280px; margin-left:auto; margin-right:auto; padding-left:1.5rem; padding-right:1.5rem; }
    @media (min-width:768px){ .section-container{ padding-left:3rem; padding-right:3rem; } }
    @media (min-width:1024px){ .section-container{ padding-left:80px; padding-right:80px; } }
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

  /**
   * force_storage_url($raw, $fallback)
   * - jika $raw sudah full URL -> kembalikan apa adanya
   * - jika $raw relatif (mungkin diawali "storage/" atau "public/") -> normalisasi lalu coba Storage::disk('public')->url(...)
   * - jika gagal, cek public/storage/ dan public/ secara langsung
   * - fallback ke gambar default
   */
  if (! function_exists('force_storage_url')) {
    function force_storage_url($raw, $fallback = null) {
      $fallback = $fallback ?? asset('images/beranda/slide1.jpg');
      if (empty($raw)) return $fallback;

      // full URL -> ok
      if (preg_match('/^https?:\\/\\//i', $raw)) return $raw;

      // normalisasi path untuk disk public
      $p = trim($raw);
      $p = preg_replace('#^storage/+#i', '', $p);
      $p = preg_replace('#^public/+#i', '', $p);
      $p = ltrim($p, '/');

      try {
        if (Storage::disk('public')->exists($p)) {
          return Storage::disk('public')->url($p); // biasanya menghasilkan /storage/...
        }
      } catch (\Throwable $e) {
        // ignore dan lanjut ke cek file_exists
      }

      // cek public/storage/<p>
      if (file_exists(public_path('storage/' . $p))) {
        return asset('storage/' . $p);
      }

      // cek public/<p>
      if (file_exists(public_path($p))) {
        return asset($p);
      }

      return $fallback;
    }
  }

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

          // Dapatkan raw image path kemudian pakai force_storage_url
          $rawImg = $fIsModel ? ($featured->image ?? null) : ($featured['thumb'] ?? null);
          $fImgUrl = force_storage_url($rawImg, asset('images/beranda/slide1.jpg'));

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
          <div class="lg:col-span-5">
            <a href="{{ $fIsModel ? route('berita.show', $fSlug) : ($fSlug) }}" class="block group">
              <div class="aspect-[16/12] md:aspect-[16/11] w-full rounded-[18px] overflow-hidden">
                <img src="{{ $fImgUrl }}" alt="{{ $fTitle }}" class="w-full h-full object-cover transition group-hover:scale-[1.02]" loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('images/beranda/slide1.jpg') }}'">
              </div>
            </a>
          </div>

          <div class="lg:col-span-7">
            <div class="mb-2">
              <span class="inline-flex items-center px-3 py-1 rounded-md bg-[#F3E8E9] text-[#861D23] font-[Volkhov] text-[15px] leading-none shadow-sm">
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

            <h2 class="font-[Volkhov] font-bold text-[24px] md:text-[26px] leading-tight text-[#1524AF] mb-2">
              <a href="{{ $fIsModel ? route('berita.show', $fSlug) : ($fSlug) }}" class="hover:opacity-90 transition">
                {{ $fTitle }}
              </a>
            </h2>

            <p class="font-[Montserrat] text-[14.5px] md:text-[15px] text-slate-800 leading-relaxed mb-3 max-w-[60ch]">
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
      <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($others as $post)
          @php
            $isModel = is_object($post);
            $title = $isModel ? ($post->title ?? '—') : ($post['title'] ?? '—');
            $slugOrUrl = $isModel ? route('berita.show', $post->slug) : ($post['url'] ?? '#');

            $rawImg = $isModel ? ($post->image ?? null) : ($post['thumb'] ?? null);
            $imgUrl = force_storage_url($rawImg, asset('images/beranda/slide1.jpg'));

            $date = $isModel ? ($post->published_at ?? $post->created_at) : ($post['date'] ?? null);
            if ($date && is_object($date) && method_exists($date, 'setTimezone')) {
              $dateForDisplay = $date->setTimezone($tz)->translatedFormat('d F Y');
            } elseif (!empty($date)) {
              $dateForDisplay = Carbon::parse($date)->setTimezone($tz)->translatedFormat('d F Y');
            } else {
              $dateForDisplay = '-';
            }
            $excerpt = $isModel ? Str::limit(strip_tags($post->content ?? ''), 120) : ($post['excerpt'] ?? '');
          @endphp

          <article class="rounded-2xl border border-slate-200 bg-white p-3 sm:p-4 shadow-sm hover:shadow-md transition">
            <a href="{{ $slugOrUrl }}" class="block mb-3">
              <div class="aspect-[16/11] w-full rounded-xl border border-[#1524AF]/40 overflow-hidden">
                <img src="{{ $imgUrl }}" alt="{{ $title }}" class="w-full h-full object-cover hover:scale-[1.02] transition" loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('images/beranda/slide1.jpg') }}'">
              </div>
            </a>

            <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
              </svg>
              <span>{{ $dateForDisplay }}</span>
            </div>

            <h3 class="font-[Volkhov] text-[16px] sm:text-[18px] leading-snug text-slate-900 mb-2">
              <a href="{{ $slugOrUrl }}" class="hover:text-[#1524AF] transition">{{ $title }}</a>
            </h3>

            <p class="font-[Montserrat] text-[13px] sm:text-[14px] text-slate-700 mb-3">
              {!! $excerpt !!}
            </p>

            <a href="{{ $slugOrUrl }}" class="inline-flex items-center gap-2 text-[#1524AF] text-[13px] sm:text-[14px] hover:underline">
              Baca Selengkapnya
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </article>
        @endforeach
      </div>

      {{-- PAGINATION --}}
      <div class="mt-8 flex justify-center">
        @if($postsPaginator)
          @php
            $p = $postsPaginator;
            $current = $p->currentPage();
            $last = $p->lastPage();
            $start = max(1, $current - 3);
            $end = min($last, $current + 3);
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
