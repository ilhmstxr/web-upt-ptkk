@extends('dashboard.layouts.main')

@section('title', 'Profile')
@section('page-title', 'Profil Peserta')

@section('content')

    {{-- ===================== DATA DIRI ===================== --}}
    <div class="bg-white p-6 rounded-xl shadow-md fade-in card-hover mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-xl text-slate-800">Data Diri</h2>
        </div>

        @if(isset($peserta))
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm text-slate-700">
                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Nama Lengkap</dt>
                    <dd class="mt-0.5">{{ $peserta->nama }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">NIK</dt>
                    <dd class="mt-0.5">{{ $peserta->nik }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Tempat / Tanggal Lahir</dt>
                    <dd class="mt-0.5">
                        {{ $peserta->tempat_lahir }},
                        {{ \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d M Y') }}
                    </dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Jenis Kelamin</dt>
                    <dd class="mt-0.5">{{ $peserta->jenis_kelamin }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Agama</dt>
                    <dd class="mt-0.5">{{ $peserta->agama }}</dd>
                </div>

                {{-- JABATAN DIHAPUS --}}

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Instansi</dt>
                    <dd class="mt-0.5">
                        {{ optional($peserta->instansi)->asal_instansi ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">No. HP</dt>
                    <dd class="mt-0.5">{{ $peserta->no_hp }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Email</dt>
                    <dd class="mt-0.5">
                        {{-- asumsi relasi ke tabel users --}}
                        {{ optional($peserta->user)->email ?? '-' }}
                    </dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Alamat</dt>
                    <dd class="mt-0.5">{{ $peserta->alamat }}</dd>
                </div>
            </dl>
        @else
            <p class="text-gray-600">Data peserta belum tersedia.</p>
        @endif
    </div>

    {{-- ===================== INFORMASI PELATIHAN AKTIF ===================== --}}
    <div class="bg-white p-6 rounded-xl shadow-md fade-in card-hover mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-xl text-slate-800">Informasi Pelatihan Aktif</h2>
        </div>

        @if(isset($pendaftaranAktif))
            @php
                $statusPendaftaranColor = [
                    'pending'    => 'bg-amber-100 text-amber-800 border-amber-200',
                    'verifikasi' => 'bg-sky-100 text-sky-800 border-sky-200',
                    'diterima'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'ditolak'    => 'bg-red-100 text-red-800 border-red-200',
                ][$pendaftaranAktif->status_pendaftaran] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                $statusColor = [
                    'Lulus'        => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'Tidak Lulus'  => 'bg-red-100 text-red-800 border-red-200',
                    'Belum Lulus'  => 'bg-slate-100 text-slate-700 border-slate-200',
                ][$pendaftaranAktif->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
            @endphp

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm text-slate-700">
                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Pelatihan</dt>
                    <dd class="mt-0.5">
                        {{-- ambil dari model Pelatihan: kolom nama_pelatihan --}}
                        {{ optional($pendaftaranAktif->pelatihan)->nama_pelatihan ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Kompetensi</dt>
                    <dd class="mt-0.5">
                        {{-- lewat KompetensiPelatihan -> kompetensi -> nama_kompetensi --}}
                        {{ optional(optional($pendaftaranAktif->kompetensiPelatihan)->kompetensi)->nama_kompetensi ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Kelas</dt>
                    <dd class="mt-0.5">{{ $pendaftaranAktif->kelas ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Tanggal Pendaftaran</dt>
                    <dd class="mt-0.5">
                        {{ $pendaftaranAktif->tanggal_pendaftaran
                            ? \Carbon\Carbon::parse($pendaftaranAktif->tanggal_pendaftaran)->format('d M Y H:i')
                            : '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Nomor Registrasi</dt>
                    <dd class="mt-0.5 font-mono text-xs">
                        {{ $pendaftaranAktif->nomor_registrasi }}
                    </dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Status Pendaftaran</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusPendaftaranColor }}">
                            {{ ucfirst($pendaftaranAktif->status_pendaftaran) }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide">Status Kelulusan</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusColor }}">
                            {{ $pendaftaranAktif->status }}
                        </span>
                    </dd>
                </div>

                <div class="md:col-span-2 border-t border-slate-200 pt-3 mt-2">
                    <dt class="font-semibold text-slate-500 text-xs uppercase tracking-wide mb-2">Nilai</dt>
                    <dd>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                <p class="text-slate-500">Pre-Test</p>
                                <p class="text-lg font-semibold text-slate-800">
                                    {{ $pendaftaranAktif->nilai_pre_test }}
                                </p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                <p class="text-slate-500">Post-Test</p>
                                <p class="text-lg font-semibold text-slate-800">
                                    {{ $pendaftaranAktif->nilai_post_test }}
                                </p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                <p class="text-slate-500">Praktek</p>
                                <p class="text-lg font-semibold text-slate-800">
                                    {{ $pendaftaranAktif->nilai_praktek }}
                                </p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                <p class="text-slate-500">Rata-rata</p>
                                <p class="text-lg font-semibold text-slate-800">
                                    {{ $pendaftaranAktif->rata_rata }}
                                </p>
                            </div>
                        </div>
                    </dd>
                </div>
            </dl>
        @else
            <p class="text-gray-600">
                Belum ada pelatihan aktif yang terdaftar untuk peserta ini.
            </p>
        @endif
    </div>

@endsection
