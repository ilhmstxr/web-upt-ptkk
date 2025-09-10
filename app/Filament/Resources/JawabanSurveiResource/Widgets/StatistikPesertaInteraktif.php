<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\PesertaSurvei;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Widgets\Widget;

class StatistikPesertaInteraktif extends Widget
{
    protected static string $view = 'filament.resources.jawaban-survei-resource.widgets.statistik-peserta-interaktif';

    use InteractsWithActions;

    protected int | string | array $columnSpan = 'full';
    public ?Pelatihan $pelatihan = null;

    // Properti untuk menampung hasil perhitungan
    public int $totalPeserta = 0;
    public int $pesertaMengisi = 0;
    public int $pesertaBelumMengisi = 0;
    public string $persentase = '0%';

    // Method mount() untuk melakukan kalkulasi sekali saja
    public function mount(): void
    {
        if (is_null($this->pelatihan)) return;

        $this->totalPeserta = Peserta::where('pelatihan_id', $this->pelatihan->id)->count();
        $this->pesertaMengisi = PesertaSurvei::where('pelatihan_id', $this->pelatihan->id)->whereHas('percobaans')->count();
        $this->pesertaBelumMengisi = $this->totalPeserta - $this->pesertaMengisi;
        $this->persentase = $this->totalPeserta > 0 ? round(($this->pesertaMengisi / $this->totalPeserta) * 100) . '%' : '0%';
    }

    // Aksi untuk menampilkan peserta yang SUDAH mengisi
    public function viewCompletedAction(): Action
    {
        return Action::make('viewCompleted')
            ->label('Lihat Daftar')
            ->modalHeading('Peserta yang Sudah Mengisi')
            ->modalContent(function () {
                $peserta = PesertaSurvei::where('pelatihan_id', $this->pelatihan->id)->whereHas('percobaans')->get();
                return view('modals.daftar-peserta', ['peserta' => $peserta]);
            })
            // Sembunyikan tombol submit/cancel
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }

    // Aksi untuk menampilkan peserta yang BELUM mengisi
    public function viewNotCompletedAction(): Action
    {
        return Action::make('viewNotCompleted')
            ->label('Lihat Daftar')
            ->modalHeading('Peserta yang Belum Mengisi')
            ->modalContent(function () {
                $peserta = PesertaSurvei::where('pelatihan_id', $this->pelatihan->id)->whereDoesntHave('percobaans')->get();
                return view('modals.daftar-peserta', ['peserta' => $peserta]);
            })
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
