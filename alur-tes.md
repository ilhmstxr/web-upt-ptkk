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


abd41d0f - nambahin biar tidak ada <p></p> tampil di tes nambahim lagi yg monev (Sat Dec 13 2025)
f8ed4d13 - error dikit di monev (Wed Dec 10 2025)
bf3bfd38 - save sementara (Tue Dec 9 2025)
b7064de7 - save (Wed Sep 24 2025)
f5c56262 - bisa bisa (Wed Sep 24 2025)
f6d28b03 - benerin dikit (Wed Sep 24 2025)
d643bca2 - nilai udah dan gambar udah bisa di edit di opsi dan soal (Mon Sep 22 2025)
b84551a5 - work, sisa penilaian & gambar (Mon Sep 22 2025)
b3b22b48 - postest pretest done bagian user done (Thu Sep 11 2025)
081cb78a - pendaftaran done (Tue Sep 9 2025)
81446ed5 - kurang nyimpen skor di database jawaban user (Thu Aug 28 2025)
191d4f1e - masih kurang (Thu Aug 28 2025)
0b680aa3 - ini (Thu Aug 28 2025)
a76cbdb0 - udah postest pretest (Thu Aug 28 2025)

dc1c16f2 (Wed Dec 17 2025) - Merge branch 'haura-coba-merge' of https://github.com/ilhmstxr/web-upt-ptkk into haura-coba-merge
90fbcc94 (Wed Dec 17 2025) - debug soal
17c00026 (Wed Dec 17 2025) - sisa pertanyaannya belum masuk
23cb1fa3 (Mon Dec 15 2025) - ini bisa
ddf009bc (Mon Dec 15 2025) - hapus dummy
a470j4d05 (Sun Dec 14 2025) - Refactor Filament Clusters Evaluasi Resources TesResource and DashboardController
a1726321 (Sun Dec 14 2025) - Fix dashboard controller (essay + monev)
6b5de384 (Sun Dec 14 2025) - refactor: update kompetensi pelatihan schema and fix data injection
4eb50c61 (Sat Dec 13 2025) - nyoba anti survey ke survei
6e9e37f6 (Thu Dec 11 2025) - profile di assessment done
9902ca16 (Thu Dec 11 2025) - pretest-posttest, monev donee
b0b4d0e1 (Thu Dec 11 2025) - nyantol di pretest kompetensi ga sama
d099f700 (Wed Dec 10 2025) - kompetensi perets postest masi ga sinkron
f8ed4d13 (Wed Dec 10 2025) - error dikit di monev
7673bd9d (Tue Dec 9 2025) - sorry yah aku nyerah nanti bar maem tak lanjut huhu
bf3bfd38 (Tue Dec 9 2025) - save sementara
32f6334a (Tue Dec 9 2025) - dashboard assesment tersambung kurang cek per pages dan cek lagi asrama
66a4fe51 (Mon Dec 8 2025) - masi ada yang belmum tambahin monev 3 file
adb9d440 (Mon Dec 8 2025) - kurang cek per halaman dashboard
5e85783b (Mon Dec 8 2025) - belum fix
10734056 (Mon Dec 8 2025) - belum testing
8224cdec (Mon Dec 8 2025) - save
807ece1b (Sun Dec 7 2025) - benerin asrama + benerin tes
151f2eb2 (Sun Dec 7 2025) - ngerubah bidang menjadi kompetensi & perubahan ada di dalam TODO-strux
8653499f (Mon Oct 6 2025) - mau nambahin kaya pages tapi gajadi
3c7dde2b (Sun Oct 5 2025) - save bentar
c8651218 (Tue Sep 30 2025) - otw download chart zip
098b9990 (Thu Sep 25 2025) - instansi udah otomatis dan udah kugabnti peserta id
4e902a54 (Thu Sep 25 2025) - benerin dashboard
04bb70e3 (Wed Sep 24 2025) - benerin store surveycontroller
35362b1c (Wed Sep 24 2025) - mod: benerin file gambar (manual)
d643bca2 (Mon Sep 22 2025) - nilai udah dan gambar udah bisa di edit di opsi dan soal
b84551a5 (Mon Sep 22 2025) - work, sisa penilaian & gambar
ca39aa3e (Mon Sep 22 2025) - db done
5ee2bc5b (Sun Sep 21 2025) - mod: mau fixing merging blm fix
178820c9 (Fri Sep 19 2025) - mod: otw rombak pretest
38015824 (Fri Sep 19 2025) - masi error bagian id nama sama nilai
eec443f2 (Thu Sep 18 2025) - semoga ini selesai
83f52e7a (Thu Sep 18 2025) - ini tesnya bisa tapi login dll masi belum
b3b22b48 (Thu Sep 11 2025) - postest pretest done bagian user done
f0c02fd7 (Thu Sep 11 2025) - save sementara
081cb78a (Tue Sep 9 2025) - pendaftaran done
ad932b8c (Thu Sep 4 2025) - save sementara after merge database dan benerin dikit postest
c85f2b32 (Fri Aug 29 2025) - final
81446ed5 (Thu Aug 28 2025) - kurang nyimpen skor di database jawaban user
191d4f1e (Thu Aug 28 2025) - masih kurang
0b680aa3 (Thu Aug 28 2025) - ini
4d5c42e0 (Mon Aug 25 2025) - nambah dashboard + benerin export biodata
