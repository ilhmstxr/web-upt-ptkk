{{-- resources/views/pages/profil/program-pelatihan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Program Pelatihan - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Font Volkhov --}}
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">

  {{-- Font Montserrat --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    .section-container {
  max-width: 80rem; /* setara max-w-7xl */
  margin-left: auto;
  margin-right: auto;
  padding-left: 1.5rem;  /* px-6 */
  padding-right: 1.5rem;
  padding-top: 2.5rem;   /* py-10 */
  padding-bottom: 2.5rem;
}

@media (min-width: 768px) {
  .section-container {
    padding-left: 3rem;   /* md:px-12 */
    padding-right: 3rem;
    padding-top: 3rem;    /* md:py-12 */
    padding-bottom: 3rem;
  }
}

@media (min-width: 1024px) {
  .section-container {
    padding-left: 80px;   /* lg:px-[80px] */
    padding-right: 80px;
    padding-top: 4rem;    /* lg:py-16 */
    padding-bottom: 4rem;
  }
}


    .upt-stroke {
      text-shadow:
        -1px -1px 0 #861D23,
         1px -1px 0 #861D23,
        -1px  1px 0 #861D23,
         1px  1px 0 #861D23;
    }

    .yellow-stroke {
      text-shadow:
        -1px -1px 0 #FFDE59,
         1px -1px 0 #FFDE59,
        -1px  1px 0 #FFDE59,
         1px  1px 0 #FFDE59,
         0    0   1px #FFDE59;
    }

    .tujuan-card {
      background: #FEFEFE;
      box-shadow:
        0 2px 4px rgba(0, 0, 0, .06),
        0 12px 24px rgba(0, 0, 0, .08),
        0 40px 80px rgba(0, 0, 0, .08);
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
  title="Program Pelatihan"
  :crumbs="[
    ['label' => 'Beranda', 'route' => 'landing'],
    ['label' => 'Profil'],
    ['label' => 'Program Pelatihan'],
  ]"
  image="images/profil/profil-upt.JPG"   {{-- Gambar latar belakang hero --}}
  height="h-[368px]"              {{-- Tinggi hero --}}
/>
{{-- /HERO --}}

{{-- SECTION: Mobil Training Unit --}}
<section id="mobil-training-unit" class="relative bg-[#F8FAFC]">
  <div class="section-container">

    {{-- 2 Kolom Atas: Judul + Deskripsi (kiri) & Gambar Utama (kanan) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14 items-center mb-8">
      {{-- Kolom Kiri --}}
      <div>
        <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[#1524AF] yellow-stroke mb-2">
          Mobil Training Unit
        </h2>
        <p class="font-[Montserrat] text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify">
          Mobil Keliling UPT. PTKK Dinas Pendidikan Jawa Timur adalah sebuah program unggulan yang dirancang khusus
          untuk menjangkau sekolah di pelosok-pelosok Jawa Timur. Mobil ini bukan sekadar kendaraan biasa, melainkan
          sebuah bengkel pendidikan dan ruang kelas berjalan yang dilengkapi dengan peralatan modern. Di dalamnya,
          para instruktur profesional siap memberikan pelatihan keterampilan di seluruh Jawa Timur.
        </p>
      </div>

      {{-- Kolom Kanan: Gambar Utama (tanpa hiasan) --}}
      <div class="flex justify-center md:justify-end">
        <img src="{{ asset('images/profil/MTU1.svg') }}"
             alt="Mobil Training Unit Jawa Timur"
             class="w-full max-w-[520px] h-auto" loading="lazy">
      </div>
    </div>

    {{-- 3 Foto Bawah (asli, sama rata) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <img src="{{ asset('images/profil/MTU2.svg') }}"
           alt="Kegiatan Pelatihan Lapangan"
           class="w-full h-auto" loading="lazy">
      <img src="{{ asset('images/profil/MTU3.svg') }}"
           alt="Peserta Pelatihan di Sekolah"
           class="w-full h-auto" loading="lazy">
      <img src="{{ asset('images/profil/MTU4.svg') }}"
           alt="Instruktur dan Peserta Pelatihan"
           class="w-full h-auto" loading="lazy">
    </div>

  </div>
</section>

{{-- SECTION: Diklat Peningkatan Kompetensi --}}
<section id="diklat-peningkatan-kompetensi" class="relative bg-[#F8FAFC]">
  <div class="section-container">

    {{-- 2 Kolom Atas: Gambar (kiri) & Judul + Deskripsi (kanan) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14 items-center mb-8">

      {{-- Kolom Kiri: Gambar Utama --}}
      <div class="flex justify-center md:justify-start">
        <img src="{{ asset('images/profil/Diklat1.svg') }}"
             alt="Diklat Peningkatan Kompetensi - UPT PTKK"
             class="w-full max-w-[720px] h-auto" loading="lazy">
      </div>

      {{-- Kolom Kanan: Judul + Deskripsi --}}
      <div class="flex flex-col justify-center">
        <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px] text-[#1524AF] yellow-stroke mb-2">
          Diklat Peningkatan Kompetensi
        </h2>
        <p class="font-[Montserrat] text-[16px] md:text-[17px] text-[#081526] leading-relaxed text-justify">
          Proses peningkatan kompetensi di UPT. PTKK dipandu oleh para asesor kompetensi profesional yang telah
          tersertifikasi. Ini adalah bukti nyata komitmen Pemerintah Provinsi Jawa Timur membangun sumber daya
          manusia yang unggul dan siap menghadapi tantangan global serta menjadi garda terdepan yang menjamin
          mutu sumber daya manusia di Jawa Timur.
        </p>
      </div>
    </div>

    {{-- 3 Foto Bawah (asli, sama rata) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <img src="{{ asset('images/profil/Diklat2.svg') }}"
           alt="Praktik Kompetensi Bidang Kuliner"
           class="w-full h-auto" loading="lazy">
      <img src="{{ asset('images/profil/Diklat3.svg') }}"
           alt="Asesmen Kompetensi Teknologi Informasi"
           class="w-full h-auto" loading="lazy">
      <img src="{{ asset('images/profil/Diklat4.svg') }}"
           alt="Pembimbingan dan Umpan Balik Asesor"
           class="w-full h-auto" loading="lazy">
    </div>

  </div>
</section>

{{-- SECTION: Sertifikasi Berbasis KKNI Bertaraf Nasional --}}
<section id="sertifikasi-kkni" class="relative bg-[#F8FAFC]">
  <div class="section-container">

   {{-- 2 Kolom Atas: Kiri (logo + teks sejajar) & Kanan (gambar utama) --}}
<div class="grid grid-cols-1 md:grid-cols-2 items-stretch gap-x-16 md:gap-x-20 gap-y-8 mb-8">

  {{-- Kolom Kiri --}}
  <div class="flex items-stretch">
    <div class="flex items-stretch gap-8 md:gap-12 w-full">

      {{-- Logo (tinggi fix 358px) --}}
      <img src="{{ asset('images/profil/logosertifikasi.svg') }}"
           alt="Logo Sertifikasi KKNI"
           class="w-[70px] h-[340px] object-contain shrink-0" loading="lazy">

      {{-- Teks --}}
      <div class="flex flex-col justify-between h-[340px] max-w-[576px]">
        <div>
          <h2 class="font-['Volkhov'] font-bold text-[22px] md:text-[26px]
                     text-[#1524AF] yellow-stroke leading-tight mb-5">
            Sertifikasi Berbasis KKNI<br>Bertaraf Nasional
          </h2>
        </div>

        {{-- Paragraf sedikit lebih ke bawah --}}
        <p class="font-[Montserrat] text-[15px] md:text-[16px] text-[#081526]
                  leading-relaxed text-justify mt-6">
          UPT PTKK memiliki 6 kompetensi yang tersertifikasi oleh Kemendikdasmen sebagai tempat uji kompetensi
          yang memiliki fasilitas mumpuni. Dari TUK UPT PTKK dipercaya mengembangkan 9 skema kompetensi, yang mana
          akan mengantarkan para peserta pelatihan baik guru dan siswa untuk memperoleh pengakuan kompetensi mereka
          dengan standar nasional maupun internasional.
        </p>
      </div>
    </div>
  </div>

  {{-- Kolom Kanan: Gambar Utama --}}
  <div class="flex items-center justify-center md:justify-end h-[358px]">
    <img src="{{ asset('images/profil/Sertifikasi1.svg') }}"
         alt="Sertifikasi Berbasis KKNI Bertaraf Nasional"
         class="h-[358px] w-auto max-w-[560px] object-contain" loading="lazy">
  </div>
</div>

    {{-- 3 Foto Bawah --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <img src="{{ asset('images/profil/Sertifikasi2.svg') }}"
           alt="Pelatihan Uji Kompetensi KKNI"
           class="w-full h-auto" loading="lazy">
      <img src="{{ asset('images/profil/Sertifikasi3.svg') }}"
           alt="Peserta Sertifikasi Nasional"
           class="w-full h-auto" loading="lazy">
      <img src="{{ asset('images/profil/Sertifikasi4.svg') }}"
           alt="Proses Sertifikasi Kompetensi"
           class="w-full h-auto" loading="lazy">
    </div>

  </div>
</section>



 {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
