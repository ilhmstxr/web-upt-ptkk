<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Biodata Peserta</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.5; }
        h2 { text-align: center; margin-bottom: 10px; }
        p { text-align: center; margin-top: 0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 4px 6px; vertical-align: top; }
        .signature { text-align: right; margin-top: 40px; }
    </style>
</head>
<body>
    <h2>BIODATA PESERTA</h2>
    <p>
        Kegiatan Pengembangan dan Pelatihan Kompetensi Vokasi bagi Siswa SMA/SMK (MILEA) <br>
        menuju Generasi Emas 2045 (Kelas Keterampilan) Angkatan II Tahun 2025 <br>
        Tanggal 25 s/d 30 Agustus 2025
    </p>

    <table>
        <tr><td>Nama</td><td>: {{ $peserta->nama }}</td></tr>
        <tr><td>Tempat / Tgl Lahir</td><td>: {{ $peserta->tempat_lahir }}, {{ $peserta->tanggal_lahir?->format('d-m-Y') }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>: {{ $peserta->jenis_kelamin }}</td></tr>
        <tr><td>Agama</td><td>: {{ $peserta->agama }}</td></tr>
        <tr><td>No. HP</td><td>: {{ $peserta->no_hp }}</td></tr>
        <tr><td>NIK</td><td>: {{ $peserta->nik }}</td></tr>
        <tr><td>Asal Lembaga</td><td>: {{ $peserta->instansi->asal_instansi ?? '-' }}</td></tr>
        <tr><td>Alamat Lembaga</td><td>: {{ $peserta->instansi->alamat_instansi ?? '-' }}</td></tr>
        <tr><td>Kelas</td><td>: {{ $peserta->instansi->kelas ?? '-' }}</td></tr>
        <tr><td>Kompetensi Keahlian</td><td>: {{ $peserta->instansi->kompetensi_keahlian ?? '-' }}</td></tr>
    </table>

    <div class="signature">
        Surabaya, .................................. <br><br><br><br>
        (..................................................)
    </div>

    <p><b>Catatan:</b><br>
        Mohon Melampirkan Surat Tugas, Fotokopi Kartu Pelajar/KTP/KK, dan Ijazah terakhir
    </p>
</body>
</html>
