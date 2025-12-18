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

        /* Stroke judul */
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
    max-width: 100% !important;
}

.prose {
    overflow-wrap: anywhere;
    word-break: break-word;
    text-align: justify; /* ✅ RATA KANAN KIRI */
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

            /* Biar konten editor ga jebol layout */
            .prose {
                overflow-wrap: anywhere;
                word-break: break-word;
            }

            .prose img {
                max-width: 100% !important;
                height: auto !important;
                border-radius: 12px;
            }
        }
    </style>
</head>

<body class="bg-[#F1F9FC] antialiased">

@include('components.layouts.app.topbar')
@include('components.layouts.app.navbarlanding')

<main class="pt-6 md:pt-10 pb-16 bg-[#F1F9FC] min-h-screen">
    <div class="section-container">

        {{-- Layout Utama: Konten + Sidebar --}}
        <div class="flex flex-col md:flex-row max-w-6xl mx-auto gap-8">

            {{-- KIRI: Konten Artikel --}}
           <div class="md:w-3/4 bg-[#FFFFFF] p-8 md:p-10 rounded-2xl shadow-[0px_4px_24px_rgba(0,0,0,0.08)]">

                {{-- 1) Judul --}}
                <h1 class="font-[Volkhov] font-bold text-2xl md:text-3xl leading-tight mb-3 judul-stroke text-left">
                    {{ $post->title }}
                </h1>

                {{-- 2) Tanggal publish --}}
                <div class="text-sm font-medium text-gray-500 flex items-center gap-2 mb-5">
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

               {{-- 3) Foto utama (DINAMIS, tidak crop) --}}
<div class="w-full mb-3">
  <div class="w-full rounded-lg shadow-md border-[2px] border-[#B6BBE6] overflow-hidden bg-[#EAF2FF]">
    <img
      src="{{ $post->image ? Storage::url($post->image) : asset('images/placeholder_kunjungan.jpg') }}"
      alt="Cover Image: {{ $post->title }}"
      class="w-full h-auto object-contain"
      loading="lazy"
      decoding="async"
      onerror="this.onerror=null;this.src='{{ asset('images/placeholder_kunjungan.jpg') }}'"
    />
  </div>
</div>


                {{-- Caption foto dari admin (opsional) --}}
                @if(!empty($post->image_caption))
                    <p class="text-center font-[Montserrat] font-medium text-[14px] text-black mt-2 mb-6">
                        {{ $post->image_caption }}
                    </p>
                @else
                    <div class="mb-6"></div>
                @endif

                {{-- 5) Isi berita --}}
                <article class="prose max-w-none text-gray-700">
                    {!! $post->content !!}

<div class="flex items-center gap-4 mt-6">

  {{-- COPY LINK --}}
  <button
    onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berhasil disalin!');"
    class="w-11 h-11 flex items-center justify-center
           border-2 border-[#F2C94C]
           rounded-lg bg-white
           text-[#F2C94C] hover:bg-[#F2C94C] hover:text-white transition">
    {{-- SVG LINK ICON --}}
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
      <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
      <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
    </svg>
  </button>

  {{-- INSTAGRAM (Sudah Benar - SVG) --}}
  <a href="https://www.instagram.com/uptptkk.jatim/" target="_blank"
     class="w-11 h-11 flex items-center justify-center
            border-2 border-[#F2C94C]
            rounded-lg bg-white
            text-[#F2C94C] hover:bg-[#F2C94C] hover:text-white transition">
    <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
        <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 5a5 5 0 100 10 5 5 0 000-10zm6-1a1 1 0 100 2 1 1 0 000-2zm-6 3a3 3 0 110 6 3 3 0 010-6z"/>
    </svg>
  </a>

  {{-- WHATSAPP --}}
  <a href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}"
     target="_blank"
     class="w-11 h-11 flex items-center justify-center
            border-2 border-[#F2C94C]
            rounded-lg bg-white
            text-[#F2C94C] hover:bg-[#F2C94C] hover:text-white transition">
    {{-- SVG WHATSAPP ICON --}}
    <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
    </svg>
  </a>

</div>
</article>
            </div>

            {{-- KANAN: Sidebar Berita Lainnya --}}
           <div class="md:w-1/4">
                <div class="bg-[#FFFFFF] p-6 rounded-2xl shadow-[0px_4px_24px_rgba(0,0,0,0.08)] border border-gray-100 md:sticky md:top-4">

                    <h2 class="font-[Volkhov] font-bold text-[20px] mb-5 judul-stroke">
                        Berita Lainnya
                    </h2>

                    @if(!empty($relatedPosts) && count($relatedPosts))
                        @foreach($relatedPosts as $item)
                            <a href="{{ route('berita.show', $item->slug) }}" class="flex items-start gap-3 mb-5 group">
                                <div class="w-20 h-14 rounded-md overflow-hidden shrink-0 bg-gray-200">
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}"
                                             class="w-full h-full object-cover"
                                             alt="{{ $item->title }}">
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <p class="text-[14px] font-[Montserrat] font-medium text-[#000000] leading-snug line-clamp-2 group-hover:text-[#1524AF] transition">
                                        {{ $item->title }}
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
                                            {{ optional($item->published_at ?? $item->created_at)->format('d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
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

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Tautan berhasil disalin!');
    });
}
</script>

@include('components.layouts.app.footer')
</body>
</html>
