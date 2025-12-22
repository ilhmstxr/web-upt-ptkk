# To-Do: Refactoring Alur Tes & Monev (Implementasi Efisiensi)

Dokumen ini berisi daftar modifikasi mendetail yang diperlukan untuk mengimplementasikan struktur database baru (Bank Soal & Bank Opsi) sesuai analisis di `alur-tes-2.md`.

## 1. Perubahan Struktur Database (Migrations)

Tujuan: Menormalisasi data pertanyaan dan opsi jawaban.

-   [ ] **Buat tabel `kelompok_opsi`**
    -   Kolom: `id`, `nama_kelompok` (misal: "Opsi Standar Likert").
-   [ ] **Modifikasi tabel `opsi_jawaban`**
    -   [ ] Tambah kolom `id_kelompok` (FK ke `kelompok_opsi`).
    -   [ ] Hapus kolom `pertanyaan_id` (Opsi tidak lagi terikat eksklusif ke satu pertanyaan).
    -   [ ] Hapus kolom `apakah_benar` (Kunci jawaban akan pindah ke tabel pertanyaan).
-   [ ] **Buat tabel `pertanyaan_tes` (Pivot)**
    -   Kolom: `id_tes`, `id_pertanyaan`, `no_urut`.
-   [ ] **Modifikasi tabel `pertanyaan`**
    -   [ ] Hapus kolom `tes_id` (Pertanyaan tidak lagi eksklusif satu tes).
    -   [ ] Tambah kolom `id_kelompok_opsi` (FK ke `kelompok_opsi`).
    -   [ ] Tambah kolom `id_opsi_benar` (FK ke `opsi_jawaban` - menyimpan kunci jawaban).

## 2. Migrasi Data (Backward Compatibility)

_Penting: Langkah ini harus dijalankan segera setelah migrasi struktur agar data existing tidak rusak._

-   [ ] **Script Migrasi Opsi Jawaban**:
    -   Loop semua **Pertanyaan** existing.
    -   Untuk setiap pertanyaan, buat 1 record `kelompok_opsi` baru (Nama: "Generated for Q-{id}").
    -   Update semua `opsi_jawaban` milik pertanyaan tersebut agar merujuk ke `kelompok_opsi` baru ini.
    -   Pindahkan data kunci jawaban (`apakah_benar`) ke kolom `id_opsi_benar` di tabel `pertanyaan`.
-   [ ] **Script Migrasi Relasi Tes**:
    -   Loop semua **Pertanyaan** existing yang punya `tes_id`.
    -   Insert record ke tabel pivot `pertanyaan_tes` (`id_tes` dari pertanyaan lama, `id_pertanyaan` = id saat ini).

## 3. Update Model (Eloquent)

-   [ ] **APP\Models\KelompokOpsi (New)**
    -   `hasMany(OpsiJawaban::class)`
-   [ ] **APP\Models\OpsiJawaban**
    -   `belongsTo(KelompokOpsi::class)`
    -   Hapus relasi `belongsTo(Pertanyaan)` (atau sesuaikan logic).
-   [ ] **APP\Models\Pertanyaan**
    -   `belongsToMany(Tes::class)` using `pertanyaan_tes`.
    -   `belongsTo(KelompokOpsi::class)`.
    -   `belongsTo(OpsiJawaban::class, 'id_opsi_benar')` (Kunci Jawaban).
-   [ ] **APP\Models\Tes**
    -   `belongsToMany(Pertanyaan::class)` using `pertanyaan_tes` withPivot `no_urut`.

## 4. Perbaikan Logika (Admin / Filament)

-   [ ] **PertanyaanResource (Bank Soal)**
    -   Buat Menu/Resource baru: **Bank Soal**.
    -   Form Create Pertanyaan:
        -   Tipe Pilihan Ganda: User harus memilih/membuat `kelompok_opsi`.
        -   Saat menambahkan opsi, sebenarnya menambahkan ke `kelompok_opsi`.
        -   _Opsi Simplifikasi_: Bisa tetap hidden user experience-nya, tapi di belakang layar membuat Kelompok Opsi unik per soal UNLESS user memilih "Gunakan Template Opsi".
-   [ ] **TesResource (Manajemen Tes)**
    -   Ubah tab/relation manager "Pertanyaan".
    -   Dari "Create New" menjadi kombinasi **"Attach from Bank Soal"** dan **"Create New (auto attach)"**.
    -   Pastikan fitur _ordering_ (nomor urut) bekerja di tabel pivot.
-   [ ] **Seeding Template Monev**
    -   Buat seeder `kelompok_opsi` bernama "Skala Likert (1-4)".
    -   Isi opsinya: "Sangat Tidak Puas", "Tidak Puas", "Puas", "Sangat Puas".
    -   Update logic Import Monev agar menggunakan kelompok opsi ini.

## 5. Perbaikan Logika (User / Controller)

-   [ ] **DashboardController / TesController**
    -   **Fetching Soal**: Ubah query dari `$tes->pertanyaan` (HasMany) menjadi `$tes->pertanyaan` (BelongsToMany).
    -   **Sorting**: Pastikan urutan soal berdasarkan `pertanyaan_tes.no_urut` (bukan ID pertanyaan).
-   [ ] **Logic Scoring (Hitung Nilai)**
    -   Update `Percobaan::hitungSkor()`:
    -   Cek kebenaran jawaban dengan membandingkan `jawaban_user.opsi_jawaban_id` == `pertanyaan.id_opsi_benar`.

## 6. Testing & Verifikasi

-   [ ] Verifikasi **Pre-Test / Post-Test**: Pastikan soal muncul dan scoring berjalan normal.
-   [ ] Verifikasi **Monev**: Pastikan opsi Likert muncul.
-   [ ] Verifikasi **Reuse**: Coba buat 2 tes berbeda, attach 1 soal yang sama, pastikan jalan di kedua tes.
