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
    .tujuan-card{
      background: #FEFEFE;
      box-shadow:
        0 2px 4px rgba(0,0,0,.06),
        0 12px 24px rgba(0,0,0,.08),
        0 40px 80px rgba(0,0,0,.08);
      border-radius: 1rem; /* rounded-2xl */
    }
    .card-manfaat{
      background: var(--card-manfaat);
      height: 300px;
      border-radius: 1rem; /* rounded-2xl */
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

  </style>
</head>

<body class="bg-[#FEFEFE] antialiased">

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
   <section class="section-compact relative bg-[#F8FAFC]">
   <div class="section-container">


      <div class="grid grid-cols-1 lg:grid-cols-[432px_auto] items-start gap-y-8 lg:gap-x-[32px]">

        {{-- Kolom Kiri --}}
        <div class="relative lg:justify-self-start">
          <div class="relative mx-auto lg:mx-0
                      w-full max-w-[420px] aspect-[432/487]
                      lg:max-w-none lg:w-[432px] lg:h-[487px]">
            <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-[#B5CDEE] to-[#1524AF]"></div>

            <img src="{{ asset('images/profil/Kepala-UPT.svg') }}"
                 alt="Kepala UPT PTKK"
                 class="absolute bottom-0 left-1/2 -translate-x-1/2
                        h-[540px] w-auto object-contain drop-shadow-md z-10"
                 loading="lazy" decoding="async" />
          </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="w-full lg:pt-[8px] lg:-mt-[6px]">
          <p class="font-[Montserrat] text-[#0F172A] text-[15px] md:text-[16px] leading-7 text-justify">
            Puji syukur ke hadirat Tuhan Yang Maha Esa atas limpahan rahmat-Nya sehingga UPT PTKK dapat melaksanakan setiap program pelatihan dengan baik. Program pelatihan kami disusun sebagai wujud nyata komitmen UPT PTKK dalam mendukung kebijakan Merdeka Belajar dan perubahan paradigma pendidikan vokasi agar selaras dengan kebutuhan industri, khususnya dalam meningkatkan kompetensi guru dan siswa di pendidikan kejuruan Jawa Timur.
          </p>

          <p class="mt-4 font-[Montserrat] text-[#0F172A] text-[15px] md:text-[16px] leading-7 text-justify">
            Kami menyadari bahwa pencapaian tujuan besar ini tidak dapat terwujud tanpa kerja sama dan dukungan dari berbagai pihak, baik internal Dinas Pendidikan maupun eksternal. Untuk itu, kami mengucapkan terima kasih atas segala bentuk dukungan dan kontribusi yang telah diberikan. Semoga program pelatihan kami dapat menjadi landasan kuat dalam menciptakan SDM vokasi yang unggul, adaptif, kompetitif serta mampu membawa pendidikan kejuruan Jawa Timur semakin maju.
          </p>

          <h3 class="mt-[25px] font-[Volkhov] font-bold text-[#1E3A8A] text-[19px] md:text-[20px] lg:text-[22px] tracking-tight stroke-yellow">
            ENDANG WINARSIH, S. Sos, M. Si
          </h3>
          <p class="mt-1 font-[Montserrat] text-[#1E3A8A] text-[13px] md:text-[14px] lg:text-[15px]">
            Kepala UPT. PTKK
          </p>
        </div>
      </div>
    </div>
  </section>

{{-- SECTION: Visi, Misi, Motto, Sasaran (Misi ditinggikan & warna DBE7F7) --}}
<section class="section-compact relative bg-[#F8FAFC]">
  <div class="section-container">

    {{-- Grid 2 kolom, tinggi otomatis --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-[30px] gap-y-[20px] items-start">

      {{-- KOLOM KIRI: VISI + MISI --}}
      <div class="flex flex-col gap-[20px] w-full">
        {{-- VISI --}}
        <div class="w-full rounded-2xl ring-1 ring-black/5 bg-white">
          <div class="p-6 flex flex-col">
            <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-[10px]">Visi</h3>
            <p class="font-[Montserrat] font-medium text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify">
              Profesional dalam pelayanan guna meningkatkan kualitas SDM dalam pelatihan yang berintegritas
              dan berkompeten sesuai kebutuhan perkembangan pasar global.
            </p>
          </div>
        </div>

        {{-- MISI (warna DBE7F7 & tinggi disesuaikan agar sejajar dengan Sasaran) --}}
        <div class="w-full rounded-2xl ring-1 ring-black/5 bg-[#DBE7F7] h-[325px] flex flex-col">
          <div class="p-6 flex flex-col h-full">
            <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-[10px]">Misi</h3>
            <ul class="list-disc pl-5 space-y-2 font-[Montserrat] font-medium text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify">
              <li>Memberikan pelayanan prima guna mendukung program pemerintah.</li>
              <li>Mengembangkan sistem pelatihan yang cerdas, berwawasan, terampil, adaptif, dan berkompeten.</li>
              <li>Meningkatkan keterampilan SDM berbasis vokasi siap kerja, berwirausaha, atau melanjutkan ke jenjang lebih tinggi.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- KOLOM KANAN: MOTTO + SASARAN --}}
      <div class="flex flex-col gap-[20px] w-full">
        {{-- MOTTO (warna DBE7F7) --}}
        <div class="w-full rounded-2xl ring-1 ring-black/5 bg-[#DBE7F7]">
          <div class="p-6 flex flex-col justify-center">
            <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-[8px]">Motto</h3>
            <p class="font-[Montserrat] font-medium text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify italic">
              “Mencetak Generasi Unggul Indonesia Maju”
            </p>
          </div>
        </div>

        {{-- SASARAN --}}
        <div class="w-full rounded-2xl ring-1 ring-black/5 bg-white flex flex-col">
          <div class="p-6 flex flex-col">
            <h3 class="font-[Volkhov] font-bold text-[22px] md:text-[24px] text-[#081526] mb-[10px]">Sasaran</h3>
            <ul class="list-disc pl-5 space-y-2 font-[Montserrat] font-medium text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify">
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
<section class="relative bg-[#FEFEFE] pt-20 pb-[300px]">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">

    {{-- Heading --}}
    <div class="text-center mb-16">
      <div class="w-full flex justify-center mb-[15px]">
        <span class="inline-flex items-center justify-center
                    px-5 py-2 rounded-lg bg-[#F3E8E9] text-[#861D23]
                    font-bold text-base md:text-lg lg:text-[24px] font-[Volkhov] shadow-sm leading-tight">
          Sejarah
        </span>
      </div>

      <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[#1524AF] text-center stroke-yellow">
        Perjalanan Panjang UPT PTKK Membangun Pendidikan Vokasi Jawa Timur
      </h2>
    </div>

    {{-- ===== Timeline container (semua item harus di sini) ===== --}}
    <div class="relative h-[1400px] mx-auto px-6 md:px-12 lg:px-[80px] overflow-visible">
      {{-- Garis vertikal --}}
      <span class="absolute left-1/2 top-0 -translate-x-1/2 w-[5px] h-full bg-[#D1EDF5] z-10"></span>

      {{-- 5 Titik --}}
      <span class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 top-[25%] -translate-x-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 top-[50%] -translate-x-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 top-[75%] -translate-x-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>
      <span class="absolute left-1/2 bottom-0 -translate-x-1/2 translate-y-1/2 w-[25px] h-[25px] rounded-full bg-[#1524AF] z-20"></span>

      {{-- 1974 --}}
      <div class="absolute top-[3%] left-1/2 w-full max-w-7xl -translate-x-1/2">
        <div class="grid md:grid-cols-2 gap-x-[100px] items-start">
          <div class="flex justify-center md:justify-end items-center -mt-10 pr-[40px]">
            <img src="{{ asset('images/profil/sejarah1974.svg') }}" alt="Sejarah 1974" class="w-[280px] md:w-[320px]" loading="lazy">
          </div>
          <div class="-mt-6 md:pl-[32px] lg:pl-[40px]">
            <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">1974</h4>
            <p class="font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
              Dipimpin oleh Ir. Yutadi, UPT. PTKK saat itu bernama Pusat Latihan Pendidikan Teknis (PLPT).
          Lembaga ini dibangun melalui proyek Bank Dunia dan merupakan satu-satunya lembaga Diklat Kejuruan
          yang dimiliki oleh Dinas Pendidikan Provinsi Jawa Timur dan diresmikan oleh Presiden RI Ir. Soeharto
          pada tanggal 22 Mei 1975.
            </p>
          </div>
        </div>
      </div>

      {{-- 1978 --}}
      <div class="absolute top-[23%] left-1/2 w-full -translate-x-1/2">
        <div class="mx-auto max-w-7xl px-6 md:px-12 lg:px-[80px]">
          <div class="grid md:grid-cols-2 gap-x-[100px] items-start">
            <div class="mt-2 text-right pr-[40px]">
              <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">1978</h4>
              <p class="font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
                Melalui Keputusan Menteri Pendidikan dan Kebudayaan Nomor: 0271/0/1978 tentang Susunan Organisasi dan Tata Kerja
          Balai Latihan Pendidikan Teknis, nama lembaga ini diubah menjadi
          Balai Latihan Pendidikan Teknis (BLPT) dan operasionalnya diserahkan kepada
          Kantor Wilayah Departemen Pendidikan dan Kebudayaan Provinsi Jawa Timur.
              </p>
            </div>
            <div class="flex justify-center md:justify-start items-center -mt-10 md:pl-[40px] lg:pl-[48px]">
              <img src="{{ asset('images/profil/sejarah1978.svg') }}" alt="Sejarah 1978" class="w-[280px] md:w-[320px]" loading="lazy">
            </div>
          </div>
        </div>
      </div>

      {{-- 2008 --}}
      <div class="absolute top-[47%] left-1/2 w-full -translate-x-1/2">
        <div class="mx-auto max-w-7xl px-6 md:px-12 lg:px-[80px]">
          <div class="grid md:grid-cols-2 gap-x-[100px] items-start">
            <div class="flex justify-center md:justify-end items-center -mt-10 pr-[40px]">
              <img src="{{ asset('images/profil/sejarah2008.svg') }}" alt="Sejarah 2008" class="w-[280px] md:w-[320px]" loading="lazy">
            </div>
            <div class="mt-14 md:pl-[32px] lg:pl-[40px]">
              <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">2008</h4>
              <p class="font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
                Diterbitkannya Peraturan Gubernur Jawa Timur Nomor 120 Tahun 2008
          tentang Organisasi dan Tata Kerja UPT Dinas Pendidikan Provinsi Jawa Timur,
          nama lembaga ini diubah menjadi
          Unit Pelaksana Teknis Pelatihan dan Pengembangan Pendidikan Kejuruan (UPT. PPPK).
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- 2016 --}}
      <div class="absolute top-[75%] left-1/2 w-full -translate-x-1/2">
        <div class="mx-auto max-w-7xl px-6 md:px-12 lg:px-[80px]">
          <div class="grid md:grid-cols-2 gap-x-[100px] items-start">
            <div class="mt-2 text-right pr-[40px]">
              <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">2016</h4>
              <p class="font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
                Terjadi perubahan Peraturan Gubernur Jawa Timur Nomor 95 Tahun 2016 tentang Nomenklatur,
          Susunan Organisasi, Tugas Pokok dan Fungsi serta Tata Kerja UPT Dinas Pendidikan Provinsi Jawa Timur.
          Nama lembaga diubah menjadi Unit Pelaksana Teknis Pengembangan Pendidikan Kejuruan (UPT. PPK).
              </p>
            </div>
            <div class="flex justify-center md:justify-start items-center -mt-10 md:pl-[40px] lg:pl-[48px]">
              <img src="{{ asset('images/profil/sejarah2016.svg') }}" alt="Sejarah 2016" class="w-[280px] md:w-[320px]" loading="lazy">
            </div>
          </div>
        </div>
      </div>

      {{-- 2019 (sejajar titik bawah + aman dari Tujuan) --}}
      <div class="absolute top-[100%] left-1/2 w-full -translate-x-1/2 -translate-y-1/2 translate-y-[0px]">
        <div class="mx-auto max-w-7xl px-6 md:px-12 lg:px-[80px]">
          <div class="grid md:grid-cols-2 gap-x-[100px] items-start">
            <div class="flex justify-center md:justify-end items-start pr-[40px]">
              <img src="{{ asset('images/profil/sejarah2019.svg') }}" alt="Sejarah 2019" class="w-[280px] md:w-[320px]" loading="lazy">
            </div>
            <div class="md:pl-[32px] lg:pl-[40px]">
              <h4 class="font-[Volkhov] font-bold text-[#1524AF] text-[20px] mb-2">2019</h4>
              <p class="font-[Montserrat] text-[#000000] text-[16px] leading-relaxed text-justify">
                Sesuai dengan Peraturan Gubernur Jawa Timur Nomor 1 Tahun 2019
          tentang Perubahan atas Peraturan Gubernur Jawa Timur Nomor 43 Tahun 2018
          mengenai Nomenklatur, Susunan Organisasi, Uraian Tugas dan Fungsi serta Tata Kerja
          UPT Dinas Pendidikan Provinsi Jawa Timur, nama lembaga ini diubah menjadi
          Unit Pelaksana Teknis Pengembangan Teknis dan Keterampilan Kejuruan (UPT. PTKK).
              </p>
            </div>
          </div>
        </div>
      </div>
      {{-- /2019 --}}
    </div>
    {{-- /Timeline container --}}
  </div>
</section>


  {{-- SECTION: Tujuan --}}
 <section id="tujuan" class="section-compact w-full bg-white">
    <div class="section-container">

{{-- Badge --}}
<div class="w-full flex justify-center mb-[15px]">
  <span class="inline-flex items-center justify-center
              w-[126px] h-[41px] rounded-lg bg-[#F3E8E9] text-[#861D23]
              font-bold text-base md:text-lg lg:text-[24px] font-[Volkhov] shadow-sm leading-tight">
    Tujuan
  </span>
</div>

{{-- Judul --}}
<h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[var(--biru-brand)] stroke-yellow text-center mb-[30px]">
  Komitmen Kami Untuk Masyarakat Jawa Timur
</h2>


      {{-- Grid cards --}}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-x-[72px] lg:gap-y-[72px]">

        @for ($i = 1; $i <= 6; $i++)
          <div class="tujuan-card relative w-full min-h-[236px] p-6">
            <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2
                         flex items-center justify-center w-[45px] h-[45px] rounded-full
                         text-[#FEFEFE] font-[Montserrat] font-medium text-[16px]"
                  style="background:linear-gradient(90deg,#0E65CC 0%,#01A0F6 49%,#0C69CF 100%);">
              {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
            </span>
            <div class="flex flex-col gap-4 h-full justify-center">
              <img src='{{ asset("images/icons/tujuan-{$i}.svg") }}' alt="Ikon Tujuan {{ $i }}"
                   class="w-[50px] h-[50px]" loading="lazy" decoding="async" />
              <p class="font-[Montserrat] font-medium text-[20px] leading-snug text-gray-800">
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
 <section class="section-compact relative bg-[#F8FAFC]">
   <div class="section-container">

      {{-- Label Manfaat --}}
      <div class="text-center">
  <div class="inline-flex items-center justify-center mb-4 px-5 py-2 bg-[#F3E8E9] rounded-lg">
    <span class="font-['Volkhov'] font-bold text-[#861D23] text-[18px]">Manfaat</span>
  </div>

  <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] mb-10 text-[var(--biru-brand)] stroke-yellow text-center">
    Untuk Jawa Timur yang Lebih Maju dan Berdaya Saing
  </h2>
</div>

      {{-- Grid Manfaat --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @for ($i = 1; $i <= 5; $i++)
          <div class="card-manfaat shadow-5x p-6 flex flex-col items-center justify-start transition-transform duration-200 hover:-translate-y-1">
            <img src='{{ asset("images/icons/manfaat{$i}.svg") }}'
                 alt="@switch($i)
                        @case(1) Ikon peningkatan produktivitas & inovasi guru/siswa @break
                        @case(2) Ikon penguatan ekosistem ekonomi kreatif @break
                        @case(3) Ikon adaptasi transformasi digital @break
                        @case(4) Ikon ekosistem kolaboratif pendidikan–industri @break
                        @case(5) Ikon karakter & etika profesional era digital @break
                      @endswitch"
                 class="w-[50px] h-[50px] mb-[30px]" loading="lazy" decoding="async">

            <p class="text-[#081526] font-[Montserrat] font-medium text-[15px] leading-snug text-center">
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
<section class="section-compact bg-[#F8FAFC]">
  <div class="section-container">

{{-- Judul --}}
<div class="text-center mb-[50px]">
  <span class="inline-block bg-[#F3E8E9] text-[#861D23] font-['Volkhov'] font-bold text-[20px] px-6 py-2 rounded-md mb-[15px]">
    Tugas Dan Fungsi
  </span>
  <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[var(--biru-brand)] stroke-yellow">
    Mandat dan Tanggung Jawab UPT PTKK
  </h2>
</div>

    {{-- Grid 2 kolom --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start font-['Montserrat'] font-medium text-[#000000] text-[16px] md:text-[17px] leading-relaxed">

      {{-- Kolom kiri --}}
      <div class="md:col-span-2 bg-[#FFFFFF] rounded-2xl shadow-sm ring-1 ring-black/5 p-8 text-justify">
        <p class="mb-6">
          Peraturan Gubernur Jawa Timur Nomor 1 Tahun 2019 tentang Perubahan atas Peraturan
          Gubernur Jawa Timur Nomor 43 Tahun 2018, tentang nomenklatur, susunan organisasi,
          uraian tugas dan fungsi serta tata kerja Unit Pelaksana Teknis Dinas Pendidikan
          Provinsi Jawa Timur.
        </p>

        {{-- Garis pembatas hitam solid --}}
        <hr class="border-t-1 border-[#000000] mb-6">

        <p class="mb-4">
          Untuk melaksanakan tugas sebagaimana dimaksud dalam Pasal 8C,
          UPT Pengembangan Teknis dan Keterampilan Kejuruan mempunyai fungsi:
        </p>

        <ul class="list-disc pl-6 space-y-2">
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

      {{-- Kolom kanan --}}
      <div class="bg-[#FFFFFF] rounded-2xl shadow-sm ring-1 ring-black/5 p-8 flex flex-col justify-between text-[16px] md:text-[17px]">
        <div>
          <h3 class="font-['Volkhov'] font-bold text-[#000000] text-[20px] mb-4">📄 File Peraturan</h3>
          <p class="text-base mb-4">
            Pergub No. 1 Tahun 2019 ttg Perubahan Pergub 43 Tahun 2018
            tentang UPT Dinas Pendidikan.pdf
          </p>

          {{-- Garis pemisah hitam solid --}}
          <hr class="border-t-1 border-[#000000] my-5">
        </div>

        {{-- Tombol: sama panjang, rata kanan kiri --}}
        <div class="flex gap-4">
          <a href="#"
             class="inline-flex items-center justify-center gap-2 w-1/2 px-6 py-3 bg-[#1524AF] text-[#FFFFFF] rounded-lg text-[15px] font-semibold hover:bg-[#0F1D8F] transition text-center">
            <img src="{{ asset('images/icons/mata.svg') }}" alt="Lihat" class="w-5 h-5">
            Lihat
          </a>

          <a href="#"
             class="inline-flex items-center justify-center gap-2 w-1/2 px-6 py-3 bg-[#1524AF] text-[#FFFFFF] rounded-lg text-[15px] font-semibold hover:bg-[#0F1D8F] transition text-center">
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
