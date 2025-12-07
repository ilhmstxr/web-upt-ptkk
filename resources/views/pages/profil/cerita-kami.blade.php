{{-- resources/views/pages/profil/cerita-kami.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cerita Kami - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

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

    /* Stroke teks utilitas */
    .stroke-yellow{
      text-shadow:
        -1px -1px 0 var(--kuning-stroke),
         1px -1px 0 var(--kuning-stroke),
        -1px  1px 0 var(--kuning-stroke),
         1px  1px 0 var(--kuning-stroke);
    }
    .stroke-red{
      text-shadow:
        -1px -1px 0 var(--merah-stroke),
         1px -1px 0 var(--merah-stroke),
        -1px  1px 0 var(--merah-stroke),
         1px  1px 0 var(--merah-stroke);
    }

    /* Shadow bertingkat (5 lapis) */
    .shadow-5x{
      box-shadow:
        0 1px 3px rgba(0,0,0,0.05),
        0 2px 6px rgba(0,0,0,0.07),
        0 4px 10px rgba(0,0,0,0.09),
        0 6px 14px rgba(0,0,0,0.11),
        0 8px 20px rgba(0,0,0,0.13);
    }

    /* Kartu Tujuan & Manfaat */
   /* Kartu Tujuan – dasar */
.tujuan-card{
  position: relative;
  background: #F1F9FC;
  border-radius: 1rem;
  border: 1px solid #E0E7FF;
  box-shadow:
    0 2px 4px rgba(0,0,0,.04),
    0 10px 20px rgba(0,0,0,.08);
  overflow: visible;  /* biarkan lingkaran keluar 1/4 */
  transition:
    transform 220ms ease,
    box-shadow 220ms ease,
    border-color 220ms ease,
    background-color 220ms ease;
}

/* SHAPE biru di DALAM kartu, cuma bagian bawah */
/* SHAPE biru full di bawah, ngikut bentuk kartu */
.tujuan-card::after{
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  height: 10px;
  background: linear-gradient(90deg,#0E65CC 0%,#01A0F6 50%,#0C69CF 100%);
  border-radius: 0 0 1rem 1rem;   /* sudut bawah ikut kartu */
  opacity: 0;
  transform: translateY(100%);
  transition:
    opacity 220ms ease,
    transform 220ms ease;
}

.tujuan-card:hover{
  transform: translateY(-6px);
  border-color: #0E65CC;
  background-color: #E8F3FF;
  box-shadow:
    0 4px 8px rgba(0,0,0,.05),
    0 22px 45px rgba(0,0,0,.14);
}

.tujuan-card:hover::after{
  opacity: 1;
  transform: translateY(0);
}

/* Badge angka – membesar sedikit saat hover */
/* Posisi default: agak masuk ke dalam */
.tujuan-badge{
  position: absolute;
  top: 0;          /* tepat di tepi atas */
  right: 0;        /* tepat di tepi kanan */
  width: 45px;
  height: 45px;
  border-radius: 999px;
  background: linear-gradient(135deg,#0B4695 0%,#5E96E3 49%,#0B4695 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #FFFFFF;
  font-family: 'Montserrat', system-ui, -apple-system, sans-serif;
  font-weight: 600;
  font-size: 16px;
  box-shadow: 0 2px 6px rgba(0,0,0,.18);
  z-index: 20;
  transform: translate(35%, -35%);  /* 3/4 di dalam, 1/4 di luar */
  transition:
    transform 240ms cubic-bezier(0.19, 1, 0.22, 1),
    box-shadow 240ms cubic-bezier(0.19, 1, 0.22, 1);
}


/* Hover: sedikit membesar dan “masuk” ke dalam dikit */
.tujuan-card:hover .tujuan-badge{
  transform: translate(35%, -35%) scale(1.16);  /* tetap di pojok yang sama, cuma membesar */
  box-shadow: 0 8px 18px rgba(0,0,0,.25);
}


/* Icon – miring dikit ke kanan */
.tujuan-icon{
  transition: transform 220ms ease;
}
.tujuan-card:hover .tujuan-icon{
  transform: rotate(7deg) translateY(-2px);
}

.tujuan-text{
  transition: transform 220ms ease;
}
.tujuan-card:hover .tujuan-text{
  transform: scale(1.03);
}

  .card-manfaat{
  background: var(--card-manfaat);
  height: 250px;
  border-radius: 1rem;
}

/* ==== RAMPELIN LEBAR KHUSUS TABLET ==== */
@media (min-width: 768px) and (max-width: 1023.98px) {
  .card-manfaat {
    max-width: 230px;   /* bikin lebih ramping */
    margin-left: auto;
    margin-right: auto;
  }
}


    /* =======================================================
   GLOBAL SECTION LAYOUT CONSISTENCY
   ======================================================= */

/* Padding horizontal sama untuk semua section */
.section-container {
  max-width: 1280px; /* setara max-w-7xl */
  margin-left: auto;
  margin-right: auto;
  padding-left: 1.5rem;   /* px-6 */
  padding-right: 1.5rem;  /* px-6 */
}

@media (min-width: 768px) {
  .section-container {
    padding-left: 3rem;   /* md:px-12 */
    padding-right: 3rem;
  }
}

@media (min-width: 1024px) {
  .section-container {
    padding-left: 80px;   /* lg:px-[80px] */
    padding-right: 80px;
  }
}

/* === Baru: kompak 30px vertikal untuk semua section === */
.section-compact {
  padding-top: 30px !important;
  padding-bottom: 30px !important;
}

/* === Baru: jarak antar section 30px === */
section + section {
  margin-top: 30px !important;
}

/* ================= TIMELINE SEJARAH ================= */

/* Default (desktop & tablet) – posisi diatur lewat class Tailwind di HTML */
.timeline-container {
  position: relative;
}

/* Tablet: tinggi sedikit lebih besar, gambar agak mengecil */
@media (min-width: 768px) and (max-width: 1023.98px) {
  .timeline-container {
    height: 1700px;
  }

  .sejarah-img {
    width: 260px !important;
  }
}

/* Desktop: gambar dibesarkan */
@media (min-width: 1024px) {
  .sejarah-img {
    width: 360px !important;  /* silakan ubah 340/380 kalau mau */
  }
}

/* ============ MOBILE (<= 767px) ============ */
/* Garis pindah ke kiri, item bertumpuk: gambar → tahun → teks */
@media (max-width: 767.98px) {

  .timeline-container {
    height: auto !important;      /* override h-[1400px] */
    padding-left: 2.5rem;         /* ruang untuk garis di kiri */
    padding-right: 1.25rem;
  }

  /* Sembunyikan garis & 5 titik versi desktop */
  .timeline-container > span {
    display: none;
  }

  /* Garis vertikal baru di kiri (sepanjang konten) */
  .timeline-container::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 1.5rem;                 /* referensi center garis */
    width: 3px;
    background: #D1EDF5;
    transform: translateX(-50%);  /* biar center di 1.5rem */
  }

  /* Setiap blok tahun (1974–2019) jadi item bertumpuk */
  .timeline-container > div[class*="absolute"] {
    position: relative;
    top: auto;
    left: auto;
    transform: none;
    max-width: 100%;
    margin: 0 0 2.5rem 0;         /* jarak antar tahun */
  }

  .timeline-container > div[class*="absolute"]::before {
  content: '';
  position: absolute;
  top: 0rem;
  left: -0rem;  /* 🎯 SUPER FINE TUNE */
  width: 14px;
  height: 14px;
  border-radius: 999px;
  background: #1524AF;
  transform: translate(-50%, 0);
  z-index: 99;
}

  /* Grid 2 kolom dipecah jadi vertikal */
  .timeline-container > div[class*="absolute"] > .grid {
    display: block;
  }

  /* Reset padding & align supaya rapi di HP */
  .timeline-left-col,
  .timeline-right-col,
  .timeline-container > div[class*="absolute"] > .grid > div {
    padding: 0 !important;
    margin: 0 0 0.5rem 0 !important;
    text-align: left !important;
  }
  /* Geser teks (tahun + paragraf) sedikit ke kanan di HP */
  .timeline-container > div[class*="absolute"] > .grid > div:nth-child(2) {
    padding-left: 1rem !important; /* boleh dinaikkan ke 1rem kalau mau lebih jauh */
  }

  /* Gambar di HP: sedikit lebih kecil + jarak bawah */
  .timeline-container .sejarah-img {
    width: 180px !important;
    margin-bottom: 0.5rem;
  }

  /* Teks paragraf sejarah di HP */
  .sejarah-text {
    font-size: 12px !important;
    line-height: 1.6 !important;
    text-align: justify !important;
  }

  /* Tahun di HP: kecil & rata tengah */
  .timeline-container h4 {
    font-size: 14px !important;
    margin-bottom: 0.25rem;
    text-align: center !important;
    width: 100%;
  }
}

/* Jauhkan kolom gambar dari garis tengah (desktop & tablet) */
.timeline-left-col {
  padding-right: 3.75rem;   /* ~60px dari garis */
}

.timeline-right-col {
  padding-left: 3.75rem;
}


  </style>
</head>

<body class="bg-[#F1F9FC] antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- HERO (komponen reusable) --}}
  <x-layouts.app.profile-hero
    title="Cerita Kami"
    :crumbs="[
      ['label' => 'Beranda', 'route' => 'landing'],
      ['label' => 'Profil'],
      ['label' => 'Cerita Kami'],
    ]"
  />
  {{-- /HERO --}}

{{-- SECTION: Sambutan Kepala UPT --}}
@if($kepala)
<section class="section-compact relative bg-[#F1F9FC]">
  <div class="section-container">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[380px_auto]
                md:items-stretch lg:items-start
                gap-y-8 md:gap-y-0
                gap-x-4 md:gap-x-6 lg:gap-x-[32px] pt-6">

      {{-- KOLOM KIRI: FOTO + NAMA --}}
      <div class="relative flex flex-col items-center lg:items-start h-full w-fit">

        @php
          $defaultFoto = asset('images/profil/Kepala-UPT.svg');
          $fotoUrl = $defaultFoto;

          if (!empty($kepala->foto)) {
              $raw = ltrim($kepala->foto, '/');

              if (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://'])) {
                  // kalau yang disimpan sudah full URL
                  $fotoUrl = $raw;
              } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($raw)) {
                  // kalau file ada di storage/public
                  $fotoUrl = \Illuminate\Support\Facades\Storage::url($raw);
              } elseif (file_exists(public_path($raw))) {
                  // kalau file ada di public langsung
                  $fotoUrl = asset($raw);
              }
          }
        @endphp

        {{-- WRAPPER FOTO --}}
        <div class="relative mx-auto lg:mx-0
            w-full
            max-w-[260px] sm:max-w-[280px] md:max-w-[360px]
            aspect-[432/487]
            lg:max-w-none lg:w-[380px] lg:h-[430px]
            flex justify-center md:justify-center
            md:mt-16 lg:mt-0">

          {{-- background gradient --}}
          <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-[#B5CDEE] to-[#1524AF]"></div>

          {{-- FOTO DINAMIS --}}
          <img src="{{ $fotoUrl }}"
               alt="Kepala UPT PTKK"
               class="absolute bottom-0 left-1/2 -translate-x-1/2
                      h-[260px]
                      sm:h-[300px]
                      md:h-[420px]
                      lg:h-[540px]
                      w-auto object-contain drop-shadow-md z-10"
               loading="lazy"
               decoding="async" />
        </div>

        {{-- NAMA (HP & TABLET) --}}
        <div class="block lg:hidden text-center mt-4">
          <h3 class="mt-[25px] font-[Volkhov] font-bold text-[#1524AF]
                     text-[19px] md:text-[20px] tracking-tight stroke-yellow">
            {{ $kepala->nama_kepala_upt ?? 'Kepala UPT PTKK' }}
          </h3>
          <p class="mt-1 font-[Volkhov] text-[#861D23]
                    text-[13px] md:text-[14px]">
            Kepala UPT. PTKK
          </p>
        </div>

      </div>

      {{-- KOLOM KANAN: TEKS SAMBUTAN --}}
      <div class="w-full h-full flex flex-col lg:pt-[8px] lg:-mt-[6px]">

        @if(!empty($kepala->sambutan))
          <p class="font-[Montserrat] text-[#0F172A] text-[15px] md:text-[16px] leading-7 text-justify">
            {!! nl2br(e($kepala->sambutan)) !!}
          </p>
        @else
          <p class="font-[Montserrat] text-[#0F172A] text-[15px] md:text-[16px] leading-7 text-justify">
            Sambutan Kepala UPT akan diperbarui melalui sistem.
          </p>
        @endif

        {{-- NAMA (DESKTOP) --}}
        <div class="hidden lg:block">
          <h3 class="mt-[25px] font-[Volkhov] font-bold text-[#1E3A8A] text-[22px] tracking-tight stroke-yellow">
            {{ $kepala->nama_kepala_upt ?? 'Kepala UPT PTKK' }}
          </h3>
          <p class="mt-1 font-[Volkhov] text-[#861D23] text-[20px]">
            Kepala UPT. PTKK
          </p>
        </div>

      </div>

    </div>
  </div>
</section>
@endif


 {{-- SECTION: Visi, Misi, Motto, Sasaran --}}
<section class="section-compact relative bg-[#F1F9FC]">
  <div class="section-container">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">

      {{-- KOLOM KIRI: VISI + MISI --}}
      <div class="flex flex-col gap-6">

        {{-- VISI --}}
        <div class="rounded-2xl ring-1 ring-black/5 bg-white overflow-hidden">
          <div class="p-6 bg-white/85"
               style="background-image: url('{{ asset('images/profil/visi.svg') }}');
                      background-size: cover;
                      background-position: center;">
            <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-3">
              Visi
            </h3>
            <p class="font-[Montserrat] font-medium text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify">
              Profesional dalam pelayanan guna meningkatkan kualitas SDM dalam pelatihan yang berintegritas
              dan berkompeten sesuai kebutuhan perkembangan pasar global.
            </p>
          </div>
        </div>

        {{-- MISI --}}
        <div class="rounded-2xl ring-1 ring-black/5 bg-[#DBE7F7] p-6 flex-1">
          <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-3">
            Misi
          </h3>
          <ul class="list-disc pl-5 space-y-2 font-[Montserrat] font-medium text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify">
            <li>Memberikan pelayanan prima guna mendukung program pemerintah.</li>
            <li>Mengembangkan sistem pelatihan yang cerdas, berwawasan, terampil, adaptif, dan berkompeten.</li>
            <li>Meningkatkan keterampilan SDM berbasis vokasi siap kerja, berwirausaha, atau melanjutkan ke jenjang lebih tinggi.</li>
          </ul>
        </div>

      </div>

      {{-- KOLOM KANAN: MOTTO + SASARAN --}}
      <div class="flex flex-col gap-6">

        {{-- MOTTO --}}
        <div class="rounded-2xl ring-1 ring-black/5 bg-[#DBE7F7] p-6">
          <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-3">
            Motto
          </h3>
          <p class="font-[Montserrat] italic font-medium text-[16px] md:text-[17px] leading-relaxed text-justify text-[#081526]">
            “Mencetak Generasi Unggul Indonesia Maju”
          </p>
        </div>

        {{-- SASARAN --}}
        <div class="rounded-2xl ring-1 ring-black/5 overflow-hidden"
             style="background-image: url('{{ asset('images/profil/sasaran.svg') }}');
                    background-size: cover;
                    background-position: center;">
          <div class="p-6 bg-[#DBE7F7]/75 rounded-2xl">
            <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-3">
              Sasaran
            </h3>
            <ul class="list-disc pl-5 space-y-1 font-[Montserrat] font-medium text-[16px] md:text-[17px] leading-relaxed text-justify text-[#081526]">
              <li>Meningkatkan kompetensi siswa dan guru SMK/SMA di wilayah Jawa Timur.</li>
              <li>Mengembangkan kurikulum pembelajaran.</li>
              <li>Meningkatkan jejaring kerja UPT. PTKK.</li>
              <li>Meningkatkan kualitas program pendidikan dan pelatihan.</li>
              <li>Meningkatkan koordinasi dengan cabdin dan lembaga sekolah di Jawa Timur.</li>
              <li>Mengetahui tingkat penyerapan alumni di masyarakat atau DU/DI.</li>
            </ul>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>


{{-- SECTION: Sejarah (garis tengah + 5 titik sejajar, subjudul stroke kuning) --}}
<section class="relative bg-[#F1F9FC] pt-6 pb-[300px]">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- Heading --}}
    <div class="text-center mb-16">
      <div class="w-full flex justify-center mb-[15px]">
        <span class="inline-flex items-center justify-center
                    px-5 py-2 rounded-lg bg-[#F3E8E9] text-[#861D23]
                    font-bold text-base md:text-lg lg:text-[20px] font-[Volkhov] shadow-sm leading-tight">
          Sejarah
        </span>
      </div>

      <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[#1524AF] text-center stroke-yellow">
        Perjalanan Panjang UPT PTKK Membangun Pendidikan Vokasi Jawa Timur
      </h2>
    </div>

    {{-- ===== Timeline container ===== --}}
    <div class="timeline-container relative h-[1400px] mx-auto px-6 md:px-12 lg:px-[80px] overflow-visible">
      {{-- Garis vertikal (desktop & tablet) --}}
      <span class="absolute left-1/2 top-0 -translate-x-1/2 w-[5px] h-full bg-[#D1EDF5] z-10"></span>

      {{-- 5 Titik (desktop & tablet) --}}
      <span class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 top-[25%] -translate-x-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 top-[50%] -translate-x-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 top-[75%] -translate-x-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 bottom-0 -translate-x-1/2 translate-y-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>

      {{-- 1974 (gambar kiri, teks kanan) --}}
      <div class="absolute top-[3%] left-1/2 w-full max-w-7xl -translate-x-1/2">
        <div class="grid grid-cols-2 gap-x-[100px] items-start">
          {{-- gambar kiri --}}
          <div class="timeline-left-col flex justify-center md:justify-end items-center -mt-10 pr-[40px]">
            <img src="{{ asset('images/profil/sejarah1974.svg') }}" alt="Sejarah 1974"
                 class="sejarah-img w-[280px] md:w-[320px]" loading="lazy">
          </div>
          {{-- teks kanan --}}
          <div class="-mt-6 md:pl-[32px] lg:pl-[40px]">
            <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">1974</h4>
            <p class="sejarah-text font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
              Dipimpin oleh Ir. Yutadi, UPT. PTKK saat itu bernama Pusat Latihan Pendidikan Teknis (PLPT).
              Lembaga ini dibangun melalui proyek Bank Dunia dan merupakan satu-satunya lembaga Diklat Kejuruan
              yang dimiliki oleh Dinas Pendidikan Provinsi Jawa Timur dan diresmikan oleh Presiden RI Ir. Soeharto
              pada tanggal 22 Mei 1975.
            </p>
          </div>
        </div>
      </div>

      {{-- 1978 (desktop: teks kiri, gambar kanan / HP: gambar → tahun → teks) --}}
      <div class="absolute top-[23%] left-1/2 w-full max-w-7xl -translate-x-1/2">
        <div class="grid grid-cols-2 gap-x-[100px] items-start">
          {{-- gambar kanan di desktop, tapi DOM duluan untuk HP --}}
          <div class="timeline-right-col flex justify-center md:justify-start items-center -mt-10 md:pl-[40px] lg:pl-[48px]
                      order-1 md:order-2">
            <img src="{{ asset('images/profil/sejarah1978.svg') }}" alt="Sejarah 1978"
                 class="sejarah-img w-[280px] md:w-[320px]" loading="lazy">
          </div>
          {{-- teks kiri di desktop, tapi muncul setelah gambar di HP --}}
          <div class="mt-2 md:mt-2 pr-0 md:pr-[40px] text-left md:text-right
                      order-2 md:order-1">
            <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">1978</h4>
            <p class="sejarah-text font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
              Melalui Keputusan Menteri Pendidikan dan Kebudayaan Nomor: 0271/0/1978 tentang Susunan Organisasi dan Tata Kerja
              Balai Latihan Pendidikan Teknis, nama lembaga ini diubah menjadi
              Balai Latihan Pendidikan Teknis (BLPT) dan operasionalnya diserahkan kepada
              Kantor Wilayah Departemen Pendidikan dan Kebudayaan Provinsi Jawa Timur.
            </p>
          </div>
        </div>
      </div>

      {{-- 2008 (gambar kiri, teks kanan) --}}
      <div class="absolute top-[47%] left-1/2 w-full max-w-7xl -translate-x-1/2">
        <div class="grid grid-cols-2 gap-x-[100px] items-start">
          {{-- gambar kiri --}}
          <div class="timeline-left-col flex justify-center md:justify-end items-center -mt-10 pr-[40px]">
            <img src="{{ asset('images/profil/sejarah2008.svg') }}" alt="Sejarah 2008"
                 class="sejarah-img w-[280px] md:w-[320px]" loading="lazy">
          </div>
          {{-- teks kanan --}}
          <div class="mt-14 md:pl-[32px] lg:pl-[40px]">
            <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">2008</h4>
            <p class="sejarah-text font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
              Diterbitkannya Peraturan Gubernur Jawa Timur Nomor 120 Tahun 2008
              tentang Organisasi dan Tata Kerja UPT Dinas Pendidikan Provinsi Jawa Timur,
              nama lembaga ini diubah menjadi
              Unit Pelaksana Teknis Pelatihan dan Pengembangan Pendidikan Kejuruan (UPT. PPPK).
            </p>
          </div>
        </div>
      </div>

      {{-- 2016 (desktop: teks kiri, gambar kanan / HP: gambar → tahun → teks) --}}
      <div class="absolute top-[75%] left-1/2 w-full max-w-7xl -translate-x-1/2">
        <div class="grid grid-cols-2 gap-x-[100px] items-start">
          {{-- gambar kanan di desktop, tapi DOM duluan untuk HP --}}
          <div class="timeline-right-col flex justify-center md:justify-start items-center -mt-10 md:pl-[40px] lg:pl-[48px]
                      order-1 md:order-2">
            <img src="{{ asset('images/profil/sejarah2016.svg') }}" alt="Sejarah 2016"
                 class="sejarah-img w-[280px] md:w-[320px]" loading="lazy">
          </div>
          {{-- teks kiri di desktop, tapi setelah gambar in HP --}}
          <div class="mt-2 md:mt-2 pr-0 md:pr-[40px] text-left md:text-right
                      order-2 md:order-1">
            <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">2016</h4>
            <p class="sejarah-text font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
              Terjadi perubahan Peraturan Gubernur Jawa Timur Nomor 95 Tahun 2016 tentang Nomenklatur,
              Susunan Organisasi, Tugas Pokok dan Fungsi serta Tata Kerja UPT Dinas Pendidikan Provinsi Jawa Timur.
              Nama lembaga diubah menjadi Unit Pelaksana Teknis Pengembangan Pendidikan Kejuruan (UPT. PPK).
            </p>
          </div>
        </div>
      </div>

      {{-- 2019 (gambar kiri, teks kanan) --}}
      <div class="absolute top-[100%] left-1/2 w-full max-w-7xl
                  -translate-x-1/2 -translate-y-1/2 translate-y-[0px]">
        <div class="grid grid-cols-2 gap-x-[100px] items-start">
          {{-- gambar kiri --}}
          <div class="timeline-left-col flex justify-center md:justify-end items-start pr-[40px]">
            <img src="{{ asset('images/profil/sejarah2019.svg') }}" alt="Sejarah 2019"
                 class="sejarah-img w-[300px] md:w-[340px]" loading="lazy">
          </div>
          {{-- teks kanan --}}
          <div class="md:pl-[32px] lg:pl-[40px] mt-4 md:mt-0">
            <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">2019</h4>
            <p class="sejarah-text font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
              Sesuai dengan Peraturan Gubernur Jawa Timur Nomor 1 Tahun 2019
              tentang Perubahan atas Peraturan Gubernur Jawa Timur Nomor 43 Tahun 2018
              mengenai Nomenklatur, Susunan Organisasi, Uraian Tugas dan Fungsi serta Tata Kerja
              UPT Dinas Pendidikan Provinsi Jawa Timur, nama lembaga ini diubah menjadi
              Unit Pelaksana Teknis Pengembangan Teknis dan Keterampilan Kejuruan (UPT. PTKK).
            </p>
          </div>
        </div>
      </div>

    </div> {{-- /timeline-container --}}
  </div>
</section>

  {{-- SECTION: Tujuan --}}
<section id="tujuan" class="section-compact w-full bg-[#F1F9FC]">
  <div class="section-container">


{{-- Badge --}}
<div class="w-full flex justify-center mb-[15px]">
  <span class="inline-flex items-center justify-center
              w-[126px] h-[41px] rounded-lg bg-[#F3E8E9] text-[#861D23]
              font-bold text-base md:text-lg lg:text-[20px] font-[Volkhov] shadow-sm leading-tight">
    Tujuan
  </span>
</div>

{{-- Judul --}}
<h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[var(--biru-brand)] stroke-yellow text-center mb-[30px]">
  Komitmen Kami Untuk Masyarakat Jawa Timur
</h2>
      {{-- Grid cards --}}
<div class="grid grid-cols-2 gap-4 sm:gap-6
            md:grid-cols-2 md:gap-8
            lg:grid-cols-3 lg:gap-x-[45px] lg:gap-y-[40px]">
       @for ($i = 1; $i <= 6; $i++)
  <div class="tujuan-card relative w-full min-h-[200px] py-4 px-6">
  <span class="tujuan-badge">
  {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
</span>

    <div class="flex flex-col gap-4 h-full justify-center">
      <img src='{{ asset("images/icons/tujuan-{$i}.svg") }}'
           alt="Ikon Tujuan {{ $i }}"
           class="tujuan-icon w-[50px] h-[50px]" loading="lazy" decoding="async" />

      <p class="tujuan-text font-[Montserrat] font-medium text-[18px] leading-snug text-gray-800">
        @switch($i)
          @case(1) Membangun Sumber Daya Manusia (SDM) yang kreatif, kompetitif, dan adaptif. @break
          @case(2) Membentuk mindset tangguh berbasis literasi dan kolaborasi. @break
          @case(3) Mendorong transformasi edukasi vokasi yang relevan dan inklusif. @break
          @case(4) Meningkatkan pengakuan kompetensi melalui sertifikasi nasional. @break
          @case(5) Memperkuat konektivitas antara dunia pendidikan dan dunia industri. @break
          @case(6) Mewujudkan generasi muda yang mandiri dan berjiwa wirausaha. @break
        @endswitch
      </p>
    </div>
  </div>
@endfor
      </div>
    </div>
  </section>

 {{-- SECTION: Manfaat --}}
<section class="section-compact relative bg-[#F1F9FC]">
  <div class="section-container">

    {{-- Label Manfaat --}}
    <div class="text-center">
      <div class="inline-flex items-center justify-center mb-4 px-5 py-2 bg-[#F3E8E9] rounded-lg">
        <span class="font-['Volkhov'] font-bold text-[#861D23] text-[20px]">Manfaat</span>
      </div>

      <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px]
                 mb-10 text-[var(--biru-brand)] stroke-yellow text-center">
        Untuk Jawa Timur yang Lebih Maju dan Berdaya Saing
      </h2>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-6 lg:grid-cols-5 gap-8">

      @for ($i = 1; $i <= 5; $i++)
        @php
          $extraClasses = '';

          // Layout khusus TABLET
          if ($i === 1) {
              $extraClasses = 'md:col-span-2 lg:col-span-1';
          } elseif ($i === 2) {
              $extraClasses = 'md:col-span-2 lg:col-span-1';
          } elseif ($i === 3) {
              $extraClasses = 'md:col-span-2 lg:col-span-1';
          } elseif ($i === 4) {
              // 4 => tengah kiri
              $extraClasses = 'md:col-span-2 md:col-start-2 lg:col-span-1 lg:col-start-auto';
          } elseif ($i === 5) {
              // 5 => tengah kanan
              $extraClasses = 'md:col-span-2 md:col-start-4 lg:col-span-1 lg:col-start-auto';
          }
        @endphp

        <div class="card-manfaat shadow-5x p-6 flex flex-col items-center justify-start
                    transition-transform duration-200 hover:-translate-y-1 {{ $extraClasses }}">

          <img src='{{ asset("images/icons/manfaat{$i}.svg") }}'
               class="w-[50px] h-[50px] mb-[30px]"
               loading="lazy" decoding="async">

          <p class="text-[#081526] font-[Montserrat] font-medium text-[15px]
                    leading-snug text-center">
            @switch($i)
              @case(1) Peningkatan Produktivitas dan Inovasi Guru dan Siswa SMA/SMK @break
              @case(2) Penguatan Ekosistem Ekonomi Kreatif Nasional @break
              @case(3) Meningkatkan Kemampuan Adaptasi Terhadap Transformasi Digital @break
              @case(4) Terciptanya Ekosistem Kolaboratif Antara Pendidikan dan Industri @break
              @case(5) Pembentukan Karakter dan Etika Profesional di Era Digital @break
            @endswitch
          </p>

        </div>
      @endfor

    </div>

  </div>
</section>

{{-- SECTION: Mandat dan Tanggung Jawab UPT PTKK --}}
<section class="section-compact bg-[#F1F9FC]">
  <div class="section-container">

    {{-- Judul --}}
    <div class="text-center mb-[50px]">
      <span class="inline-block bg-[#F3E8E9] text-[#861D23]
                   font-['Volkhov'] font-bold text-[18px] md:text-[20px]
                   px-5 py-2 rounded-md mb-[15px]">
        Tugas Dan Fungsi
      </span>
      <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[var(--biru-brand)] stroke-yellow">
        Mandat dan Tanggung Jawab UPT PTKK
      </h2>
    </div>

    {{-- GRID UTAMA --}}
   <div class="grid grid-cols-1
             md:[grid-template-columns:2fr_minmax(0,1.3fr)]
             lg:grid-cols-3
             gap-6 md:gap-8 items-start
             -mt-6 md:-mt-8 lg:-mt-6
             font-['Montserrat'] font-medium text-[#000000]
             text-[14px] md:text-[16px] leading-relaxed">

      {{-- KOLOM KIRI: isi mandat --}}
      <div class="md:col-span-1 lg:col-span-2 bg-[#FFFFFF] rounded-2xl shadow-sm ring-1 ring-black/5
                  p-5 md:p-8 text-justify">
        <p class="mb-5">
          Peraturan Gubernur Jawa Timur Nomor 1 Tahun 2019 tentang Perubahan atas Peraturan
          Gubernur Jawa Timur Nomor 43 Tahun 2018, tentang nomenklatur, susunan organisasi,
          uraian tugas dan fungsi serta tata kerja Unit Pelaksana Teknis Dinas Pendidikan
          Provinsi Jawa Timur.
        </p>

        <hr class="border-t-1 border-[#000000] mb-5">

        <p class="mb-4">
          Untuk melaksanakan tugas sebagaimana dimaksud dalam Pasal 8C,
          UPT Pengembangan Teknis dan Keterampilan Kejuruan mempunyai fungsi:
        </p>

        <ul class="list-disc pl-5 md:pl-6 space-y-2 leading-[1.3]">
          <li>Penyusunan perencanaan program dan kegiatan UPT.</li>
          <li>Penyusunan dan pengembangan materi teknis keterampilan kejuruan.</li>
          <li>Penyelenggaraan pelatihan dan bimbingan teknis keterampilan kejuruan.</li>
          <li>Pengembangan media teknis pelatihan dan bimbingan keterampilan kejuruan.</li>
          <li>Pelaksanaan dukungan kerjasama pelatihan dan bimbingan teknis keterampilan kejuruan.</li>
          <li>Pelaksanaan tugas-tugas ketatausahaan dan pelayanan masyarakat.</li>
          <li>Pelaksanaan monitoring, evaluasi, dan pelaporan.</li>
          <li>Pelaksanaan tugas-tugas lain yang diberikan oleh Kepala Dinas.</li>
        </ul>
      </div>

      {{-- KOLOM KANAN: file peraturan --}}
      <div
        class="bg-[#FFFFFF] rounded-2xl shadow-sm ring-1 ring-black/5
               p-5 md:p-7 flex flex-col justify-between gap-5
               text-[14px] md:text-[15px]">

        {{-- Judul + nama file --}}
        <div class="space-y-3">
          <div class="flex items-center gap-2">
            <img src="{{ asset('images/icons/pdf-file.svg') }}"
              alt="PDF File Icon"
              class="w-[22px] h-[22px]" loading="lazy" decoding="async" />
            <h3 class="font-['Volkhov'] font-bold text-[#000000] text-[17px] md:text-[18px]">
              File Peraturan
            </h3>
          </div>

          <p class="leading-snug">
            Pergub No. 1 Tahun 2019 ttg Perubahan Pergub 43 Tahun 2018
            tentang UPT Dinas Pendidikan.pdf
          </p>

          <hr class="border-t border-[#000000] mt-2 mb-1 md:my-3">
        </div>

        {{-- Tombol --}}
        <div class="flex flex-col sm:flex-row gap-3">
          <a href="{{ asset('pdf/peraturan-pergub.pdf') }}"
            target="_blank"
             class="inline-flex items-center justify-center gap-2
                    w-full sm:w-1/2
                    px-4 py-1 bg-[#1524AF] text-[#FFFFFF] rounded-lg
                    text-[14px] md:text-[15px] font-semibold
                    hover:bg-[#0F1D8F] transition text-center">
            <img src="{{ asset('images/icons/mata.svg') }}" alt="Lihat" class="w-5 h-5">
            Lihat
          </a>

          <a href="{{ asset('pdf/peraturan-pergub.pdf') }}"
             download
             class="inline-flex items-center justify-center gap-2
                    w-full sm:w-1/2
                    px-4 py-3 bg-[#1524AF] text-[#FFFFFF] rounded-lg
                    text-[14px] md:text-[15px] font-semibold
                    hover:bg-[#0F1D8F] transition text-center">
            <img src="{{ asset('images/icons/download.svg') }}" alt="Download" class="w-5 h-5">
            Download
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
