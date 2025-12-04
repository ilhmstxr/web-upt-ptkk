{{-- resources/views/pages/berita_show.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} – UPT PTKK</title>
    {{-- Menggunakan Tailwind CSS dari CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Mendefinisikan warna primer dan latar belakang */
        :root {
            --color-primary: #1524AF; /* Warna Biru UPT */
            --color-accent: #FFC107; /* Kuning (Amber) untuk Aksen */
            --color-bg-light: #F1F9FC; /* Latar Belakang */
        }

        /* Konfigurasi Prosa yang sesuai untuk konten yang dimuat dari editor (WYSIWYG) */
        @layer components {
            .prose {
                max-width: none; /* Biarkan lebar mengikuti container utama */
            }
            .prose h1, .prose h2, .prose h3, .prose h4 {
                color: #2c3e50; /* Warna Judul Konten lebih gelap */
                font-weight: 700;
                margin-top: 2rem;
                margin-bottom: 0.5rem;
            }
            .prose p {
                margin-top: 1em;
                margin-bottom: 1em;
                line-height: 1.75;
                text-align: justify;
            }
            .prose blockquote {
                border-left: 4px solid var(--color-primary); /* Menggunakan warna primary */
                padding-left: 1rem;
                color: #4b5563;
                font-style: italic;
                background-color: #f7f9ff;
            }
        }
    </style>
</head>

<body class="bg-white antialiased">
    
    @include('components.layouts.app.topbar')
    @include('components.layouts.app.navbarlanding')

    <main class="py-10 md:py-16 bg-[--color-bg-light] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Artikel (Gambar Utama dan Judul) --}}
            <header class="max-w-6xl mx-auto mb-8">
                {{-- Placeholder Gambar Tim Manajemen SMAN 1 Soko Tuban --}}
                <div class="w-full h-auto mb-4">
                    {{-- Menggunakan Storage::url untuk gambar yang diupload dari Filament --}}
                    <img src="{{ Storage::url($post->image) ?? asset('images/placeholder_kunjungan.jpg') }}"
                         alt="Cover Image: {{ $post->title }}"
                         class="w-full h-full object-cover rounded-lg shadow-md"
                         style="aspect-ratio: 16 / 6;"
                    />
                    <p class="text-xs text-gray-500 text-right mt-1">
                        {{-- Asumsi teks ini bisa dimasukkan ke dalam $post->image_credit --}}
                        Foto: Harpy Kumtum for Getty.com
                    </p>
                </div>

                {{-- Judul dan Metadata --}}
                <h1 class="text-3xl lg:text-4xl font-extrabold text-[--color-primary] leading-tight mb-4">
                    {{ $post->title }}
                </h1>
                
                <div class="text-sm font-medium text-gray-500 flex items-center space-x-4 mb-6 border-b pb-4">
                    <a href="{{ route('berita.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold">
                        <svg class="mr-1 w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Berita
                    </a>
                    <span>|</span>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 inline mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M3 15h18M5 9h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"></path></svg>
                        <span>{{ optional($post->published_at ?? $post->created_at)->format('d F Y') }}</span>
                    </div>
                </div>
            </header>
            
            {{-- Konten Utama (Kiri) dan Sidebar (Kanan) --}}
            <div class="flex flex-col lg:flex-row max-w-6xl mx-auto gap-8">
                
                {{-- Konten Artikel (Kiri) --}}
                <div class="lg:w-3/4 bg-white p-6 md:p-8 rounded-lg shadow-xl border-t-4 border-[--color-primary]">
                    <article class="prose max-w-none text-gray-700">
                        {!! $post->content !!}
                        
                        {{-- Contoh struktur Konten Lanjutan yang di inject (opsional, jika konten $post->content tidak mengandungnya) --}}
                        {{-- <h3 class="font-bold text-lg text-gray-800">Heading</h3> --}}
                        {{-- <p>...</p> --}}
                        
                        <p class="mt-8 font-semibold text-gray-800">Bagikan Berita ini</p>
                        {{-- Ikon Sosial Media menggunakan SVG yang lebih standar --}}
                        <div class="flex space-x-3 mt-2">
                            <a href="#" class="p-2 border rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition" title="Share on Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625H4.661V8.05H6.75V6.273c0-2.009 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.353 2.302h-1.865v5.625c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                            </a>
                            <a href="#" class="p-2 border rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition" title="Share on Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .491 6.344v.047a3.285 3.285 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.237 3.237 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045 9.344 9.344 0 0 0 5.026 1.459z"/></svg>
                            </a>
                            <a href="#" class="p-2 border rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition" title="Share on WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M13.602 2.316a7.518 7.518 0 0 0-10.742 0A7.518 7.518 0 0 0 0 8.001a7.518 7.518 0 0 0 2.316 5.366L0 16l2.634-2.634a7.518 7.518 0 0 0 5.366 2.316 7.518 7.518 0 0 0 5.366-2.316 7.518 7.518 0 0 0 2.316-5.366 7.518 7.518 0 0 0-2.316-5.366zM11.517 9.471a.636.636 0 0 1-.412.167c-.206 0-.348-.124-.48-.288-.133-.164-.294-.393-.453-.623-.158-.23-.332-.46-.464-.624a.636.636 0 0 0-.412-.167c-.206 0-.348-.124-.48-.288-.133-.164-.294-.393-.453-.623-.158-.23-.332-.46-.464-.624a.636.636 0 0 0-.412-.167c-.206 0-.348-.124-.48-.288-.133-.164-.294-.393-.453-.623-.158-.23-.332-.46-.464-.624z"/></svg>
                            </a>
                        </div>

                    </article>
                </div>

                {{-- Sidebar (Kanan) --}}
                <div class="lg:w-1/4">
                    {{-- Blok Berita Lainnya --}}
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 sticky top-4">
                        <h2 class="text-xl font-bold mb-4 border-b pb-2 text-[--color-primary]">Berita Lainnya</h2>
                        
                        {{-- Mengulang item Berita Lainnya (gunakan @foreach jika data tersedia) --}}
                        @for ($i = 1; $i <= 3; $i++)
                        <div class="flex items-start mb-4 pb-4 border-b border-gray-100 last:border-b-0">
                            <div class="w-16 h-12 bg-gray-200 mr-3 rounded shrink-0"></div>
                            <div>
                                <p class="text-sm font-semibold hover:text-gray-900 cursor-pointer line-clamp-2">Judul Berita {{ $i }} - Contoh Artikel Pendek</p>
                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M3 15h18M5 9h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"></path></svg>
                                    <span>{{ 24 - $i }} Oktober 2024</span>
                                </div>
                            </div>
                        </div>
                        @endfor
                        
                    </div>
                </div> {{-- End Sidebar --}}
            </div>
            
            {{-- Bagian Footer UPT PTKK (Bawah halaman) - Disempurnakan Tata Letaknya --}}
            
        </div>
    </main>

    @include('components.layouts.app.footer') 
</body>
</html>