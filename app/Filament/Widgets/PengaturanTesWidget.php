<?php

// Lokasi: app/Filament/Widgets/Custom/PengaturanTesWidget.php

namespace App\Filament\Widgets;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use App\Models\Pelatihan;
use App\Models\Tes;
use Filament\Widgets\Widget;

class PengaturanTesWidget extends Widget
{
    protected static ?int $sort = 2;

    // Tentukan file view blade-nya
    protected static string $view = 'filament.widgets.pengaturan-tes-widget';

    public ?Pelatihan $pelatihan = null;

    // Properti untuk menyimpan data yang akan dikirim ke view
    public $preTestData = [];
    public $postTestData = [];
    public $surveiData = [];

    // Agar widget ini mengambil lebar penuh
    protected int | string | array $columnSpan = 'full';

    public function mount(?Pelatihan $pelatihan = null): void
    {
        $this->pelatihan = $pelatihan;

        $this->preTestData = $this->buildTesData('pre-test', 'Atur Pertanyaan', 'Kelola Soal');
        $this->postTestData = $this->buildTesData('post-test', 'Atur Pertanyaan', 'Kelola Soal');
        $this->surveiData = $this->buildSurveiData('Atur Pertanyaan', 'Kelola Pertanyaan');
    }

    private function buildTesData(string $tipe, string $labelEmpty, string $labelExisting): array
    {
        $tes = $this->findTes($tipe);

        if (! $this->pelatihan) {
            return [
                'status' => 'BELUM',
                'soal' => 0,
                'url' => '#',
                'label' => $labelEmpty,
            ];
        }

        if (! $tes) {
            return [
                'status' => 'BELUM',
                'soal' => 0,
                'url' => TesResource::getUrl('create', [
                    'pelatihan_id' => $this->pelatihan->id,
                    'tipe' => $tipe,
                ]),
                'label' => $labelEmpty,
            ];
        }

        return [
            'status' => $this->resolveStatus($tes),
            'soal' => (int) ($tes->pertanyaan_count ?? 0),
            'url' => TesResource::getUrl('edit', ['record' => $tes->id]),
            'label' => $labelExisting,
        ];
    }

    private function buildSurveiData(string $labelEmpty, string $labelExisting): array
    {
        $tes = $this->findTes('survei');

        if (! $this->pelatihan) {
            return [
                'status' => 'BELUM',
                'pertanyaan' => 0,
                'url' => '#',
                'label' => $labelEmpty,
            ];
        }

        if (! $tes) {
            return [
                'status' => 'BELUM',
                'pertanyaan' => 0,
                'url' => TesResource::getUrl('create', [
                    'pelatihan_id' => $this->pelatihan->id,
                    'tipe' => 'survei',
                ]),
                'label' => $labelEmpty,
            ];
        }

        return [
            'status' => $this->resolveStatus($tes),
            'pertanyaan' => (int) ($tes->pertanyaan_count ?? 0),
            'url' => TesResource::getUrl('edit', ['record' => $tes->id]),
            'label' => $labelExisting,
        ];
    }

    private function findTes(string $tipe): ?Tes
    {
        if (! $this->pelatihan) {
            return null;
        }

        $query = Tes::query()
            ->withCount('pertanyaan')
            ->where('pelatihan_id', $this->pelatihan->id);

        if ($tipe === 'survei') {
            $query->whereIn('tipe', ['survei', 'survey']);
        } else {
            $query->where('tipe', $tipe);
        }

        return $query->orderByDesc('id')->first();
    }

    private function resolveStatus(Tes $tes): string
    {
        $now = now();
        $start = $tes->tanggal_mulai;
        $end = $tes->tanggal_selesai;

        if ($start && $now->lt($start)) {
            return 'BELUM';
        }

        if ($end && $now->gt($end)) {
            return 'DITUTUP';
        }

        return 'DIBUKA';
    }
}
