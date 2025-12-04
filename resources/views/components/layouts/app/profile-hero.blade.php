@props([
  'title' => 'Judul Halaman',
  'subtitle' => null,
  'crumbs' => [],
  'image' => 'images/profil/profil-upt.JPG',
  'overlay' => 'bg-[#1524AF]/60', // transparan biru
  'height' => 'h-[368px]',        // tinggi hero
])

<section class="relative w-full {{ $height }} overflow-hidden flex items-center justify-center text-center">
  {{-- Background --}}
  <img src="{{ asset($image) }}" alt="" class="absolute inset-0 w-full h-full object-cover object-[center_50%]">
  <div class="absolute inset-0 {{ $overlay }}"></div>

  {{-- Konten Tengah --}}
  <div class="relative z-10 flex flex-col items-center justify-center">
    {{-- Judul Volkhov: warna putih + stroke merah --}}
    <h1 class="font-[Volkhov] font-bold text-[48px] md:text-[60px] lg:text-[72px]
               text-[#FEFEFE] drop-shadow-md leading-tight upt-stroke">
      {{ $title }}
    </h1>

    {{-- Subjudul: warna emas FFDE59 --}}
    @if($subtitle)
      <p class="mt-2 font-[Montserrat] font-medium text-[#FFDE59] text-[18px] md:text-[20px] tracking-tight">
        {{ $subtitle }}
      </p>

    {{-- Breadcrumbs (defensif: cek keberadaan route, dukung array route + params, fallback ke url atau plain text) --}}
    @elseif(!empty($crumbs))
      <nav class="mt-2 font-[Montserrat] font-medium text-[#FFDE59] text-[18px] md:text-[20px] tracking-tight">
        @foreach($crumbs as $i => $c)
          @php
            // standar: $c bisa berisi ['label'=>'...', 'route'=>'name'] atau ['label'=>'...','route'=>['name', $params]] atau ['label'=>'...','url'=>'/path']
            $label = $c['label'] ?? '';
            $routeSpec = $c['route'] ?? null;
            $urlFallback = $c['url'] ?? null;
            $href = null;

            if ($routeSpec !== null) {
                // jika routeSpec adalah array: ['route.name', $params]
                if (is_array($routeSpec) && count($routeSpec) > 0) {
                    $routeName = $routeSpec[0];
                    $routeParams = $routeSpec[1] ?? [];
                    if (\Illuminate\Support\Facades\Route::has($routeName)) {
                        try {
                            $href = route($routeName, $routeParams);
                        } catch (\Exception $e) {
                            $href = null;
                        }
                    }
                } elseif (is_string($routeSpec)) {
                    if (\Illuminate\Support\Facades\Route::has($routeSpec)) {
                        try {
                            $href = route($routeSpec);
                        } catch (\Exception $e) {
                            $href = null;
                        }
                    }
                }
            }

            // jika belum dapat href dari route, pakai url fallback bila ada
            if (!$href && !empty($urlFallback)) {
                $href = url($urlFallback);
            }
          @endphp

          @if($href)
            <a href="{{ $href }}" class="hover:underline">{{ $label }}</a>
          @else
            <span>{{ $label }}</span>
          @endif

          @if($i < count($crumbs) - 1)
            <span class="mx-2 text-[#FFDE59]">›</span>
          @endif
        @endforeach
      </nav>
    @endif
  </div>

  {{-- Style kustom stroke merah --}}
  <style>
    .upt-stroke {
      text-shadow:
        -1px -1px 0 #861D23,
         1px -1px 0 #861D23,
        -1px  1px 0 #861D23,
         1px  1px 0 #861D23;
    }
  </style>
</section>
