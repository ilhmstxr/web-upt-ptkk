<?php

namespace App\Jobs;

use App\Mail\CertificateNotification;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Training;
use App\Models\User; // atau model Peserta Anda
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $participant;
    protected $template;
    protected $training;

    /**
     * Create a new job instance.
     */
    public function __construct(User $participant, EmailTemplate $template, Training $training) // pelatihan
    {
        $this->participant = $participant;
        $this->template = $template;
        $this->training = $training;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Personalisasi konten email dengan data dinamis
        $placeholders = [
            '[nama_peserta]'    => $this->participant->name,
            '[nama_pelatihan]'  => $this->training->name,
            '[tanggal_mulai]'   => $this->training->start_date->format('d F Y'),
            '[tanggal_selesai]' => $this->training->end_date->format('d F Y'),
        ];

        $subject = str_replace(array_keys($placeholders), array_values($placeholders), $this->template->subject);
        $body = str_replace(array_keys($placeholders), array_values($placeholders), $this->template->body);

        $status = 'sent';
        $errorMessage = null;

        // 2. Kirim email
        try {
            $mailable = new CertificateNotification($subject, $body, $this->participant->certificate_path);
            Mail::to($this->participant->email)->send($mailable);
        } catch (Throwable $e) {
            $status = 'failed';
            $errorMessage = $e->getMessage();
        }

        // 3. Catat ke dalam log (EmailLog)
        EmailLog::create([
            'email' => $this->participant->email,
            'subject' => $subject,
            'body' => $body . ($errorMessage ? "\n\nERROR: " . $errorMessage : ''),
            'status' => $status,
            'sent_at' => now(),
        ]);
    }
}