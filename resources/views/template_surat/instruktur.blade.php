<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biodata Instruktur Massal</title>
    <style>
        /* CSS dasar untuk styling, mirip seperti di Word */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 2.5cm; /* Margin standar dokumen */
        }
        .page-break {
            page-break-after: always; /* Ini kunci untuk membuat halaman baru untuk setiap instruktur */
        }
        .container {
            width: 100%;
        }
        .header, .footer {
            text-align: center;
        }
        .content-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .content-table td {
            padding: 4px 0;
        }
        .label {
            width: 30%;
        }
        .separator {
            width: 5%;
        }
        .data {
            width: 65%;
        }
        .signature {
            margin-top: 80px;
            text-align: right;
        }
        .catatan {
            margin-top: 100px;
            font-size: 10pt;
        }
    </style>
</head>
<body>

    {{-- Loop untuk setiap instruktur yang datanya dikirim dari controller --}}
    @foreach ($instruktur as $instruktur)
        <div class="container">
            <div class="header">
                <h3>BIODATA INSTRUKTUR</h3>
                <p>
                    KOMPETENSI {{ strtoupper($instruktur->bidang->nama_bidang ?? '') }}<br>
                    {{ strtoupper($instruktur->pelatihan->nama_pelatihan ?? '') }}<br> {{-- Asumsi ada nama_pelatihan --}}
                    TANGGAL {{ $instruktur->pelatihan->tanggal_mulai->isoFormat('D') }} S/D {{ $instruktur->pelatihan->tanggal_akhir->isoFormat('D MMMM YYYY') }}
                </p>
            </div>

            <table class="content-table">
                <tr>
                    <td class="label">Nama ( dengan gelar )</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->nama_gelar }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat / tgl Lahir</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->tempat_lahir }}, {{ $instruktur->tgl_lahir->isoFormat('D MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <td class="label">Agama</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->agama }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Rumah</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->alamat_rumah }}</td>
                </tr>
                 <tr>
                    <td class="label">No. HP</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->no_hp }}</td>
                </tr>
                 <tr>
                    <td class="label">Instansi</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->instansi }}</td>
                </tr>
                 <tr>
                    <td class="label">No. NPWP</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->npwp }}</td>
                </tr>
                 <tr>
                    <td class="label">NIK</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->nik }}</td>
                </tr>
                 <tr>
                    <td class="label">Nama Bank / No. Rekening</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->nama_bank }} / {{ $instruktur->no_rekening }}</td>
                </tr>
                 <tr>
                    <td class="label">Pendidikan Terakhir</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->pendidikan_terakhir }}</td>
                </tr>
                 <tr>
                    <td class="label">Pengalaman Kerja</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $instruktur->pengalaman_kerja }}</td>
                </tr>
            </table>

            <div class="signature">
                <p>(........................................)</p>
            </div>

            <div class="catatan">
                <b>Catatan :</b><br>
                Mohon Melampirkan CV, Fc KTP, Buku Rekening dan Sertifikat Pendukung Yang Dimiliki
            </div>

        </div>

        {{-- Jika ini bukan instruktur terakhir, tambahkan page break --}}
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
