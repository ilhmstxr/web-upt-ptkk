<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource;
use App\Models\Kompetensi;
use App\Models\KompetensiPelatihan;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    #[Url]
    public $search = '';

    #[Url]
    public $filterProgram = '';

    #[Url]
    public $filterCompetency = '';

    #[Url]
    public $filterStatus = '';

    public $newParticipant = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'program_id' => '',
        'competency_id' => '',
    ];

    public function getView(): string
    {
        return 'filament.clusters.kesiswaan.resources.pendaftaran-resource.pages.list-pendaftaran';
    }

    public function getPendaftaransProperty()
    {
        return PendaftaranPelatihan::query()
            ->with(['peserta.user', 'pelatihan', 'kompetensiPelatihan.kompetensi'])
            ->when($this->search, function (Builder $query) {
                $query->whereHas('peserta', function (Builder $q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('nik', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($u) {
                          $u->where('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterProgram, function (Builder $query) {
                $query->whereHas('pelatihan', function (Builder $q) {
                    $q->where('nama_pelatihan', $this->filterProgram);
                });
            })
            ->when($this->filterCompetency, function (Builder $query) {
                $query->whereHas('kompetensiPelatihan.kompetensi', function (Builder $q) {
                    $q->where('nama_kompetensi', $this->filterCompetency);
                });
            })
            ->when($this->filterStatus, function (Builder $query) {
                $query->where('status_pendaftaran', $this->filterStatus);
            })
            ->latest('tanggal_pendaftaran')
            ->paginate(10);
    }

    public function getStatsProperty()
    {
        $baseQuery = PendaftaranPelatihan::query();
        
        return [
            'total' => $baseQuery->count(),
            'pending' => (clone $baseQuery)->whereIn('status_pendaftaran', ['Menunggu Seleksi', 'Pending', 'Verifikasi'])->count(),
            'accepted' => (clone $baseQuery)->where('status_pendaftaran', 'Diterima')->count(),
            'rejected' => (clone $baseQuery)->whereIn('status_pendaftaran', ['Ditolak', 'Tidak Lolos', 'Gugur'])->count(),
        ];
    }

    public function getProgramsProperty()
    {
        return Pelatihan::distinct()->pluck('nama_pelatihan');
    }

    public function getCompetenciesProperty()
    {
        return Kompetensi::distinct()->pluck('nama_kompetensi');
    }

    public function getAllProgramsProperty()
    {
        return Pelatihan::pluck('nama_pelatihan', 'id');
    }

    public function getAllCompetenciesProperty()
    {
        return KompetensiPelatihan::with('kompetensi', 'pelatihan')
            ->get()
            ->mapWithKeys(function ($item) {
                $kompetensiName = $item->kompetensi->nama_kompetensi ?? 'Unknown';
                $pelatihanName = $item->pelatihan->nama_pelatihan ?? '-';
                return [$item->id => "$kompetensiName ($pelatihanName)"];
            });
    }

    public function updateStatus($id, $status)
    {
        $pendaftaran = PendaftaranPelatihan::find($id);
        if ($pendaftaran) {
            $pendaftaran->update(['status_pendaftaran' => $status]);
            
            Notification::make()
                ->title('Status Diperbarui')
                ->body("Peserta " . ($pendaftaran->peserta->nama ?? 'Unknown') . " telah " . strtoupper($status))
                ->success()
                ->send();
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterProgram', 'filterCompetency', 'filterStatus']);
    }

    public function createParticipant()
    {
        $this->validate([
            'newParticipant.name' => 'required',
            'newParticipant.email' => 'required|email',
            'newParticipant.program_id' => 'required',
            'newParticipant.competency_id' => 'required',
        ]);

        // 1. Create or Find Peserta
        $user = \App\Models\User::firstOrCreate(
            ['email' => $this->newParticipant['email']],
            ['name' => $this->newParticipant['name'], 'password' => bcrypt('password')]
        );

        $peserta = Peserta::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $this->newParticipant['name'],
                'no_hp' => $this->newParticipant['phone'],
                'pelatihan_id' => $this->newParticipant['program_id'],
            ]
        );

        // 2. Create Pendaftaran
        PendaftaranPelatihan::create([
            'peserta_id' => $peserta->id,
            'pelatihan_id' => $this->newParticipant['program_id'],
            'kompetensi_pelatihan_id' => $this->newParticipant['competency_id'],
            'tanggal_pendaftaran' => now(),
            'status_pendaftaran' => 'Menunggu Seleksi',
            'nomor_registrasi' => 'REG-' . time() . '-' . $peserta->id,
        ]);

        $this->reset('newParticipant');
        $this->dispatch('close-modal', id: 'modalAddParticipant');

        Notification::make()
            ->title('Peserta Berhasil Ditambahkan')
            ->success()
            ->send();
    }

    public function exportExcel()
    {
        Notification::make()
            ->title('Mengunduh Data Excel...')
            ->body('Fitur ini belum diimplementasikan sepenuhnya (mock).')
            ->info()
            ->send();
    }
}
