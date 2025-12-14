# Log Implementasi

Dokumen ini mencatat perubahan teknis dan perbaikan bug yang telah diimplementasikan dalam proyek.

## 13 Desember 2025

### Perbaikan Widget Dashboard

#### 1. Perbaikan Referensi View pada `PengaturanTesWidget`

-   Masalah: Path view mengarah ke `filament.widgets.custom.pengaturan-tes-widget` yang tidak ditemukan.
-   File: `app/Filament/Widgets/PengaturanTesWidget.php`
-   Perbaikan: Mengubah property `$view` menjadi `filament.widgets.pengaturan-tes-widget` agar sesuai dengan lokasi file sebenarnya di direktori `resources/views`.

#### 2. Menghapus Duplikat `InformasiAsramaWidget`

-   Masalah: Terdapat dua class dengan nama yang sama. Satu di folder root `Widgets` (kosong) dan satu lagi di `Widgets/Dashboard` (implementasi benar). File yang kosong menimpa file yang benar sehingga menyebabkan error variabel tidak ditemukan (`$male`, `$female`, dll).
-   File: `app/Filament/Widgets/InformasiAsramaWidget.php`

#### 3. Perbaikan Relasi & Method Model

-   Masalah: `Call to undefined method` pada `OpsiJawaban::jawabanUsers()` dan `PenempatanAsrama::penghuniAktif()`.
-   File: `app/Models/OpsiJawaban.php`, `app/Models/PenempatanAsrama.php`
-   Perbaikan:
    -   Mengubah nama method relasi `jawabanUser` menjadi `jawabanUsers`.
    -   Menambahkan method `scopePenghuniAktif` pada model `PenempatanAsrama`.

#### 4. Penanganan Data Kosong pada Widget

-   Masalah: Error saat data kosong (misal tidak ada pelatihan aktif).
-   File: `app/Filament/Widgets/Dashboard/InformasiAsramaWidget.php`, `app/Filament/Widgets/Dashboard/AkumulasiSurveiChart.php`

#### 5. Perubahan Widget: Akumulasi -> Kepuasan Peserta

-   Masalah: Layout dashboard membutuhkan chart "Kepuasan Peserta".
-   File: `app/Filament/Widgets/Dashboard/AkumulasiSurveiChart.php` (Renamed to `KepuasanPesertaChart.php`)
-   Perbaikan:
    -   Rename file dan class dari `AkumulasiSurveiChart` menjadi `KepuasanPesertaChart`.
    -   Ubah heading widget menjadi "Kepuasan Peserta".

#### 6. Menghapus Widget Kepuasan Peserta

-   Masalah: Permintaan untuk menghapus widget Kepuasan Peserta dan chart-nya.
-   File: `app/Filament/Widgets/Dashboard/KepuasanPesertaChart.php`
-   Perbaikan: Menghapus file `app/Filament/Widgets/Dashboard/KepuasanPesertaChart.php` dari sistem.

#### 7. Menghapus Widget SurveyChart

-   Masalah: Permintaan untuk menghapus widget SurveyChart tambahan.
-   File: `app/Filament/Widgets/Dashboard/SurveyChart.php`
-   Perbaikan: Menghapus file `app/Filament/Widgets/Dashboard/SurveyChart.php`.
    -   _Note:_ Jika muncul error `ComponentNotFoundException` setelah penghapusan, itu karena browser masih menyimpan state widget lama. **Refresh halaman** akan memperbaiki masalah ini.
