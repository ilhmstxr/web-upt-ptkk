<?php

namespace App\Providers\Filament;

use App\Filament\Resources\JawabanSurveiResource\Pages\PelatihanReport;
use App\Filament\Widgets\BidangScoresChart;
use App\Filament\Widgets\BidangSummaryTable;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_START,
                fn (): string => '
                    <script src="https://cdn.tailwindcss.com"></script>
                    <script>
                        tailwind.config = {
                            theme: {
                                extend: {
                                    colors: {
                                        primary: "#2563EB",
                                        secondary: "#475569",
                                        success: "#10B981",
                                        warning: "#F59E0B",
                                        danger: "#EF4444",
                                    },
                                    fontFamily: {
                                        sans: ["Poppins", "sans-serif"],
                                    }
                                }
                            }
                        }
                    </script>
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                    <style>
                        body { font-family: "Poppins", sans-serif; }
                        .fi-sidebar { background-color: #111827 !important; } /* bg-gray-900 */
                        .fi-sidebar-header { background-color: #030712 !important; border-bottom-color: #1f2937 !important; } /* bg-gray-950 border-gray-800 */
                        .fi-sidebar-nav .fi-sidebar-item-label { color: #d1d5db !important; } /* text-gray-300 */
                        .fi-sidebar-nav .fi-sidebar-item-icon { color: #f59e0b !important; } /* text-amber-500 */
                        .fi-topbar { background-color: white !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
                    </style>
                ',
            )
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->login()
            ->colors([
                'primary' => '#2563EB',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(FilamentFullCalendarPlugin::make());
    }
}
