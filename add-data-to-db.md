# Required Database Columns for Data Entry

Berikut adalah daftar kolom yang dibutuhkan untuk mengisi data pada tabel-tabel terkait, berdasarkan struktur migrasi dan model.

## 1. User (`users`)

**Tabel:** `users`
**Model:** `App\Models\User`

| Kolom               | Tipe Data | Wajib (Required) | Keterangan           |
| :------------------ | :-------- | :--------------: | :------------------- |
| `name`              | String    |        ✅        | Nama lengkap user.   |
| `email`             | String    |        ✅        | Email unik.          |
| `password`          | String    |        ✅        | Password (hashed).   |
| `email_verified_at` | Timestamp |        -         | Opsional (nullable). |

---

## 2. Peserta (`peserta`)

**Tabel:** `peserta`
**Model:** `App\Models\Peserta`

| Kolom           | Tipe Data   | Wajib (Required) | Keterangan                    |
| :-------------- | :---------- | :--------------: | :---------------------------- |
| `instansi_id`   | Foreign ID  |        ✅        | Relasi ke `instansi`.         |
| `user_id`       | Foreign ID  |        ✅        | Relasi ke `users`.            |
| `nama`          | String(150) |        ✅        | Nama lengkap peserta.         |
| `nik`           | String(20)  |        ✅        | NIK unik.                     |
| `tempat_lahir`  | String(100) |        ✅        |                               |
| `tanggal_lahir` | Date        |        ✅        |                               |
| `jenis_kelamin` | Enum        |        ✅        | 'Laki-laki' atau 'Perempuan'. |
| `agama`         | String(50)  |        ✅        |                               |
| `alamat`        | Text        |        ✅        |                               |
| `no_hp`         | String(20)  |        ✅        |                               |

---

## 3. Pendaftaran Pelatihan (`pendaftaran_pelatihan`)

**Tabel:** `pendaftaran_pelatihan`
**Model:** `App\Models\PendaftaranPelatihan`

| Kolom                     | Tipe Data  | Wajib (Required) | Keterangan              |
| :------------------------ | :--------- | :--------------: | :---------------------- |
| `peserta_id`              | Foreign ID |        ✅        | Relasi ke `peserta`.    |
| `pelatihan_id`            | Foreign ID |        ✅        | Relasi ke `pelatihan`.  |
| `nomor_registrasi`        | String     |        ✅        | Unik.                   |
| `tanggal_pendaftaran`     | Timestamp  |        ✅        |                         |
| `kompetensi_pelatihan_id` | Foreign ID |        -         | Opsional (Nullable).    |
| `kelas`                   | String     |        -         | Opsional (Nullable).    |
| `nilai_pre_test`          | Integer    |        -         | Default: 0.             |
| `nilai_post_test`         | Integer    |        -         | Default: 0.             |
| `nilai_praktek`           | Integer    |        -         | Default: 0.             |
| `rata_rata`               | Integer    |        -         | Default: 0.             |
| `nilai_survey`            | Integer    |        -         | Default: 0.             |
| `status`                  | Enum       |        -         | Default: 'Belum Lulus'. |
| `status_pendaftaran`      | Enum       |        -         | Default: 'Pending'.     |

---

## 4. Tes (`tes`)

**Tabel:** `tes`
**Model:** `App\Models\Tes`

| Kolom           | Tipe Data  | Wajib (Required) | Keterangan                         |
| :-------------- | :--------- | :--------------: | :--------------------------------- |
| `judul`         | String     |        ✅        | Judul tes.                         |
| `tipe`          | Enum       |        ✅        | 'post-test', 'pre-test', 'survei'. |
| `pelatihan_id`  | Foreign ID |        ✅        | Relasi ke `pelatihan`.             |
| `deskripsi`     | Text       |        -         | Opsional (Nullable).               |
| `kompetensi_id` | Foreign ID |        -         | Opsional (Nullable).               |
| `durasi_menit`  | Integer    |        -         | Opsional (Nullable).               |

---

## 5. Percobaan (`percobaan`)

**Tabel:** `percobaan`
**Model:** `App\Models\Percobaan`

