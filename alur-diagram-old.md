# Alur Implementasi Diagram Report Survey

Dokumen ini menjelaskan komponen, data, alur, dan struktur database yang digunakan untuk membuat **Report Survey** (Pie Chart Akumulatif, Stacked Bar Kategori, dan Pie Chart Per Pertanyaan).

## 1. Kebutuhan Komponen

Sistem menggunakan teknologi berikut:

-   **Filament PHP**: Admin panel.
-   **Chart.js**: Library visualisasi.
-   **Trait PHP (`BuildsLikertData`)**: Logic reusable pengolah data Likert.

## 2. File-File Terkait (Klik untuk membuka)

### Controller & Page


-   [ReportJawabanSurvei.php](html/php/ReportJawabanSurvei.php)
    -   _Fungsi_: Page controller, menerima `pelatihanId`.

### Widgets (Charts)

-   [JawabanAkumulatifChart.php](html\php\JawabanAkumulatifChart.php) (Pie Chart Total)
-   [JawabanPerKategoriChart.php](html\php\JawabanPerKategoriChart.php) (Stacked Bar Kategori)
-   [JawabanPerPertanyaanChart.php](html\php\JawabanPerPertanyaanChart.php) (Pie Chart per Soal)

### Logic & Trait


-   [BuildsLikertData.php](html\php\BuildsLikertData.php)
    -   _Core Logic_: Query DB, Normalisasi Data, Mapping Skala (1-4).

### Helper & View

-   [report-page.blade.php](html\php\report-page.blade.php)

## 3. Alur Logika (Flowchart)

Diagram alur bagaimana data diproses dari database hingga menjadi chart.

```mermaid
flowchart TD
    User([User]) -->|Buka Halaman Report| Page[ReportJawabanSurvei Page]
    Page -->|Load Widgets| Widgets

    subgraph Widgets [Chart Widgets]
        AccChart[Akumulatif Chart]
        CatChart[Kategori Chart]
        QChart[Per Pertanyaan Chart]
    end

    subgraph Logic [BuildsLikertData Trait]
        Step1[Collect Pertanyaan IDs]
        Step2[Build Likert Maps]
        Step3[Normalize Answers]
    end

    subgraph Database
        T_Jawaban[Tabel Jawaban User]
        T_Opsi[Tabel Opsi Jawaban]
    end

    Widgets -->|Call Trait| Step1
    Step1 -->|Query| T_Jawaban
    Step2 -->|Query| T_Opsi
    Step3 -->|Fetch Data| T_Jawaban

    Step1 --> Step2 --> Step3
    Step3 -->|Return Normalized Data| Widgets

    Widgets -->|Render Chart.js| Helper[Tampilan Browser]
```

## 4. Struktur Database (ERD)

Relasi antar tabel yang mendukung fitur survei ini.

```mermaid
erDiagram
    PELATIHAN ||--|{ TES : has
    TES ||--|{ PERTANYAAN : contains
    TES ||--|{ PERCOBAAN : has_sessions

    PERTANYAAN ||--|{ OPSI_JAWABAN : has_options
    PERTANYAAN ||--|{ JAWABAN_USER : receives

    PERCOBAAN ||--|{ JAWABAN_USER : contains
    OPSI_JAWABAN ||--|{ JAWABAN_USER : selected_in

    PELATIHAN {
        int id PK
        string nama
    }

    TES {
        int id PK
        int pelatihan_id FK
        enum tipe "survei/pre/post"
    }

    PERTANYAAN {
        int id PK
        int tes_id FK
        enum tipe_jawaban "skala_likert/teks"
        string teks
    }

    OPSI_JAWABAN {
        int id PK
        int pertanyaan_id FK
        string teks_opsi
        int nilai "Implisit urutan 1-4"
    }

    JAWABAN_USER {
        int id PK
        int percobaan_id FK
        int pertanyaan_id FK
        int opsi_jawaban_id FK
        string jawaban_teks
    }
```

## 5. Metadata Data

-   **Input**: `pelatihanId` (URL Parameter).
-   **Filter Data**: Hanya pertanyaan dengan `topik_jawaban = 'skala_likert'` (untuk survei).
-   **Skala**:
    1.  Tidak Memuaskan
    2.  Kurang Memuaskan
    3.  Cukup Memuaskan
    4.  Sangat Memuaskan
