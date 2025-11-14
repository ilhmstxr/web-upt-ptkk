{{-- resources/views/pages/panduan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panduan - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

 <style>
  :root {
    --biru-brand: #1524AF;
    --kuning-stroke: #FFDE59;
    --merah-stroke: #861D23;
  }

  .yellow-stroke {
    text-shadow:
      -1px -1px 0 var(--kuning-stroke),
       1px -1px 0 var(--kuning-stroke),
      -1px  1px 0 var(--kuning-stroke),
       1px  1px 0 var(--kuning-stroke);
  }

  .oval-flow {
    width: 330px;
    height: 140px;
    background: #DBE7F7;
    border: 2px solid var(--biru-brand);
    border-radius: 50%;
  }

  /* LINGKARAN ANGKA STEP */
 .step-badge {
  width: 36px;
  height: 36px;
  border-radius: 9999px;
  background: linear-gradient(
    135deg,
    #0F5DC7 0%,     /* Biru tua full */
    #3F87DA 55%,    /* Biru mid (sesuai 55% di Figma) */
    #A9D4F7 100%    /* Biru muda cerah */
  );
  border: 3px solid #FFFFFF;
}

</style>
</head>

<body class="bg-[#FEFEFE] antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO --}}
  <x-layouts.app.profile-hero
    title="Panduan"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Panduan', 'route' => 'panduan'],
    ]"
    height="h-[320px]"
  />
  {{-- /HERO --}}

  {{-- =========================== --}}
  {{--   SECTION: ALUR PERMOHONAN  --}}
  {{-- =========================== --}}

  <section class="relative bg-white py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] text-center">

      <!-- BADGE -->
      <div class="inline-block bg-[#FDECEC] text-[#861D23] text-sm px-4 py-1.5 rounded-md mb-3 font-[Volkhov] font-bold">
        Alur Permohonan Peserta
      </div>

      <!-- TITLE (kecil + stroke kuning) -->
      <h2 class="text-[18px] md:text-[22px] lg:text-[24px] font-[Volkhov] font-bold
                 text-[#0E2A7B] yellow-stroke leading-snug mb-14">
        Proses Permohonan Peserta Untuk Mengikuti Program Pelatihan di<br class="hidden md:block">
        UPT PTKK
      </h2>

      <!-- DESKTOP CIRCLE LAYOUT -->
<div class="relative hidden md:block w-full h-[520px]">

  <!-- STEP 2 (TOP) -->
  <div class="absolute top-[0%] left-1/2 -translate-x-1/2">
    <div class="oval-flow flex flex-col items-center justify-center text-center px-6">
<div class="step-badge flex items-center justify-center text-white font-bold text-sm mb-1">
  2
</div>
      <h3 class="font-[Volkhov] font-bold text-[var(--biru-brand)] yellow-stroke text-[16px] leading-snug">
        Kepala Dinas Pendidikan<br>Prov. Jatim
      </h3>
      <p class="font-[Montserrat] font-medium text-[#000000] text-[12px] leading-tight mt-1">
        mendisposisi Surat Permohonan
      </p>
    </div>
  </div>

  <!-- STEP 1 (LEFT) -->
  <div class="absolute top-1/2 left-0 -translate-y-1/2">
    <div class="oval-flow flex flex-col items-center justify-center text-center px-6">
<div class="step-badge flex items-center justify-center text-white font-bold text-sm mb-1">
  1
</div>
      <h3 class="font-[Volkhov] font-bold text-[var(--biru-brand)] yellow-stroke text-[16px] leading-snug">
        Lembaga/Sekolah
      </h3>
      <p class="font-[Montserrat] font-medium text-[#000000] text-[12px] leading-tight mt-1">
        mengajukan Surat Permohonan<br>
        Pengajuan Peserta<br>
        Pelatihan
      </p>
    </div>
  </div>

  <!-- STEP 3 (RIGHT) -->
  <div class="absolute top-1/2 right-0 -translate-y-1/2">
    <div class="oval-flow flex flex-col items-center justify-center text-center px-6">
<div class="step-badge flex items-center justify-center text-white font-bold text-sm mb-1">
  3
</div>
      <h3 class="font-[Volkhov] font-bold text-[var(--biru-brand)] yellow-stroke text-[16px] leading-snug">
        UPT PTKK
      </h3>
      <p class="font-[Montserrat] font-medium text-[#000000] text-[12px] leading-tight mt-1">
        membuat Nota Dinas<br>
        pemanggilan peserta
      </p>
    </div>
  </div>

  <!-- STEP 4 (BOTTOM) -->
  <div class="absolute bottom-[0%] left-1/2 -translate-x-1/2">
    <div class="oval-flow flex flex-col items-center justify-center text-center px-6">
<div class="step-badge flex items-center justify-center text-white font-bold text-sm mb-1">
  4
</div>
      <h3 class="font-[Volkhov] font-bold text-[var(--biru-brand)] yellow-stroke text-[16px] leading-snug">
        Cabang Dinas
      </h3>
      <p class="font-[Montserrat] font-medium text-[#000000] text-[12px] leading-tight mt-1">
        menindaklanjuti Nota Dinas
      </p>
    </div>
  </div>

        <!-- PANAH 1 → 2 (di antara 1 dan 2) -->
        <div class="absolute top-[22%] left-[26%] md:left-[28%]">
          <svg class="w-9 h-9 text-[var(--biru-brand)] -rotate-20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </div>

        <!-- PANAH 2 → 3 (di antara 2 dan 3) -->
        <div class="absolute top-[22%] right-[26%] md:right-[28%]">
          <svg class="w-9 h-9 text-[var(--biru-brand)] rotate-20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </div>

        <!-- PANAH 3 → 4 (di antara 3 dan 4) -->
        <div class="absolute bottom-[22%] right-[26%] md:right-[28%]">
          <svg class="w-9 h-9 text-[var(--biru-brand)] rotate-[110deg]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </div>

        <!-- PANAH 4 → 1 (di antara 4 dan 1) -->
        <div class="absolute bottom-[22%] left-[26%] md:left-[28%]">
          <svg class="w-9 h-9 text-[var(--biru-brand)] rotate-[200deg]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </div>

      </div>

      <!-- MOBILE STACK -->
      <div class="md:hidden flex flex-col items-center gap-8 mt-4">
        @php
          $steps = [
            ['n'=>1,'t'=>'Lembaga/Sekolah','d'=>'mengajukan Surat Permohonan Pengajuan Peserta Pelatihan'],
            ['n'=>2,'t'=>'Kepala Dinas Pendidikan Prov. Jatim','d'=>'mendisposisi Surat Permohonan'],
            ['n'=>3,'t'=>'UPT PTKK','d'=>'membuat Nota Dinas pemanggilan peserta'],
            ['n'=>4,'t'=>'Cabang Dinas','d'=>'menindaklanjuti Nota Dinas'],
          ];
        @endphp

        @foreach($steps as $s)
          <div class="oval-flow flex flex-col items-center justify-center text-center px-6">
            <div class="w-9 h-9 rounded-full bg-[var(--biru-brand)] flex items-center justify-center text-white font-bold text-sm mb-1">
              {{ $s['n'] }}
            </div>
            <h3 class="font-[Volkhov] font-bold text-[var(--biru-brand)] yellow-stroke text-[17px] leading-snug">
              {{ $s['t'] }}
            </h3>
            <p class="font-[Montserrat] font-medium text-[#000000] text-[12px] leading-tight mt-1">
              {{ $s['d'] }}
            </p>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
