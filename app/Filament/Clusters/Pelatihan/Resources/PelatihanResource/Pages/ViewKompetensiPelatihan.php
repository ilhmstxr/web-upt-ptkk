<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\KompetensiPelatihan;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ViewKompetensiPelatihan extends Page
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-kompetensi-pelatihan';

    public Pelatihan $record;
    public KompetensiPelatihan $kompetensiPelatihan;

    /**
     * Route: /{record}/kompetensi/{kompetensi_pelatihan_id}
     */
    public function mount(Pelatihan $record, int $kompetensi_pelatihan_id): void
    {
        $this->record = $record;

        $this->kompetensiPelatihan = KompetensiPelatihan::query()
            ->with(['kompetensi', 'instrukturs'])
            ->whereKey($kompetensi_pelatihan_id)
            ->where('pelatihan_id', $record->getKey())
            ->firstOrFail();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('detail_monev')
                ->label('Detail Survei Monev')
                ->icon('heroicon-o-chart-pie')
                ->color('primary')
                ->url(fn () => ViewMonevDetail::getUrl([
                    'record' => $this->record,
                    'kompetensi_pelatihan_id' => $this->kompetensiPelatihan->getKey(),
                ])),

            $this->addParticipantAction(),
            $this->addInstructorAction(),
        ];
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
                $exists = \App\Models\PendaftaranPelatihan::query()
                    ->where('peserta_id', $data['peserta_id'])
                    ->where('pelatihan_id', $this->record->getKey())
                    ->where('kompetensi_pelatihan_id', $this->kompetensiPelatihan->getKey())
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
                    'pelatihan_id' => $this->record->getKey(),
                    'kompetensi_pelatihan_id' => $this->kompetensiPelatihan->getKey(),
                    'tanggal_pendaftaran' => now(),
                    'status_pendaftaran' => 'diterima', // konsisten
                    'nomor_registrasi' => 'REG-' . time() . '-' . $data['peserta_id'],
                ]);

                \Filament\Notifications\Notification::make()
                    ->title('Peserta berhasil ditambahkan')
                    ->success()
                    ->send();
            });
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
                    ->multiple()
                    ->required(),
            ])
            ->action(function (array $data) {
                $ids = $data['instruktur_id'] ?? [];

                if (! empty($ids)) {
                    // ✅ benar: tambahkan instruktur ke pivot, bukan replicate kompetensiPelatihan
                    $this->kompetensiPelatihan->instrukturs()->syncWithoutDetaching($ids);
                }

                // refresh relasi biar tampilan langsung update
                $this->kompetensiPelatihan->load('instrukturs');

                \Filament\Notifications\Notification::make()
                    ->title('Instruktur berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }

    /**
     * Tetap ada fitur lama: ambil semua sesi (kompetensi_pelatihan) yang satu kompetensi master
     * dalam pelatihan ini beserta instruktursnya.
     */
    public function getInstructorsProperty()
    {
        return KompetensiPelatihan::query()
            ->with('instrukturs')
            ->where('pelatihan_id', $this->record->getKey())
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

    /**
     * Fitur lama tetap: daftar tes per sesi (kompetensi_pelatihan)
     */
    public function getTesProperty()
    {
        return \App\Models\Tes::query()
            ->where('pelatihan_id', $this->record->getKey())
            ->where('kompetensi_pelatihan_id', $this->kompetensiPelatihan->getKey())
            ->get();
    }

    /**
     * Fitur lama tetap: peserta di sesi ini
     */
    public function getPesertaProperty()
    {
        return $this->kompetensiPelatihan->peserta;
    }

    /**
     * Fitur lama tetap: statistik pretest/posttest/monev
     */
    public function getStatistikProperty()
    {
        $tes = $this->tes;

        $pretestIds = $tes->where('tipe', 'pre-test')->pluck('id');
        $posttestIds = $tes->where('tipe', 'post-test')->pluck('id');
        $surveiIds  = $tes->where('tipe', 'survei')->pluck('id');

        // Pretest
        $pretestAttempts = \App\Models\Percobaan::query()->whereIn('tes_id', $pretestIds)->get();
        $pretestAvg = $pretestAttempts->avg('skor') ?? 0;
        $pretestUniqueCount = $pretestAttempts->pluck('peserta_id')->unique()->count();

        $pretestStats = [
            'avg' => number_format($pretestAvg, 1),
            'max' => $pretestAttempts->max('skor') ?? 0,
            'min' => $pretestAttempts->min('skor') ?? 0,
            'count' => $pretestUniqueCount,
        ];

        // Posttest
        $posttestAttempts = \App\Models\Percobaan::query()->whereIn('tes_id', $posttestIds)->get();
        $posttestAvg = $posttestAttempts->avg('skor') ?? 0;
        $posttestUniqueCount = $posttestAttempts->pluck('peserta_id')->unique()->count();

        $posttestStats = [
            'avg' => number_format($posttestAvg, 1),
            'lulus' => $posttestAttempts->where('lulus', true)->pluck('peserta_id')->unique()->count(),
            'remedial' => $posttestAttempts->where('lulus', false)->pluck('peserta_id')->unique()->count(),
            'kenaikan' => number_format($posttestAvg - $pretestAvg, 1),
            'count' => $posttestUniqueCount,
        ];

        // Monev (skala 0-100 => 0-5)
        $surveiAttempts = \App\Models\Percobaan::query()->whereIn('tes_id', $surveiIds)->get();
        $surveiAvg = $surveiAttempts->avg('skor') ?? 0;
        $surveiScale5 = ($surveiAvg / 20);
        $surveiUniqueCount = $surveiAttempts->pluck('peserta_id')->unique()->count();

        $totalPeserta = $this->peserta->count();

        $monevStats = [
            'avg' => number_format($surveiScale5, 1),
            'responden' => $surveiUniqueCount,
            'total_peserta' => $totalPeserta,
            'percentage' => $totalPeserta > 0 ? ($surveiUniqueCount / $totalPeserta) * 100 : 0,
        ];

        return [
            'pretest' => $pretestStats,
            'posttest' => $posttestStats,
            'monev' => $monevStats,
        ];
    }
}
