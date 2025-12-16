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
        <div class="flex flex-col lg:flex-row max-w-6xl mx-auto gap-8">

            {{-- KIRI: Konten Artikel --}}
            <div class="lg:w-3/4 bg-[#FFFFFF] p-8 md:p-10 rounded-2xl shadow-[0px_4px_24px_rgba(0,0,0,0.08)]">

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

                {{-- 3) Foto utama --}}
                <div class="w-full mb-3">
                    <img
                        src="{{ $post->image ? Storage::url($post->image) : asset('images/placeholder_kunjungan.jpg') }}"
                        alt="Cover Image: {{ $post->title }}"
                        class="w-full object-cover rounded-lg shadow-md border-[2px] border-[#B6BBE6]"
                        style="aspect-ratio: 16 / 6;"
                        onerror="this.onerror=null;this.src='{{ asset('images/placeholder_kunjungan.jpg') }}'"
                    />
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
    onclick="navigator.clipboard.writeText(window.location.href)"
    class="w-11 h-11 flex items-center justify-center
           border-2 border-[#F2C94C]
           rounded-lg bg-white">
    <img src="{{ asset('images/icons/link.png') }}"
         class="w-5 h-5
                brightness-0
                invert
                sepia
                saturate-[500%]
                hue-rotate-[10deg]" />
  </button>

  {{-- INSTAGRAM --}}
  <a href="https://www.instagram.com/uptptkk.jatim/" target="_blank"
     class="w-11 h-11 flex items-center justify-center
            border-2 border-[#F2C94C]
            rounded-lg bg-white">
    <img src="{{ asset('images/icons/instagram.png') }}"
         class="w-5 h-5
                brightness-0
                invert
                sepia
                saturate-[500%]
                hue-rotate-[10deg]" />
  </a>

  {{-- WHATSAPP --}}
  <a href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}"
     target="_blank"
     class="w-11 h-11 flex items-center justify-center
            border-2 border-[#F2C94C]
            rounded-lg bg-white">
    <img src="{{ asset('images/icons/whatsapp.png') }}"
         class="w-5 h-5
                brightness-0
                invert
                sepia
                saturate-[500%]
                hue-rotate-[10deg]" />
  </a>

</div>
                </article>
            </div>

            {{-- KANAN: Sidebar Berita Lainnya --}}
            <div class="lg:w-1/4">
                <div class="bg-[#FFFFFF] p-6 rounded-2xl shadow-[0px_4px_24px_rgba(0,0,0,0.08)] border border-gray-100 sticky top-4">

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
