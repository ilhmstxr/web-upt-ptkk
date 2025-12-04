{{-- resources/views/pages/daftar.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pendaftaran Program Pelatihan - UPT PTKK</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    :root{
      --biru-brand: #1524AF;
      --bg-soft: #F1F9FC;
    }

    body{
      font-family: 'Montserrat', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background-color: var(--bg-soft);
      color: #0f172a;
    }

    .font-volkhov{
      font-family: 'Volkhov', serif;
    }

    /* Hilangkan warna autofill Chrome */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:hover,
    select:-webkit-autofill:focus {
      -webkit-box-shadow: 0 0 0 1000px white inset !important;
      box-shadow: 0 0 0 1000px white inset !important;
      transition: background-color 5000s ease-in-out 0s;
      color: inherit !important;
    }

    /* Stroke kuning judul accordion */
    .judul-stroke {
      color: #1524AF;
      -webkit-text-stroke: 1.3px #FFDE59;
      paint-order: stroke fill;
    }
  </style>
</head>

<body class="min-h-screen flex flex-col text-slate-900">

  {{-- TOPBAR --}}
  @includeIf('components.layouts.app.topbar')

  {{-- NAVBAR LANDING --}}
  @includeIf('components.layouts.app.navbarlanding')

 <main class="flex-1 pt-6 pb-12">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] space-y-8">

    @php
      // pastikan selalu boolean, aman walau $pelatihan null
      $adaPelatihanAktif = isset($pelatihan)
        && $pelatihan instanceof \Illuminate\Support\Collection
        && $pelatihan->isNotEmpty();
    @endphp

    @if (! $adaPelatihanAktif)
      {{-- =================== STATE: TIDAK ADA PELATIHAN =================== --}}
      <section class="mt-10">
        <div class="flex justify-center">
          <div
            class="w-full md:w-[720px] bg-gradient-to-r from-[#F1F9FF] to-[#C9E3FF]
                   border-2 border-[#1D4ED8] rounded-3xl px-6 py-10 md:px-10 md:py-12
                   shadow-sm"
          >
            <h2
              class="font-volkhov font-bold text-[18px] md:text-[22px] text-[#1524AF] text-center mb-3"
            >
              Saat ini belum ada program pelatihan yang sedang berlangsung
            </h2>

            <p class="text-slate-700 text-[14px] md:text-[15px] text-center mb-8">
              Sambil menunggu, kamu dapat menyiapkan berkas dan mengajukan permohonan
              peserta terlebih dahulu sesuai panduan.
            </p>

            <div class="flex justify-center">
              <a
                href="{{ url('/panduan') }}" {{-- nanti sesuaikan route panduanmu --}}
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-full
                       bg-[#1524AF] text-white text-[14px] font-medium
                       hover:bg-[#1524AF]/90 transition"
              >
                Lihat Panduan
                <span class="ml-2">➜</span>
              </a>
            </div>
          </div>
        </div>
      </section>
    @else
      {{-- =================== STATE: ADA PELATIHAN → TAMPILKAN ACCORDION + FORM =================== --}}

      {{-- ===================== ACCORDION INFORMASI PROGRAM ===================== --}}
      <section class="space-y-4" id="accordionInformasiProgram">

        {{-- 1. Syarat dan Ketentuan Peserta --}}
        <details
          class="group rounded-2xl border-[2px] border-[#1524AF] overflow-hidden"
          style="background: linear-gradient(90deg, #F1F9FC 0%, #98C1F8 100%);"
        >
          <summary class="list-none cursor-pointer px-2 md:px-3 py-2 flex items-center gap-2 min-h-[40px]">
            {{-- Panah segitiga --}}
            <span
              class="inline-flex items-center justify-center w-6 h-6 flex-shrink-0
                     transition-transform duration-200 group-open:rotate-90"
            >
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <polygon
                  points="7,4 7,20 19,12"
                  fill="#1524AF"
                  stroke="#FFDE59"
                  stroke-width="1"
                  stroke-linejoin="round"
                />
              </svg>
            </span>

            {{-- Judul --}}
            <span class="font-volkhov font-bold text-[16px] judul-stroke">
              Syarat dan Ketentuan Peserta
            </span>
          </summary>

          {{-- Isi --}}
          <div class="px-4 md:px-6 pb-5 pt-1 text-[14px] text-[#000] font-medium">
            <ul class="space-y-2 text-[14px] font-medium text-[#000000]">
              <li class="flex gap-2">
                <span class="text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                Calon peserta kelas XI dengan bidang kompetensi sesuai kegiatan pelatihan.
              </li>

              <li class="flex gap-2">
                <span class="text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                Mengisi formulir pendaftaran dengan data diri lengkap.
              </li>

              <li class="flex flex-col gap-1">
                <div class="flex gap-2">
                  <span class="text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                  Menyiapkan berkas berikut:
                </div>

                <ul class="pl-6 space-y-1">
                  <li class="flex gap-2 items-center">
                    <span
                      class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                             text-[10px] font-bold"
                    >
                      ✓
                    </span>
                    Fotocopy ijazah terakhir
                  </li>
                  <li class="flex gap-2 items-center">
                    <span
                      class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                             text-[10px] font-bold"
                    >
                      ✓
                    </span>
                    Pas foto background merah (3 lembar)
                  </li>
                  <li class="flex gap-2 items-center">
                    <span
                      class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                             text-[10px] font-bold"
                    >
                      ✓
                    </span>
                    Surat tugas pejabat berwenang
                  </li>
                  <li class="flex gap-2 items-center">
                    <span
                      class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                             text-[10px] font-bold"
                    >
                      ✓
                    </span>
                    Fotocopy KTP/KK atau Kartu Pelajar
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </details>

        {{-- 2. Jadwal Program Pelatihan --}}
        <details
          class="group rounded-2xl border-[2px] border-[#1524AF] overflow-hidden"
          style="background: linear-gradient(90deg, #F1F9FC 0%, #98C1F8 100%);"
        >
          <summary class="list-none cursor-pointer px-2 md:px-3 py-2 flex items-center gap-2 min-h-[40px]">
            <span
              class="inline-flex items-center justify-center w-6 h-6 flex-shrink-0
                     transition-transform duration-200 group-open:rotate-90"
            >
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <polygon
                  points="7,4 7,20 19,12"
                  fill="#1524AF"
                  stroke="#FFDE59"
                  stroke-width="1"
                  stroke-linejoin="round"
                />
              </svg>
            </span>

            <span class="font-volkhov font-bold text-[16px] judul-stroke">
              Jadwal Program Pelatihan
            </span>
          </summary>

          <div class="px-3 md:px-4 pb-4 pt-2 text-[14px] text-[#000] font-medium">
            <div class="space-y-2">

              {{-- Baris 1 --}}
              <div class="flex items-start gap-4">
                <div class="relative pl-6 flex-1">
                  <span
                    class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold
                           leading-none text-[17px]"
                  >
                    >
                  </span>
                  Masa Pengisian Link Pendaftaran
                </div>

                <span
                  class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right"
                >
                  17 September s/d 20 September 2025
                </span>
              </div>

              {{-- Baris 2 --}}
              <div class="flex items-start gap-4">
                <div class="relative pl-6 flex-1">
                  <span
                    class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold
                           leading-none text-[17px]"
                  >
                    >
                  </span>
                  Proses Administrasi
                </div>

                <span
                  class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right"
                >
                  17 September s/d 20 September 2025
                </span>
              </div>

              {{-- Baris 3 --}}
              <div class="flex items-start gap-4">
                <div class="relative pl-6 flex-1">
                  <span
                    class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold
                           leading-none text-[17px]"
                  >
                    >
                  </span>
                  Registrasi Ulang Secara Offline
                </div>

                <span
                  class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right"
                >
                  17 September s/d 20 September 2025
                </span>
              </div>

              {{-- Baris 4 --}}
              <div class="flex items-start gap-4">
                <div class="relative pl-6 flex-1">
                  <span
                    class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold
                           leading-none text-[17px]"
                  >
                    >
                  </span>
                  Masa Belajar
                </div>

                <span
                  class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right"
                >
                  17 September s/d 20 September 2025
                </span>
              </div>
            </div>

            <p class="text-xs mt-3">
              *Jadwal dapat berubah sewaktu-waktu oleh admin.
            </p>
          </div>
        </details>

        {{-- 3. Lokasi Pelaksanaan Pelatihan --}}
        <details
          class="group rounded-2xl border-[2px] border-[#1524AF] overflow-hidden"
          style="background: linear-gradient(90deg, #F1F9FC 0%, #98C1F8 100%);"
        >
          <summary class="list-none cursor-pointer px-2 md:px-3 py-2 flex items-center gap-2 min-h-[40px]">
            <span
              class="inline-flex items-center justify-center w-6 h-6 flex-shrink-0
                     transition-transform duration-200 group-open:rotate-90"
            >
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <polygon
                  points="7,4 7,20 19,12"
                  fill="#1524AF"
                  stroke="#FFDE59"
                  stroke-width="1"
                  stroke-linejoin="round"
                />
              </svg>
            </span>

            <span class="font-volkhov font-bold text-[16px] judul-stroke">
              Lokasi Pelaksanaan Pelatihan
            </span>
          </summary>

          {{-- Isi --}}
          <div class="px-3 md:px-4 pb-4 pt-2 text-[14px] text-[#000] font-medium">
            <ul class="space-y-3 text-[14px] font-medium text-[#000000]">

              {{-- 1. Programmable Logic Controller --}}
              <li class="relative pl-6">
                <span
                  class="absolute left-0 top-[4px] text-[#1524AF] inline-block font-bold
                         leading-none text-[17px]"
                >
                  >
                </span>

                <div class="space-y-1">
                  <p class="font-semibold">Programmable Logic Controller</p>

                  <div class="flex items-start gap-2">
                    {{-- Icon lokasi --}}
                    <span
                      class="mt-[2px] inline-flex h-5 w-5 items-center justify-center
                             rounded-full bg-[#1524AF]"
                    >
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="#FFFFFF">
                        <path
                          d="M12 2C8.7 2 6 4.7 6 8c0 4.1 4.6 9.4 5.6 10.6.2.2.5.4.8.4s.6-.1.8-.4C13.4 17.4 18 12.1 18 8c0-3.3-2.7-6-6-6zm0 8.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 5.5 12 5.5s2.5 1.1 2.5 2.5S13.4 10.5 12 10.5z"
                        />
                      </svg>
                    </span>

                    <div class="space-y-0.5">
                      <p>UPT Pengembangan Teknik Dan Keterampilan Kejuruan</p>
                      <p>
                        Komplek Kampus Unesa Jl. Ketintang No.25, Ketintang, Kec. Gayungan,
                        Surabaya, Jawa Timur 60231
                      </p>
                    </div>
                  </div>
                </div>
              </li>

              {{-- 2. Teknik Pendingin dan Tata Udara --}}
              <li class="relative pl-6">
                <span
                  class="absolute left-0 top-[4px] text-[#1524AF] inline-block font-bold
                         leading-none text-[17px]"
                >
                  >
                </span>

                <div class="space-y-1">
                  <p class="font-semibold">Teknik Pendingin dan Tata Udara</p>

                  <div class="flex items-start gap-2">
                    <span
                      class="mt-[2px] inline-flex h-5 w-5 items-center justify-center
                             rounded-full bg-[#1524AF]"
                    >
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="#FFFFFF">
                        <path
                          d="M12 2C8.7 2 6 4.7 6 8c0 4.1 4.6 9.4 5.6 10.6.2.2.5.4.8.4s.6-.1.8-.4C13.4 17.4 18 12.1 18 8c0-3.3-2.7-6-6-6zm0 8.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 5.5 12 5.5s2.5 1.1 2.5 2.5S13.4 10.5 12 10.5z"
                        />
                      </svg>
                    </span>

                    <div class="space-y-0.5">
                      <p>SMK Negeri 1 Wonoerjo</p>
                      <p>
                        Komplek Kampus Unesa Jl. Ketintang No.25, Ketintang, Kec. Gayungan,
                        Surabaya, Jawa Timur 60231
                      </p>
                    </div>
                  </div>
                </div>
              </li>

              {{-- 3. Fotografi --}}
              <li class="relative pl-6">
                <span
                  class="absolute left-0 top-[4px] text-[#1524AF] inline-block font-bold
                         leading-none text-[17px]"
                >
                  >
                </span>

                <div class="space-y-1">
                  <p class="font-semibold">Fotografi</p>

                  <div class="flex items-start gap-2">
                    <span
                      class="mt-[2px] inline-flex h-5 w-5 items-center justify-center
                             rounded-full bg-[#1524AF]"
                    >
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="#FFFFFF">
                        <path
                          d="M12 2C8.7 2 6 4.7 6 8c0 4.1 4.6 9.4 5.6 10.6.2.2.5.4.8.4s.6-.1.8-.4C13.4 17.4 18 12.1 18 8c0-3.3-2.7-6-6-6zm0 8.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 5.5 12 5.5s2.5 1.1 2.5 2.5S13.4 10.5 12 10.5z"
                        />
                      </svg>
                    </span>

                    <div class="space-y-0.5">
                      <p>SMK Negeri 1 Wonoerjo</p>
                      <p>
                        Komplek Kampus Unesa Jl. Ketintang No.25, Ketintang, Kec. Gayungan,
                        Surabaya, Jawa Timur 60231
                      </p>
                    </div>
                  </div>
                </div>
              </li>

            </ul>
          </div>
        </details>
      </section>

      {{-- =========================================================
           SECTION: FORM PENDAFTARAN (MULTI STEP + STEPPER AKTIF)
      ========================================================= --}}
      <section aria-labelledby="form-pendaftaran-heading" class="mt-8">

        {{-- KARTU WIZARD --}}
        <div
          id="wizardContainer"
          class="rounded-2xl border-[4px] border-[#B6BBE6] overflow-hidden bg-[#F1F9FC]"
        >
          <div
            id="wizardSteps"
            class="flex flex-col md:flex-row items-stretch min-h-[520px]"
          >

          {{-- ======================= KIRI: STEPPER (HANYA TABLET & DESKTOP) ======================= --}}
<aside
  class="hidden md:flex md:w-[260px] lg:w-[300px] bg-[#DBE7F7] px-6 py-8 flex-col"
>
  <h2
    id="form-pendaftaran-heading"
    class="font-volkhov text-[20px] md:text-[22px] text-[#1524AF] judul-stroke"
  >
    Form Pendaftaran
  </h2>

  <div class="mt-10 flex-1 flex">
    <div class="flex flex-col items-start relative">

      {{-- STEP 1 --}}
      <div class="flex items-center gap-3" id="stepItem1">
        <div
          id="stepCircle1"
          class="w-12 h-12 rounded-full flex items-center justify-center
                 text-[15px] font-semibold shadow-sm transition-colors duration-200"
        >
          1
        </div>
        <span
          id="stepLabel1"
          class="text-[15px] transition-colors duration-200"
        >
          Data diri
        </span>
      </div>

      {{-- GARIS 1 → 2 --}}
      <div class="flex">
        <div
          id="stepLine1"
          class="ml-[23px] w-[2px] h-28 md:h-32 bg-white transition-colors duration-200"
          style="margin-top:-1px; margin-bottom:-14px;"
        ></div>
      </div>

      {{-- STEP 2 --}}
      <div class="flex items-center gap-3" id="stepItem2">
        <div
          id="stepCircle2"
          class="w-12 h-12 rounded-full flex items-center justify-center
                 text-[15px] font-semibold shadow-sm transition-colors duration-200"
        >
          2
        </div>
        <span
          id="stepLabel2"
          class="text-[15px] transition-colors duration-200"
        >
          Data Lembaga
        </span>
      </div>

      {{-- GARIS 2 → 3 --}}
      <div class="flex">
        <div
          id="stepLine2"
          class="ml-[23px] w-[2px] h-28 md:h-32 bg-white transition-colors duration-200"
          style="margin-top:-1px; margin-bottom:-14px;"
        ></div>
      </div>

      {{-- STEP 3 --}}
      <div class="flex items-center gap-3" id="stepItem3">
        <div
          id="stepCircle3"
          class="w-12 h-12 rounded-full flex items-center justify-center
                 text-[15px] font-semibold shadow-sm transition-colors duration-200"
        >
          3
        </div>
        <span
          id="stepLabel3"
          class="text-[15px] transition-colors duration-200"
        >
          Lampiran
        </span>
      </div>

    </div>
  </div>
</aside>

            {{-- ======================= KANAN: FORM MULTI STEP ======================= --}}
            <div class="flex-1 px-6 py-8">

            {{-- ======================= MOBILE STEPPER (HORIZONTAL FINAL + GARIS NEMPEL) ======================= --}}
<div class="md:hidden mb-6">
  <h2
    class="font-volkhov text-[18px] text-[#1524AF] judul-stroke text-center mb-4"
  >
    Form Pendaftaran
  </h2>

  <div class="max-w-md mx-auto select-none">

    {{-- ROW ATAS: CIRCLE + GARIS --}}
    <div class="flex items-center w-full">

      {{-- STEP 1 CIRCLE --}}
      <div class="flex flex-col items-center">
        <div
          id="mobileStepCircle1"
          class="w-10 h-10 rounded-full flex items-center justify-center
                 text-[14px] font-semibold bg-white text-[#6B7280]
                 border border-transparent shadow-sm transition-colors duration-200"
        >
          1
        </div>
      </div>

      {{-- GARIS 1 → 2 --}}
      <div
        id="mobileStepLine1"
        class="flex-1 h-[2px] bg-white transition-colors duration-200"
      ></div>

      {{-- STEP 2 CIRCLE --}}
      <div class="flex flex-col items-center">
        <div
          id="mobileStepCircle2"
          class="w-10 h-10 rounded-full flex items-center justify-center
                 text-[14px] font-semibold bg-white text-[#6B7280]
                 border border-transparent shadow-sm transition-colors duration-200"
        >
          2
        </div>
      </div>

      {{-- GARIS 2 → 3 --}}
      <div
        id="mobileStepLine2"
        class="flex-1 h-[2px] bg-white transition-colors duration-200"
      ></div>

      {{-- STEP 3 CIRCLE --}}
      <div class="flex flex-col items-center">
        <div
          id="mobileStepCircle3"
          class="w-10 h-10 rounded-full flex items-center justify-center
                 text-[14px] font-semibold bg-white text-[#6B7280]
                 border border-transparent shadow-sm transition-colors duration-200"
        >
          3
        </div>
      </div>

    </div>

    {{-- ROW BAWAH LABEL (PAS ADA DI BAWAH CIRCLE) --}}
    <div class="flex justify-between mt-2 text-[12px] tracking-tight w-full">

      <span
        id="mobileStepLabel1"
        class="w-10 text-center text-[#6B7280] leading-tight"
      >
        Data diri
      </span>

      <span
        id="mobileStepLabel2"
        class="w-[72px] text-center text-[#6B7280] leading-tight"
      >
        Data Lembaga
      </span>

      <span
        id="mobileStepLabel3"
        class="w-10 text-center text-[#6B7280] leading-tight"
      >
        Lampiran
      </span>

    </div>
  </div>
</div>
              {{-- STEP 1: DATA DIRI --}}
              <div id="step1" class="step-content block">
                <form id="form-step1" class="flex flex-col min-h-[520px] space-y-6">

                  {{-- KONTEN FIELD --}}
                  <div class="space-y-4 flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">

                      {{-- Nama --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Nama
                        </label>
                        <input
                          type="text"
                          name="nama"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan Nama"
                          oninvalid="this.setCustomValidity('Nama wajib diisi')"
                          oninput="this.setCustomValidity('')"
                        />
                      </div>

                      {{-- NIK --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          NIK
                        </label>
                        <input
                          type="text"
                          id="nik"
                          name="nik"
                          required
                          maxlength="16"
                          inputmode="numeric"
                          pattern="\d{16}"
                          data-form-field="true"
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan NIK"
                          oninvalid="this.setCustomValidity('NIK harus tepat 16 digit angka')"
                          oninput="
                            this.value = this.value.replace(/[^0-9]/g, '');
                            this.setCustomValidity('');
                          "
                        >
                      </div>

                      {{-- Nomor HP --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Nomor Handphone
                        </label>
                        <input
                          type="tel"
                          id="no_hp"
                          name="no_hp"
                          required
                          maxlength="16"
                          inputmode="tel"
                          pattern="^\+?\d{1,15}$"
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan No. HP (contoh: 0812… atau +62812…)"
                          oninvalid="this.setCustomValidity('Nomor HP hanya boleh angka dan boleh diawali +, maksimal 15 digit angka')"
                          oninput="
                            this.setCustomValidity('');
                            this.value = this.value.replace(/[^\d+]/g, '');
                            if (this.value.indexOf('+') > 0) {
                              this.value = this.value.replace(/\+/g, '');
                            }
                            const plus = this.value.startsWith('+');
                            const digits = this.value.replace(/[^\d]/g, '');
                            this.value = (plus ? '+' : '') + digits;
                          "
                        >
                      </div>

                      {{-- Email --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Email
                        </label>
                        <input
                          type="email"
                          name="email"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan Email"
                          oninvalid="this.setCustomValidity('Format email tidak valid, pastikan mengandung @')"
                          oninput="this.setCustomValidity('')"
                        >
                      </div>

                      {{-- Tempat Lahir --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Tempat Lahir
                        </label>
                        <input
                          type="text"
                          name="tempat_lahir"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan Tempat Lahir"
                          oninvalid="this.setCustomValidity('Tempat lahir wajib diisi')"
                          oninput="this.setCustomValidity('')"
                        >
                      </div>

                      {{-- Tanggal Lahir --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Tanggal Lahir
                        </label>
                        <input
                          type="date"
                          name="tanggal_lahir"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          oninvalid="this.setCustomValidity('Tanggal lahir wajib diisi')"
                          oninput="this.setCustomValidity('')"
                        >
                      </div>

                      {{-- Jenis Kelamin --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Jenis Kelamin
                        </label>
                        <select
                          name="jenis_kelamin"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          oninvalid="this.setCustomValidity('Silakan pilih jenis kelamin')"
                          oninput="this.setCustomValidity('')"
                        >
                          <option value="">Pilih Jenis Kelamin</option>
                          <option value="Laki-laki">Laki-laki</option>
                          <option value="Perempuan">Perempuan</option>
                        </select>
                      </div>

                      {{-- Agama --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Agama
                        </label>
                        <select
                          name="agama"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          oninvalid="this.setCustomValidity('Silakan pilih agama')"
                          oninput="this.setCustomValidity('')"
                        >
                          <option value="">Masukkan Agama</option>
                          <option value="Islam">Islam</option>
                          <option value="Kristen">Kristen</option>
                          <option value="Katolik">Katolik</option>
                          <option value="Hindu">Hindu</option>
                          <option value="Buddha">Buddha</option>
                          <option value="Konghucu">Konghucu</option>
                        </select>
                      </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                      <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                        Alamat Tempat Tinggal
                      </label>
                      <textarea
                        name="alamat"
                        rows="3"
                        required
                        class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                               text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                               focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                        placeholder="Masukkan Alamat"
                        oninvalid="this.setCustomValidity('Alamat wajib diisi')"
                        oninput="this.setCustomValidity('')"
                      ></textarea>
                    </div>
                  </div>

                  {{-- TOMBOL BAWAH --}}
                  <div class="flex justify-end">
                    <button
                      type="button"
                      id="btnToStep2"
                      class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             bg-[#1524AF] text-white font-medium font-[Montserrat]
                             text-[14px] hover:opacity-90 transition"
                    >
                      Selanjutnya
                      <span class="inline-block ml-2">➜</span>
                    </button>
                  </div>

                </form>
              </div>

              {{-- STEP 2: DATA LEMBAGA --}}
              <div id="step2" class="step-content hidden">
                <form id="form-step2" class="flex flex-col min-h-[520px] space-y-6">

                  <div class="flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">

                      {{-- Asal Lembaga Sekolah --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Asal Lembaga Sekolah
                        </label>
                        <input
                          type="text"
                          name="asal_lembaga"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan Asal Lembaga"
                        >
                      </div>

                      {{-- Alamat Sekolah --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Alamat Sekolah
                        </label>
                        <input
                          type="text"
                          name="alamat_sekolah"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan Alamat Sekolah"
                        >
                      </div>

                      {{-- Kompetensi / Bidang Pelatihan --}}
                      <div>
                        <label
                          for="bidang_keahlian"
                          class="block text-sm font-semibold text-blue-900 mb-2"
                        >
                          Kompetensi / Bidang Pelatihan
                        </label>
                        <select
                          id="bidang_keahlian"
                          name="bidang_keahlian"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] text-slate-800
                                 focus:outline-none focus:ring-2 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                        >
                          <option value="">Pilih Kompetensi</option>
                          @foreach ($bidang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                          @endforeach
                        </select>
                      </div>

                      {{-- Kelas --}}
                      <div>
                        <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                          Kelas
                        </label>
                        <input
                          type="text"
                          name="kelas"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                          placeholder="Masukkan Kelas"
                        >
                      </div>

                      {{-- Cabang Dinas Wilayah --}}
                      <div>
                        <label
                          for="cabangDinas_id"
                          class="block text-sm font-semibold text-blue-900 mb-2"
                        >
                          Cabang Dinas Wilayah
                        </label>
                        <select
                          id="cabangDinas_id"
                          name="cabangDinas_id"
                          required
                          class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] text-slate-800
                                 focus:outline-none focus:ring-2 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                        >
                          <option value="">Pilih Cabang Dinas</option>
                          @foreach ($cabangDinas as $cb)
                            <option value="{{ $cb->id }}">{{ $cb->nama }}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>
                  </div>

                  {{-- Tombol Step 2 --}}
                  <div class="flex justify-end gap-3">
                    <button
                      type="button"
                      id="btnBackToStep1"
                      class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             border border-[#1524AF] bg-[#DBE7F7]
                             text-[#1524AF] font-medium font-[Montserrat]
                             text-[14px] hover:bg-[#DBE7F7]/80 transition"
                    >
                      <span class="inline-block transform rotate-180 mr-2">➜</span>
                      Sebelumnya
                    </button>

                    <button
                      type="button"
                      id="btnToStep3"
                      class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             bg-[#1524AF] text-white font-medium font-[Montserrat]
                             text-[14px] hover:opacity-90 transition"
                    >
                      Selanjutnya
                      <span class="inline-block ml-2">➜</span>
                    </button>
                  </div>

                </form>
              </div>

              {{-- STEP 3: LAMPIRAN --}}
              <div id="step3" class="step-content hidden">
                <div class="flex flex-col min-h-[520px] space-y-6">

                  <div class="flex-1">
                    <h2 class="text-[18px] md:text-[20px] font-semibold text-[#1524AF] mb-4">
                      Lampiran Berkas
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">

                      {{-- KOLOM KIRI --}}
                      <div class="space-y-4">

                        {{-- Unggah Fotocopy KTP --}}
                        <div>
                          <label class="block text-[14px] text-[#1A2340] mb-1">
                            Unggah Fotocopy KTP
                          </label>
                        <div class="relative">
                        <input
                            type="file"
                            name="lampiran_ktp"
                            accept=".jpg,.jpeg,.png,.pdf"
                            data-file-input="lampiran_ktp"
                            class="absolute inset-0 opacity-0 cursor-pointer z-10"
                        />
                        <div
                            class="flex items-center bg-white/80 rounded-full
                                border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]"
                        >
                            {{-- ⬇️ LABEL FILE: dikasih flex-1 + min-w-0 biar yang ke-truncate dia --}}
                            <span
                            class="truncate file-label flex-1 min-w-0"
                            data-file-label="lampiran_ktp"
                            >
                            Tidak ada file yang dipilih
                            </span>

                            {{-- ⬇️ CHIP PILIH FILE: dikasih flex-shrink-0 + whitespace-nowrap biar nggak ikut mengecil --}}
                            <span
                            class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap"
                            >
                            Pilih File
                            </span>
                        </div>
                        </div>
                          <div class="mt-2 min-h-[1.25rem]" data-file-preview="lampiran_ktp"></div>
                        </div>

                       {{-- Unggah Fotocopy Surat Tugas --}}
                        <div>
                        <label class="block text-[14px] text-[#1A2340] mb-1">
                            Unggah Fotocopy Surat Tugas
                        </label>

                        <div class="relative">
                            <input
                            type="file"
                            name="lampiran_surat_tugas"
                            accept=".jpg,.jpeg,.png,.pdf"
                            data-file-input="lampiran_surat_tugas"
                            class="absolute inset-0 opacity-0 cursor-pointer z-10"
                            />

                            <div
                            class="flex items-center bg-white/80 rounded-full
                                    border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]"
                            >
                            {{-- Label Nama File --}}
                            <span
                                class="truncate file-label flex-1 min-w-0"
                                data-file-label="lampiran_surat_tugas"
                            >
                                Tidak ada file yang dipilih
                            </span>

                            {{-- Tombol Pilih File --}}
                            <span
                                class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap"
                            >
                                Pilih File
                            </span>
                            </div>
                        </div>

                        <div class="mt-2 min-h-[1.25rem]" data-file-preview="lampiran_surat_tugas"></div>
                        </div>

                        {{-- Nomor Surat Tugas --}}
                        <div>
                          <label class="block text-[14px] text-[#1A2340] mb-1">
                            Nomor Surat Tugas
                          </label>
                          <input
                            type="text"
                            name="nomor_surat_tugas"
                            placeholder="Masukkan Nomor Surat Tugas"
                            class="w-full h-10 rounded-full border border-[#B6BBE6]
                                   bg-white px-4 text-[13px] text-[#1A2340]
                                   placeholder:text-[#9AA1B3] focus:outline-none
                                   focus:ring-1 focus:ring-[#1524AF] focus:border-[#1524AF]"
                          />
                        </div>
                      </div>

                     {{-- KOLOM KANAN --}}
                    <div class="space-y-4">

                    {{-- Unggah Fotocopy Ijazah Terakhir --}}
                    <div>
                        <label class="block text-[14px] text-[#1A2340] mb-1">
                        Unggah Fotocopy Ijazah Terakhir
                        </label>

                        <div class="relative">
                        <input
                            type="file"
                            name="lampiran_ijazah"
                            accept=".jpg,.jpeg,.png,.pdf"
                            data-file-input="lampiran_ijazah"
                            class="absolute inset-0 opacity-0 cursor-pointer z-10"
                        />

                        <div
                            class="flex items-center bg-white/80 rounded-full
                                border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]"
                        >
                            {{-- Label Nama File --}}
                            <span
                            class="truncate file-label flex-1 min-w-0"
                            data-file-label="lampiran_ijazah"
                            >
                            Tidak ada file yang dipilih
                            </span>

                            {{-- Tombol Pilih File --}}
                            <span
                            class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap"
                            >
                            Pilih File
                            </span>
                        </div>
                        </div>

                        <div class="mt-2 min-h-[1.25rem]" data-file-preview="lampiran_ijazah"></div>
                    </div>


                       {{-- Unggah Surat Sehat --}}
                        <div>
                        <label class="block text-[14px] text-[#1A2340] mb-1">
                            Unggah Surat Sehat
                        </label>

                        <div class="relative">
                            <input
                            type="file"
                            name="lampiran_surat_sehat_kanan"
                            accept=".jpg,.jpeg,.png,.pdf"
                            data-file-input="lampiran_surat_sehat_kanan"
                            class="absolute inset-0 opacity-0 cursor-pointer z-10"
                            />

                            <div
                            class="flex items-center bg-white/80 rounded-full
                                    border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]"
                            >
                            {{-- Label Nama File --}}
                            <span
                                class="truncate file-label flex-1 min-w-0"
                                data-file-label="lampiran_surat_sehat_kanan"
                            >
                                Tidak ada file yang dipilih
                            </span>

                            {{-- Tombol Pilih File --}}
                            <span
                                class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap"
                            >
                                Pilih File
                            </span>
                            </div>
                        </div>

                        <div class="mt-2 min-h-[1.25rem]" data-file-preview="lampiran_surat_sehat_kanan"></div>
                        </div>

                    {{-- Unggah Pas Foto --}}
                    <div>
                    <label class="block text-[14px] text-[#1A2340] mb-1">
                        Unggah Pas Foto
                    </label>

                    <div class="relative">
                        <input
                        type="file"
                        name="lampiran_pas_foto_kanan"
                        accept=".jpg,.jpeg,.png,.pdf"
                        data-file-input="lampiran_pas_foto_kanan"
                        class="absolute inset-0 opacity-0 cursor-pointer z-10"
                        />

                        <div
                        class="flex items-center bg-white/80 rounded-full
                                border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]"
                        >
                        {{-- Label Nama File --}}
                        <span
                            class="truncate file-label flex-1 min-w-0"
                            data-file-label="lampiran_pas_foto_kanan"
                        >
                            Tidak ada file yang dipilih
                        </span>

                        {{-- Tombol Pilih File --}}
                        <span
                            class="ml-3 inline-flex items-center justify-center h-7 px-4
                                rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                flex-shrink-0 whitespace-nowrap"
                        >
                            Pilih File
                        </span>
                        </div>
                    </div>

                    <div class="mt-2 min-h-[1.25rem]" data-file-preview="lampiran_pas_foto_kanan"></div>
                    </div>


                      </div>
                    </div>
                  </div>

                  {{-- TOMBOL NAVIGASI BAWAH --}}
                  <div class="flex justify-end gap-3">
                    <button
                      type="button"
                      id="btnBackToStep2"
                      class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             border border-[#1524AF] bg-[#DBE7F7]
                             text-[#1524AF] font-medium font-[Montserrat]
                             text-[14px] hover:bg-[#DBE7F7]/80 transition"
                    >
                      <span class="inline-block transform rotate-180 mr-2">➜</span>
                      Sebelumnya
                    </button>

                    <button
                      type="submit"
                      id="btnSubmit"
                      class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             bg-[#1524AF] text-white font-medium font-[Montserrat]
                             text-[14px] hover:opacity-90 transition"
                    >
                      Submit
                      <span class="inline-block ml-2">➜</span>
                    </button>
                  </div>

                </div>
              </div>

            </div> {{-- end flex-1 (KANAN) --}}
          </div> {{-- end wizardSteps --}}
        </div> {{-- end wizardContainer --}}

        {{-- CARD SUKSES SETELAH SUBMIT --}}
        <div class="mt-8 p-2">
          <div
            id="successCard"
            class="hidden rounded-2xl border-[4px] border-[#B6BBE6] bg-[#DBE7F7]
                   px-6 py-12 md:py-16 text-center"
          >
            <h2
              class="font-volkhov font-bold text-[22px] md:text-[26px] text-[#1524AF] judul-stroke mb-4"
            >
              Data pendaftaran Anda berhasil dikirim dan akan diproses di hari kerja
            </h2>

            <p
              class="text-[14px] md:text-[16px] font-medium text-[#070D3D] leading-relaxed mx-auto"
            >
              Informasi lebih lanjut akan kami kirimkan melalui email yang telah Anda daftarkan.
            </p>
            <p
              class="mt-2 text-[14px] md:text-[16px] font-medium text-[#070D3D] leading-relaxed max-w-2xl mx-auto"
            >
              Harap periksa folder Inbox dan Spam secara berkala.
            </p>
          </div>
        </div>

      </section>
@endif
    </div>
  </main>

  @push('scripts')
  <script>
    // ================== LOGIC MULTI STEP + STEPPER + HALAMAN SUKSES ==================
    (function () {
      const step1 = document.getElementById('step1');
      const step2 = document.getElementById('step2');
      const step3 = document.getElementById('step3');

      const form1 = document.getElementById('form-step1');
      const form2 = document.getElementById('form-step2');

      // container & card sukses
      const wizardContainer = document.getElementById('wizardContainer');
      const wizardSteps     = document.getElementById('wizardSteps');
      const successCard     = document.getElementById('successCard');
      const btnSubmit       = document.getElementById('btnSubmit');

      // tombol navigasi
      const btnToStep2     = document.getElementById('btnToStep2');
      const btnBackToStep1 = document.getElementById('btnBackToStep1');
      const btnToStep3     = document.getElementById('btnToStep3');
      const btnBackToStep2 = document.getElementById('btnBackToStep2');

      // elemen stepper
      const circles = {
        1: document.getElementById('stepCircle1'),
        2: document.getElementById('stepCircle2'),
        3: document.getElementById('stepCircle3'),
      };
      const labels = {
        1: document.getElementById('stepLabel1'),
        2: document.getElementById('stepLabel2'),
        3: document.getElementById('stepLabel3'),
      };
      const lines = {
        1: document.getElementById('stepLine1'),
        2: document.getElementById('stepLine2'),
      };

            // elemen stepper MOBILE (horizontal)
      const mobileCircles = {
        1: document.getElementById('mobileStepCircle1'),
        2: document.getElementById('mobileStepCircle2'),
        3: document.getElementById('mobileStepCircle3'),
      };
      const mobileLabels = {
        1: document.getElementById('mobileStepLabel1'),
        2: document.getElementById('mobileStepLabel2'),
        3: document.getElementById('mobileStepLabel3'),
      };
      const mobileLines = {
        1: document.getElementById('mobileStepLine1'),
        2: document.getElementById('mobileStepLine2'),
      };

           function updateStepper(step) {
        const current = Math.min(Math.max(step, 1), 3);

        // ============ DESKTOP / TABLET STEPPER (KIRI) ============
        // reset semua circle & label ke default
        for (let i = 1; i <= 3; i++) {
          const c = circles[i];
          const l = labels[i];
          if (!c || !l) continue;

          c.className =
            'w-12 h-12 rounded-full flex items-center justify-center text-[15px] font-semibold ' +
            'shadow-sm transition-colors duration-200 bg-white text-[#6B7280] border border-transparent';
          c.textContent = String(i);

          l.className = 'text-[15px] transition-colors duration-200 text-[#6B7280]';
        }

        // reset garis ke putih
        Object.values(lines).forEach(line => {
          if (!line) return;
          line.classList.remove('bg-[#1524AF]');
          line.classList.add('bg-white');
        });

        // step yang SUDAH dilewati → biru + centang
        for (let i = 1; i < current; i++) {
          const c = circles[i];
          const l = labels[i];
          if (!c || !l) continue;

          c.classList.remove('bg-white', 'text-[#6B7280]');
          c.classList.add('bg-[#1524AF]', 'text-white', 'border-[#FFDE59]');
          c.textContent = '✓';

          l.classList.remove('text-[#6B7280]');
          l.classList.add('text-[#1524AF]', 'font-semibold');
        }

        // garis yang sudah dilewati jadi biru
        if (current >= 2 && lines[1]) {
          lines[1].classList.remove('bg-white');
          lines[1].classList.add('bg-[#1524AF]');
        }
        if (current >= 3 && lines[2]) {
          lines[2].classList.remove('bg-white');
          lines[2].classList.add('bg-[#1524AF]');
        }

        // ============ MOBILE STEPPER (HORIZONTAL ATAS FORM) ============
        // reset semua circle & label mobile ke default
        for (let i = 1; i <= 3; i++) {
          const c = mobileCircles[i];
          const l = mobileLabels[i];
          if (!c || !l) continue;

          c.className =
            'w-9 h-9 rounded-full flex items-center justify-center text-[13px] font-semibold ' +
            'bg-white text-[#6B7280] border border-transparent shadow-sm transition-colors duration-200';
          c.textContent = String(i);

          l.className = 'mt-1 text-[12px] text-[#6B7280]';
        }

        // reset garis mobile ke putih
        Object.values(mobileLines).forEach(line => {
          if (!line) return;
          line.classList.remove('bg-[#1524AF]');
          line.classList.add('bg-white');
        });

        // step yang SUDAH dilewati di mobile → biru + centang (sama dengan desktop)
        for (let i = 1; i < current; i++) {
          const c = mobileCircles[i];
          const l = mobileLabels[i];
          if (!c || !l) continue;

          c.classList.remove('bg-white', 'text-[#6B7280]');
          c.classList.add('bg-[#1524AF]', 'text-white', 'border-[#FFDE59]');
          c.textContent = '✓';

          l.classList.remove('text-[#6B7280]');
          l.classList.add('text-[#1524AF]', 'font-semibold');
        }

        // garis mobile yang sudah dilewati → biru
        if (current >= 2 && mobileLines[1]) {
          mobileLines[1].classList.remove('bg-white');
          mobileLines[1].classList.add('bg-[#1524AF]');
        }
        if (current >= 3 && mobileLines[2]) {
          mobileLines[2].classList.remove('bg-white');
          mobileLines[2].classList.add('bg-[#1524AF]');
        }
      }

      function showStep(step) {
        if (step1) step1.classList.add('hidden');
        if (step2) step2.classList.add('hidden');
        if (step3) step3.classList.add('hidden');

        if (step === 1 && step1) step1.classList.remove('hidden');
        if (step === 2 && step2) step2.classList.remove('hidden');
        if (step === 3 && step3) step3.classList.remove('hidden');

        updateStepper(step);
      }

      // posisi awal: Step 1 aktif
      updateStepper(1);
      showStep(1);

      // ========== EVENT NAVIGASI STEP ==========
      btnToStep2 && btnToStep2.addEventListener('click', () => {
        if (!form1) return;

        if (form1.checkValidity()) {
          showStep(2);
        } else {
          form1.reportValidity();
        }
      });

      btnBackToStep1 && btnBackToStep1.addEventListener('click', () => {
        showStep(1);
      });

      btnToStep3 && btnToStep3.addEventListener('click', () => {
        if (!form2) return;

        if (form2.checkValidity()) {
          showStep(3);
        } else {
          form2.reportValidity();
        }
      });

      btnBackToStep2 && btnBackToStep2.addEventListener('click', () => {
        showStep(2);
      });

      // ========== SUBMIT STEP 3 → TAMPILKAN HALAMAN SUKSES ==========
      btnSubmit && btnSubmit.addEventListener('click', (e) => {
        e.preventDefault(); // jangan reload page dulu

        // validasi Step 1
        if (form1 && !form1.checkValidity()) {
          showStep(1);
          form1.reportValidity();
          return;
        }

        // validasi Step 2
        if (form2 && !form2.checkValidity()) {
          showStep(2);
          form2.reportValidity();
          return;
        }

        // TODO: kalau nanti mau submit ke backend, taruh AJAX/fetch di sini

        // sembunyikan wizardContainer, tampilkan successCard
        if (wizardContainer) wizardContainer.classList.add('hidden');

        if (successCard) {
          successCard.classList.remove('hidden');
          successCard.scrollIntoView({ behavior: 'smooth' });
        }

        // kunci stepper di step 3
        updateStepper(3);
      });
    })();

    // =============== FILE INPUT PREVIEW + VALIDASI (MAX 5MB) ===============
    (function () {
      const allowedMime = [
        'image/jpeg',
        'image/png',
        'image/jpg',
        'application/pdf'
      ];

      const MAX_SIZE = 5 * 1024 * 1024; // 5 MB

      const fileInputs = document.querySelectorAll('input[type="file"][data-file-input]');

      fileInputs.forEach(input => {
        input.addEventListener('change', function () {
          const key     = this.dataset.fileInput;
          const label   = document.querySelector(`span[data-file-label="${key}"]`);
          const preview = document.querySelector(`[data-file-preview="${key}"]`);

          // reset preview
          if (preview) preview.innerHTML = '';

          if (!this.files || this.files.length === 0) {
            if (label) label.textContent = 'Tidak ada file yang dipilih';
            return;
          }

          const file = this.files[0];

          // Validasi tipe file
          if (!allowedMime.includes(file.type)) {
            alert('Format file tidak didukung. Hanya boleh JPG, PNG, atau PDF.');
            this.value = '';
            if (label) label.textContent = 'Tidak ada file yang dipilih';
            return;
          }

          // Validasi ukuran file (max 5MB)
          if (file.size > MAX_SIZE) {
            alert('Ukuran file terlalu besar. Maksimal 5 MB per file.');
            this.value = '';
            if (label) label.textContent = 'Tidak ada file yang dipilih';
            if (preview) preview.innerHTML = '';
            return;
          }

          // Tampilkan nama file
          if (label) {
            label.textContent = file.name;
          }

          // PREVIEW
          if (!preview) return;

          if (file.type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            preview.innerHTML = `
              <img src="${url}"
                   alt="Preview ${key}"
                   class="h-20 w-auto rounded-md border border-[#DEE5F0] object-cover">
            `;
          } else if (file.type === 'application/pdf') {
            preview.innerHTML = `
              <div class="flex items-center gap-2 text-[12px] text-[#1A2340]">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded bg-red-500 text-white text-[10px] font-semibold">
                  PDF
                </span>
                <span class="truncate">${file.name}</span>
              </div>
            `;
          }
        });
      });
    })();

    // =============== ACCORDION: HANYA 1 YANG BISA TERBUKA ===============
    (function () {
      const container = document.getElementById('accordionInformasiProgram');
      if (!container) return;

      const accordions = container.querySelectorAll('details');

      accordions.forEach((item) => {
        item.addEventListener('toggle', () => {
          if (item.open) {
            accordions.forEach((other) => {
              if (other !== item && other.open) {
                other.open = false;
              }
            });
          }
        });
      });
    })();
  </script>
  @endpush

  {{-- FOOTER (full width, nempel bawah) --}}
  @includeIf('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>
