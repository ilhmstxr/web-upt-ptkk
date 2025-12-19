{{-- resources/views/pages/daftar.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pendaftaran {{ $pelatihan->nama_pelatihan ?? 'Program Pelatihan' }} - UPT PTKK</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --biru-brand: #1524AF;
      --bg-soft: #F1F9FC;
    }

    body {
      font-family: 'Montserrat', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background-color: var(--bg-soft);
      color: #0f172a;
    }

    .font-volkhov {
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
        $adaPelatihanAktif = isset($pelatihan) && $pelatihan !== null;
      @endphp

      @if (! $adaPelatihanAktif)
        {{-- =================== STATE: TIDAK ADA PELATIHAN =================== --}}
        <section class="mt-10">
          <div class="flex justify-center">
            <div
              class="w-full md:w-[720px] bg-gradient-to-r from-[#F1F9FF] to-[#C9E3FF]
                   border-2 border-[#1D4ED8] rounded-3xl px-6 py-10 md:px-10 md:py-12
                   shadow-sm">
              <h2
                class="font-volkhov font-bold text-[18px] md:text-[22px] text-[#1524AF] text-center mb-3">
                Saat ini belum ada program pelatihan yang sedang berlangsung
              </h2>

              <p class="text-slate-700 text-[14px] md:text-[15px] text-center mb-8">
                Sambil menunggu, kamu dapat menyiapkan berkas dan mengajukan permohonan
                peserta terlebih dahulu sesuai panduan.
              </p>

              <div class="flex justify-center">
                <a
                  href="{{ url('/panduan') }}"
                  class="inline-flex items-center justify-center px-5 py-2.5 rounded-full
                         bg-[#1524AF] text-white text-[14px] font-medium
                         hover:bg-[#1524AF]/90 transition">
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
            style="background: linear-gradient(90deg, #F1F9FC 0%, #98C1F8 100%);">
            <summary class="list-none cursor-pointer px-2 md:px-3 py-2 flex items-center gap-2 min-h-[40px]">
              {{-- Panah segitiga --}}
              <span
                class="inline-flex items-center justify-center w-6 h-6 flex-shrink-0
                       transition-transform duration-200 group-open:rotate-90">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                  <polygon
                    points="7,4 7,20 19,12"
                    fill="#1524AF"
                    stroke="#FFDE59"
                    stroke-width="1"
                    stroke-linejoin="round" />
                </svg>
              </span>

              {{-- Judul --}}
              <span class="font-volkhov font-bold text-[16px] judul-stroke">
                Syarat dan Ketentuan Peserta
              </span>
            </summary>

            {{-- Isi --}}
            <div class="px-4 md:px-6 pb-5 pt-1 text-[14px] text-[#000] font-medium">
              @php
                $syarat = $pelatihan->syarat_ketentuan ?? null;
              @endphp

              @if (!empty($syarat))
                {!! $syarat !!}
              @else
                {{-- Fallback lama kalau admin belum isi apa-apa --}}
                <ul class="space-y-2 text-[14px] font-medium text-[#000000]">
                  <li class="flex gap-2">
                    <span class="text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                    Calon peserta kelas XI dengan kompetensi keahlian sesuai kegiatan pelatihan.
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
                        <span class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                               text-[10px] font-bold">
                          ✓
                        </span>
                        Fotocopy ijazah terakhir
                      </li>
                      <li class="flex gap-2 items-center">
                        <span class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                               text-[10px] font-bold">
                          ✓
                        </span>
                        Pas foto background merah (3 lembar)
                      </li>
                      <li class="flex gap-2 items-center">
                        <span class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                               text-[10px] font-bold">
                          ✓
                        </span>
                        Surat tugas pejabat berwenang
                      </li>
                      <li class="flex gap-2 items-center">
                        <span class="w-4 h-4 rounded-full bg-[#1524AF] text-white flex items-center justify-center
                               text-[10px] font-bold">
                          ✓
                        </span>
                        Fotocopy KTP/KK atau Kartu Pelajar
                      </li>
                    </ul>
                  </li>
                </ul>
              @endif
            </div>
          </details>

          {{-- 2. Jadwal Program Pelatihan --}}
          <details
            class="group rounded-2xl border-[2px] border-[#1524AF] overflow-hidden"
            style="background: linear-gradient(90deg, #F1F9FC 0%, #98C1F8 100%);">
            <summary class="list-none cursor-pointer px-2 md:px-3 py-2 flex items-center gap-2 min-h-[40px]">
              <span
                class="inline-flex items-center justify-center w-6 h-6 flex-shrink-0
                       transition-transform duration-200 group-open:rotate-90">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                  <polygon
                    points="7,4 7,20 19,12"
                    fill="#1524AF"
                    stroke="#FFDE59"
                    stroke-width="1"
                    stroke-linejoin="round" />
                </svg>
              </span>

              <span class="font-volkhov font-bold text-[16px] judul-stroke">
                Jadwal Program Pelatihan
              </span>
            </summary>

            <div class="px-3 md:px-4 pb-4 pt-2 text-[14px] text-[#000] font-medium">
              @php
                $jadwalText = $pelatihan->jadwal_text ?? null;
              @endphp

              @if (!empty($jadwalText))
                {!! $jadwalText !!}
              @else
                {{-- Fallback: pakai jadwal otomatis --}}
                @php
                  $tglMulai   = $pelatihan->tanggal_mulai;
                  $tglSelesai = $pelatihan->tanggal_selesai;

                  $mulaiDaftar   = $tglMulai->copy()->subDays(14);
                  $selesaiDaftar = $tglMulai->copy()->subDays(3);
                  $mulaiAdmin    = $tglMulai->copy()->subDays(3);
                  $selesaiAdmin  = $tglMulai->copy()->subDays(1);
                  $daftarUlang   = $tglMulai->copy()->subDays(1);
                @endphp

                <div class="space-y-2">
                  {{-- Baris 1: Masa Pengisian Link --}}
                  <div class="flex items-start gap-4">
                    <div class="relative pl-6 flex-1">
                      <span class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                      Masa Pengisian Link Pendaftaran
                    </div>
                    <span class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right">
                      {{ $mulaiDaftar->translatedFormat('d F') }} s/d {{ $selesaiDaftar->translatedFormat('d F Y') }}
                    </span>
                  </div>

                  {{-- Baris 2: Verifikasi Data --}}
                  <div class="flex items-start gap-4">
                    <div class="relative pl-6 flex-1">
                      <span class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                      Verifikasi Data oleh Admin
                    </div>
                    <span class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right">
                      {{ $mulaiAdmin->translatedFormat('d F') }} s/d {{ $selesaiAdmin->translatedFormat('d F Y') }}
                    </span>
                  </div>

                  {{-- Baris 3: Daftar Ulang --}}
                  <div class="flex items-start gap-4">
                    <div class="relative pl-6 flex-1">
                      <span class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                      Daftar Ulang / Konfirmasi Kehadiran Peserta
                    </div>
                    <span class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right">
                      {{ $daftarUlang->translatedFormat('d F Y') }}
                    </span>
                  </div>

                  {{-- Baris 4: Pelaksanaan Pelatihan --}}
                  <div class="flex items-start gap-4">
                    <div class="relative pl-6 flex-1">
                      <span class="absolute left-0 top-[3px] text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                      Pelaksanaan Kegiatan Pelatihan
                    </div>
                    <span class="text-[13px] md:text-[14px] text-[#000000] whitespace-nowrap md:text-right">
                      {{ $tglMulai->translatedFormat('d F') }} s/d {{ $tglSelesai->translatedFormat('d F Y') }}
                    </span>
                  </div>
                </div>

                <p class="text-xs mt-3">
                  *Jadwal dapat berubah sewaktu-waktu oleh admin.
                </p>
              @endif
            </div>
          </details>

          {{-- 3. Lokasi Pelaksanaan Pelatihan --}}
          <details
            class="group rounded-2xl border-[2px] border-[#1524AF] overflow-hidden"
            style="background: linear-gradient(90deg, #F1F9FC 0%, #98C1F8 100%);">
            <summary class="list-none cursor-pointer px-2 md:px-3 py-2 flex items-center gap-2 min-h-[40px]">
              <span
                class="inline-flex items-center justify-center w-6 h-6 flex-shrink-0
                       transition-transform duration-200 group-open:rotate-90">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                  <polygon
                    points="7,4 7,20 19,12"
                    fill="#1524AF"
                    stroke="#FFDE59"
                    stroke-width="1"
                    stroke-linejoin="round" />
                </svg>
              </span>

              <span class="font-volkhov font-bold text-[16px] judul-stroke">
                Lokasi Pelaksanaan Pelatihan
              </span>
            </summary>

            <div class="px-3 md:px-4 pb-4 pt-2 text-[14px] text-[#000] font-medium">
              @php
                $lokasiText = $pelatihan->lokasi_text ?? null;
              @endphp

              @if (!empty($lokasiText))
                {!! $lokasiText !!}
              @else
                <ul class="space-y-3 text-[14px] font-medium text-[#000000]">
                  @forelse($pelatihan->kompetensiPelatihan as $bp)
                    <li class="relative pl-6">
                      <span class="absolute left-0 top-[4px] text-[#1524AF] inline-block font-bold leading-none text-[17px]">></span>
                      <div class="space-y-1">
                        <p class="font-semibold">{{ $bp->kompetensi->nama_kompetensi ?? 'Kejuruan' }}</p>
                        <div class="flex items-start gap-2">
                          <span class="mt-[2px] inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#1524AF]">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="#FFFFFF">
                              <path d="M12 2C8.7 2 6 4.7 6 8c0 4.1 4.6 9.4 5.6 10.6.2.2.5.4.8.4s.6-.1.8-.4C13.4 17.4 18 12.1 18 8c0-3.3-2.7-6-6-6zm0 8.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 5.5 12 5.5s2.5 1.1 2.5 2.5S13.4 10.5 12 10.5z" />
                            </svg>
                          </span>
                          <div class="space-y-0.5">
                            <p>{{ $bp->lokasi ?? 'UPT Pengembangan Teknik Dan Keterampilan Kejuruan' }}</p>
                            <p>{{ $bp->kota ?? 'Surabaya, Jawa Timur' }}</p>
                          </div>
                        </div>
                      </div>
                    </li>
                  @empty
                    <li class="pl-6 text-gray-500 italic">Belum ada data lokasi.</li>
                  @endforelse
                </ul>
              @endif
            </div>
          </details>
        </section>

        {{-- =========================================================
             SECTION: FORM PENDAFTARAN (MULTI STEP + STEPPER AKTIF)
        ========================================================= --}}
        <section aria-labelledby="form-pendaftaran-heading" class="mt-8">

          {{-- KARTU WIZARD --}}
          <form id="form-pendaftaran" action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ALERT ERROR --}}
            @if ($errors->any())
              <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md animate-fade-in-down">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                      Terdapat beberapa kesalahan pengisian:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                      <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            @endif


            {{-- ALERT ERROR DARI SERVER (EXCEPTION DI CONTROLLER) --}}
@if (session('error'))
  <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md animate-fade-in-down">
    <div class="flex">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 001 1h1a1 1 0 110 2h-1v2a1 1 0 11-2 0v-2a1 1 0 011-1h1a1 1 0 100-2h-1z"
                clip-rule="evenodd" />
        </svg>
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-medium text-red-800">
          Terjadi kesalahan saat memproses pendaftaran:
        </h3>
        <div class="mt-2 text-sm text-red-700">
          {{ session('error') }}
        </div>
      </div>
    </div>
  </div>
@endif


            <input type="hidden" name="pelatihan_id" value="{{ $pelatihan->id }}">
            <div
              id="wizardContainer"
              class="rounded-2xl border-[4px] border-[#B6BBE6] overflow-hidden bg-[#F1F9FC]">
              <div
                id="wizardSteps"
                class="flex flex-col md:flex-row items-stretch min-h-[520px]">

                {{-- ======================= KIRI: STEPPER (HANYA TABLET & DESKTOP) ======================= --}}
                <aside
                  class="hidden md:flex md:w-[260px] lg:w-[300px] bg-[#DBE7F7] px-6 py-8 flex-col">
                  <h2
                    id="form-pendaftaran-heading"
                    class="font-volkhov text-[20px] md:text-[22px] text-[#1524AF] judul-stroke">
                    Form Pendaftaran
                  </h2>

                  <div class="mt-10 flex-1 flex">
                    <div class="flex flex-col items-start relative">

                      {{-- STEP 1 --}}
                      <div class="flex items-center gap-3" id="stepItem1">
                        <div
                          id="stepCircle1"
                          class="w-12 h-12 rounded-full flex items-center justify-center
                 text-[15px] font-semibold shadow-sm transition-colors duration-200">
                          1
                        </div>
                        <span
                          id="stepLabel1"
                          class="text-[15px] transition-colors duration-200">
                          Data diri
                        </span>
                      </div>

                      {{-- GARIS 1 → 2 --}}
                      <div class="flex">
                        <div
                          id="stepLine1"
                          class="ml-[23px] w-[2px] h-28 md:h-32 bg-white transition-colors duration-200"
                          style="margin-top:-1px; margin-bottom:-14px;"></div>
                      </div>

                      {{-- STEP 2 --}}
                      <div class="flex items-center gap-3" id="stepItem2">
                        <div
                          id="stepCircle2"
                          class="w-12 h-12 rounded-full flex items-center justify-center
                 text-[15px] font-semibold shadow-sm transition-colors duration-200">
                          2
                        </div>
                        <span
                          id="stepLabel2"
                          class="text-[15px] transition-colors duration-200">
                          Data Lembaga
                        </span>
                      </div>

                      {{-- GARIS 2 → 3 --}}
                      <div class="flex">
                        <div
                          id="stepLine2"
                          class="ml-[23px] w-[2px] h-28 md:h-32 bg-white transition-colors duration-200"
                          style="margin-top:-1px; margin-bottom:-14px;"></div>
                      </div>

                      {{-- STEP 3 --}}
                      <div class="flex items-center gap-3" id="stepItem3">
                        <div
                          id="stepCircle3"
                          class="w-12 h-12 rounded-full flex items-center justify-center
                 text-[15px] font-semibold shadow-sm transition-colors duration-200">
                          3
                        </div>
                        <span
                          id="stepLabel3"
                          class="text-[15px] transition-colors duration-200">
                          Lampiran
                        </span>
                      </div>

                    </div>
                  </div>
                </aside>

                {{-- ======================= KANAN: FORM MULTI STEP ======================= --}}
                <div class="flex-1 px-6 py-8">

                  {{-- ======================= MOBILE STEPPER ======================= --}}
                  <div class="md:hidden mb-6">
                    <h2
                      class="font-volkhov text-[18px] text-[#1524AF] judul-stroke text-center mb-4">
                      Form Pendaftaran
                    </h2>

                    <div class="max-w-md mx-auto select-none">
                      <div class="flex items-center w-full">
                        <div class="flex flex-col items-center">
                          <div
                            id="mobileStepCircle1"
                            class="w-10 h-10 rounded-full flex items-center justify-center
                 text-[14px] font-semibold bg-white text-[#6B7280]
                 border border-transparent shadow-sm transition-colors duration-200">
                            1
                          </div>
                        </div>

                        <div
                          id="mobileStepLine1"
                          class="flex-1 h-[2px] bg-white transition-colors duration-200"></div>

                        <div class="flex flex-col items-center">
                          <div
                            id="mobileStepCircle2"
                            class="w-10 h-10 rounded-full flex items-center justify-center
                 text-[14px] font-semibold bg-white text-[#6B7280]
                 border border-transparent shadow-sm transition-colors duration-200">
                            2
                          </div>
                        </div>

                        <div
                          id="mobileStepLine2"
                          class="flex-1 h-[2px] bg-white transition-colors duration-200"></div>

                        <div class="flex flex-col items-center">
                          <div
                            id="mobileStepCircle3"
                            class="w-10 h-10 rounded-full flex items-center justify-center
                 text-[14px] font-semibold bg-white text-[#6B7280]
                 border border-transparent shadow-sm transition-colors duration-200">
                            3
                          </div>
                        </div>

                      </div>

                      <div class="flex justify-between mt-2 text-[12px] tracking-tight w-full">
                        <span
                          id="mobileStepLabel1"
                          class="w-10 text-center text-[#6B7280] leading-tight">
                          Data diri
                        </span>

                        <span
                          id="mobileStepLabel2"
                          class="w-[72px] text-center text-[#6B7280] leading-tight">
                          Data Lembaga
                        </span>

                        <span
                          id="mobileStepLabel3"
                          class="w-10 text-center text-[#6B7280] leading-tight">
                          Lampiran
                        </span>
                      </div>
                    </div>
                  </div>

                  {{-- STEP 1: DATA DIRI --}}
                  <div id="step1" class="step-content block">
                    <div id="div-step1" class="flex flex-col min-h-[520px] space-y-6">

                      <div class="space-y-4 flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">

                          {{-- Nama --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Nama Lengkap
                            </label>
                            <input
                              type="text"
                              name="nama"
                              value="{{ old('nama') }}"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                              placeholder="Masukkan Nama Lengkap (tulis gelar jika ada, cth: S.Kom, S.Pd)"
                              oninvalid="this.setCustomValidity('Nama wajib diisi')"
                              oninput="this.setCustomValidity('')" />
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
                              value="{{ old('nik') }}"
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
                              ">
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
                              value="{{ old('no_hp') }}"
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
                              ">
                          </div>

                          {{-- Email --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Email
                            </label>
                            <input
                              type="email"
                              name="email"
                              value="{{ old('email') }}"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                              placeholder="Masukkan Email"
                              oninvalid="this.setCustomValidity('Format email tidak valid, pastikan mengandung @')"
                              oninput="this.setCustomValidity('')">
                          </div>

                          {{-- Tempat Lahir --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Tempat Lahir
                            </label>
                            <input
                              type="text"
                              name="tempat_lahir"
                              value="{{ old('tempat_lahir') }}"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                              placeholder="Masukkan Tempat Lahir"
                              oninvalid="this.setCustomValidity('Tempat lahir wajib diisi')"
                              oninput="this.setCustomValidity('')">
                          </div>

                          {{-- Tanggal Lahir --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Tanggal Lahir
                            </label>
                            <input
                              type="date"
                              name="tanggal_lahir"
                              value="{{ old('tanggal_lahir') }}"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                              oninvalid="this.setCustomValidity('Tanggal lahir wajib diisi')"
                              oninput="this.setCustomValidity('')">
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
                              oninput="this.setCustomValidity('')">
                              <option value="">Pilih Jenis Kelamin</option>
                              <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                              <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                              oninput="this.setCustomValidity('')">
                              <option value="">Masukkan Agama</option>
                              <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                              <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                              <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                              <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                              <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                              <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
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
                            oninput="this.setCustomValidity('')">{{ old('alamat') }}</textarea>
                        </div>
                      </div>

                      <div class="flex justify-end">
                        <button
                          type="button"
                          id="btnToStep2"
                          class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             bg-[#1524AF] text-white font-medium font-[Montserrat]
                             text-[14px] hover:opacity-90 transition">
                          Selanjutnya
                          <span class="inline-block ml-2">➜</span>
                        </button>
                      </div>

                    </div>
                  </div>

                  {{-- STEP 2: DATA LEMBAGA --}}
                  <div id="step2" class="step-content hidden">
                    <div id="div-step2" class="flex flex-col min-h-[520px] space-y-6">

                      <div class="flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">

                          {{-- Asal Lembaga Sekolah --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Asal Lembaga Sekolah
                            </label>
                            <input
                              type="text"
                              name="asal_instansi"
                              value="{{ old('asal_instansi') }}"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                              placeholder="Masukkan Asal Instansi">
                          </div>

                          {{-- Alamat Sekolah --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Alamat Sekolah
                            </label>
                            <input
                              type="text"
                              name="alamat_instansi"
                              value="{{ old('alamat_instansi') }}"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                              placeholder="Masukkan Alamat Instansi">
                          </div>

                          {{-- Kompetensi / Kompetensi Pelatihan --}}
                          <div>
                            <label
                              for="kompetensi_keahlian"
                              class="block text-sm font-semibold text-blue-900 mb-2">
                              Kompetensi / Kompetensi Pelatihan
                            </label>
                            <select
                              id="kompetensi_keahlian"
                              name="kompetensi_keahlian"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] text-slate-800
                                 focus:outline-none focus:ring-2 focus:ring-[#1524AF]/40 focus:border-[#1524AF]">
                              <option value="">Pilih Kompetensi</option>
                              @foreach ($kompetensi as $b)
                                <option value="{{ $b->id }}" {{ old('kompetensi_keahlian') == $b->id ? 'selected' : '' }}>
                                  {{ $b->kompetensi->nama_kompetensi }}
                                </option>
                              @endforeach
                            </select>
                          </div>

                          {{-- Kelas --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Kelas
                            </label>
                            <select
                              name="kelas"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                 focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                              oninvalid="this.setCustomValidity('Silakan pilih kelas')"
                              oninput="this.setCustomValidity('')">
                              <option value="">Pilih Kelas</option>
                              <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>X</option>
                              <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                              <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                            </select>
                          </div>

                          {{-- Kota / Kabupaten --}}
                          <div>
                            <label class="block text-[13px] md:text-[14px] text-slate-800 mb-1">
                              Kota / Kabupaten
                            </label>
                            <div class="relative">
                              <input
                                type="text"
                                id="kota"
                                name="kota"
                                value="{{ old('kota') }}"
                                required
                                autocomplete="off"
                                class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                     text-[13px] md:text-[14px] focus:outline-none focus:ring-2
                                     focus:ring-[#1524AF]/40 focus:border-[#1524AF]"
                                placeholder="Ketik nama kota...">
                              <div id="kotaSuggestions" class="absolute z-10 w-full bg-white border border-[#B6BBE6] rounded-b-lg mt-1 max-h-60 overflow-y-auto shadow-lg hidden"></div>
                              <input type="hidden" name="kota_id" id="kota_id" value="{{ old('kota_id') }}">
                            </div>
                          </div>

                          {{-- Cabang Dinas Wilayah --}}
                          <div>
                            <label
                              for="cabangDinas_id"
                              class="block text-sm font-semibold text-blue-900 mb-2">
                              Cabang Dinas Wilayah
                            </label>
                            <select
                              id="cabangDinas_id"
                              name="cabangDinas_id"
                              required
                              class="w-full rounded-lg border border-[#B6BBE6] bg-white px-3 py-2.5
                                 text-[13px] md:text-[14px] text-slate-800
                                 focus:outline-none focus:ring-2 focus:ring-[#1524AF]/40 focus:border-[#1524AF]">
                              <option value="">Pilih Cabang Dinas</option>
                              @foreach ($cabangDinas as $cb)
                                <option value="{{ $cb->id }}" {{ old('cabangDinas_id') == $cb->id ? 'selected' : '' }}>
                                  {{ $cb->nama }}
                                </option>
                              @endforeach
                            </select>
                          </div>

                        </div>
                      </div>

                      <div class="flex justify-end gap-3">
                        <button
                          type="button"
                          id="btnBackToStep1"
                          class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             border border-[#1524AF] bg-[#DBE7F7]
                             text-[#1524AF] font-medium font-[Montserrat]
                             text-[14px] hover:bg-[#DBE7F7]/80 transition">
                          <span class="inline-block transform rotate-180 mr-2">➜</span>
                          Sebelumnya
                        </button>

                        <button
                          type="button"
                          id="btnToStep3"
                          class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             bg-[#1524AF] text-white font-medium font-[Montserrat]
                             text-[14px] hover:opacity-90 transition">
                          Selanjutnya
                          <span class="inline-block ml-2">➜</span>
                        </button>
                      </div>

                    </div>
                  </div>

                  {{-- STEP 3: LAMPIRAN --}}
                  <div id="step3" class="step-content hidden">
                    <div class="flex flex-col min-h-[520px] space-y-6">

                      <div class="flex-1">
                        <h2 class="text-[18px] md:text-[20px] font-semibold text-[#1524AF] mb-4">
                          Lampiran Berkas
                        </h2>
                        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                          <div class="flex">
                            <div class="flex-shrink-0">
                              <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                              </svg>
                            </div>
                            <div class="ml-3">
                              <p class="text-sm text-yellow-700">
                                Catatan: Pastikan ukuran file yang diunggah tidak lebih dari <strong>2 MB</strong> per file. Format yang didukung: JPG, JPEG, PNG, PDF.
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">

                          {{-- KOLOM KIRI --}}
                          <div class="space-y-4">

                            {{-- Unggah Fotocopy KTP --}}
                            <div>
                              <label class="block text-[14px] text-[#1A2340] mb-1">
                                Unggah Fotocopy KTP / Kartu Keluarga
                              </label>
                              <div class="relative">
                                <input
                                  type="file"
                                  name="fc_ktp"
                                  accept=".jpg,.jpeg,.png,.pdf"
                                  data-file-input="fc_ktp"
                                  class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                                <div
                                  class="flex items-center bg-white/80 rounded-full
                                border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]">
                                  <span
                                    class="truncate file-label flex-1 min-w-0"
                                    data-file-label="fc_ktp">
                                    Tidak ada file yang dipilih
                                  </span>
                                  <span
                                    class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap">
                                    Pilih File
                                  </span>
                                </div>
                              </div>
                              <div class="mt-2 min-h-[1.25rem]" data-file-preview="fc_ktp"></div>
                            </div>

                            {{-- Unggah Fotocopy Surat Tugas --}}
                            <div>
                              <label class="block text-[14px] text-[#1A2340] mb-1">
                                Unggah Fotocopy Surat Tugas
                              </label>

                              <div class="relative">
                                <input
                                  type="file"
                                  name="fc_surat_tugas"
                                  accept=".jpg,.jpeg,.png,.pdf"
                                  data-file-input="fc_surat_tugas"
                                  class="absolute inset-0 opacity-0 cursor-pointer z-10" />

                                <div
                                  class="flex items-center bg-white/80 rounded-full
                                    border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]">
                                  <span
                                    class="truncate file-label flex-1 min-w-0"
                                    data-file-label="fc_surat_tugas">
                                    Tidak ada file yang dipilih
                                  </span>

                                  <span
                                    class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap">
                                    Pilih File
                                  </span>
                                </div>
                              </div>

                              <div class="mt-2 min-h-[1.25rem]" data-file-preview="fc_surat_tugas"></div>
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
                                   focus:ring-1 focus:ring-[#1524AF] focus:border-[#1524AF]" />
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
                                  name="fc_ijazah"
                                  accept=".jpg,.jpeg,.png,.pdf"
                                  data-file-input="fc_ijazah"
                                  class="absolute inset-0 opacity-0 cursor-pointer z-10" />

                                <div
                                  class="flex items-center bg-white/80 rounded-full
                                border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]">
                                  <span
                                    class="truncate file-label flex-1 min-w-0"
                                    data-file-label="fc_ijazah">
                                    Tidak ada file yang dipilih
                                  </span>

                                  <span
                                    class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap">
                                    Pilih File
                                  </span>
                                </div>
                              </div>

                              <div class="mt-2 min-h-[1.25rem]" data-file-preview="fc_ijazah"></div>
                            </div>

                            {{-- Unggah Surat Sehat --}}
                            <div>
                              <label class="block text-[14px] text-[#1A2340] mb-1">
                                Unggah Surat Sehat
                              </label>

                              <div class="relative">
                                <input
                                  type="file"
                                  name="fc_surat_sehat"
                                  accept=".jpg,.jpeg,.png,.pdf"
                                  data-file-input="fc_surat_sehat"
                                  class="absolute inset-0 opacity-0 cursor-pointer z-10" />

                                <div
                                  class="flex items-center bg-white/80 rounded-full
                                    border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]">
                                  <span
                                    class="truncate file-label flex-1 min-w-0"
                                    data-file-label="fc_surat_sehat">
                                    Tidak ada file yang dipilih
                                  </span>

                                  <span
                                    class="ml-3 inline-flex items-center justify-center h-7 px-4
                                    rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                    flex-shrink-0 whitespace-nowrap">
                                    Pilih File
                                  </span>
                                </div>
                              </div>

                              <div class="mt-2 min-h-[1.25rem]" data-file-preview="fc_surat_sehat"></div>
                            </div>

                            {{-- Unggah Pas Foto --}}
                            <div>
                              <label class="block text-[14px] text-[#1A2340] mb-1">
                                Unggah Pas Foto
                              </label>

                              <div class="relative">
                                <input
                                  type="file"
                                  name="pas_foto"
                                  accept=".jpg,.jpeg,.png,.pdf"
                                  data-file-input="pas_foto"
                                  class="absolute inset-0 opacity-0 cursor-pointer z-10" />

                                <div
                                  class="flex items-center bg-white/80 rounded-full
                                border border-[#DEE5F0] h-10 px-3 text-[13px] text-[#9AA1B3]">
                                  <span
                                    class="truncate file-label flex-1 min-w-0"
                                    data-file-label="pas_foto">
                                    Tidak ada file yang dipilih
                                  </span>

                                  <span
                                    class="ml-3 inline-flex items-center justify-center h-7 px-4
                                rounded-full bg-[#1524AF] text-white text-[13px] font-medium
                                flex-shrink-0 whitespace-nowrap">
                                    Pilih File
                                  </span>
                                </div>
                              </div>

                              <div class="mt-2 min-h-[1.25rem]" data-file-preview="pas_foto"></div>
                            </div>

                          </div>
                        </div>
                      </div>

                      <div class="flex justify-end gap-3">
                        <button
                          type="button"
                          id="btnBackToStep2"
                          class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             border border-[#1524AF] bg-[#DBE7F7]
                             text-[#1524AF] font-medium font-[Montserrat]
                             text-[14px] hover:bg-[#DBE7F7]/80 transition">
                          <span class="inline-block transform rotate-180 mr-2">➜</span>
                          Sebelumnya
                        </button>

                        <button
                          type="submit"
                          id="btnSubmit"
                          class="inline-flex items-center justify-center h-10 px-6 rounded-lg
                             bg-[#1524AF] text-white font-medium font-[Montserrat]
                             text-[14px] hover:opacity-90 transition">
                          Submit
                          <span class="inline-block ml-2">➜</span>
                        </button>
                      </div>

                    </div>
                  </div>

                </div> {{-- end flex-1 --}}
              </div> {{-- end wizardSteps --}}
            </div> {{-- end wizardContainer --}}
          </form>

          {{-- CARD SUKSES SETELAH SUBMIT --}}
          <div class="mt-8 p-2">
            <div
              id="successCard"
              class="hidden rounded-2xl border-[4px] border-[#B6BBE6] bg-[#DBE7F7]
                   px-6 py-12 md:py-16 text-center">
              <h2
                class="font-volkhov font-bold text-[22px] md:text-[26px] text-[#1524AF] judul-stroke mb-4">
                Data pendaftaran Anda berhasil dikirim dan akan diproses di hari kerja
              </h2>

              <p
                class="text-[14px] md:text-[16px] font-medium text-[#070D3D] leading-relaxed mx-auto">
                Informasi lebih lanjut akan kami kirimkan melalui email yang telah Anda daftarkan.
              </p>
              <p
                class="mt-2 text-[14px] md:text-[16px] font-medium text-[#070D3D] leading-relaxed max-w-2xl mx-auto">
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
    (function() {
      const step1 = document.getElementById('step1');
      const step2 = document.getElementById('step2');
      const step3 = document.getElementById('step3');

      const mainForm = document.getElementById('form-pendaftaran');

      const wizardContainer = document.getElementById('wizardContainer');
      const wizardSteps = document.getElementById('wizardSteps');
      const successCard = document.getElementById('successCard');
      const btnSubmit = document.getElementById('btnSubmit');

      const btnToStep2 = document.getElementById('btnToStep2');
      const btnBackToStep1 = document.getElementById('btnBackToStep1');
      const btnToStep3 = document.getElementById('btnToStep3');
      const btnBackToStep2 = document.getElementById('btnBackToStep2');

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

        Object.values(lines).forEach(line => {
          if (!line) return;
          line.classList.remove('bg-[#1524AF]');
          line.classList.add('bg-white');
        });

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

        if (current >= 2 && lines[1]) {
          lines[1].classList.remove('bg-white');
          lines[1].classList.add('bg-[#1524AF]');
        }
        if (current >= 3 && lines[2]) {
          lines[2].classList.remove('bg-white');
          lines[2].classList.add('bg-[#1524AF]');
        }

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

        Object.values(mobileLines).forEach(line => {
          if (!line) return;
          line.classList.remove('bg-[#1524AF]');
          line.classList.add('bg-white');
        });

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

      const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
      if (hasErrors) {
        updateStepper(3);
        showStep(3);
      } else {
        updateStepper(1);
        showStep(1);
      }

      btnToStep2 && btnToStep2.addEventListener('click', () => {
        const inputsStep1 = step1.querySelectorAll('input, select, textarea');
        let valid = true;
        inputsStep1.forEach(el => {
          if (!el.checkValidity()) {
            el.reportValidity();
            valid = false;
            return;
          }
        });

        if (valid) {
          showStep(2);
        }
      });

      btnBackToStep1 && btnBackToStep1.addEventListener('click', () => {
        showStep(1);
      });

      btnToStep3 && btnToStep3.addEventListener('click', () => {
        const inputsStep2 = step2.querySelectorAll('input, select, textarea');
        let valid = true;
        inputsStep2.forEach(el => {
          if (!el.checkValidity()) {
            el.reportValidity();
            valid = false;
            return;
          }
        });

        if (valid) {
          showStep(3);
        }
      });

      btnBackToStep2 && btnBackToStep2.addEventListener('click', () => {
        showStep(2);
      });

      btnSubmit && btnSubmit.addEventListener('click', (e) => {
        const inputsStep1 = step1.querySelectorAll('input, select, textarea');
        for (let el of inputsStep1) {
          if (!el.checkValidity()) {
            showStep(1);
            el.reportValidity();
            e.preventDefault();
            return;
          }
        }

        const inputsStep2 = step2.querySelectorAll('input, select, textarea');
        for (let el of inputsStep2) {
          if (!el.checkValidity()) {
            showStep(2);
            el.reportValidity();
            e.preventDefault();
            return;
          }
        }

        const inputsStep3 = step3.querySelectorAll('input, select, textarea');
        for (let el of inputsStep3) {
          if (!el.checkValidity()) {
            showStep(3);
            el.reportValidity();
            e.preventDefault();
            return;
          }
        }
      });
    })();

    // =============== FILE INPUT PREVIEW + VALIDASI (MAX 5MB) ===============
    (function() {
      const allowedMime = [
        'image/jpeg',
        'image/png',
        'image/jpg',
        'application/pdf'
      ];

      const MAX_SIZE = 5 * 1024 * 1024; // 5 MB

      const fileInputs = document.querySelectorAll('input[type="file"][data-file-input]');

      fileInputs.forEach(input => {
        input.addEventListener('change', function() {
          const key = this.dataset.fileInput;
          const label = document.querySelector(`span[data-file-label="${key}"]`);
          const preview = document.querySelector(`[data-file-preview="${key}"]`);

          if (preview) preview.innerHTML = '';

          if (!this.files || this.files.length === 0) {
            if (label) label.textContent = 'Tidak ada file yang dipilih';
            return;
          }

          const file = this.files[0];

          if (!allowedMime.includes(file.type)) {
            alert('Format file tidak didukung. Hanya boleh JPG, PNG, atau PDF.');
            this.value = '';
            if (label) label.textContent = 'Tidak ada file yang dipilih';
            return;
          }

          if (file.size > MAX_SIZE) {
            alert('Ukuran file terlalu besar. Maksimal 5 MB per file.');
            this.value = '';
            if (label) label.textContent = 'Tidak ada file yang dipilih';
            if (preview) preview.innerHTML = '';
            return;
          }

          if (label) {
            label.textContent = file.name;
          }

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
    (function() {
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

    // =============== UPPERCASE TEXT INPUTS ===============
    (function() {
      // Function to convert text to uppercase in real-time, excluding email and password fields
      function applyUppercaseToFields() {
        // Get all input and textarea elements, but exclude email, password, date, number, tel, and hidden inputs
        const textInputs = [
          ...document.querySelectorAll('input[type="text"]'),
          ...document.querySelectorAll('textarea')
        ].filter(input => {
          // Don't apply to email, password, date, number, tel, or hidden inputs
          return input.type !== 'email' &&
                 input.type !== 'password' &&
                 input.type !== 'date' &&
                 input.type !== 'number' &&
                 input.type !== 'tel' &&
                 input.type !== 'hidden';
        });

        textInputs.forEach(input => {
          // Convert to uppercase as user types
          input.addEventListener('input', function() {
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.toUpperCase();
            // Maintain cursor position
            this.setSelectionRange(start, end);
          });

          // Convert to uppercase when pasting content
          input.addEventListener('paste', function(e) {
            setTimeout(() => {
              const start = this.selectionStart;
              const end = this.selectionEnd;
              this.value = this.value.toUpperCase();
              this.setSelectionRange(start, end);
            }, 10);
          });
        });
      }

      // Apply uppercase to text fields when DOM is loaded
      document.addEventListener('DOMContentLoaded', function() {
        applyUppercaseToFields();
      });

      // Also apply when new fields might be added dynamically
      setTimeout(applyUppercaseToFields, 500);
    })();

    // =============== AUTOCOMPLETE KOTA ===============
    (function() {
      const kotaInput = document.getElementById("kota");
      const kotaIdHidden = document.getElementById("kota_id");
      const suggestionsContainer = document.getElementById("kotaSuggestions");

      if (!kotaInput || !suggestionsContainer) return;

      let allRegencies = [];
      let filtered = [];
      let activeIndex = -1;

      const normalize = (s) => (s || "").toString().trim().toLowerCase().replace(/\s+/g, " ");

      fetch("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/35.json")
        .then(res => res.json())
        .then(data => {
          allRegencies = data;
        })
        .catch(err => console.error("Gagal load kota", err));

      const showSuggestions = () => suggestionsContainer.classList.remove("hidden");
      const hideSuggestions = () => {
        suggestionsContainer.classList.add("hidden");
        activeIndex = -1;
      };

      const renderSuggestions = (items) => {
        suggestionsContainer.innerHTML = "";
        items.forEach((item, idx) => {
          const div = document.createElement("div");
          div.textContent = item.name;
          div.className = "p-2 cursor-pointer hover:bg-gray-100 text-sm " + (idx === activeIndex ? "bg-gray-100" : "");
          div.addEventListener("mousedown", (e) => {
            e.preventDefault();
            chooseItem(item);
          });
          suggestionsContainer.appendChild(div);
        });
        if (items.length > 0) showSuggestions();
        else hideSuggestions();
      };

      const chooseItem = (item) => {
        kotaInput.value = item.name;
        kotaIdHidden.value = item.id;
        kotaInput.setCustomValidity("");
        hideSuggestions();
      };

      kotaInput.addEventListener("input", function() {
        kotaIdHidden.value = "";
        kotaInput.setCustomValidity("Pilih kota dari daftar");

        const q = normalize(this.value);
        if (!q) {
          hideSuggestions();
          return;
        }
        filtered = allRegencies.filter(r => normalize(r.name).includes(q));
        renderSuggestions(filtered);
      });

      kotaInput.addEventListener("blur", function() {
        setTimeout(() => {
          hideSuggestions();
          if (!kotaIdHidden.value) {
            kotaInput.setCustomValidity("Pilih kota dari daftar");
          }
        }, 200);
      });

      kotaInput.addEventListener("keydown", (e) => {
        if (e.key === "ArrowDown") {
          e.preventDefault();
          activeIndex = (activeIndex + 1) % filtered.length;
          renderSuggestions(filtered);
        } else if (e.key === "Enter") {
          if (activeIndex >= 0 && filtered[activeIndex]) {
            e.preventDefault();
            chooseItem(filtered[activeIndex]);
          }
        }
      });
    })();
  </script>
  @endpush

  {{-- FOOTER --}}
  @includeIf('components.layouts.app.footer')

  @stack('scripts')
</body>

</html>
