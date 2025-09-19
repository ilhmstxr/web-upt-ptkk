<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use PhpOffice\PhpWord\Settings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Pilih renderer DomPDF
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);

        // Tunjuk folder vendor DomPDF (WAJIB, terutama di Windows/Laragon)
        Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
