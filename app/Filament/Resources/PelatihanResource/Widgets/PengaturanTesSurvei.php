<?php

namespace App\Filament\Widgets;

use App\Models\Pelatihan;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class PengaturanTesSurvei extends Widget
{
    protected static string $view = 'filament.widgets.pengaturan-tes-survei';
    public ?Model $record = null;
    public int $jumlahSoalPreTest = 0;
    public int $jumlahSoalPostTest = 0;
    public int $jumlahPertanyaanSurvei = 0;

    public function mount(): void
    {
        if ($this->record) {
            // Logika untuk menghitung jumlah soal
            $this->jumlahSoalPreTest = $this->record->soalTes()->where('jenis_tes', 'pre-test')->count();
            $this->jumlahSoalPostTest = $this->record->soalTes()->where('jenis_tes', 'post-test')->count();
            $this->jumlahPertanyaanSurvei = $this->record->pertanyaanSurvei()->count();
        }
    }
}