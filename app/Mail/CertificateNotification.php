<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class CertificateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailSubject;
    public string $emailBody;
    public string $certificatePath;

    /**
     * Create a new message instance.
     */
    public function __construct(string $emailSubject, string $emailBody, string $certificatePath)
    {
        // REVISI: Mengisi properti kelas
        $this->emailSubject = $emailSubject;
        $this->emailBody = $emailBody;
        $this->certificatePath = $certificatePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // REVISI: Menggunakan `html` untuk merender body email dari variabel (database)
        return new Content(
            html: $this->emailBody,
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // REVISI: Menambahkan pengecekan file dan menggunakan Storage::path()
        // Ini memastikan path file absolut dan filenya benar-benar ada
        if ($this->certificatePath && Storage::disk('public')->exists($this->certificatePath)) {
            return [
                Attachment::fromPath(Storage::disk('public')->path($this->certificatePath))
            ];
        }

        return [];
    }
}