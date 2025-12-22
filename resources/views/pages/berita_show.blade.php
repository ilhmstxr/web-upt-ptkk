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
        /* =========================
   FIX LIST (ANGKA & BULLET)
   UNTUK KONTEN BERITA
========================= */
.prose ol {
  list-style: decimal !important;
  padding-left: 1.5rem !important;
  margin-top: .5rem !important;
  margin-bottom: .5rem !important;
}

.prose ul {
  list-style: disc !important;
  padding-left: 1.5rem !important;
  margin-top: .5rem !important;
  margin-bottom: .5rem !important;
}

.prose li {
  margin-top: .25rem !important;
}

/* Nested list (jika ada) */
.prose ul ul { list-style: circle !important; }
.prose ol ol { list-style: lower-alpha !important; }

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

                {{-- 5 Isi berita --}}
                <article class="prose max-w-none text-gray-700">
                    {!! $post->content !!}
                </article>

                {{-- AREA TOMBOL SHARE --}}
                {{-- FIX: Hapus class 'flex' di sini agar elemen menumpuk ke bawah (Block) --}}
                <div class="mt-8">

                    {{-- 1. JUDUL: Otomatis di ATAS karena parent-nya bukan flex row --}}
                    <h3 class="font-['Volkhov'] font-bold text-[#0E2A7B] text-[20px] mb-4 judul-stroke">
                        Bagikan Berita Ini
                    </h3>

                    {{-- 2. KONTAINER IKON: Flex Row (Agar ikon-ikonnya berjajar ke samping) --}}
                    <div class="flex items-center gap-4">

                        {{-- TOMBOL: COPY LINK --}}
                        <button
                            onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berhasil disalin!');"
                            class="w-12 h-12 flex items-center justify-center
                                   border-2 border-[#F2C94C]
                                   rounded-lg bg-white
                                   text-[#F2C94C] hover:bg-[#F2C94C] hover:text-white transition"
                            title="Salin Tautan">
                            <svg viewBox="0 0 36 36" fill="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <path d="M9.8535 3.375H19.3965C20.3025 3.375 21.0495 3.375 21.6585 3.4245C22.29 3.477 22.872 3.588 23.418 3.8655C24.2647 4.29692 24.9531 4.98531 25.3845 5.832C25.6635 6.378 25.7745 6.96 25.8255 7.5915C25.875 8.2005 25.875 8.9475 25.875 9.8535V10.125H26.475C27.2295 10.125 27.852 10.125 28.3605 10.1595C28.8885 10.1955 29.3775 10.2735 29.847 10.467C30.3931 10.6931 30.8894 11.0247 31.3073 11.4427C31.7253 11.8606 32.0568 12.3569 32.283 12.903C32.478 13.3725 32.553 13.8615 32.5905 14.388C32.625 14.898 32.625 15.5205 32.625 16.2735V16.3515C32.625 17.1045 32.625 17.727 32.5905 18.2355C32.5689 18.7221 32.4733 19.2026 32.307 19.6605C31.4419 22.6092 29.8614 25.2984 27.706 27.4888C25.5507 29.6792 22.8874 31.3029 19.953 32.2155L19.6695 32.304C19.2095 32.474 18.7255 32.5702 18.2355 32.589C17.7255 32.625 17.1045 32.625 16.3515 32.625H16.2735C15.5205 32.625 14.898 32.625 14.3895 32.5905C13.8615 32.5545 13.3725 32.4765 12.903 32.283C12.3569 32.0568 11.8606 31.7253 11.4427 31.3073C11.0247 30.8894 10.6931 30.3931 10.467 29.847C10.272 29.3775 10.197 28.8885 10.1595 28.362C10.125 27.852 10.125 27.2295 10.125 26.4765V25.875H9.855C8.949 25.875 8.2005 25.875 7.5915 25.8255C6.96 25.773 6.378 25.6635 5.832 25.3845C4.98531 24.9531 4.29692 24.2647 3.8655 23.418C3.588 22.872 3.4755 22.29 3.4245 21.6585C3.375 21.0495 3.375 20.3025 3.375 19.3965V9.8535C3.375 8.9475 3.375 8.2005 3.4245 7.5915C3.477 6.96 3.588 6.378 3.8655 5.832C4.29692 4.98531 4.98531 4.29692 5.832 3.8655C6.378 3.588 6.96 3.4755 7.5915 3.4245C8.2005 3.375 8.9475 3.375 9.8535 3.375ZM12.375 26.4375C12.375 27.2385 12.375 27.7845 12.405 28.2075C12.432 28.6215 12.4845 28.836 12.546 28.986C12.6591 29.2591 12.8248 29.5072 13.0338 29.7162C13.2428 29.9252 13.4909 30.0909 13.764 30.204C13.914 30.2655 14.1285 30.318 14.5425 30.345C14.9655 30.375 15.5115 30.375 16.3125 30.375C17.1135 30.375 17.6595 30.375 18.0825 30.345C18.4965 30.318 18.711 30.2655 18.861 30.204C19.1341 30.0909 19.3822 29.9252 19.5912 29.7162C19.8002 29.5072 19.9659 29.2591 20.079 28.986C20.1405 28.836 20.1915 28.6215 20.22 28.2075C20.25 27.7845 20.25 27.2385 20.25 26.4375V26.3985C20.25 25.6455 20.25 25.023 20.2845 24.5145C20.3205 23.9865 20.3985 23.4975 20.592 23.028C20.8181 22.4819 21.1497 21.9856 21.5677 21.5677C21.9856 21.1497 22.4819 20.8181 23.028 20.592C23.4975 20.397 23.9865 20.322 24.513 20.2845C25.023 20.25 25.6455 20.25 26.3985 20.25H26.4375C27.2385 20.25 27.7845 20.25 28.2075 20.22C28.6215 20.1915 28.836 20.1405 28.986 20.079C29.2591 19.9659 29.5072 19.8002 29.7162 19.5912C29.9252 19.3822 30.0909 19.1341 30.204 18.861C30.2655 18.711 30.318 18.4965 30.345 18.0825C30.375 17.6595 30.375 17.1135 30.375 16.3125C30.375 15.5115 30.375 14.9655 30.345 14.5425C30.318 14.1285 30.2655 13.914 30.204 13.764C30.0909 13.4909 29.9252 13.2428 29.7162 13.0338C29.5072 12.8248 29.2591 12.6591 28.986 12.546C28.836 12.4845 28.6215 12.432 28.2075 12.405C27.7845 12.375 27.2385 12.375 26.4375 12.375H16.65C15.687 12.375 15.03 12.375 14.5245 12.417C14.031 12.4575 13.779 12.531 13.6035 12.6195C13.1798 12.8353 12.8353 13.1798 12.6195 13.6035C12.5295 13.779 12.4575 14.031 12.417 14.5245C12.3765 15.0315 12.375 15.687 12.375 16.65V26.4375Z"/>
                            </svg>
                        </button>

                        {{-- TOMBOL: INSTAGRAM --}}
                        <a href="https://www.instagram.com/uptptkk.jatim/" target="_blank"
                           class="w-12 h-12 flex items-center justify-center
                                  border-2 border-[#F2C94C]
                                  rounded-lg bg-white
                                  text-[#F2C94C] hover:bg-[#F2C94C] hover:text-white transition"
                           title="Instagram">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 5a5 5 0 100 10 5 5 0 000-10zm6-1a1 1 0 100 2 1 1 0 000-2zm-6 3a3 3 0 110 6 3 3 0 010-6z"/>
                            </svg>
                        </a>

                        {{-- TOMBOL: WHATSAPP --}}
                        <a href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}"
                           target="_blank"
                           class="w-12 h-12 flex items-center justify-center
                                  border-2 border-[#F2C94C]
                                  rounded-lg bg-white
                                  text-[#F2C94C] hover:bg-[#F2C94C] hover:text-white transition"
                           title="WhatsApp">
                            <svg viewBox="0 0 30 30" fill="currentColor" class="w-6 h-6 fill-current">
                                <path d="M15.001 2.5C21.9047 2.5 27.501 8.09625 27.501 15C27.501 21.9037 21.9047 27.5 15.001 27.5C12.7919 27.5038 10.6217 26.9192 8.7135 25.8063L2.506 27.5L4.196 21.29C3.08219 19.3812 2.49712 17.21 2.501 15C2.501 8.09625 8.09725 2.5 15.001 2.5ZM10.741 9.125L10.491 9.135C10.3294 9.14614 10.1714 9.18859 10.026 9.26C9.89046 9.33689 9.7667 9.43287 9.6585 9.545C9.5085 9.68625 9.4235 9.80875 9.33225 9.9275C8.8699 10.5286 8.62096 11.2666 8.62475 12.025C8.62725 12.6375 8.78725 13.2338 9.03725 13.7913C9.5485 14.9187 10.3897 16.1125 11.4997 17.2188C11.7672 17.485 12.0297 17.7525 12.3122 18.0013C13.6915 19.2155 15.3351 20.0912 17.1122 20.5588L17.8222 20.6675C18.0535 20.68 18.2847 20.6625 18.5172 20.6512C18.8812 20.6321 19.2366 20.5335 19.5585 20.3625C19.7221 20.2779 19.8818 20.1862 20.0372 20.0875C20.0372 20.0875 20.0902 20.0517 20.1935 19.975C20.3622 19.85 20.466 19.7612 20.606 19.615C20.711 19.5067 20.7985 19.3808 20.8685 19.2375C20.966 19.0338 21.0635 18.645 21.1035 18.3212C21.1335 18.0737 21.1247 17.9388 21.121 17.855C21.116 17.7213 21.0047 17.5825 20.8835 17.5238L20.156 17.1975C20.156 17.1975 19.0685 16.7238 18.4035 16.4213C18.3339 16.3909 18.2593 16.3736 18.1835 16.37C18.098 16.3611 18.0115 16.3706 17.93 16.398C17.8485 16.4254 17.7738 16.47 17.711 16.5287C17.7047 16.5262 17.621 16.5975 16.7172 17.6925C16.6654 17.7622 16.5939 17.8149 16.512 17.8438C16.4301 17.8728 16.3414 17.8767 16.2572 17.855C16.1758 17.8333 16.096 17.8057 16.0185 17.7725C15.8635 17.7075 15.8097 17.6825 15.7035 17.6375C14.9858 17.3249 14.3215 16.9018 13.7347 16.3838C13.5772 16.2463 13.431 16.0962 13.281 15.9513C12.7893 15.4803 12.3607 14.9475 12.006 14.3663L11.9322 14.2475C11.8801 14.1672 11.8373 14.0813 11.8047 13.9913C11.7572 13.8075 11.881 13.66 11.881 13.66C11.881 13.66 12.1847 13.3275 12.326 13.1475C12.4635 12.9725 12.5797 12.8025 12.6547 12.6813C12.8022 12.4438 12.8485 12.2 12.771 12.0113C12.421 11.1562 12.0593 10.3058 11.686 9.46C11.6122 9.2925 11.3935 9.1725 11.1947 9.14875C11.1272 9.14042 11.0597 9.13375 10.9922 9.12875C10.8244 9.11912 10.6561 9.12079 10.4885 9.13375L10.741 9.125Z"/>
                            </svg>
                        </a>

                    </div>
                </div>
              </div>

            {{-- KANAN: Sidebar Berita Lainnya --}}
           <div class="md:w-1/4">
                <div class="bg-[#FFFFFF] p-6 rounded-2xl shadow-[0px_4px_24px_rgba(0,0,0,0.08)]
                            border border-gray-100 md:sticky md:top-28">
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
