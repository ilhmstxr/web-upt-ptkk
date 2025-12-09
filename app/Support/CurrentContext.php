<?php

namespace App\Support;

use App\Models\Peserta;
use App\Models\PendaftaranPelatihan;

class CurrentContext
{
    public static function peserta(): ?Peserta
    {
        $id = session('peserta_id');
        return $id ? Peserta::find($id) : null;
    }

    public static function pendaftaran(): ?PendaftaranPelatihan
    {
        $id = session('pendaftaran_pelatihan_id');
        return $id ? PendaftaranPelatihan::find($id) : null;
    }

    public static function pelatihanId(): ?int
    {
        return session('pelatihan_id');
    }

    public static function kompetensiId(): ?int
    {
        return session('kompetensi_id');
    }
}
