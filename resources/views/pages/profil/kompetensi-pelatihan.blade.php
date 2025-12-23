{{-- resources/views/pages/profil/bidang-kompetensi.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kompetensi Pelatihan - UPT PTKK Jawa Timur</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Judul: Fill Biru, Stroke Kuning */
        .judul-kompetensi {
            color: #1524AF;
            -webkit-text-stroke: 1px #FFDE59;
            paint-order: stroke fill;
        }
    </style>
</head>

<body class="bg-[#F1F9FC] antialiased" x-data="{ open: false, img: '', title: '', desc: '' }">

    @include('components.layouts.app.topbar')
    @include('components.layouts.app.navbarlanding')

    <x-layouts.app.profile-hero
        title="Bidang Kompetensi"
        :crumbs="[['label' => 'Beranda', 'route' => 'landing'], ['label' => 'Profil'], ['label' => 'Bidang Kompetensi']]"
        image="images/profil/profil-upt.JPG"
        height="h-[368px]"
    />

    @php
        $listKompetensi = $listKompetensi ?? collect();
        // Bagi data untuk 2 kolom (Tablet & Desktop)
        $leftCol  = $listKompetensi->filter(fn ($_, $i) => $i % 2 === 0)->values();
        $rightCol = $listKompetensi->filter(fn ($_, $i) => $i % 2 === 1)->values();
    @endphp

    <section class="w-full bg-[#F1F9FC] py-6 md:py-10">
        <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

            {{-- Grid Wrapper: HP (1 kolom), Tablet/Desktop (2 kolom) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">

                {{-- KOLOM KIRI --}}
                <div class="space-y-6 md:space-y-8">
                    @forelse ($leftCol as $item)
                        @php $img = $item->gambar ? asset('storage/'.$item->gambar) : asset('images/profil/default-bidang.svg'); @endphp
                        <article class="relative group overflow-hidden rounded-2xl shadow-md border border-[#1524AF] h-72 md:h-80 lg:h-96">
                            <button type="button" class="block w-full h-full text-left"
                                @click="open=true; img=@js($img); title=@js($item->nama_kompetensi); desc=@js($item->deskripsi);">
                                <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 cursor-zoom-in">
                            </button>
                            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to top, rgba(21, 36, 175, 0.95) 5%, rgba(21, 36, 175, 0.4) 40%, transparent 100%);"></div>
                            <div class="absolute inset-0 flex flex-col justify-end p-5 md:p-6 space-y-2 text-white pointer-events-none">
                                <h3 class="font-[Volkhov] font-bold text-[18px] md:text-[22px] judul-kompetensi">
                                    {{ $item->nama_kompetensi }}
                                </h3>
                                <p class="text-[11px] md:text-[12px] font-[Montserrat] text-justify leading-snug opacity-95 line-clamp-3 md:line-clamp-4">
                                    {{ $item->deskripsi }}
                                </p>
                            </div>
                        </article>
                    @empty
                        <p class="text-center font-[Montserrat] md:col-span-2">Belum ada data kompetensi.</p>
                    @endforelse
                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-6 md:space-y-8">
                    @foreach ($rightCol as $item)
                        @php $img = $item->gambar ? asset('storage/'.$item->gambar) : asset('images/profil/default-bidang.svg'); @endphp
                        <article class="relative group overflow-hidden rounded-2xl shadow-md border border-[#1524AF] h-72 md:h-80 lg:h-96">
                            <button type="button" class="block w-full h-full text-left"
                                @click="open=true; img=@js($img); title=@js($item->nama_kompetensi); desc=@js($item->deskripsi);">
                                <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 cursor-zoom-in">
                            </button>
                            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to top, rgba(21, 36, 175, 0.95) 5%, rgba(21, 36, 175, 0.4) 40%, transparent 100%);"></div>
                            <div class="absolute inset-0 flex flex-col justify-end p-5 md:p-6 space-y-2 text-white pointer-events-none">
                                <h3 class="font-[Volkhov] font-bold text-[18px] md:text-[22px] judul-kompetensi">
                                    {{ $item->nama_kompetensi }}
                                </h3>
                                <p class="text-[11px] md:text-[12px] font-[Montserrat] text-justify leading-snug opacity-95 line-clamp-3 md:line-clamp-4">
                                    {{ $item->deskripsi }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    {{-- MODAL PREVIEW (Menampilkan data yang sama persis, tapi teks Full) --}}
    <div x-show="open" x-cloak x-transition class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 px-4">
        <div class="absolute inset-0" @click="open=false"></div>

        <div class="relative bg-white rounded-2xl max-w-4xl w-full overflow-hidden z-10 flex flex-col md:flex-row max-h-[90vh] shadow-2xl">
            {{-- Tombol Close --}}
            <button @click="open=false" class="absolute top-4 right-4 bg-red-600 text-white w-9 h-9 rounded-full z-20 flex items-center justify-center text-2xl hover:bg-red-700 transition shadow-lg">&times;</button>

            {{-- Foto yang diklik --}}
            <div class="md:w-1/2 bg-gray-200">
                <img :src="img" class="w-full h-full object-cover">
            </div>

            {{-- Detail Teks (Tidak dipotong/Line-clamp) --}}
            <div class="md:w-1/2 p-8 overflow-y-auto no-scrollbar bg-white">
                <h2 class="font-[Volkhov] font-bold text-2xl text-[#1524AF] mb-4 judul-kompetensi" x-text="title"></h2>
                <div class="border-t border-gray-100 pt-4">
                    <p class="font-[Montserrat] text-gray-700 text-justify leading-relaxed whitespace-pre-line text-sm md:text-base" x-text="desc"></p>
                </div>
            </div>
        </div>
    </div>

    @include('components.layouts.app.footer')
        @stack('scripts')
</body>
</html>
