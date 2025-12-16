# Alur Pengerjaan Tes (Pre-Test / Post-Test / Survei)

Dokumen ini menjelaskan alur teknis pengerjaan tes dari sisi **DashboardController**, **View**, dan **Database**.

---

## 1. Memulai Tes (Start)

**Route**: `dashboard.pretest.start` (atau posttest/survei)
**Controller**: `DashboardController@pretestStart`

1.  **Validasi Tes**: Controller mengecek apakah tes tersedia untuk kompetensi/pelatihan peserta.
2.  **Cek Sesi**:
    *   Mencari record `Percobaan` (attempts) yang belum selesai (`waktu_selesai` IS NULL).
    *   Jika ada, lanjutkan sesi tersebut.
    *   Jika tidak ada, buat record `Percobaan` baru di database dengan `waktu_mulai` = `now()`.
3.  **Redirect**: Setelah sesi siap, redirect ke halaman pengerjaan soal (`pretestShow`).

---

## 2. Menampilkan Soal (Show)

**Route**: `dashboard.pretest.show`
**View**: `dashboard.pages.pre-test.pretest-start.blade.php`
**Controller**: `DashboardController@handleTesShow`

1.  **Ambil Pertanyaan**:
    *   Controller mengambil semua pertanyaan milik tes tersebut.
    *   Menggunakan `orderBy('nomor')` agar urutan soal konsisten (1, 2, 3...).
2.  **Navigasi Index (`?q=...`)**:
    *   Menentukan soal mana yang ditampilkan berdasarkan parameter query `q` (default: 0).
    *   `$pertanyaan = $pertanyaanList->get($currentQuestionIndex);`
3.  **Timer**:
    *   Menghitung sisa waktu berdasarkan `(durasi_menit * 60) - (waktu_sekarang - waktu_mulai)`.
    *   Jika waktu habis, otomatis tutup sesi (force submit).
4.  **Render View**: Menampilkan view `pretest-start.blade.php` dengan data pertanyaan tunggal.

---

## 3. Menjawab & Navigasi (Submit)

**Form Action**: `dashboard.pretest.submit`
**Controller**: `DashboardController@pretestSubmit` -> `processTesSubmit`

Setiap kali user mengklik tombol nomor soal atau "Selanjutnya", form akan disubmit.

1.  **Simpan Jawaban**:
    *   Data jawaban (`jawaban` untuk Pilihan Ganda, `teks` untuk Essay, `nilai` untuk Likert) disimpan ke tabel `jawaban_user`.
    *   Menggunakan `updateOrCreate` agar jawaban bisa diubah-ubah selama sesi aktif.
2.  **Cek Navigasi (`next_q`)**:
    *   Input hidden `next_q` menentukan tujuan selanjutnya.
    *   **Jika `next_q` ada** (User klik tombol navigasi): Redirect kembali ke `pretestShow` dengan `?q=next_q`.
    *   **Jika `next_q` tidak ada/selesai** (User klik "Selesai" di soal terakhir): Lanjut ke proses finalisasi.

---

## 4. Finalisasi & Penilaian (Finish)

**Controller**: `processTesSubmit` (Lanjutan)

Jika user menekan tombol Selesai di soal terakhir:

1.  **Set Waktu Selesai**: Update `percobaan->waktu_selesai = now()`.
2.  **Hitung Skor**:
    *   Membandingkan `jawaban_user` dengan `opsi_jawaban.apakah_benar`.
    *   Skor = `(Jumlah Benar / Total Soal) * 100`.
    *   *Catatan: Skor Essay/Likert mungkin dihitung berbeda tergantung logika tipe soal.*
3.  **Cek Kelulusan**: Bandingkan skor dengan `passing_score` tes.
4.  **Sinkronisasi (`syncScoreToPendaftaran`)**:
    *   Salin nilai akhir dari tabel `percobaan` ke tabel `pendaftaran_pelatihan` (kolom `nilai_pre_test`, `nilai_post_test`, dll) agar muncul di rekap admin.
5.  **Redirect Result**: Arahkan user ke halaman hasil.

---

## 5. Halaman Hasil (Result)

**Route**: `dashboard.pretest.result`
**View**: `dashboard.pages.pre-test.pretest-result.blade.php`


---

## 6. Peta File & Route (File Map)

Berikut adalah daftar file yang terlibat untuk setiap jenis tes.

### A. Pre-Test
| Komponen | File / Route |
| :--- | :--- |
| **Route Start** | `dashboard.pretest.start` |
| **Route Show** | `dashboard.pretest.show` |
| **Route Submit** | `dashboard.pretest.submit` |
| **Route Result** | `dashboard.pretest.result` |
| **Controller** | `app/Http/Controllers/DashboardController.php` (Methods: `pretestStart`, `pretestShow`, `pretestSubmit`, `pretestResult`) |
| **View Start** | `resources/views/dashboard/pages/pre-test/pretest-start.blade.php` |
| **View Result** | `resources/views/dashboard/pages/pre-test/pretest-result.blade.php` |

### B. Post-Test
| Komponen | File / Route |
| :--- | :--- |
| **Route Start** | `dashboard.posttest.start` |
| **Route Show** | `dashboard.posttest.show` |
| **Route Submit** | `dashboard.posttest.submit` |
| **Route Result** | `dashboard.posttest.result` |
| **Controller** | `DashboardController.php` (Methods: `posttestStart`, `posttestShow`, `posttestSubmit`, `posttestResult`) |
| **View Start** | `resources/views/dashboard/pages/post-test/posttest-start.blade.php` |
| **View Result** | `resources/views/dashboard/pages/post-test/posttest-result.blade.php` |

### C. Monev (Survei)
| Komponen | File / Route |
| :--- | :--- |
| **Route Start** | `dashboard.monev.start` |
| **Route Show** | `dashboard.monev.show` |
| **Route Submit** | `dashboard.monev.submit` |
| **Route Result** | `dashboard.monev.result` |
| **Controller** | `DashboardController.php` (Methods: `monevStart`, `monevShow`, `monevSubmit`, `monevResult`) |
| **View Start** | `resources/views/dashboard/pages/monev/monev-start.blade.php` |
| **View Result** | `resources/views/dashboard/pages/monev/monev-result.blade.php` |

### D. Model (Database)
| Model | Tabel | Kegunaan |
| :--- | :--- | :--- |
| `Tes` | `tes` | Menyimpan data tes, durasi, dan passing score. |
| `Pertanyaan` | `pertanyaan` | Menyimpan soal-soal linked ke `tes_id`. |
| `OpsiJawaban` | `opsi_jawaban` | Menyimpan pilihan jawaban (linked ke `pertanyaan_id`). |
| `Percobaan` | `percobaan` | **Sesi Tes User**. Menyimpan `waktu_mulai`, `waktu_selesai`, `skor`, `lulus`. |
| `JawabanUser` | `jawaban_user` | Menyimpan jawaban detail per soal. |
| `PendaftaranPelatihan` | `pendaftaran_pelatihan` | Menyimpan **Nilai Akhir** (`nilai_pre_test`, `nilai_post_test`) untuk rekap admin. |