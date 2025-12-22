# RENCANA IMPLEMENTASI: Rombak Database & Impor Data Monev Lama

## 1. Gambaran Umum

Tujuannya adalah mengimpor data Monev lama ke struktur database baru secara bersih menggunakan Eloquent Models.

**Data Sumber**:

-   Pertanyaan & Opsi: `backupData/data-mentah-monev.sql` (Tabel Sementara: `data-mentah-monev`)
-   Jawaban User: `backupData/mentah-jawaban-user-monev.sql` (Tabel Sementara: `data-jawaban-user`)

**Target Model**:

-   `App\Models\Percobaan`
-   `App\Models\Pertanyaan`
-   `App\Models\OpsiJawaban`
-   `App\Models\JawabanUser`

## 2. Langkah Implementasi

### Fase 1: Persiapan (Import ke Tabel Sementara)

Langkah pertama adalah mengimpor file SQL mentah ke database sebagai tabel sementara. Ini memungkinkan kita untuk membaca data mentah tersebut menggunakan query SQL standar atau DB facade dari Laravel.

1.  **Struktur Data**: File mentah melakukan perintah `CREATE` dan `INSERT` ke tabel `data-mentah-monev` dan `data-jawaban-user`.
2.  **Aksi**: Menjalankan import SQL (via `mysql < file.sql` atau `DB::unprepared()`) untuk memuat file-file ini ke database.

### Fase 2: Command Migrasi (PHP/Laravel)

Kita akan membuat Artisan command baru `php artisan import:monev-legacy` untuk menangani logika pemindahan data. Menggunakan command ini menjamin integritas data (melalui Model) dan penanganan timestamp otomatis.

#### A. Logika untuk Pertanyaan (`Pertanyaan` & `OpsiJawaban`)

**Sumber Data**: `DB::table('data-mentah-monev')`

1.  **Iterasi (Loop)** baris data dimana kolom `tipe = 'pertanyaan'`.

    -   Ambil `pelatihan_id` dan `tes_id`.
    -   Loop kolom dari `pertanyaan 1` sampai `pertanyaan 37`.
    -   **Aksi**: Buat record baru menggunakan model `Pertanyaan`.
        -   `tes_id` = diambil dari `tes_id` sumber.
        -   `teks_pertanyaan` = nilai dari kolom pertanyaan tersebut.
        -   `nomor_urut` = index loop (1..37).
        -   `tipe` = Deteksi otomatis berdasarkan kata kunci teks (misal: jika ada kata "Saran" -> 'essay', selain itu 'pilihan_ganda').

2.  **Iterasi (Loop)** baris data dimana kolom `tipe = 'jawaban'`.
    -   Baris-baris ini merepresentasikan _Opsi Jawaban_ untuk pertanyaan yang sudah diproses di atas.
    -   **Aksi**: Untuk setiap index pertanyaan (1..37), cari `Pertanyaan` yang sesuai (yang dibuat di langkah 1).
    -   Buat record baru menggunakan model `OpsiJawaban`.
        -   `pertanyaan_id` = ID dari pertanyaan yang ditemukan.
        -   `teks_opsi` = nilai pada kolom `pertanyaan N` (logika sumber menggunakan nama kolom pertanyaan untuk menyimpan opsi).
        -   `nilai` = Tentukan bobot nilai (misal: "Sangat Memuaskan" = 5, "Kurang" = 2).

#### B. Logika untuk Jawaban User (`Percobaan` & `JawabanUser`)

**Sumber Data**: `DB::table('data-jawaban-user')`

1.  **Iterasi (Loop)** semua baris data user.
2.  **Record Induk**: Buat record baru menggunakan model `Percobaan`.

    -   `peserta_id` = `peserta_id` (dari sumber).
    -   `pelatihan_id` = `pelatihan_id`.
    -   `tes_id` = `tes_id`.
    -   `tipe` = 'Monev' (Hardcoded).
    -   `waktu_mulai`, `waktu_selesai` = `NOW()` (Waktu sekarang).

3.  **Record Anak**: Loop kolom header `jawaban_1` sampai `jawaban_37`.
    -   **Aksi**: Buat record baru menggunakan model `JawabanUser`.
        -   `percobaan_id` = ID dari Percobaan yang baru dibuat.
        -   `pertanyaan_id` = Cari Pertanyaan berdasarkan `tes_id` dan nomor urut `N`.
        -   `jawaban_value` = Isi teks jawaban dari kolom.
        -   **Normalisasi Data**:
            -   Coba cari `OpsiJawaban` yang cocok berdasarkan teks (menggunakan `LIKE %value%`).
            -   **Jika ketemu opsi**: set `opsi_jawaban_id` dan `nilai_jawaban` sesuai opsi tersebut.
            -   **Jika tidak ketemu (Essay/Lainnya)**: simpan isi jawaban ke kolom `jawaban_teks`.

### Fase 3: Eksekusi via `rombak-db.bat`

Update script batch `rombak-db.bat` untuk menjalankan urutan berikut:

1.  **Migrate Fresh**: `php artisan migrate:fresh --seed` (Reset database).
2.  **Import Temp Tables**: Import file SQL mentah ke tabel sementara.
3.  **Jalankan Command Import**: `php artisan import:monev-legacy`.
4.  **Cleanup**: Hapus tabel sementara (`DROP TABLE`).

## 3. Perubahan Kode yang Diperlukan

1.  **Buat Command Baru**: `app/Console/Commands/ImportMonevLegacy.php`.
2.  **Helper Mapping**: Menambahkan logika helper untuk memetakan jawaban teks (seperti "Sangat memuaskan") ke nilai numerik (5) untuk keperluan statistik.
