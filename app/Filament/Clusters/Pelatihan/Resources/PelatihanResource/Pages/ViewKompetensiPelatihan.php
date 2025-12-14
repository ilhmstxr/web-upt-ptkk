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

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('detail_monev')
                ->label('Detail Survei Monev')
                ->icon('heroicon-o-chart-pie')
                ->url(fn () => ViewMonevDetail::getUrl(['record' => $this->record->id, 'kompetensi_id' => $this->kompetensiPelatihan->id]))
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

    public Pelatihan $record;
    public KompetensiPelatihan $kompetensiPelatihan;

    public function mount(Pelatihan $record, $kompetensi_id): void
    {
        $this->record = $record;
        $this->kompetensiPelatihan = KompetensiPelatihan::findOrFail($kompetensi_id);
    }

    public function getTitle(): string | Htmlable
    {
        return $this->record->nama_pelatihan;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(<<<'BLADE'
            <div class="flex flex-col gap-2 mt-2">
                <h2 class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ $kompetensi->kompetensi->nama_kompetensi ?? 'Nama Kompetensi' }}</h2>
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <x-heroicon-o-clock class="w-4 h-4" /> 
                        {{ \Carbon\Carbon::parse($kompetensi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($kompetensi->jam_selesai)->format('H:i') }} WIB
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
        $surveiIds = $tes->where('tipe', 'survei')->pluck('id');

        // Pretest Stats
        $pretestAttempts = \App\Models\Percobaan::whereIn('tes_id', $pretestIds)->get();
        $pretestAvg = $pretestAttempts->avg('skor') ?? 0;
        $pretestUniqueCount = $pretestAttempts->pluck('peserta_id')->unique()->count();
        $pretestStats = [
            'avg' => number_format($pretestAvg, 1),
            'max' => $pretestAttempts->max('skor') ?? 0,
            'min' => $pretestAttempts->min('skor') ?? 0,
            'count' => $pretestUniqueCount,
        ];

        // Posttest Stats
        $posttestAttempts = \App\Models\Percobaan::whereIn('tes_id', $posttestIds)->get();
        $posttestAvg = $posttestAttempts->avg('skor') ?? 0;
        $posttestUniqueCount = $posttestAttempts->pluck('peserta_id')->unique()->count();
        $posttestStats = [
            'avg' => number_format($posttestAvg, 1),
            'lulus' => $posttestAttempts->where('lulus', true)->pluck('peserta_id')->unique()->count(),
            'remedial' => $posttestAttempts->where('lulus', false)->pluck('peserta_id')->unique()->count(),
            'kenaikan' => number_format($posttestAvg - $pretestAvg, 1), // Simple diff of averages
            'count' => $posttestUniqueCount,
        ];

        // Monev Stats
        $surveiAttempts = \App\Models\Percobaan::whereIn('tes_id', $surveiIds)->get();
        $surveiAvg = $surveiAttempts->avg('skor') ?? 0;
        // Convert 0-100 scale to 0-5 scale
        $surveiScale5 = ($surveiAvg / 20); 
        $surveiUniqueCount = $surveiAttempts->pluck('peserta_id')->unique()->count();

        $monevStats = [
            'avg' => number_format($surveiScale5, 1),
            'responden' => $surveiUniqueCount,
            'total_peserta' => $this->peserta->count(),
            'percentage' => $this->peserta->count() > 0 ? ($surveiUniqueCount / $this->peserta->count()) * 100 : 0,
        ];

        return [
            'pretest' => $pretestStats,
            'posttest' => $posttestStats,
            'monev' => $monevStats,
        ];
    }
}
