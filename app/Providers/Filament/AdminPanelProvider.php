<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // Brand Color
            ->colors([
                'primary' => '#7c061a',
            ])

            // Theme Settings
            ->darkMode(false)
            ->brandName('ALPHA DIGITAL')
            ->font('Inter')

            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )

            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )

            ->pages([
                Pages\Dashboard::class,
            ])

            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

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
            ]);
    }

    /**
     * Inject custom Filament styling
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => Blade::render('
                <style>
                    /* Sidebar */
                    .fi-sidebar {
                        background-color: #1a1515 !important;
                    }

                    .fi-sidebar-header {
                        background-color: #1a1515 !important;
                        border-bottom: none !important;
                    }

                    /* Sidebar Text & Icons */
                    .fi-sidebar-nav-label,
                    .fi-sidebar-nav-item-icon {
                        color: rgba(255,255,255,0.6) !important;
                    }

                    /* Active Sidebar Item */
                    .fi-sidebar-nav-item-active {
                        background-color: #7c061a !important;
                        border-radius: 12px !important;
                        margin: 0 12px !important;
                    }

                    .fi-sidebar-nav-item-active .fi-sidebar-nav-label,
                    .fi-sidebar-nav-item-active .fi-sidebar-nav-item-icon {
                        color: #fcfcfc !important;
                        opacity: 1 !important;
                    }

                    /* Brand */
                    .fi-brand {
                        color: #fcfcfc !important;
                        font-weight: 900 !important;
                        letter-spacing: 2px !important;
                        text-transform: uppercase !important;
                    }

                    /* Main Area */
                    .fi-main {
                        background-color: #fcfcfc !important;
                    }

                    .fi-topbar {
                        display: none !important;
                    }

                    /* Form Inputs */
                    input,
                    textarea,
                    select {
                        background-color: #f8f8f8 !important;
                        border: none !important;
                        border-radius: 16px !important;
                    }
                </style>
            '),
        );
    }
}