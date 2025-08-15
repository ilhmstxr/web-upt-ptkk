<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailJob; // Job ini perlu kita buat
use App\Models\EmailTemplate;
use App\Models\Training; // Asumsi Anda punya model Training
use Illuminate\Console\Command;

class SendCertificatesCommand extends Command
{
    /**
     * REVISI: Signature command yang lebih fleksibel.
     * {training} : ID dari pelatihan yang pesertanya akan dikirimi email.
     * {--template} : Slug dari template email yang akan digunakan.
     */
    protected $signature = 'app:send-certificates {training} {--template=notifikasi-sertifikat}';

    /**
     * REVISI: Deskripsi yang lebih jelas.
     */
    protected $description = 'Mengirim email sertifikat ke semua peserta pelatihan yang telah selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $trainingId = $this->argument('training');
        $templateSlug = $this->option('template');

        // 1. Ambil template email dari database
        $template = EmailTemplate::where('slug', $templateSlug)->first();
        if (!$template) {
            $this->error("Template email dengan slug '{$templateSlug}' tidak ditemukan.");
            return 1; // Keluar dengan status error
        }

        // 2. Ambil data pelatihan dan pesertanya
        $training = Training::with('participants')->find($trainingId); // Asumsi ada relasi 'participants' (pelatihan)
        if (!$training) {
            $this->error("Pelatihan dengan ID '{$trainingId}' tidak ditemukan.");
            return 1;
        }

        $participants = $training->participants;
        if ($participants->isEmpty()) {
            $this->info("Tidak ada peserta pada pelatihan '{$training->name}' untuk dikirimi email.");
            return 0; // Selesai tanpa error
        }

        $this->info("Mempersiapkan pengiriman email untuk {$participants->count()} peserta pelatihan '{$training->name}'...");

        // 3. Looping setiap peserta dan masukkan tugas ke antrian (Queue)
        $bar = $this->output->createProgressBar($participants->count());
        $bar->start();

        foreach ($participants as $participant) {
            // Kita asumsikan setiap $participant punya properti: name, email, dan certificate_path
            if (empty($participant->email) || empty($participant->certificate_path)) {
                $this->warn("\nMelewati peserta {$participant->name} (ID: {$participant->id}) karena email atau path sertifikat kosong.");
                $bar->advance();
                continue;
            }

            // Dispatch Job yang akan menangani pengiriman dan logging
            SendEmailJob::dispatch($participant, $template, $training);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n\nSemua tugas pengiriman email telah berhasil dimasukkan ke dalam antrian.");
        $this->comment("Jalankan 'php artisan queue:work' untuk memulai proses pengiriman.");
        return 0;
    }
}