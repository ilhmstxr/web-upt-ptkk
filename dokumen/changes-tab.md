# Analisis Implementasi Desain Cluster Pelatihan

Berdasarkan pengecekan pada kode sumber, berikut adalah cara cluster `Pelatihan` mengimplementasikan desain tab dan header kustom seperti yang terlihat pada gambar.

## 1. Override Header pada Page Class

Pada file `app/Filament/Clusters/Pelatihan/Resources/PelatihanResource/Pages/ListPelatihans.php`, metode `getHeader()` di-override untuk merender view kustom sebagai header halaman.

```php
public function getHeader(): ?View
{
    return view('filament.clusters.pelatihan.components.resource-tabs', [
        'activeTab' => 'pelatihans'
    ]);
}
```

Ini menggantikan header standar Filament dengan komponen kustom kita sendiri.

## 2. Komponen View Kustom

View yang digunakan adalah `resources/views/filament/clusters/pelatihan/components/resource-tabs.blade.php`. View ini menangani dua hal utama:

### a. Navigasi Tab (Sub-menu)
Bagian atas view berisi navigasi tab yang menghubungkan antar resource dalam cluster (misalnya: Kompetensi dan Pelatihan).

```html
<nav class="-mb-px flex space-x-8">
    {{-- Link ke Resource Kompetensi --}}
    <a href="{{ ... }}" class="...">Kompetensi</a>
    
    {{-- Link ke Resource Pelatihan --}}
    <a href="{{ ... }}" class="...">Pelatihan</a>
</nav>
```

### b. Header Konten (Judul & Aksi)
Bagian bawah view menampilkan Judul, Deskripsi, dan Tombol Aksi (seperti "Buat Pelatihan Baru" dan "Export Data"). Bagian ini dirender secara kondisional berdasarkan `$activeTab`.

```html
@if($activeTab === 'pelatihans')
    <div class="...">
        {{-- Judul & Deskripsi --}}
        <h1>Program Pelatihan</h1>
        <p>Buat, kelola, dan pantau...</p>

        {{-- Tombol Aksi --}}
        <div class="...">
            <x-filament::button ...>Buat Pelatihan Baru</x-filament::button>
            <x-filament::button ...>Export Data</x-filament::button>
        </div>
    </div>
@endif
```

## Kesimpulan
Pendekatan yang digunakan adalah **Header Injection**. Alih-alih membungkus seluruh konten tabel dengan layout baru (seperti yang kita lakukan dengan `cluster-layout`), pendekatan ini hanya mengganti bagian atas halaman (header) dan membiarkan Filament menangani rendering tabel di bawahnya secara standar.
