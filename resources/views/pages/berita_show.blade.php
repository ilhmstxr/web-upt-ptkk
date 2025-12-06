
{{-- resources/views/pages/berita_show.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} – UPT PTKK</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Fonts (Volkhov & Montserrat) --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

   <style>
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

  :root {
    --color-primary: #1524AF;
    --color-accent: #FFDE59;
    --color-bg-light: #F1F9FC;
  }

  /* 🔹 Stroke judul */
  .judul-stroke {
    color: var(--color-primary);
    text-shadow:
      -1px -1px 0 var(--color-accent),
       1px -1px 0 var(--color-accent),
      -1px  1px 0 var(--color-accent),
       1px  1px 0 var(--color-accent);
  }

 @layer components {
  .prose,
  .prose * {
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 500 !important;
    color: #000000 !important;
    letter-spacing: normal !important;
    line-height: 1.7 !important;
  }

  /* Heading konten tetap Volkhov */
  .prose h1,
  .prose h2,
  .prose h3,
  .prose h4 {
    font-family: 'Volkhov', serif !important;
    font-weight: 700 !important;
    color: #2c3e50 !important;
    margin-top: 1.5rem;
    margin-bottom: .5rem;
  }

  /* Link dalam artikel */
  .prose a {
    color: var(--color-primary) !important;
    font-weight: 600 !important;
    text-decoration: none !important;
  }
}

</style>
</head>

<body class="bg-[#F1F9FC] antialiased">

    @include('components.layouts.app.topbar')
    @include('components.layouts.app.navbarlanding')

  <main class="pt-6 md:pt-10 pb-16 bg-[#F1F9FC] min-h-screen">
      <div class="section-container">

        {{-- Header Artikel: hanya gambar + caption --}}
        <header class="max-w-6xl mx-auto mb-8">
          <div class="w-full h-auto mb-2">
            {{-- Gambar dari Storage atau fallback --}}
           <img
  src="{{ $post->image ? Storage::url($post->image) : asset('images/placeholder_kunjungan.jpg') }}"
  alt="Cover Image: {{ $post->title }}"
  class="w-full h-full object-cover rounded-lg shadow-md border-[2px] border-[#B6BBE6]"
  style="aspect-ratio: 16 / 6;"
/>
          </div>

          {{-- Caption foto dari admin (opsional) --}}
          @if(!empty($post->image_caption))
            <p class="text-center font-[Montserrat] font-medium text-[14px] text-black mt-2 mb-4">
              {{ $post->image_caption }}
            </p>
          @endif
        </header>

        {{-- Layout Utama: Konten + Sidebar --}}
        <div class="flex flex-col lg:flex-row max-w-6xl mx-auto gap-8">

        {{-- KIRI: Konten Artikel --}}
<div class="lg:w-3/4 bg-[#FFFFFF] p-8 md:p-10 rounded-2xl shadow-[0px_4px_24px_rgba(0,0,0,0.08)]">

            {{-- Judul + Metadata di dalam layout kiri --}}
           <h1 class="font-[Volkhov] font-bold text-2xl md:text-3xl leading-tight mb-3 judul-stroke text-left">
  {{ $post->title }}
</h1>

           <div class="text-sm font-medium text-gray-500 flex items-center gap-2 mb-6">
    <svg class="w-4 h-4 text-[#727272]" fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
    </svg>

    <span class="text-[#727272]">
        {{ optional($post->published_at ?? $post->created_at)->format('d F Y') }}
    </span>
</div>

           <article class="prose max-w-none text-gray-700 -mt-3">
              {!! $post->content !!}

              <p class="mt-8 font-[Volkhov] font-bold text-[18px] text-[#081526]">
    Bagikan Berita ini
</p>
              <div class="flex space-x-3 mt-2">
                <a href="#"
                   class="p-2 border rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                   title="Share on Facebook">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                       fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625H4.661V8.05H6.75V6.273c0-2.009 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.353 2.302h-1.865v5.625c3.824-.604 6.75-3.934 6.75-7.951z"/>
                  </svg>
                </a>

                <a href="#"
                   class="p-2 border rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                   title="Share on Twitter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                       fill="currentColor" viewBox="0 0 16 16">
                    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .491 6.344v.047a3.285 3.285 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.237 3.237 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045 9.344 9.344 0 0 0 5.026 1.459z"/>
                  </svg>
                </a>

               <a href="#"
   class="p-2 border rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition flex items-center justify-center"
   title="Bagikan ke Instagram">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-4 h-4"
         fill="currentColor"
         viewBox="0 0 24 24">

      <path d="M7.75 2A5.76 5.76 0 002 7.75v8.5A5.76 5.76 0 007.75 22h8.5A5.76 5.76 0 0022 16.25v-8.5A5.76 5.76 0 0016.25 2h-8.5zm8.5 1.5c2.36 0 3.75 1.39 3.75 3.75v8.5c0 2.36-1.39 3.75-3.75 3.75h-8.5c-2.36 0-3.75-1.39-3.75-3.75v-8.5C4 4.89 5.39 3.5 7.75 3.5h8.5zM12 7a5 5 0 015 5 5 5 0 01-10 0 5 5 0 015-5zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm4.75-.75a1 1 0 11-2 0 1 1 0 012 0z"/>

    </svg>
</a>
              </div>
            </article>
          </div>

        {{-- KANAN: Sidebar Berita Lainnya --}}
<div class="lg:w-1/4">
  <div class="bg-[#FFFFFF] p-6 rounded-2xl shadow-[0px_4px_24px_rgba(0,0,0,0.08)] border border-gray-100 sticky top-4">

    {{-- Judul Sidebar --}}
    <h2 class="font-[Volkhov] font-bold text-[20px] mb-5 judul-stroke">
      Berita Lainnya
    </h2>

    {{-- Versi dinamis: berita dari admin --}}
    @if(!empty($relatedPosts) && count($relatedPosts))
      @foreach($relatedPosts as $item)
        <a href="{{ route('berita.show', $item->slug) }}" class="flex items-start gap-3 mb-5 group">

          {{-- Thumbnail --}}
          <div class="w-20 h-14 rounded-md overflow-hidden shrink-0 bg-gray-200">
            @if($item->image)
              <img src="{{ Storage::url($item->image) }}"
                   class="w-full h-full object-cover"
                   alt="{{ $item->title }}">
            @endif
          </div>

          <div class="flex-1">
            {{-- Judul --}}
            <p class="text-[14px] font-[Montserrat] font-medium text-[#000000] leading-snug line-clamp-2 group-hover:text-[#1524AF] transition">
              {{ $item->title }}
            </p>

            {{-- Tanggal --}}
            <div class="flex items-center text-xs mt-1 gap-1">
              <svg class="w-3 h-3 text-[#727272]" fill="none" stroke="currentColor" stroke-width="2"
                   viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8"  y1="2" x2="8"  y2="6"/>
                <line x1="3"  y1="10" x2="21" y2="10"/>
              </svg>

              <span class="text-[#727272]">
                {{ optional($item->published_at ?? $item->created_at)->format('d F Y') }}
              </span>
            </div>
          </div>

        </a>
      @endforeach

    @else
      {{-- Dummy fallback --}}
      @for ($i = 1; $i <= 3; $i++)
        <div class="flex items-start gap-3 mb-5">
          <div class="w-20 h-14 rounded-md bg-gray-200"></div>
          <div>
            <p class="text-[14px] font-[Montserrat] font-medium text-[#000000] leading-snug line-clamp-2">
              Judul Berita {{ $i }} - Contoh Artikel Pendek
            </p>
            <div class="flex items-center text-xs mt-1 gap-1">
              <svg class="w-3 h-3 text-[#727272]" fill="none" stroke="currentColor" stroke-width="2"
                   viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8"  y1="2" x2="8"  y2="6"/>
                <line x1="3"  y1="10" x2="21" y2="10"/>
              </svg>
              <span class="text-[#727272]">
                {{ 24 - $i }} Oktober 2024
              </span>
            </div>
          </div>
        </div>
      @endfor
    @endif

  </div>
</div>

        </div>

      </div>
    </main>

    @include('components.layouts.app.footer')
</body>
</html>
