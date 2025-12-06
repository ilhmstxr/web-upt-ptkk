<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\KompetensiPelatihan;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ViewKompetensiPelatihan extends Page
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-kompetensi-pelatihan';

    public function addParticipantAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('addParticipant')
            ->label('Tambah Peserta')
            ->icon('heroicon-o-user-plus')
            ->color('success')
            ->form([
                \Filament\Forms\Components\Select::make('peserta_id')
                    ->label('Pilih Peserta')
                    ->options(\App\Models\Peserta::query()->pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
            ])
            ->action(function (array $data) {
                // Check if already registered
                $exists = \App\Models\PendaftaranPelatihan::where('peserta_id', $data['peserta_id'])
                    ->where('kompetensi_pelatihan_id', $this->kompetensiPelatihan->id)
                    ->exists();

                if ($exists) {
                    \Filament\Notifications\Notification::make()
                        ->title('Peserta sudah terdaftar di kelas ini')
                        ->warning()
                        ->send();
                    return;
                }

                \App\Models\PendaftaranPelatihan::create([
                    'peserta_id' => $data['peserta_id'],
                    'pelatihan_id' => $this->record->id,
                    'kompetensi_pelatihan_id' => $this->kompetensiPelatihan->id,
                    'tanggal_pendaftaran' => now(),
                    'status_pendaftaran' => 'Diterima',
                    'nomor_registrasi' => 'REG-' . time() . '-' . $data['peserta_id'],
                ]);

                \Filament\Notifications\Notification::make()
                    ->title('Peserta berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\Action::make('detail_monev')
                ->label('Detail Survey Monev')
                ->icon('heroicon-o-chart-pie')
                ->url(fn () => ViewMonevDetail::getUrl(['record' => $this->record->id, 'kompetensi_id' => $this->kompetensiPelatihan->id]))
                ->color('primary'),
        ];
    }

    public Pelatihan $record;
    public KompetensiPelatihan $kompetensiPelatihan;

    public function mount(Pelatihan $record, $kompetensi_id): void
    {
        $this->record = $record;
        $this->kompetensiPelatihan = KompetensiPelatihan::findOrFail($kompetensi_id);
    }

    public function getTitle(): string | Htmlable
    {
        return $this->kompetensiPelatihan->kompetensi->nama_kompetensi ?? 'Detail Kompetensi';
    }

    public function getTesProperty()
    {
        return \App\Models\Tes::where('pelatihan_id', $this->record->id)
            ->where('kompetensi_id', $this->kompetensiPelatihan->kompetensi_id)
            ->get();
    }

    public function getPesertaProperty()
    {
        return $this->kompetensiPelatihan->peserta;
    }

    public function getStatistikProperty()
    {
        $tes = $this->tes;
        
        $pretestIds = $tes->where('tipe', 'pre-test')->pluck('id');
        $posttestIds = $tes->where('tipe', 'post-test')->pluck('id');
        $surveyIds = $tes->where('tipe', 'survey')->pluck('id');

        // Pretest Stats
        $pretestAttempts = \App\Models\Percobaan::whereIn('tes_id', $pretestIds)->get();
        $pretestAvg = $pretestAttempts->avg('skor') ?? 0;
        $pretestStats = [
            'avg' => number_format($pretestAvg, 1),
            'max' => $pretestAttempts->max('skor') ?? 0,
            'min' => $pretestAttempts->min('skor') ?? 0,
            'count' => $pretestAttempts->count(),
        ];

        // Posttest Stats
        $posttestAttempts = \App\Models\Percobaan::whereIn('tes_id', $posttestIds)->get();
        $posttestAvg = $posttestAttempts->avg('skor') ?? 0;
        $posttestStats = [
            'avg' => number_format($posttestAvg, 1),
            'lulus' => $posttestAttempts->where('lulus', true)->count(),
            'remedial' => $posttestAttempts->where('lulus', false)->count(),
            'kenaikan' => number_format($posttestAvg - $pretestAvg, 1),
        ];

        // Monev Stats
        $surveyAttempts = \App\Models\Percobaan::whereIn('tes_id', $surveyIds)->get();
        $surveyAvg = $surveyAttempts->avg('skor') ?? 0;
        // Convert 0-100 scale to 0-5 scale
        $surveyScale5 = ($surveyAvg / 20); 

        $monevStats = [
            'avg' => number_format($surveyScale5, 1),
            'responden' => $surveyAttempts->count(),
            'total_peserta' => $this->peserta->count(),
            'percentage' => $this->peserta->count() > 0 ? ($surveyAttempts->count() / $this->peserta->count()) * 100 : 0,
        ];

        return [
            'pretest' => $pretestStats,
            'posttest' => $posttestStats,
            'monev' => $monevStats,
        ];
    }
}
