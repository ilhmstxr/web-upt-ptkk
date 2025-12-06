<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biodata Peserta Massal</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
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
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12pt;
            font-weight: bold;
        }
        .content-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
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
            margin-top: 50px;
            width: 100%;
            display: table;
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
                <div class="title">BIODATA PESERTA PELATIHAN</div>
                <div class="subtitle">
                    {{ strtoupper($p->pelatihan->nama_pelatihan ?? 'PROGRAM PELATIHAN') }}<br>
                    TAHUN {{ date('Y') }}
                </div>
            </div>

            <table class="content-table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td class="data"><b>{{ $p->nama }}</b></td>
                </tr>
                <tr>
                    <td class="label">NIK</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
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
                    <td class="label">Alamat Rumah</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">No. HP / WhatsApp</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->no_hp ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->user->email ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Program Pelatihan</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->pelatihan->nama_pelatihan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Angkatan</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->pelatihan->angkatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Asal Lembaga / Sekolah</td>
                    <td class="separator">:</td>
                    <td class="data">{{ $p->instansi->name ?? ($p->asal_sekolah ?? '-') }}</td>
                </tr>
                {{-- Add conditional fields if they exist in schema, usually helpful to be defensive --}}
            </table>

            <div class="signature-section">
                <!-- Can add left signature like "Mengetahui" if needed, keeping simple based on request -->
                <div class="signature-right">
                    <p>
                        {{-- Generic validation date or from created_at --}}
                        Malang, {{ now()->isoFormat('D MMMM YYYY') }}<br>
                        Peserta Pelatihan,
                    </p>
                    <br><br><br>
                    <p style="text-decoration: underline; font-weight: bold;">{{ $p->nama }}</p>
                </div>
            </div>

            <!-- Optional: Photo Placeholder -->
            {{-- <div class="photo-box">
                FOTO 3x4
            </div> --}}
            
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
