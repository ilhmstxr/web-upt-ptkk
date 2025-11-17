<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Survei - {{ $pelatihan->nama ?? 'Tanpa Nama' }}</title>
    <style>
        body { font-family: sans-serif; margin: 30px; color: #333; }
        h1, h2, h3 { text-align: center; margin: 0; }
        h1 { font-size: 20px; }
        h2 { font-size: 18px; margin-top: 4px; }
        h3 { font-size: 16px; margin-top: 8px; }
        .chart { width: 100%; text-align: center; margin-top: 20px; }
        .info { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; font-size: 12px; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h1>{{ $judulMonev }}</h1>
    <h2>{{ $pelatihan->nama ?? 'Pelatihan Tidak Ditemukan' }}</h2>
    <h3>Angkatan {{ $angkatan }}</h3>
    <p class="info">Total Jawaban Masuk: <b>{{ $totalJawaban }}</b></p>

    <div class="chart">
        <h3>Grafik Total Jawaban</h3>
        <img src="https://quickchart.io/chart?c={
            type:'pie',
            data:{
                labels:['Jawaban Diterima','Sisa Pertanyaan'],
                datasets:[{data:[{{ $totalJawaban }}, 100 - {{ $totalJawaban }}],
                backgroundColor:['#36A2EB','#EAEAEA']}]
            }
        }" alt="Total Chart" width="250">
    </div>

    <div class="chart">
        <h3>Grafik Per Soal</h3>
        @foreach($jawabanPerSoal as $soal)
            <p><b>{{ $soal->teks_pertanyaan }}</b></p>
            <img src="https://quickchart.io/chart?c={
                type:'pie',
                data:{
                    labels:['Jumlah Jawaban'],
                    datasets:[{data:[{{ $soal->total_jawaban ?? 0 }}],
                    backgroundColor:['#FF6384']}]
                }
            }" alt="Grafik Soal" width="200">
        @endforeach
    </div>
</body>
</html>
