<!DOCTYPE html>
<html>

<head>
    <title>Konfirmasi Pendaftaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            text-decoration: underline;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            width: 40%;
            font-weight: bold;
        }

        .important {
            color: red;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <p>Halo <strong>{{ $data['nama_peserta'] }}</strong>,</p>

        <p>Terima kasih telah mendaftar pada Program <strong>{{ $data['nama_pelatihan'] }}</strong> yang diselenggarakan oleh UPT Pengembangan Teknis dan Keterampilan Kejuruan Dinas Pendidikan Provinsi Jawa Timur. Kami telah melakukan verifikasi terhadap data yang Anda kirim. Berikut hal yang perlu Anda ketahui dan perhatikan:</p>

        <div class="header">INFORMASI PENDAFTARAN</div>
        <table class="info-table">
            <tr>
                <td class="label">ID Peserta</td>
                <td>: {{ $data['id_peserta'] }}</td>
            </tr>
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $data['nama_peserta'] }}</td>
            </tr>
            <tr>
                <td class="label">Asal Lembaga</td>
                <td>: {{ $data['asal_lembaga'] }}</td>
            </tr>
            <tr>
                <td class="label">Cabang Dinas Wilayah</td>
                <td>: {{ $data['cabang_dinas'] }}</td>
            </tr>
            <tr>
                <td class="label">Kompetensi</td>
                <td>: {{ $data['kompetensi'] }}</td>
            </tr>
            <tr>
                <td class="label">Kamar Asrama</td>
                <td>: {{ $data['kamar_asrama'] }}</td>
            </tr>
            <tr>
                <td class="label">Token Assesment</td>
                <td>: {{ $data['token_assessment'] }}</td>
            </tr> <!-- tanggal lahir yang  tidak ada stripnya -->
            <tr>
                <td class="label">Password</td>
                <td>: {{ $data['password'] }}</td>
            </tr> <!-- tanggal lahir yang  tidak ada stripnya -->
        </table>

        <div class="header">WAKTU DAN TEMPAT PELAKSANAAN</div>
        <table class="info-table">
            <tr>
                <td class="label">Waktu</td>
                <td>: {{ $data['waktu_mulai'] }} s.d {{ $data['waktu_selesai'] }}</td>
            </tr>
            <tr>
                <td class="label">Lokasi</td>
                <td>: {{ $data['lokasi'] }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $data['alamat'] }}</td>
            </tr>
        </table>

        <p class="important">Catatan Penting !!!</p>
        <ol>
            <li>Peserta diharapkan membawa dokumen administrasi lengkap.</li>
            <li>Kegiatan Pembelajaran dan Proses Administrasi dilakukan secara offline dan peserta diharapkan datang tepat waktu.</li>
        </ol>

        <div class="header">DOKUMEN ADMINISTRASI YANG WAJIB DIBAWA</div>
        <ol>
            <li>Fotocopy Ijazah terakhir</li>
            <li>Pas Foto formal background merah (ukuran 3x4 cm sebanyak 3 lembar), pakaian kemeja putih, jilbab hitam polos (bagi yang berjilbab)</li>
            <li>Surat Tugas yang telah ditandatangani oleh pejabat berwenang di Cabang Dinas Pendidikan Provinsi Jawa Timur setempat</li>
            <li>Fotocopy KTP/KK atau Kartu Pelajar/Surat Keterangan yang menyatakan sebagai siswa aktif dari lembaga masing-masing</li>
            <li>Surat Keterangan Sehat [bagi peserta Reguler dan Akselerasi]</li>
        </ol>

        <div class="header">TATA TERTIB PESERTA</div>
        <ul>
            <li>Hadir 15 menit sebelum pelatihan dimulai</li>
            <li>Mengisi dan menandatangani daftar hadir</li>
            <li>Tidak meninggalkan kegiatan tanpa izin panitia</li>
            <li>Dilarang merokok atau di sekitar area pelatihan</li>
            <li>Jika sakit, wajib memberitahu panitia</li>
            <li>Ketidakhadiran lebih dari 20% tanpa alasan sah → dinyatakan tidak lulus</li>
            <li>Tidak diperbolehkan keluar dari lingkungan UPT PTKK</li>
            <li>Pelanggaran tata tertib → dikembalikan ke lembaga asal</li>
        </ul>

        <div class="header">KETENTUAN SERAGAM DAN BARANG BAWAAN</div>
        <ul>
            <li>Membawa pakaian kemeja putih, bawahan warna hitam, jilbab hitam (Putri) selama mengikuti pelatihan</li>
            <li>Membawa pakaian olahraga lengkap, dan membawa pakaian bebas rapi, sopan, untuk aktifitas sehari-hari.</li>
        </ul>

        <p><strong>Informasi Tambahan:</strong><br>
            Informasi dan konfirmasi lebih lanjut dapat menghubungi {{ $data['cp_nama'] }} (CP. – {{ $data['cp_phone'] }}).</p>

        <div class="footer">
            <p>Hormat Kami,<br>
                UPT. Pengembangan Teknis dan Keterampilan Kejuruan<br>
                Dinas Pendidikan Provinsi Jawa Timur</p>
        </div>
    </div>
</body>

</html>