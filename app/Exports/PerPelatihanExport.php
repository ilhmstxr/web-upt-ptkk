<?php

namespace App\Exports;

use App\Models\PendaftaranPelatihan;
use App\Models\Pelatihan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Str;

class PerPelatihanExport implements FromView, WithTitle
{
    protected $pelatihanId;
    protected $pelatihan;

    public function __construct($pelatihanId)
    {
        $this->pelatihanId = $pelatihanId;
        $this->pelatihan = Pelatihan::find($pelatihanId);
    }

    public function view(): View
    {
        $query = PendaftaranPelatihan::with([
            'peserta.user',
            'peserta.instansi.cabangDinas',
            'peserta.lampiran',
            'pelatihan',
            'kompetensi'
        ])->where('pelatihan_id', $this->pelatihanId);

        return view('template_surat.export_per_pelatihan', [
            'pendaftaran' => $query->get(),
            'pelatihan' => $this->pelatihan
        ]);
    }

    /**
     * Set the title of the sheet (max 31 chars)
     */
    public function title(): string
    {
        if ($this->pelatihan) {
            // Excel sheet limit is 31 chars
            return Str::limit($this->pelatihan->nama_pelatihan, 30, '');
        }
        return 'Data Peserta';
    }
}
