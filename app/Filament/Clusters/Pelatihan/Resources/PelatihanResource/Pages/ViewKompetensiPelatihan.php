<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\KompetensiPelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ViewKompetensiPelatihan extends Page
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-kompetensi-pelatihan';

    public $record;
    public $kompetensiPelatihan;

    public function mount($record, $kompetensi_id = null)
    {
        $this->record = Pelatihan::findOrFail($record);

        if ($kompetensi_id) {
            $this->kompetensiPelatihan = KompetensiPelatihan::with(['kompetensi', 'instrukturs'])
                ->where('pelatihan_id', $this->record->id)
                ->findOrFail($kompetensi_id);
        }
    }

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

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('detail_monev')
                ->label('Detail Survei Monev')
                ->icon('heroicon-o-chart-pie')
                ->url(fn() => ViewMonevDetail::getUrl(['record' => $this->record->id, 'kompetensi_id' => $this->kompetensiPelatihan->id]))
                ->color('primary'),
            $this->addInstructorAction(),
        ];
    }

    public function addInstructorAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('addInstructor')
            ->label('Tambah Instruktur')
            ->icon('heroicon-o-plus')
            ->form([
                \Filament\Forms\Components\Select::make('instruktur_id')
                    ->label('Pilih Instruktur')
                    ->options(\App\Models\Instruktur::query()->pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
            ])
            ->action(function (array $data) {
                // Replicate current record but with new instructor
                $newRecord = $this->kompetensiPelatihan->replicate();
                $newRecord->instruktur_id = $data['instruktur_id'];
                // Ensure unique code or handle it? Assuming auto-increment ID handling
                $newRecord->push(); // Save

                \Filament\Notifications\Notification::make()
                    ->title('Instruktur berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }

    public function getInstructorsProperty()
    {
        return KompetensiPelatihan::with('instrukturs')
            ->where('pelatihan_id', $this->record->id)
            ->where('kompetensi_id', $this->kompetensiPelatihan->kompetensi_id)
            ->get();
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->nama_pelatihan;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString(\Illuminate\Support\Facades\Blade::render(<<<'BLADE'
            <div class="flex flex-col gap-2 mt-2">
                <h2 class="text-xl font-bold text-primary-600 dark:text-primary-400">
                    {{ $kompetensi->kompetensi->nama_kompetensi ?? 'Nama Kompetensi' }}
                </h2>

                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <x-heroicon-o-clock class="w-4 h-4" />
                        {{ $kompetensi->jam_mulai ? \Carbon\Carbon::parse($kompetensi->jam_mulai)->format('H:i') : '-' }}
                        -
                        {{ $kompetensi->jam_selesai ? \Carbon\Carbon::parse($kompetensi->jam_selesai)->format('H:i') : '-' }}
                        WIB
                    </span>

                    <span class="text-gray-300 dark:text-gray-600">|</span>

                    <span class="flex items-center gap-1">
                        <x-heroicon-o-map-pin class="w-4 h-4" />
                        {{ $kompetensi->lokasi ?? 'Ruang Kelas' }}
                    </span>

                    <span class="text-gray-300 dark:text-gray-600">|</span>

                    <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs px-2.5 py-0.5 rounded-full font-medium border border-green-200 dark:border-green-800">
                        Sedang Berlangsung
                    </span>
                </div>
            </div>
        BLADE, ['kompetensi' => $this->kompetensiPelatihan]));
    }

    public function getTesProperty()
    {
        return \App\Models\Tes::where('pelatihan_id', $this->record->id)
            ->where('kompetensi_pelatihan_id', $this->kompetensiPelatihan->id)
            ->get();
    }

    public function getPesertaProperty()
    {
        return $this->kompetensiPelatihan->peserta;
    }

    /**
     * Fitur lama tetap: statistik pretest/posttest/monev
     */
    public function getStatistikProperty()
    {
        $pelatihanId = $this->record->id;
        $kompetensiId = $this->kompetensiPelatihan->id;

        $baseQuery = PendaftaranPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('kompetensi_pelatihan_id', $kompetensiId)
            ->where('status_pendaftaran', '!=', 'Batal');

        // ======================
        // 1. PRETEST
        // ======================
        $pretestQuery = (clone $baseQuery)
            ->where('nilai_pre_test', '>', 0);

        $pretestAvg   = (float) ($pretestQuery->avg('nilai_pre_test') ?? 0);
        $pretestMax   = (float) ($pretestQuery->max('nilai_pre_test') ?? 0);
        $pretestMin   = (float) ($pretestQuery->min('nilai_pre_test') ?? 0);
        $pretestCount = (int) $pretestQuery->count();

        // ======================
        // 2. POSTTEST
        // ======================
        $posttestQuery = (clone $baseQuery)
            ->where('nilai_post_test', '>', 0);

        $posttestAvg   = (float) ($posttestQuery->avg('nilai_post_test') ?? 0);
        $posttestCount = (int) $posttestQuery->count();
        $lulus         = (clone $posttestQuery)->where('nilai_post_test', '>=', 75)->count();
        $remedial      = (clone $posttestQuery)->where('nilai_post_test', '<', 75)->count();

        // 3. MONEV (SURVEI) - Simple Avg Calculation
        $monevRespondents = \App\Models\Percobaan::query()
            ->join('tes as t', 't.id', '=', 'percobaan.tes_id')
            ->join('pendaftaran_pelatihan as pp', 'pp.peserta_id', '=', 'percobaan.peserta_id')
            ->where('t.tipe', 'survei')
            ->where('pp.pelatihan_id', $pelatihanId)
            ->where('pp.kompetensi_pelatihan_id', $kompetensiId)
            ->distinct('percobaan.peserta_id')
            ->count('percobaan.peserta_id');

        $totalPeserta = (clone $baseQuery)->count();

        return [
            'pretest' => [
                'avg'   => round($pretestAvg, 2),
                'max'   => $pretestMax,
                'min'   => $pretestMin,
                'count' => $pretestCount,
            ],
            'posttest' => [
                'avg'      => round($posttestAvg, 2),
                'lulus'    => $lulus,
                'remedial' => $remedial,
                'count'    => $posttestCount,
            ],
            'monev' => [
                'avg' => 0,
                'responden' => $monevRespondents,
                'total_peserta' => $totalPeserta,
                'percentage' => $totalPeserta > 0 ? ($monevRespondents / $totalPeserta) * 100 : 0,
            ],
        ];
    }
}
