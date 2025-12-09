<?php

namespace App\Mail;

use App\Models\PenempatanAsrama;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PenempatanMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PenempatanAsrama $penempatan) {}

    public function build()
    {
        return $this->subject('Info Penempatan Asrama')
            ->view('emails.penempatan')
            ->with([
                'penempatan' => $this->penempatan,
            ]);
    }
}
