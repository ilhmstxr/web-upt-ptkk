<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Assessment Peserta</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />

  <style>
    body{
      background-color: #1524AF; /* sementara, nanti diganti ke background landing */
      font-family: 'Montserrat', sans-serif;
    }

    /* 🔹 Biar class font-volkhov beneran pakai font Volkhov */
    .font-volkhov{
      font-family: 'Volkhov', serif;
    }

    .material-symbols-rounded {
      font-variation-settings:
        'FILL' 0,
        'wght' 200,
        'GRAD' 0,
        'opsz' 24;
    }

    /* Stroke kuning + fill biru */
    .judul-stroke {
      color: #1524AF;
      -webkit-text-stroke: 1.3px #FFDE59;
      paint-order: stroke fill;
    }
  </style>
</head>

<body class="min-h-screen relative">

  {{-- 🔹 Background Blur --}}
  <div class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

  {{-- 🔹 Card Login Popup --}}
  <div class="fixed inset-0 flex justify-center items-start md:items-center p-4 overflow-y-auto">
    <div class="relative bg-[#F1F9FC] w-full max-w-[440px]
                rounded-2xl shadow-xl border-[4px] border-[#B6BBE6]
                px-6 py-8 sm:px-8 sm:py-10">

      {{-- Judul --}}
      <h2 class="font-volkhov font-bold text-[22px] text-center judul-stroke tracking-wide">
        Login Assessment Peserta
      </h2>
      <p class="text-center text-[#861D23] text-[13px] font-medium mt-1 mb-6 sm:mb-8">
        Pre Test, Post Test, Monev
      </p>

      {{-- ✅ ALERT ERROR / SUCCESS --}}
      @if (session('error'))
        <div class="mb-4 rounded-xl bg-red-100 border border-red-300 text-red-700 px-4 py-3 text-sm">
          {{ session('error') }}
        </div>
      @endif

      @if (session('success'))
        <div class="mb-4 rounded-xl bg-green-100 border border-green-300 text-green-700 px-4 py-3 text-sm">
          {{ session('success') }}
        </div>
      @endif

      {{-- ✅ VALIDATION ERRORS --}}
      @if ($errors->any())
        <div class="mb-4 rounded-xl bg-red-100 border border-red-300 text-red-700 px-4 py-3 text-sm space-y-1">
          @foreach ($errors->all() as $err)
            <div>- {{ $err }}</div>
          @endforeach
        </div>
      @endif

      {{-- 🔹 FORM LOGIN (SUDAH FIX) --}}
      <form action="{{ route('assessment.login.submit') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Input Token / Nomor Registrasi --}}
        <div>
          <label class="block text-[#1A1A1A] text-sm font-medium">ID Peserta</label>
          <input type="text"
                 name="token"
                 value="{{ old('token') }}"
                 class="w-full mt-1 rounded-xl border border-gray-300 px-4 py-3 text-sm
                        focus:ring-2 focus:ring-[#1524AF] outline-none"
                 placeholder="Masukkan ID Peserta / Nomor Registrasi">
          <p class="text-xs text-gray-500 mt-1">
            Isi dengan nomor registrasi atau assessment token.
          </p>
        </div>

        {{-- Input Password --}}
        <div>
          <label class="block text-[#1A1A1A] text-sm font-medium mt-1">Password</label>
          <div class="relative mt-1">
            <input type="password" id="passwordInput"
                   name="password"
                   value="{{ old('password') }}"
                   class="w-full rounded-xl border border-gray-300 px-4 py-3 pr-12 text-sm
                          focus:ring-2 focus:ring-[#1524AF] outline-none"
                   placeholder="Masukkan Password (ddmmyyyy)">

            <button type="button" id="togglePassword"
                    class="absolute right-3 top-3 text-gray-400 hover:text-[#1524AF] transition">
              <span id="eyeIcon" class="material-symbols-rounded text-[22px]">visibility_off</span>
            </button>
          </div>
          <p class="text-xs text-gray-500 mt-1">
            Password = tanggal lahir peserta format ddmmyyyy.
          </p>
        </div>

        <hr class="my-4 border-[#B6BBE6]">

        {{-- Tombol --}}
        <div class="flex justify-center gap-3 sm:gap-4 mt-2 w-full">

          {{-- Tombol Kembali --}}
          <button type="button"
                  onclick="window.history.back()"
                  class="px-6 py-2 rounded-xl bg-[#F1F9FC] text-[#1524AF]
                         text-sm font-medium hover:bg-[#DBE7F7]
                         transition border-[3px] border-[#1524AF]
                         min-w-[90px]">
            Kembali
          </button>

          {{-- Tombol Login --}}
          <button type="submit"
                  class="px-6 py-2 rounded-xl bg-[#1524AF] text-white
                         text-sm font-medium hover:bg-[#0E1A82] transition
                         flex items-center justify-center gap-2
                         min-w-[90px]">
            <span>Login</span>
            <span class="material-symbols-rounded text-[18px]">arrow_forward</span>
          </button>

        </div>

      </form>

    </div>
  </div>

  {{-- 🔹 Script Toggle Password --}}
  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("passwordInput");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", () => {
      const isHidden = passwordInput.type === "password";

      passwordInput.type = isHidden ? "text" : "password";
      eyeIcon.textContent = isHidden ? "visibility" : "visibility_off";
    });
  </script>

</body>
</html>
