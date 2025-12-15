<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pendaftaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #2563eb;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
            color: #1e40af;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 250px;
            font-weight: bold;
            color: #555;
        }

        .alert-box {
            background-color: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 15px;
            margin: 15px 0;
        }

        ul,
        ol {
            padding-left: 20px;
            margin-top: 5px;
        }

        li {
            margin-bottom: 5px;
        }

        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>UPT PTKK - Dinas Pendidikan Jatim</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px;">Informasi Kegiatan Pelatihan</p>
        </div>

        <div class="content">
            <p>Kepada Yth. <strong>{{ $nama_peserta ?? '[Nama Peserta]' }}</strong>,</p>
            <p>Berikut kami sampaikan detail informasi mengenai kegiatan pelatihan Anda:</p>

            <div class="section-title">INFORMASI PENDAFTARAN</div>
            <table class="info-table">
                <tr>
                    <td>ID Peserta</td>
                    <td>: {{ $id_peserta ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>: {{ $nama_peserta ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Asal lembaga</td>
                    <td>: {{ $asal_lembaga ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Cabang Dinas Wilayah</td>
                    <td>: {{ $cabang_dinas ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kompetensi Pelatihan</td>
                    <td>: {{ $kompetensi ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kamar Asrama Penginapan</td>
                    <td>: {{ $kamar_asrama ?? '-' }}</td>
                </tr>
            </table>

            <div class="section-title">WAKTU DAN TEMPAT PELAKSANAAN</div>
            <table class="info-table">
                <tr>
                    <td>Waktu</td>
                    <td>: {{ $waktu_mulai ?? '...' }} s.d {{ $waktu_selesai ?? '...' }}</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>: {{ $lokasi ?? 'UPT PTKK' }}, {{ $alamat_lengkap ?? '-' }}</td>
                </tr>
            </table>

            <div class="alert-box">
                <strong>Catatan Penting !!!</strong>
                <ol style="margin-top: 5px; margin-bottom: 0;">
                    <li>Peserta diharapkan membawa dokumen administrasi lengkap.</li>
                    <li>Kegiatan Pembelajaran dan Proses Administrasi dilakukan secara offline dan peserta diharapkan datang tepat waktu.</li>
                </ol>
            </div>

            <div class="section-title">DOKUMEN ADMINISTRASI YANG WAJIB DIBAWA</div>
            <ol>
                <li>Fotocopy Ijazah terakhir</li>
                <li>Pas Foto formal background merah (ukuran 3x4 cm sebanyak 3 lembar), pakaian kemeja putih, jilbab hitam polos (bagi yang berjilbab)</li>
                <li>Surat Tugas yang telah ditandatangani oleh pejabat berwenang di Cabang Dinas Pendidikan Provinsi Jawa Timur setempat</li>
                <li>Fotocopy KTP/KK atau Kartu Pelajar/Surat Keterangan yang menyatakan sebagai siswa aktif dari lembaga masing-masing</li>
                <li>Surat Keterangan Sehat [bagi peserta Reguler dan Akselerasi]</li>
            </ol>

            <div class="section-title">TATA TERTIB PESERTA</div>
            <ul>
                <li>Hadir 15 menit sebelum pelatihan dimulai</li>
                <li>Mengisi dan menandatangani daftar hadir</li>
                <li>Tidak meninggalkan kegiatan tanpa izin panitia</li>
                <li>Dilarang merokok saat pelatihan berlangsung</li>
                <li>Jika sakit, wajib memberitahu panitia</li>
                <li>Ketidakhadiran lebih dari 20% tanpa alasan sah → dinyatakan tidak lulus</li>
                <li>Tidak diperbolehkan keluar dari lingkungan UPT PTKK</li>
                <li>Pelanggaran tata tertib → dikembalikan ke lembaga asal</li>
            </ul>

            <div class="section-title">KETENTUAN SERAGAM DAN BARANG BAWAAN</div>
            <ul>
                <li>Membawa pakaian kemeja putih, bawahan warna hitam, jilbab hitam (Putri) selama mengikuti pelatihan</li>
                <li>Membawa pakaian olahraga lengkap, dan membawa pakaian bebas rapi, sopan, untuk aktifitas sehari-hari.</li>
            </ul>

            <div class="section-title">Informasi Tambahan</div>
            <p>Informasi dan konfirmasi lebih lanjut dapat menghubungi:</p>
            <p style="font-weight: bold;">
                {{ $cp_nama ?? 'Sdri. Pramudya Putri Dewanti, S.Pi' }} (CP. – {{ $cp_no ?? '082249999447' }})
            </p>

            <br>
            <p>Hormat Kami,</p>
            <p><strong>UPT. Pengembangan Teknis dan Keterampilan Kejuruan<br>
                    Dinas Pendidikan Provinsi Jawa Timur</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>