| Kolom              | Tipe Data    | Wajib (Required) | Keterangan                                    |
| :----------------- | :----------- | :--------------: | :-------------------------------------------- |
| `tes_id`           | Foreign ID   |        ✅        | Relasi ke `tes`.                              |
| `waktu_mulai`      | Timestamp    |        ✅        |                                               |
| `peserta_id`       | Foreign ID   |        -         | Opsional (Nullable).                          |
| `pesertaSurvei_id` | Foreign ID   |        -         | Opsional (Nullable).                          |
| `pelatihan_id`     | Foreign ID   |        -         | Opsional (Nullable).                          |
| `tipe`             | Enum         |        -         | 'survey', 'pre-test', 'post-test' (Nullable). |
| `waktu_selesai`    | Timestamp    |        -         | Opsional (Nullable).                          |
| `skor`             | Decimal(5,2) |        -         | Opsional (Nullable).                          |
| `lulus`            | Boolean      |        -         | Default: false.                               |
| `pesan_kesan`      | Text         |        -         | Opsional (Nullable).                          |

<br>

# Analisis Data dari Gambar/Excel

Berdasarkan data yang terlihat pada gambar spreadsheet yang Anda unggah, berikut adalah analisis mengenai **data yang tersedia** dan **data yang kurang (Wajib Diisi tapi Tidak Ada)** untuk proses impor ke database.

## 1. Tabel: `users`

Kita membutuhkan user untuk setiap peserta.

-   ✅ **Available**: `nama` (Nama Lengkap).
-   ❌ **MISSING (CRITICAL)**: `email`. Pada gambar, kolom `email` tertulis `null`. Email wajib ada dan unik untuk login.
-   ❌ **MISSING**: `password`. Bisa di-generate default (misal: `password123`).

## 2. Tabel: `peserta`

-   ✅ **Available**: `nama`, `instansi` (Perlu dicocokkan dengan tabel `instansi` berdasarkan nama).
-   ❌ **MISSING (CRITICAL)**:
    -   `nik` (Nomor Induk Kependudukan) - **Wajib & Unik**.
    -   `tempat_lahir` - Wajib.
    -   `tanggal_lahir` - Wajib.
    -   `jenis_kelamin` - Wajib.
    -   `agama` - Wajib.
    -   `alamat` - Wajib.
    -   `no_hp` - Wajib.

> **Catatan:** Data peserta sangat tidak lengkap. Hanya ada Nama dan Instansi. Tidak bisa membuat data peserta yang valid tanpa NIK dan biodata lainnya kecuali validasi database dimatikan atau diisi data dummy.

## 3. Tabel: `pendaftaran_pelatihan`

-   ✅ **Available**:
    -   `nilai_pre_test` / `nilai_post_test`: Tersedia di kolom `skor` (format `75 / 100`, perlu di-parsing).
    -   `pelatihan_id`: Bisa dipetakan dari kolom `bidang_pelatihan` (misal: "posttest aksel guru animasi").
-   ❌ **MISSING**:
    -   `nomor_registrasi` - Wajib & Unik. Bisa di-generate otomatis.
    -   `tanggal_pendaftaran` - Bisa pakai tanggal hari ini.

## 4. Tabel: `tes`

-   ✅ **Available**:
    -   `id`: Kolom `tes_id` tersedia (misal: `58`, `59`, `18`).
    -   `tipe`: Tersedia di kolom pertama (misal: `posttest`, `pretest`, `monev`).
-   ❌ **MISSING**:
    -   `judul`: Tidak ada kolom judul tes spesifik, tapi bisa pakai nama bidang/tipe.
    -   `pelatihan_id`: Perlu dipetakan manual atau dari `bidang_pelatihan`.

## 5. Tabel: `percobaan`

-   ✅ **Available**:
    -   `tes_id`: Tersedia.
    -   `skor`: Tersedia.
-   ❌ **MISSING**:
    -   `waktu_mulai`: Wajib. Bisa diisi default waktu sekarang.

## Kesimpulan & Rekomendasi

Data dari Excel/Gambar tersebut **TIDAK CUKUP** untuk mengisi tabel `users` dan `peserta` secara valid karena banyaknya field mandatory yang kosong (Email, NIK, TTL, Alamat, dll).

**Opsi Solusi:**

1.  **Lengkapi Data**: Minta data lengkap (NIK, Email, Biodata) dari sumber lain.
2.  **Mode "Not Real"**: Jika hanya untuk testing/rekap nilai dan tidak butuh profil peserta valid:
    -   Generate `email` dummy (misal: `nama_tanpa_spasi@dummy.com`).
    -   Generate `nik` dummy random.
    -   Isi biodata wajib lain dengan strip (`-`) atau data default.
