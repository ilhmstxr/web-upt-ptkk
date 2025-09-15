@extends('dashboard.layouts.main')

@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')

@php
    $peserta = $peserta ?? collect();
@endphp

{{-- Pesan notifikasi --}}
@if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 font-semibold" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 font-semibold" role="alert">
        {{ session('error') }}
    </div>
@endif

{{-- Hidden form untuk unset peserta (tidak nested) --}}
<form id="hiddenUnsetForm" action="{{ route('dashboard.unsetPeserta') }}" method="POST" style="display:none;">
    @csrf
</form>

{{-- Konten dashboard --}}
<div class="{{ empty($pesertaAktif) ? 'blur-sm pointer-events-none select-none' : '' }}">
    @if($pesertaAktif)
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Hai, {{ explode(' ', $pesertaAktif->nama)[0] ?? 'Peserta' }} 👋</h2>
                <p class="text-gray-600">Selamat datang di dashboard pelatihan</p>
            </div>
            <div class="flex gap-2">
                {{-- Ganti Peserta (men-submit hidden form) --}}
                <button id="btnGantiPeserta"
                        class="text-sm px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Ganti Peserta
                </button>

                {{-- Logout --}}
                <form action="{{ route('dashboard.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-sm px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Card dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Pre-Test --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-bold text-gray-800">Pre-Test</h3>
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                </div>
                <p class="text-gray-500 mt-2 mb-4">Cek kesiapanmu sebelum mengikuti materi.</p>
            </div>
            <a href="{{ route('dashboard.pretest.index') }}"
               class="w-full block text-center bg-yellow-400 text-yellow-900 font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition-colors">
                Kerjakan Pre-Test
            </a>
        </div>

        {{-- Post-Test --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
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
        <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-gray-800">MONEV</h3>
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                </div>
                <p class="text-gray-500 mt-2">Akses Monitoring dan Evaluasi Selama Mengikuti Pelatihan.</p>
            </div>
            <a href="{{ route('dashboard.materi') }}"
               class="mt-6 block text-center w-full lg:w-auto bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                Mulai Survey
            </a>
        </div>

        {{-- Progress Cards --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="text-lg font-bold text-gray-800">Progress Pre-Test</h3>
            <div class="flex items-baseline mt-2">
                <span class="text-3xl font-bold text-yellow-600">2</span>
                <span class="text-lg text-gray-500 ml-1">/ 3 dikerjakan</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 66%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="text-lg font-bold text-gray-800">Progress Post-Test</h3>
            <div class="flex items-center mt-2">
                <span class="text-lg font-semibold text-red-600">Belum dikerjakan</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-green-500 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="text-lg font-bold text-gray-800">Progress MONEV</h3>
            <div class="flex items-center mt-2">
                <span class="text-lg font-semibold text-red-600">Belum dikerjakan</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-green-500 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Push overlay ke stack modals agar berada di level root --}}
@push('modals')
    @if(empty($pesertaAktif))
        <div
            class="fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900 bg-opacity-60"
            aria-hidden="true"
            style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
                <h2 class="text-xl font-bold mb-4 text-center">Pilih Peserta</h2>

                <form id="setPesertaForm" action="{{ route('dashboard.setPeserta') }}" method="POST">
                    @csrf
                    <select name="peserta_id" required class="w-full p-2 border rounded mb-3">
                        <option value="">-- Pilih Peserta --</option>
                        @foreach($peserta as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nama }} - {{ $p->instansi->nama ?? '' }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex justify-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Peserta</button>
                        <button type="button" id="btnCancelSelect" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endpush

@push('scripts')
<script>
    // Submit hidden unset form when user clicks "Ganti Peserta" (di header)
    document.getElementById('btnGantiPeserta')?.addEventListener('click', function(){
        document.getElementById('hiddenUnsetForm').submit();
    });

    // Cancel button in overlay: submit unset form to clear session (hidupkan overlay)
    document.getElementById('btnCancelSelect')?.addEventListener('click', function(){
        document.getElementById('hiddenUnsetForm').submit();
    });
</script>
@endpush
