<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing Lampiran Peserta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Daftar Lampiran Peserta</h1>
        {{-- <img src="{{ asset('images/logo-upt-ptkk.png') }}" alt=""> --}}

        @forelse ($peserta as $peserta)
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h2 class="text-2xl font-bold mb-4">Detail Lampiran untuk: {{ $peserta->nama }}</h2>

                @if ($peserta->lampiran)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold border-b pb-2 mb-3">Path File (Data dari Database)</h3>
                            <ul class="space-y-2 text-sm">
                                <li><strong>Pas Foto:</strong> <code
                                        class="bg-gray-200 p-1 rounded">{{ $peserta->lampiran->pas_foto ?? 'Tidak ada' }}</code>
                                </li>
                                <li><strong>KTP:</strong> <code
                                        class="bg-gray-200 p-1 rounded">{{ $peserta->lampiran->fc_ktp ?? 'Tidak ada' }}</code>
                                </li>
                                <li><strong>Ijazah:</strong> <code
                                        class="bg-gray-200 p-1 rounded">{{ $peserta->lampiran->fc_ijazah ?? 'Tidak ada' }}</code>
                                </li>
                                <li><strong>Surat Sehat:</strong> <code
                                        class="bg-gray-200 p-1 rounded">{{ $peserta->lampiran->fc_surat_sehat ?? 'Tidak ada' }}</code>
                                </li>
                                <li><strong>Surat Tugas:</strong> <code
                                        class="bg-gray-200 p-1 rounded">{{ $peserta->lampiran->fc_surat_tugas ?? 'Tidak ada' }}</code>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold border-b pb-2 mb-3">Preview Gambar & Link Download</h3>
                            <div class="space-y-4">


                                {{-- Preview Pas Foto --}}
                                @if ($peserta->lampiran->pas_foto)
                                    @php
                                        $ext = pathinfo($peserta->lampiran->pas_foto, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div>
                                        <h4 class="font-medium">Pas Foto</h4>
                                        @if ($isImage)
                                            <img src="{{ Storage::disk('public')->url($peserta->lampiran->pas_foto) }}"
                                                alt="Pas Foto" class="mt-2 border rounded-md w-40 h-auto">
                                        @else
                                            <a href="{{ Storage::disk('public')->url($peserta->lampiran->pas_foto) }}"
                                                target="_blank" class="text-blue-600 hover:underline">
                                                Download Pas Foto
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Preview KTP --}}
                                @if ($peserta->lampiran->fc_ktp)
                                    @php
                                        $ext = pathinfo($peserta->lampiran->fc_ktp, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div>
                                        <h4 class="font-medium">KTP</h4>
                                        @if ($isImage)
                                            <img src="{{ Storage::disk('public')->url($peserta->lampiran->fc_ktp) }}"
                                                alt="KTP" class="mt-2 border rounded-md w-full max-w-xs h-auto">
                                        @else
                                            <a href="{{ Storage::disk('public')->url($peserta->lampiran->fc_ktp) }}"
                                                target="_blank" class="text-blue-600 hover:underline">
                                                Download KTP
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Ijazah --}}
                                @if ($peserta->lampiran->fc_ijazah)
                                    @php
                                        $ext = pathinfo($peserta->lampiran->fc_ijazah, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div>
                                        <h4 class="font-medium">Ijazah</h4>
                                        @if ($isImage)
                                            <img src="{{ Storage::disk('public')->url($peserta->lampiran->fc_ijazah) }}"
                                                alt="Ijazah" class="mt-2 border rounded-md w-40 h-auto">
                                        @else
                                            <a href="{{ Storage::disk('public')->url($peserta->lampiran->fc_ijazah) }}"
                                                target="_blank" class="text-blue-600 hover:underline">
                                                Lihat/Download Ijazah
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Surat Sehat --}}
                                @if ($peserta->lampiran->fc_surat_sehat)
                                    @php
                                        $ext = pathinfo($peserta->lampiran->fc_surat_sehat, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div>
                                        <h4 class="font-medium">Surat Keterangan Sehat</h4>
                                        @if ($isImage)
                                            <img src="{{ Storage::disk('public')->url($peserta->lampiran->fc_surat_sehat) }}"
                                                alt="Surat Sehat" class="mt-2 border rounded-md w-40 h-auto">
                                        @else
                                            <a href="{{ Storage::disk('public')->url($peserta->lampiran->fc_surat_sehat) }}"
                                                target="_blank" class="text-blue-600 hover:underline">
                                                Lihat/Download Surat Sehat
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Surat Tugas --}}
                                @if ($peserta->lampiran->fc_surat_tugas)
                                    @php
                                        $ext = pathinfo($peserta->lampiran->fc_surat_tugas, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div>
                                        <h4 class="font-medium">Surat Tugas</h4>
                                        @if ($isImage)
                                            <img src="{{ Storage::disk('public')->url($peserta->lampiran->fc_surat_tugas) }}"
                                                alt="Surat Tugas" class="mt-2 border rounded-md w-40 h-auto">
                                        @else
                                            <a href="{{ Storage::disk('public')->url($peserta->lampiran->fc_surat_tugas) }}"
                                                target="_blank" class="text-blue-600 hover:underline">
                                                Lihat/Download Surat Tugas
                                            </a>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-red-500">Peserta ini tidak memiliki data lampiran.</p>
                @endif
            </div>
        @empty
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500">Tidak ada data peserta untuk ditampilkan.</p>
            </div>
        @endforelse
    </div>
</body>

</html>
