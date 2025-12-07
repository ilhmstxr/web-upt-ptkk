@extends('dashboard.layouts.main')
@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')

    @php
        // Pastikan semua variabel punya nilai default biar nggak undefined
        $pesertaAktif     = $pesertaAktif     ?? null;
        $preTestDone      = $preTestDone      ?? false;
        $postTestDone     = $postTestDone     ?? false;
        $monevDone        = $monevDone        ?? false;
        $preTestScore     = $preTestScore     ?? null;
        $postTestScore    = $postTestScore    ?? null;
        $monevScore       = $monevScore       ?? null;
        $preTestAttempts  = $preTestAttempts  ?? 0;
        $postTestAttempts = $postTestAttempts ?? 0;
        $monevAttempts    = $monevAttempts    ?? 0;
    @endphp

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

    @php $isBlur = empty($pesertaAktif); @endphp

    <div class="{{ $isBlur ? 'blur-sm pointer-events-none select-none' : '' }}">
        @if ($pesertaAktif)
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">
                        Hai, {{ explode(' ', $pesertaAktif->nama)[0] ?? 'Peserta' }} 👋
                    </h2>
                    <p class="text-gray-600">
                        Selamat datang di dashboard pelatihan
                        @if(session('instansi_nama'))
                            <br>
                            <span class="text-sm text-gray-500">
                                {{ session('instansi_nama') }}{{ session('instansi_kota') ? ' ('.session('instansi_kota').')' : '' }}
                            </span>
                        @endif
                    </p>
                </div>
                <div class="flex gap-2">
                    {{-- (opsional) tombol aksi lain --}}
                </div>
            </div>
        @endif

        {{-- ====== CARD UTAMA TES (aksi) ====== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Pre-Test --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-gray-800">Pre-Test</h3>
                        <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                    </div>
                    <p class="text-gray-500 mt-2 mb-4">
                        Mengukur kemampuan awalmu sebelum mengikuti materi pelatihan.
                    </p>
                </div>

                @if($preTestDone)
                    <div class="mt-4 text-center">
                        <button disabled
                            class="inline-block px-3 py-2 bg-gray-100 text-gray-700 rounded cursor-not-allowed">
                            ✅ Sudah dikerjakan
                        </button>
                        @if(!is_null($preTestScore))
                            <p class="mt-2 text-sm text-gray-600">
                                Nilai: <strong>{{ $preTestScore }}</strong>
                            </p>
                        @endif
                    </div>
                @else
                    <a href="{{ route('dashboard.pretest.index') }}"
                       class="w-full block text-center bg-yellow-400 text-yellow-900 font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition-colors">
                        Kerjakan Pre-Test
                    </a>
                @endif
            </div>

            {{-- Post-Test --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-gray-800">Post-Test</h3>
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                    </div>
                    <p class="text-gray-500 mt-2 mb-4">
                        Mengevaluasi peningkatan hasil belajar setelah mengikuti pelatihan.
                    </p>
                </div>

                @if($postTestDone)
                    <div class="mt-4 text-center">
                        <button disabled
                            class="inline-block px-3 py-2 bg-gray-100 text-gray-700 rounded cursor-not-allowed">
                            ✅ Sudah dikerjakan
                        </button>

                        @if(!is_null($postTestScore))
                            <p class="mt-2 text-sm text-gray-600">
                                Nilai: <strong>{{ $postTestScore }}</strong>
                            </p>
                        @endif
                    </div>
                @else
                    <a href="{{ route('dashboard.posttest.index') }}"
                       class="w-full block text-center bg-green-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-600 transition-colors">
                        Mulai Post-Test
                    </a>
                @endif
            </div>

            {{-- MONEV / Survey --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-xl font-bold text-gray-800">MONEV</h3>
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                    </div>
                    <p class="text-gray-500 mt-2">
                        Monitoring dan Evaluasi untuk mengetahui pengalamanmu selama pelatihan.
                    </p>
                </div>

                @if($monevDone)
                    <div class="mt-4 text-center">
                        <button disabled
                            class="inline-block px-3 py-2 bg-gray-100 text-gray-700 rounded cursor-not-allowed">
                            ✅ Sudah mengisi
                        </button>
                        {{-- biasanya monev tidak pakai nilai, tapi kalau ada: --}}
                        @if(!is_null($monevScore))
                            <p class="mt-2 text-sm text-gray-600">
                                Nilai / skor: <strong>{{ $monevScore }}</strong>
                            </p>
                        @endif
                    </div>
                @else
                    <a href="{{ route('dashboard.survey') }}"
                       class="mt-6 block text-center w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                        Mulai Survey
                    </a>
                @endif
            </div>
        </div>

        {{-- ====== RINGKASAN STATUS (sudah / belum + skor) ====== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 mb-6">

            {{-- Card ringkasan Pre-Test --}}
            <div class="bg-white rounded-xl shadow-md p-4 flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
                        Pre-Test
                    </p>
                    @if($preTestDone)
                        <p class="text-sm text-gray-800 font-semibold flex items-center gap-1">
                            ✅ Sudah dikerjakan
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Skor terakhir:
                            <span class="font-bold text-gray-900">
                                {{ $preTestScore !== null ? $preTestScore : '-' }}
                            </span>
                        </p>
                    @else
                        <p class="text-sm text-gray-800 font-semibold flex items-center gap-1">
                            ⏳ Belum dikerjakan
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Segera kerjakan pre-test untuk mengukur kemampuan awalmu.
                        </p>
                    @endif
                </div>

                <div class="mt-3 flex justify-end">
                    @if(!$preTestDone)
                        <a href="{{ route('dashboard.pretest.index') }}"
                           class="text-xs px-3 py-1 rounded-full border border-blue-500 text-blue-600 hover:bg-blue-50 transition">
                            Buka Pre-Test
                        </a>
                    @else
                        <span class="text-[11px] text-gray-400 italic">
                            Pre-test sudah selesai.
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card ringkasan Post-Test --}}
            <div class="bg-white rounded-xl shadow-md p-4 flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-1">
                        Post-Test
                    </p>
                    @if($postTestDone)
                        <p class="text-sm text-gray-800 font-semibold flex items-center gap-1">
                            ✅ Sudah dikerjakan
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Skor terakhir:
                            <span class="font-bold text-gray-900">
                                {{ $postTestScore !== null ? $postTestScore : '-' }}
                            </span>
                        </p>
                    @else
                        <p class="text-sm text-gray-800 font-semibold flex items-center gap-1">
                            ⏳ Belum dikerjakan
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Post-test akan mengukur peningkatanmu setelah pelatihan.
                        </p>
                    @endif
                </div>

                <div class="mt-3 flex justify-end">
                    @if(!$postTestDone)
                        <a href="{{ route('dashboard.posttest.index') }}"
                           class="text-xs px-3 py-1 rounded-full border border-emerald-500 text-emerald-600 hover:bg-emerald-50 transition">
                            Buka Post-Test
                        </a>
                    @else
                        <span class="text-[11px] text-gray-400 italic">
                            Post-test sudah selesai.
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card ringkasan Monev --}}
            <div class="bg-white rounded-xl shadow-md p-4 flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-1">
                        Monev / Survey
                    </p>
                    @if($monevDone)
                        <p class="text-sm text-gray-800 font-semibold flex items-center gap-1">
                            ✅ Sudah mengisi
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Terima kasih, responmu membantu peningkatan program kami 🙌
                        </p>
                    @else
                        <p class="text-sm text-gray-800 font-semibold flex items-center gap-1">
                            ⏳ Belum diisi
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Mohon luangkan waktu sebentar untuk mengisi monev.
                        </p>
                    @endif
                </div>

                <div class="mt-3 flex justify-end">
                    @if(!$monevDone)
                        <a href="{{ route('dashboard.survey') }}"
                           class="text-xs px-3 py-1 rounded-full border border-purple-500 text-purple-600 hover:bg-purple-50 transition">
                            Isi Monev
                        </a>
                    @else
                        <span class="text-[11px] text-gray-400 italic">
                            Monev sudah diisi.
                        </span>
                    @endif
                </div>
            </div>

        </div>

        {{-- ====== PROGRESS BAR ====== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-2">
            {{-- Progress Pre-Test --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <h3 class="text-lg font-bold text-gray-800">Progress Pre-Test</h3>
                @php
                    $preAttemptsReal = $preTestAttempts ?? ($preTestDone ? 1 : 0);
                    $preBar = $preAttemptsReal >= 1 ? 100 : 0;
                @endphp
                <div class="flex items-baseline mt-2">
                    <span class="text-3xl font-bold text-yellow-600">{{ $preAttemptsReal }}</span>
                    <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                    <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $preBar }}%"></div>
                </div>
                @if(!is_null($preTestScore))
                    <p class="mt-2 text-sm text-gray-600">Nilai terakhir: <strong>{{ $preTestScore }}</strong></p>
                @endif
            </div>

            {{-- Progress Post-Test --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <h3 class="text-lg font-bold text-gray-800">Progress Post-Test</h3>
                @php
                    $postAttemptsReal = $postTestAttempts ?? ($postTestDone ? 1 : 0);
                    $postBar = $postAttemptsReal >= 1 ? 100 : 0;
                @endphp
                <div class="flex items-baseline mt-2">
                    <span class="text-3xl font-bold text-green-600">{{ $postAttemptsReal }}</span>
                    <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                    <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $postBar }}%"></div>
                </div>
                @if(!is_null($postTestScore))
                    <p class="mt-2 text-sm text-gray-600">Nilai terakhir: <strong>{{ $postTestScore }}</strong></p>
                @endif
            </div>

            {{-- Progress MONEV --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                <h3 class="text-lg font-bold text-gray-800">Progress MONEV</h3>
                @php
                    $monevAttemptsReal = $monevAttempts ?? ($monevDone ? 1 : 0);
                    $monevBar = $monevAttemptsReal >= 1 ? 100 : 0;
                @endphp
                <div class="flex items-baseline mt-2">
                    <span class="text-3xl font-bold text-blue-600">{{ $monevAttemptsReal }}</span>
                    <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $monevBar }}%"></div>
                </div>
                @if(!is_null($monevScore))
                    <p class="mt-2 text-sm text-gray-600">Nilai terakhir / skor: <strong>{{ $monevScore }}</strong></p>
                @endif
            </div>
        </div>
    </div>

@endsection

{{-- Overlay pilih peserta (muncul hanya bila belum ada peserta aktif) --}}
@if (empty($pesertaAktif))
<div id="pesertaOverlayRoot"
     class="fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900 bg-opacity-60"
     style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
        <h2 class="text-xl font-bold mb-4 text-center">Pilih Peserta untuk Memulai</h2>

        @if ($errors->any())
            <div class="mb-3 p-2 bg-red-50 text-red-700 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="setPesertaForm" method="POST" action="{{ route('dashboard.setPeserta') }}" class="space-y-3">
            @csrf

            {{-- Nama manual --}}
            <div class="mb-3">
                <label for="nama_input" class="block text-sm font-medium mb-1">Nama Peserta</label>
                <input type="text" id="nama_input" name="nama"
                       class="w-full p-2 border rounded focus:outline-none"
                       placeholder="Tulis nama peserta…" required autocomplete="off"
                       enterkeyhint="done" inputmode="text"
                       value="{{ old('nama') }}">
                <p id="nama_helper" class="text-xs text-gray-500 mt-1 hidden"></p>
            </div>

            {{-- Instansi auto (readonly) --}}
            <div class="mb-3">
                <label for="sekolah_display" class="block text-sm font-medium mb-1">Instansi</label>
                <input type="text" id="sekolah_display"
                       class="w-full p-2 border rounded bg-gray-100 focus:outline-none"
                       placeholder="Instansi akan muncul otomatis" readonly />
            </div>

            {{-- Hidden peserta_id dari hasil AJAX --}}
            <input type="hidden" name="peserta_id" id="peserta_id_hidden">

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit" id="btnSavePeserta"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                        disabled>
                    Lanjutkan
                </button>
                <button type="button" id="btnCancelSelect"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Batal
                </button>
            </div>
        </form>

        <p class="text-xs text-gray-400 mt-4 text-center">
            Pengisian peserta untuk Pre-test, Post-test, dan Monev.
        </p>
    </div>
</div>
@endif

{{-- Scripts --}}
<script>
    // Optional: tombol ganti peserta (kalau dipakai)
    document.getElementById('btnGantiPeserta')?.addEventListener('click', function() {
        const f = document.getElementById('hiddenUnsetForm');
        if (f) f.submit();
    });

    // Batal overlay
    document.getElementById('btnCancelSelect')?.addEventListener('click', function() {
        const f = document.getElementById('hiddenUnsetForm');
        if (f) { f.submit(); return; }
        location.reload();
    });

    // Anti double submit
    document.getElementById('setPesertaForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnSavePeserta');
        if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }
    });

    // === Instansi auto-assign via AJAX + auto-submit cepat ===
    (function(){
        const $form = document.getElementById('setPesertaForm');
        const $nama = document.getElementById('nama_input');
        const $inst = document.getElementById('sekolah_display');
        const $btn  = document.getElementById('btnSavePeserta');
        const $help = document.getElementById('nama_helper');
        const $pid  = document.getElementById('peserta_id_hidden');

        let t; let lastQuery = ''; let submitting = false;

        async function lookup(nama){
            const url = `{{ route('dashboard.ajax.peserta.instansiByNama') }}?nama=${encodeURIComponent(nama)}`;
            const res = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const data = await res.json().catch(()=>null);
            if (!res.ok || !data?.ok) throw new Error(data?.message || 'Lookup gagal');
            return data;
        }

        function setUI(data){
            const sekolah = data?.data?.instansi || '';
            const kota    = data?.data?.kota || '';
            const pid     = data?.data?.peserta_id || '';

            $inst.value   = sekolah ? (kota ? `${sekolah} (${kota})` : sekolah) : '';
            $pid.value    = pid;
            $btn.disabled = !(data?.ok && pid);
            if (data?.ok) $help.classList.add('hidden');

            // Auto-submit ketika valid
            if (data?.ok && pid && !submitting) {
                submitting = true;
                $btn.disabled = true;
                $btn.innerText = 'Menyimpan...';
                $form.requestSubmit();
            }
        }

        // Ketik nama → debounce → lookup
        $nama?.addEventListener('input', () => {
            const q = ($nama.value || '').trim();
            $btn.disabled = true; $inst.value = ''; $pid.value = '';
            if (!q || submitting) return;

            clearTimeout(t);
            t = setTimeout(async () => {
                if (q === lastQuery || submitting) return;
                lastQuery = q;
                try {
                    const data = await lookup(q);
                    setUI(data);
                } catch (e) {
                    $btn.disabled = true; $inst.value = ''; $pid.value = '';
                    $help.textContent = 'Peserta tidak ditemukan. Coba tulis lebih spesifik.';
                    $help.classList.remove('hidden');
                }
            }, 250);
        });

        // Enter → submit juga
        $nama?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !$btn.disabled && !submitting) {
                e.preventDefault();
                submitting = true;
                $btn.disabled = true;
                $btn.innerText = 'Menyimpan...';
                $form.requestSubmit();
            }
        });

        // Auto-lookup kalau old('nama') sudah ada
        document.addEventListener('DOMContentLoaded', () => {
            const q = ($nama?.value || '').trim();
            if (q) {
                const ev = new Event('input');
                $nama.dispatchEvent(ev);
            }
        });
    })();
</script>
