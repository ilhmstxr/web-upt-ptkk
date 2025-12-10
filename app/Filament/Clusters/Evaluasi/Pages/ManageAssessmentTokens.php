<?php

namespace App\Filament\Clusters\Evaluasi\Pages;

use App\Filament\Clusters\Evaluasi;
use App\Http\Controllers\PendaftaranController;
use App\Models\PendaftaranPelatihan;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ManageAssessmentTokens extends Page
{
    // --- KONFIGURASI FILAMENT PAGE ---
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Token Assessment';
    protected static ?string $cluster = Evaluasi::class;
    protected static ?string $title = 'Manajemen Token Assessment';

    // Path View: Harus sesuai dengan lokasi Blade di resources/views/filament/clusters/evaluasi/pages/
    protected static string $view = 'filament.clusters.evaluasi.pages.manage-assessment-tokens';

    // Variabel Statistik
    public $pendingPendaftarans;
    public int $totalPendaftaran = 0;
    public int $tokensGenerated = 0;
    public int $tokensPending = 0;
    
    // --- LIFECYCLE & LOGIC ---
    public function mount(): void
    {
        $this->loadStats();
    }

    protected function loadStats(): void
    {
        $this->totalPendaftaran = PendaftaranPelatihan::count();
        $this->tokensGenerated = PendaftaranPelatihan::whereNotNull('assessment_token')->count();
        
        // Ambil data pending yang statusnya diterima DAN belum punya token
        $this->pendingPendaftarans = PendaftaranPelatihan::with('peserta', 'pelatihan')
            ->whereNull('assessment_token')
            ->where('status_pendaftaran', 'diterima')
            ->get();
            
        $this->tokensPending = $this->pendingPendaftarans->count();
    }

    public function generateTokens()
    {
        $controller = new PendaftaranController();
        // Memanggil metode generate di Controller
        $controller->generateTokenMassal();

        // Feedback ke User melalui Notifikasi Filament
        $flashMessage = Session::get('success') ?? Session::get('error') ?? 'Proses generate token selesai.';
        $isSuccess = Session::has('success');

        Notification::make()
            ->title('Proses Generate Selesai')
            ->body(strip_tags($flashMessage))
            ->status($isSuccess ? 'success' : 'danger')
            ->send();

        Session::forget(['success', 'error']);
        $this->loadStats();
    }

    // --- PAGE ACTIONS ---
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate_tokens')
                ->label('Generate Token Pending (' . $this->tokensPending . ')')
                ->color('primary')
                ->icon('heroicon-o-cpu-chip')
                ->requiresConfirmation()
                ->modalHeading('Generate Token Massal')
                ->modalDescription('Sistem akan membuatkan token unik untuk semua peserta berstatus "Diterima" yang belum memiliki token.')
                ->action('generateTokens')
                ->disabled($this->tokensPending === 0), // Disable jika 0

            Action::make('download_tokens')
                ->label('Download Excel/CSV')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(route('admin.download.tokens')) 
                ->openUrlInNewTab(),
        ];
    }
}