<?php

namespace App\Mail;

use App\Models\PendaftaranPelatihan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessmentTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PendaftaranPelatihan $pendaftaran) {}

    public function build()
    {
        return $this->subject('Token Assessment UPT PTKK')
            ->view('emails.assessment-token');
    }
}
