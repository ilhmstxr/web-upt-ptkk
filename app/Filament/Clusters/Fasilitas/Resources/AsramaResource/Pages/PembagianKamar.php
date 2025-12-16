<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use Filament\Resources\Pages\Page;
use Filament\Support\Facades\Filament;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\Peserta;

class PembagianKamar extends Page
{
    // Harus tipe string (sama seperti parent class di Filament v3)
    protected static string $resource = AsramaResource::class;

    // Pastikan file blade ini ada (lihat file contoh di bawah)
    protected static string $view = 'filament.clusters.fasilitas.pages.pembagian-kamar';

    protected static ?string $title = 'Pembagian & Alokasi Kamar';
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?int $navigationSort = 3;

    // State sederhana
    public Collection $asramas;
    public string $pesertaSearch = '';

    // mount() dipanggil ketika page di-load
    public function mount(): void
    {
        $this->loadAsramas();

        if ($this->asramas->isEmpty()) {
            Filament::notify('warning', 'Tidak ada data Asrama. Silakan input Asrama & Kamar di Menu Fasilitas.', true);
        }
    }

    protected function loadAsramas(): void
    {
        // Muat asrama -> kamar -> hitung penghuni aktif (penempatan lewat peserta.kamar_id)
        $this->asramas = Asrama::with(['kamars' => function ($q) {
            $q->withCount(['pesertas as current_occupancy' => function ($q2) {
                $q2->whereNotNull('kamar_id'); // counting peserta yang telah ditempatkan (kamar_id)
            }])->with(['pesertas' => function ($q3) {
                $q3->whereNotNull('kamar_id');
            }]);
        }])->get();
    }

    /**
     * Ambil peserta yang belum dialokasikan (kamar_id = null), dengan dukungan pencarian nama.
     */
    public function getUnallocatedPesertasProperty(): Collection
    {
        return Peserta::query()
            ->whereNull('kamar_id')
            ->when($this->pesertaSearch, fn($q) => $q->where('nama', 'like', '%' . $this->pesertaSearch . '%'))
            ->select('id', 'nama', 'jenis_kelamin')
            ->orderBy('id')
            ->get();
    }

    /**
     * Alokasikan peserta ke kamar (dipanggil via request AJAX / action).
     * Pastikan frontend memanggil method Livewire ini dengan parameter pesertaId & kamarId.
     */
    public function allocatePeserta(int $pesertaId, int $kamarId): void
    {
        $peserta = Peserta::find($pesertaId);
        $kamar = Kamar::with('asrama')->withCount('pesertas')->find($kamarId);

        if (!$peserta || !$kamar) {
            Filament::notify('danger', 'Data peserta atau kamar tidak ditemukan.', true);
            return;
        }

        // Validasi kapasitas: gunakan kolom total_beds atau kapasitas
        $capacity = $kamar->total_beds ?? $kamar->kapasitas ?? null;
        if ($capacity === null) {
            Filament::notify('danger', 'Kapasitas kamar belum diset. Periksa konfigurasi kamar.', true);
            return;
        }

        if ($kamar->pesertas_count >= (int) $capacity) {
            Filament::notify('danger', 'Kamar sudah penuh.', true);
            return;
        }

        // Validasi gender: jika asrama bukan Campur, harus sama gender
        if ($kamar->asrama && $kamar->asrama->gender !== 'Campur' && $kamar->asrama->gender !== $peserta->jenis_kelamin) {
            Filament::notify('danger', 'Kamar ini khusus untuk gender: ' . $kamar->asrama->gender, true);
            return;
        }

        try {
            DB::transaction(function () use ($peserta, $kamarId) {
                $peserta->kamar_id = $kamarId;
                $peserta->save();
            });

            // refresh state
            $this->loadAsramas();

            Filament::notify('success', $peserta->nama . ' berhasil dialokasikan.', true);
        } catch (\Throwable $e) {
            Filament::notify('danger', 'Gagal menyimpan alokasi: ' . $e->getMessage(), true);
        }
    }

    /**
     * Batalkan alokasi (unassign)
     */
    public function unallocatePeserta(int $pesertaId): void
    {
        $peserta = Peserta::find($pesertaId);
        if (!$peserta) {
            Filament::notify('danger', 'Peserta tidak ditemukan.', true);
            return;
        }

        try {
            DB::transaction(function () use ($peserta) {
                $peserta->kamar_id = null;
                $peserta->save();
            });

            $this->loadAsramas();
            Filament::notify('success', $peserta->nama . ' berhasil dikeluarkan dari kamar.', true);
        } catch (\Throwable $e) {
            Filament::notify('danger', 'Gagal membatalkan alokasi: ' . $e->getMessage(), true);
        }
    }

    /**
     * Reset semua alokasi — berguna untuk testing (opsional)
     */
    public function resetAllAllocations(): void
    {
        try {
            DB::transaction(function () {
                Peserta::whereNotNull('kamar_id')->update(['kamar_id' => null]);
            });
            $this->loadAsramas();
            Filament::notify('success', 'Semua alokasi berhasil direset.', true);
        } catch (\Throwable $e) {
            Filament::notify('danger', 'Gagal mereset: ' . $e->getMessage(), true);
        }
    }
}
