@extends('dashboard.layouts.main')

@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 font-semibold" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 font-semibold" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Hidden form untuk unset peserta (dipakai oleh tombol "Ganti Peserta" / "Batal")
<form id="hiddenUnsetForm" action="{{ route('dashboard.logout') }}" method="POST" style="display:none;">
    @csrf
</form> --}}

    @php
        $isBlur = empty($pesertaAktif);
    @endphp


    <div class="{{ $isBlur ? 'blur-sm pointer-events-none select-none' : '' }}">
        @if ($pesertaAktif)
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">Hai, {{ explode(' ', $pesertaAktif->nama)[0] ?? 'Peserta' }} 👋</h2>
                    <p class="text-gray-600">Selamat datang di dashboard pelatihan</p>
                </div>
                <div class="flex gap-2">
                    {{-- Ganti Peserta
                <button id="btnGantiPeserta"
                        type="button"
                        class="text-sm px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Ganti Peserta
                </button> --}}

                    {{-- Logout
                <form action="{{ route('dashboard.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-sm px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Logout
                    </button>
                </form> --}}
                </div>
            </div>
        @endif

        {{-- Dashboard cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- ... (isi kartu seperti sebelumnya) --}}
            {{-- Pre-Test --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-gray-800">Pre-Test</h3>
                        <span
                            class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                    </div>
                    <p class="text-gray-500 mt-2 mb-4">Cek kesiapanmu sebelum mengikuti materi.</p>
                </div>
                <a href="{{ route('dashboard.pretest.index') }}"
                    class="w-full block text-center bg-yellow-400 text-yellow-900 font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition-colors">
                    Kerjakan Pre-Test
                </a>
            </div>

            {{-- Post-Test --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-gray-800">Post-Test</h3>
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                    </div>
                    <p class="text-gray-500 mt-2 mb-4">Evaluasi hasil belajarmu untuk peningkatan.</p>
                </div>
                <a href="{{ route('dashboard.posttest.index') }}"
                    class="w-full block text-center bg-green-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-600 transition-colors">
                    Mulai Post-Test
                </a>
            </div>

            {{-- MONEV --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-xl font-bold text-gray-800">MONEV</h3>
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                    </div>
                    <p class="text-gray-500 mt-2">Akses Monitoring dan Evaluasi Selama Mengikuti Pelatihan.</p>
                </div>
                    <a href="{{ route('dashboard.survey') }}"
                class="mt-6 block text-center w-full lg:w-auto bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                    Mulai Survey
                </a>

            </div>

{{-- Progress Pre-Test --}}
<div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
  <h3 class="text-lg font-bold text-gray-800">Progress Pre-Test</h3>
  @php
    $preAttempts = $preTestAttempts ?? (($preTestDone ?? false) ? 1 : 0);
    $preBar = $preAttempts >= 1 ? 100 : 0; // penuh jika >=1, kosong jika 0
  @endphp
  <div class="flex items-baseline mt-2">
    <span class="text-3xl font-bold text-yellow-600">{{ $preAttempts }}</span>
    <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
  </div>
  <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
    <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $preBar }}%"></div>
  </div>
</div>

{{-- Progress Post-Test --}}
<div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
  <h3 class="text-lg font-bold text-gray-800">Progress Post-Test</h3>
  @php
    $postAttempts = $postTestAttempts ?? (($postTestDone ?? false) ? 1 : 0);
    $postBar = $postAttempts >= 1 ? 100 : 0;
  @endphp
  <div class="flex items-baseline mt-2">
    <span class="text-3xl font-bold text-green-600">{{ $postAttempts }}</span>
    <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
  </div>
  <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
    <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $postBar }}%"></div>
  </div>
</div>

{{-- Progress MONEV --}}
<div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
  <h3 class="text-lg font-bold text-gray-800">Progress MONEV</h3>
  @php
    $monevAttempts = $monevAttempts ?? (($monevDone ?? false) ? 1 : 0);
    $monevBar = $monevAttempts >= 1 ? 100 : 0;
  @endphp
  <div class="flex items-baseline mt-2">
    <span class="text-3xl font-bold text-blue-600">{{ $monevAttempts }}</span>
    <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
  </div>
  <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $monevBar }}%"></div>
  </div>
</div>


@endsection

{{-- Modal langsung di-template (tidak mengandalkan @push) --}}
@if (empty($pesertaAktif))
    <div id="pesertaOverlayRoot"
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900 bg-opacity-60"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
            <h2 class="text-xl font-bold mb-4 text-center">Pilih Peserta untuk Memulai Post-Test</h2>

            {{-- tampilkan validation errors --}}
            @if ($errors->any())
                <div class="mb-3 p-2 bg-red-50 text-red-700 rounded">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="setPesertaForm" action="{{ route('dashboard.setPeserta') }}" method="POST" class="space-y-3">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nama Peserta</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500"
                        placeholder="Tulis nama lengkap" />
                    @error('nama')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Instansi (opsional)</label>
                    <input type="text" name="sekolah" value="{{ old('sekolah') }}"
                        class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500"
                        placeholder="Tulis nama sekolah / instansi" />
                    @error('sekolah')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" id="btnSavePeserta"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan Peserta
                    </button>

                    <button type="button" id="btnCancelSelect" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Batal
                    </button>
                </div>
            </form>

            <p class="text-xs text-gray-400 mt-4 text-center">Pengisian data peserta untuk Pre-test, Post-test, dan
                Monev.</p>
        </div>
    </div>
@endif

{{-- Inline script (tidak bergantung pada @stack) --}}
<script>
    // Ganti Peserta -> submit hidden POST form
    document.getElementById('btnGantiPeserta')?.addEventListener('click', function() {
        const f = document.getElementById('hiddenUnsetForm');
        if (f) f.submit();
    });

    // Batal pada overlay -> submit hiddenUnsetForm (POST) untuk clear session
    document.getElementById('btnCancelSelect')?.addEventListener('click', function() {
        const f = document.getElementById('hiddenUnsetForm');
        if (f) {
            f.submit();
            return;
        }
        location.reload();
    });

    // Disable tombol submit untuk mencegah double submit
    document.getElementById('setPesertaForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('btnSavePeserta');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
        }
    });

    // Safety: jika server mengirim session('success'), hapus overlay client-side (biasanya server-side sudah tidak merender modal)
    @if (session('success'))
        (function removeOverlay() {
            const ov = document.getElementById('pesertaOverlayRoot');
            if (ov) ov.remove();
        })();
    @endif
</script>
