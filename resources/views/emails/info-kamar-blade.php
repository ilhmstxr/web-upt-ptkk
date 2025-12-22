<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Kamar Asrama</title>
</head>
<body>
    <p>Yth. {{ $peserta->nama }},</p>

    <p>
        Berikut informasi penempatan asrama Anda untuk pelatihan
        <strong>{{ $pelatihan->nama ?? '-' }}</strong>:
    </p>

    <ul>
        <li>Asrama : <strong>{{ $asrama->nama }}</strong> ({{ $asrama->jenis_kelamin }})</li>
        <li>Nomor Kamar : <strong>{{ $kamar->nomor_kamar }}</strong></li>
        <li>Kapasitas : <strong>{{ $kamar->total_beds }} bed</strong></li>
    </ul>

    <p>
        Mohon memperhatikan tata tertib asrama selama tinggal:
    </p>

    <ol>
        <li>Menjaga ketertiban dan kebersihan kamar.</li>
        <li>Tidak memindahkan perabot tanpa izin pengelola.</li>
        <li>Tidak menerima tamu lawan jenis di dalam kamar.</li>
        <li>Mematuhi jam malam yang telah ditentukan.</li>
    </ol>

    <p>
        Jika ada pertanyaan, silakan hubungi pengelola asrama UPT PTKK Jawa Timur.
    </p>

    <p>Terima kasih.</p>

    <p>Salam,<br>UPT PTKK Jawa Timur</p>
</body>
</html>
