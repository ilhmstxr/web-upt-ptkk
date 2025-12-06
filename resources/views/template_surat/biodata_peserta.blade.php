<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biodata Peserta Massal</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            margin: 2cm;
        }
        .page-break {
            page-break-after: always;
        }
        .container {
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-weight: bold;
            font-size: 18pt;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14pt;
            font-weight: bold;
        }
        .content-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            font-size: 12pt;
        }
        .content-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label {
            width: 35%;
        }
        .separator {
            width: 2%;
            text-align: center;
        }
        .data {
            width: 63%;
        }
        .photo-box {
            width: 3cm;
            height: 4cm;
            border: 1px solid #000;
            margin-top: 20px;
            text-align: center;
            line-height: 4cm;
            font-size: 10pt;
            float: right;
            margin-right: 20px;
        }
        .signature-section {
            margin-top: 80px;
            width: 100%;
            display: table;
            font-size: 12pt;
        }
        .signature-right {
            display: table-cell;
            text-align: right;
            vertical-align: top;
            width: 50%; /* Adjust as needed */
        }
        .signature-left {
            display: table-cell;
            text-align: left;
            vertical-align: top;
             width: 50%;
        }
        .footer-note {
            margin-top: 50px;
            font-size: 10pt;
            font-style: italic;
        }
    </style>
</head>
<body>

    @foreach ($peserta as $p)
        <div class="container">
            <div class="header">
                <div class="title" style="text-decoration: none; margin-bottom: 20px;">BIODATA PESERTA</div>
                <div class="subtitle" style="font-weight: normal; font-size: 14pt; margin-bottom: 20px;">
                    {{ $p->pelatihan->nama_pelatihan ?? 'Kegiatan Pelatihan' }} <br>
                    Angkatan {{ $p->pelatihan->angkatan ?? '...' }} Tahun {{ date('Y') }}
                </div>
                <div style="font-size: 12pt;">
                    Tanggal {{ $p->pelatihan->tanggal_mulai ? $p->pelatihan->tanggal_mulai->isoFormat('D') : '...' }} s/d {{ $p->pelatihan->tanggal_selesai ? $p->pelatihan->tanggal_selesai->isoFormat('D MMMM YYYY') : '...' }}
                </div>
            </div>

            <table class="content-table">
                <tr>
                    <td class="label">Nama</td>
                    <td class="separator">:</td>
                    <td class="data"><b>{{ $p->nama }}</b></td>
                </tr>
                <tr>
                    <td class="label">Tempat / tgl Lahir</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->tempat_lahir ?? '-' }}, {{ $p->tanggal_lahir ? $p->tanggal_lahir->isoFormat('D MMMM YYYY') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->jenis_kelamin ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Agama</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->agama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">No. HP</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->no_hp ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Nomor Induk Siswa</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Asal Lembaga</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->instansi->asal_instansi ?? ($p->asal_sekolah ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Lembaga</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->instansi->alamat_instansi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kelas</td>
                    <td class="separator">:</td>
                    <td class="data">-</td>
                </tr>
                <tr>
                    <td class="label">Kompetensi Keahlian</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->pelatihan->nama_pelatihan ?? '-' }}</td>
                </tr>
            </table>

            <div class="signature-section">
                <div class="signature-right">
                    <p>
                        Malang, {{ now()->isoFormat('D MMMM YYYY') }}<br>
                        Peserta Pelatihan,
                    </p>
                    <br><br><br>
                    <p style="text-decoration: underline; font-weight: bold;">{{ $p->nama }}</p>
                </div>
            </div>

            <div style="margin-top: 60px; font-size: 12pt; text-align: left; width: 60%;">
                <p style="margin-bottom: 5px;"><b>Catatan :</b> Mohon Melampirkan Surat Tugas, Fc Kartu Pelajar/KTP/KK/Ijazah</p>
                <div style="border-bottom: 3px solid black; width: 100%;"></div>
            </div>
            
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
