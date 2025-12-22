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
                    'status_pendaftaran' => 'diterima',
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

    public function getStatusProperty(): string
    {
        $start = \Carbon\Carbon::parse($this->record->tanggal_mulai)->startOfDay();
        $end = \Carbon\Carbon::parse($this->record->tanggal_selesai)->endOfDay();
        $now = now();

        if ($now < $start) {
            return 'Belum Dimulai';
        } elseif ($now > $end) {
            return 'Selesai';
        } else {
            return 'Sedang Berlangsung';
        }
    }

    public function getSubheading(): string|Htmlable|null
    {
        $statusLabel = $this->status;

        $color = match ($statusLabel) {
            'Belum Dimulai' => 'blue',
            'Selesai' => 'gray',
            'Sedang Berlangsung' => 'green',
        };

        // Inline styles to guarantee visibility (fixing missing classes)
        $statusStyle = match ($color) {
            'green' => 'color: #22c55e; background-color: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3);',
            'blue' => 'color: #3b82f6; background-color: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3);',
            'gray' => 'color: #9ca3af; background-color: rgba(107, 114, 128, 0.15); border: 1px solid rgba(107, 114, 128, 0.3);',
        };

        $dotColor = match ($color) {
            'green' => '#22c55e',
            'blue' => '#3b82f6',
            'gray' => '#9ca3af',
        };

        return new HtmlString(\Illuminate\Support\Facades\Blade::render(<<<'BLADE'
            <div class="flex flex-col gap-2 mt-2">
                <h2 class="text-xl font-bold text-primary-600 dark:text-primary-400">
                    {{ $kompetensi->kompetensi->nama_kompetensi ?? 'Nama Kompetensi' }}
                </h2>

                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <x-heroicon-o-calendar class="w-4 h-4" />
                        {{ \Carbon\Carbon::parse($record->tanggal_mulai)->format('d M') }} 
                        - 
                        {{ \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y') }}
                    </span>

                    <span class="text-gray-300 dark:text-gray-600">|</span>

                    <span class="flex items-center gap-1">
                        <x-heroicon-o-map-pin class="w-4 h-4" />
                        {{ $kompetensi->lokasi ?? 'Ruang Kelas' }}
                    </span>

                    <span class="text-gray-300 dark:text-gray-600">|</span>

                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="{{ $statusStyle }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 animate-pulse" style="background-color: {{ $dotColor }};"></span>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        BLADE, [
            'kompetensi' => $this->kompetensiPelatihan,
            'record' => $this->record,
            'statusLabel' => $statusLabel,
            'statusStyle' => $statusStyle,
            'dotColor' => $dotColor
        ]));
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

        // Base Query: Peserta yang tidak batal
        $baseQuery = PendaftaranPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('kompetensi_pelatihan_id', $kompetensiId)
            ->where(function ($q) {
                $q->whereNull('status_pendaftaran')
                    ->orWhere('status_pendaftaran', '!=', 'ditolak');
            });

        // ======================
        // 1. PRETEST
        // ======================
        // Mengambil data real dari kolom nilai_pre_test di tabel PendaftaranPelatihan
        $pretestData = (clone $baseQuery)->where('nilai_pre_test', '>', 0);

        $pretestAvg   = $pretestData->avg('nilai_pre_test') ?? 0;
        $pretestMax   = $pretestData->max('nilai_pre_test') ?? 0;
        $pretestMin   = $pretestData->min('nilai_pre_test') ?? 0;
        $pretestCount = $pretestData->count();

        // ======================
        // 2. POSTTEST
        // ======================
        // Mengambil data real dari kolom nilai_post_test
        $posttestData = (clone $baseQuery)->where('nilai_post_test', '>', 0);

        $posttestAvg   = $posttestData->avg('nilai_post_test') ?? 0;
        $posttestCount = $posttestData->count();
        $lulus         = (clone $posttestData)->where('nilai_post_test', '>=', 75)->count();
        $remedial      = (clone $posttestData)->where('nilai_post_test', '<', 75)->count();

        // ======================
        // 3. MONEV (SURVEI)
        // ======================
        // Menggunakan tabel percobaan untuk menghitung responden unik
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
                'avg'   => round((float)$pretestAvg, 1),
                'max'   => (int)$pretestMax,
                'min'   => (int)$pretestMin,
                'count' => $pretestCount,
            ],
            'posttest' => [
                'avg'      => round((float)$posttestAvg, 1),
                'lulus'    => $lulus,
                'remedial' => $remedial,
                'count'    => $posttestCount,
            ],
            'monev' => [
                'avg'           => 0, // Placeholder
                'responden'     => $monevRespondents,
                'total_peserta' => $totalPeserta,
                'percentage'    => $totalPeserta > 0 ? round(($monevRespondents / $totalPeserta) * 100) : 0,
            ],
        ];
    }
}
