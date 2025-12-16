<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Uncomment jika ingin pakai Queue
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailKonfirmasi extends Mailable
{
    use Queueable, SerializesModels;

    // Properti publik agar bisa diakses langsung oleh View
    public $data;

    /**
     * Create a new message instance.
     * $data akan berisi array lengkap informasi peserta & pelatihan
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope (Subject Email).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pendaftaran - ' . $this->data['nama_pelatihan'],
        );
    }

    /**
     * Get the message content definition (View Blade).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.konfirmasi-email', // Lokasi file blade
        );
    }

    /**
     * Get the attachments for the message (Opsional).
     */
    public function attachments(): array
    {
        return [];
    }
}
