<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biodata Peserta</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12pt;
        }
        .peserta {
            page-break-after: always;
            margin-bottom: 20px;
        }
        .peserta:last-child {
            page-break-after: auto;
        }
        h2 {
            margin-bottom: 5px;
        }
        p {
            margin: 3px 0;
        }
        hr {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    @foreach ($peserta as $peserta)
        <div class="peserta">
            <h2>{{ $peserta->nama }}</h2>
            <p><strong>Email:</strong> {{ $peserta->email }}</p>
            <p><strong>NIK:</strong> {{ $peserta->nik }}</p>
            <p><strong>Tempat/Tanggal Lahir:</strong> {{ $peserta->tempat_lahir }} / {{ $peserta->tanggal_lahir->format('d-m-Y') }}</p>
            <p><strong>Jenis Kelamin:</strong> {{ $peserta->jenis_kelamin }}</p>
            <p><strong>Agama:</strong> {{ $peserta->agama }}</p>
            <p><strong>No HP:</strong> {{ $peserta->no_hp }}</p>
            <p><strong>Alamat:</strong> {{ $peserta->alamat }}</p>
            <p><strong>Asal Instansi:</strong> {{ $peserta->instansi->asal_instansi}}</p>
            <p><strong>Kompetensi Keahlian:</strong> {{ $peserta->instansi->kompetensi_keahlian ?? '-'}}</p>
            <p><strong>Kelas:</strong> {{ $peserta->kelas}}</p>
            <p><strong>Cabang Dinas/Wilayah:</strong> {{ $peserta->cabang_dinas_wilayah}}</p>
            <hr>
        </div>
    @endforeach
</body>
</html>
