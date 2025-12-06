<?php

namespace App\Filament\Clusters\Evaluasi\Pages; // Perbaikan: Namespace seharusnya menunjuk ke Pages, bukan Resources

use App\Filament\Clusters\Evaluasi; // Import Cluster Utama
use App\Http\Controllers\PendaftaranController;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // Diperlukan untuk method downloadTokens
use App\Models\PendaftaranPelatihan;
use Filament\Actions\Action; // Import Action yang dibutuhkan (Filament V3+)


class ManageAssessmentTokens extends Page
{
    // Hapus: use App\Filament\Clusters\Evaluasi\Resources\EvaluasiResource; // Tidak diperlukan di sini

    // --- KONFIGURASI FILAMENT PAGE ---
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Token Assessment';
    protected static ?string $cluster = Evaluasi::class;
    protected static ?string $title = 'Manajemen Token Assessment';
    // Penamaan view harus konsisten dengan Cluster
    protected static string $view = 'filament.clusters.evaluasi.pages.manage-assessment-tokens';

    // --- VARIABEL DATA ---
    public int $totalPendaftaran = 0;
    public int $tokensGenerated = 0;
    public int $tokensPending = 0;
    public $pendingPendaftarans;

    // --- LIFECYCLE HOOKS ---
    public function mount(): void
    {
        // PENTING: Panggil loadStats di mount()
        $this->loadStats();
    }

    // --- LOGIC METHOD ---
    protected function loadStats(): void
    {
        $this->totalPendaftaran = PendaftaranPelatihan::count();
        $this->tokensGenerated = PendaftaranPelatihan::whereNotNull('assessment_token')->count();

        $this->pendingPendaftarans = PendaftaranPelatihan::with('peserta', 'pelatihan')
            ->whereNull('assessment_token')
            ->get();

        $this->tokensPending = $this->pendingPendaftarans->count();
    }

    // Aksi Generate Token Massal
    public function generateTokens()
    {
        // Panggil controller method
        $controller = new PendaftaranController();
        $controller->generateTokenMassal();

        // Ambil flash message dari session setelah controller dieksekusi
        $flashMessage = Session::get('success') ?? Session::get('error');
        $isSuccess = Session::has('success');

        // Buat dan kirim Filament Notification
        Notification::make()
            ->title(strip_tags($flashMessage))
            ->status($isSuccess ? 'success' : 'danger')
            ->send();

        // Bersihkan session flash (agar tidak muncul di refresh berikutnya)
        Session::forget(['success', 'error']);

        // Refresh data di halaman setelah aksi
        $this->loadStats();
    }

    // Aksi Download Token
    public function downloadTokens()
    {
        $controller = new PendaftaranController();
        // Memanggil method export di controller
        return $controller->downloadTokenAssessment(new Request());
    }

    // --- FORM ACTIONS (Untuk Filament V3/V4 Page Actions) ---
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate_tokens')
                ->label('Generate Token Pending (' . $this->tokensPending . ')')
                ->color('primary')
                ->icon('heroicon-o-key')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Generate Token')
                ->modalDescription('Apakah Anda yakin ingin men-generate token untuk ' . $this->tokensPending . ' pendaftaran yang masih pending?')
                ->action('generateTokens'),

            Action::make('download_tokens')
                ->label('Download Semua Token')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                // Menggunakan route GET yang sudah ada di web.php
                ->url(route('admin.download.tokens'))
                ->openUrlInNewTab(),
        ];
    }
}