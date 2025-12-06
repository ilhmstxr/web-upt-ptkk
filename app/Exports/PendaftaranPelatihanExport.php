<?php

namespace App\Exports;

use App\Models\PendaftaranPelatihan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Collection;

class PendaftaranPelatihanExport implements FromView
{
    protected $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function view(): View
    {
        $query = PendaftaranPelatihan::with([
            'peserta.user',
            'peserta.instansi.cabangDinas',
            'peserta.lampiran',
            'pelatihan',
            'kompetensi'
        ]);

        if ($this->ids) {
            $query->whereIn('id', $this->ids);
        }

        $pendaftaran = $query->get();

        return view('template_surat.export_peserta_excel', [
            'pendaftaran' => $pendaftaran
        ]);
    }
}
