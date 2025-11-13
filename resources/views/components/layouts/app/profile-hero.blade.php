@props([
  'title' => 'Judul Halaman',
  'subtitle' => null,
  'crumbs' => [],
  'image' => 'images/profil/profil-upt.JPG',
  'overlay' => 'bg-[#1524AF]/60',  {{-- transparan biru --}}
  'height' => 'h-[368px]',          {{-- tinggi hero --}}
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
    @elseif(!empty($crumbs))
      <nav class="mt-2 font-[Montserrat] font-medium text-[#FFDE59] text-[18px] md:text-[20px] tracking-tight">
        @foreach($crumbs as $i => $c)
          @if(!empty($c['route']))
            <a href="{{ route($c['route']) }}" class="hover:underline">{{ $c['label'] }}</a>
          @else
            <span>{{ $c['label'] }}</span>
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
