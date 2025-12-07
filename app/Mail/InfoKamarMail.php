<?php

namespace App\Mail;

use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\Pelatihan;
use App\Models\Peserta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoKamarMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Peserta $peserta,
        public Asrama $asrama,
        public Kamar $kamar,
        public Pelatihan $pelatihan,
    ) {}

    public function build()
    {
        return $this
            ->subject('Informasi Kamar Asrama - ' . ($this->pelatihan->nama ?? 'Pelatihan'))
            ->view('emails.info-kamar');
    }
}
