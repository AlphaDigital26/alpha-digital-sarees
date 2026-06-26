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
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\UserQueryResource;
use App\Filament\Resources\ReviewResource;
use App\Filament\Resources\CarouselResource;
use App\Filament\Pages\ManageAttributes;
use App\Filament\Pages\ManageStory;
use App\Filament\Pages\ManagePolicies;
use App\Filament\Pages\ManageSettings;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('alpha-portal')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->sidebarWidth('18rem')
            ->collapsedSidebarWidth('4rem')
            ->sidebarCollapsibleOnDesktop()
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            ...Pages\Dashboard::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Orders')
                        ->icon('heroicon-o-shopping-bag')
                        ->items([
                            ...OrderResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make()
                        ->items([
                            ...ProductResource::getNavigationItems(),
                            ...ManageAttributes::getNavigationItems(),
                            ...CustomerResource::getNavigationItems(),
                            ...UserQueryResource::getNavigationItems(),
                            ...ReviewResource::getNavigationItems(),
                            ...CarouselResource::getNavigationItems(),
                            ...ManageStory::getNavigationItems(),
                            ...ManagePolicies::getNavigationItems(),
                            ...ManageSettings::getNavigationItems(),
                        ]),
                ]);
            })
            // Brand Color
            ->colors([
                'primary' => '#800020', // Matches frontend primary
            ])

            // Theme Settings
            ->brandName('ALPHA DIGITAL')
            ->font('Manrope') // Matches frontend sans font
            ->favicon(fn () => \App\Models\Setting::getSiteSettings()->favicon_image ? asset('storage/' . \App\Models\Setting::getSiteSettings()->favicon_image) . '?v=' . time() : null)

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
                // Default widgets removed for custom dashboard
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
                    /* Theme Variables */
                    :root {
                        --surface-neutral: #FDFBF7;
                        --dark-charcoal: #1b1c1a;
                        --primary-red: #800020;
                        --secondary-brown: #5D4037;
                    }

                    /* Main Background to match frontend neutral */
                    html:not(.dark) .fi-main {
                        background-color: var(--surface-neutral) !important;
                    }

                    /* Sidebar Styling */
                    .fi-sidebar {
                        background-color: var(--dark-charcoal) !important;
                    }

                    .fi-sidebar-header {
                        background-color: var(--dark-charcoal) !important;
                        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
                    }

                    /* Custom Sidebar Scrollbar */
                    .fi-sidebar-nav::-webkit-scrollbar {
                        width: 5px;
                    }
                    .fi-sidebar-nav::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    .fi-sidebar-nav::-webkit-scrollbar-thumb {
                        background: rgba(255, 255, 255, 0.15);
                        border-radius: 10px;
                    }
                    .fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
                        background: rgba(255, 255, 255, 0.25);
                    }

                    /* Sidebar Text & Icons */
                    .fi-sidebar-item-label,
                    .fi-sidebar-item-icon,
                    .fi-sidebar-group-label,
                    .fi-sidebar-group-icon {
                        color: rgba(255,255,255,0.9) !important; /* Made more prominent */
                        transition: all 0.2s ease;
                    }

                    /* Hover state */
                    .fi-sidebar-item:not(.fi-sidebar-item-active):hover > a,
                    .fi-sidebar-item:not(.fi-sidebar-item-active):hover > button,
                    .fi-sidebar-group-button:hover {
                        background-color: rgba(255,255,255,0.08) !important;
                        border-radius: 8px !important;
                    }

                    .fi-sidebar-item:hover .fi-sidebar-item-label,
                    .fi-sidebar-item:hover .fi-sidebar-item-icon,
                    .fi-sidebar-group-button:hover .fi-sidebar-group-label,
                    .fi-sidebar-group-button:hover .fi-sidebar-group-icon {
                        color: #ffffff !important;
                    }

                    /* Active Sidebar Item */
                    .fi-sidebar-item-active > a,
                    .fi-sidebar-item-active > button {
                        background-color: var(--primary-red) !important;
                        border-radius: 8px !important;
                    }

                    .fi-sidebar-item-active .fi-sidebar-item-label,
                    .fi-sidebar-item-active .fi-sidebar-item-icon {
                        color: #ffffff !important;
                        opacity: 1 !important;
                    }

                    /* Topbar adjustments */
                    html:not(.dark) .fi-topbar {
                        background-color: var(--surface-neutral) !important;
                        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
                    }

                    /* Brand Typography */
                    .fi-logo {
                        color: #ffffff !important;
                        font-family: "Noto Serif", serif !important;
                        font-weight: 700 !important;
                        letter-spacing: 1px !important;
                        text-transform: uppercase !important;
                    }

                    /* Subtle form input refinements */
                    .fi-input-wrapper {
                        border-radius: 8px !important;
                        box-shadow: none !important;
                        transition: all 0.2s ease;
                    }
                    html:not(.dark) .fi-input-wrapper {
                        border: 1px solid rgba(0,0,0,0.1) !important;
                    }
                    .fi-input-wrapper:focus-within {
                        border-color: var(--primary-red) !important;
                        box-shadow: 0 0 0 1px var(--primary-red) !important;
                    }

                    /* Page Headings */
                    .fi-header-heading {
                        font-family: "Noto Serif", serif !important;
                        letter-spacing: 0.5px !important;
                    }
                    html:not(.dark) .fi-header-heading {
                        color: var(--primary-red) !important;
                    }

                    /* Custom Dashboard Widget Styling */
                    .custom-stat-card {
                        border-top-width: 4px !important;
                        border-top-style: solid !important;
                        border-radius: 12px !important;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }
                    html:not(.dark) .custom-stat-card {
                        background-color: #ffffff !important;
                    }
                    .custom-stat-card:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                    }
                    html:not(.dark) .border-t-green { border-top-color: #22c55e !important; background-color: #f0fdf4 !important; }
                    html:not(.dark) .border-t-yellow { border-top-color: #eab308 !important; background-color: #fefce8 !important; }
                    html:not(.dark) .border-t-blue { border-top-color: #3b82f6 !important; background-color: #eff6ff !important; }
                    html:not(.dark) .border-t-red { border-top-color: #ef4444 !important; background-color: #fef2f2 !important; }
                    html:not(.dark) .border-t-cyan { border-top-color: #06b6d4 !important; background-color: #ecfeff !important; }
                    
                    .dark .border-t-green { border-top: 4px solid #22c55e !important; background-color: rgba(34,197,94,0.05) !important; }
                    .dark .border-t-yellow { border-top: 4px solid #eab308 !important; background-color: rgba(234,179,8,0.05) !important; }
                    .dark .border-t-blue { border-top: 4px solid #3b82f6 !important; background-color: rgba(59,130,246,0.05) !important; }
                    .dark .border-t-red { border-top: 4px solid #ef4444 !important; background-color: rgba(239,68,68,0.05) !important; }
                    .dark .border-t-cyan { border-top: 4px solid #06b6d4 !important; background-color: rgba(6,182,212,0.05) !important; }

                    /* Light Top Row Cards to match image */
                    html:not(.dark) .top-row-card {
                        background-color: #ffffff !important;
                    }
                    .dark .top-row-card {
                        background-color: #1f2937 !important;
                    }
                    .top-row-card {
                        border: none !important;
                        border-radius: 20px !important;
                        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.05), 0 10px 20px -5px rgba(0, 0, 0, 0.02) !important;
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }
                    .top-row-card:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.08) !important;
                    }
                    html:not(.dark) .top-row-card * {
                        color: #1f2937 !important; /* Dark text */
                    }
                    .dark .top-row-card * {
                        color: #e5e7eb !important; /* Light text */
                    }
                    
                    /* Custom Circular Icons for Top Row */
                    .top-row-card .fi-wi-stats-overview-stat-icon {
                        width: 64px !important;
                        height: 64px !important;
                        border-radius: 50% !important;
                        border: 6px solid #ffffff !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        margin: 0 auto 0.5rem auto !important;
                        padding: 12px !important;
                        color: #ffffff !important;
                        position: relative !important;
                        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
                    }
                    
                    /* Background colors for the circular icons */
                    .card-icon-red .fi-wi-stats-overview-stat-icon { background-color: #ff6b6b !important; }
                    .card-icon-blue .fi-wi-stats-overview-stat-icon { background-color: #3b82f6 !important; }
                    .card-icon-green .fi-wi-stats-overview-stat-icon { background-color: #10b981 !important; }
                    .card-icon-purple .fi-wi-stats-overview-stat-icon { background-color: #4f46e5 !important; } /* Indigo/Dark Blue */
                    .card-icon-yellow .fi-wi-stats-overview-stat-icon { background-color: #fb923c !important; } /* Orange */
                    
                    /* Soft Background Glow mimicking the image */
                    html:not(.dark) .card-icon-red { background: radial-gradient(circle at top, rgba(255,107,107,0.08) 0%, #ffffff 40%) !important; }
                    html:not(.dark) .card-icon-blue { background: radial-gradient(circle at top, rgba(59,130,246,0.08) 0%, #ffffff 40%) !important; }
                    html:not(.dark) .card-icon-green { background: radial-gradient(circle at top, rgba(16,185,129,0.08) 0%, #ffffff 40%) !important; }
                    html:not(.dark) .card-icon-purple { background: radial-gradient(circle at top, rgba(79,70,229,0.08) 0%, #ffffff 40%) !important; }
                    html:not(.dark) .card-icon-yellow { background: radial-gradient(circle at top, rgba(251,146,60,0.08) 0%, #ffffff 40%) !important; }
                    
                    /* Dark Mode Glow */
                    .dark .card-icon-red { background: radial-gradient(circle at top, rgba(255,107,107,0.15) 0%, #1f2937 40%) !important; }
                    .dark .card-icon-blue { background: radial-gradient(circle at top, rgba(59,130,246,0.15) 0%, #1f2937 40%) !important; }
                    .dark .card-icon-green { background: radial-gradient(circle at top, rgba(16,185,129,0.15) 0%, #1f2937 40%) !important; }
                    .dark .card-icon-purple { background: radial-gradient(circle at top, rgba(79,70,229,0.15) 0%, #1f2937 40%) !important; }
                    .dark .card-icon-yellow { background: radial-gradient(circle at top, rgba(251,146,60,0.15) 0%, #1f2937 40%) !important; }

                    /* Force center text and stack vertically on specific widgets */
                    .top-row-card > div {
                        padding: 1.5rem 0.5rem !important; /* Proper padding for light boxes */
                    }
                    
                    /* Filament wraps the icon and text in a flex container */
                    .top-row-card .flex.items-center.gap-x-4,
                    .top-row-card .flex.items-center {
                        display: flex !important;
                        flex-direction: column !important;
                        align-items: center !important;
                        justify-content: center !important;
                        gap: 0.25rem !important;
                    }

                    /* The text container */
                    .top-row-card .flex.items-center > div:last-child {
                        display: flex !important;
                        flex-direction: column !important;
                        align-items: center !important;
                        width: 100% !important;
                    }
                    
                    /* Flex Reordering */
                    .top-row-card .fi-wi-stats-overview-stat-icon { order: 1 !important; margin-bottom: 0.75rem !important; }
                    .top-row-card .fi-wi-stats-overview-stat-label { order: 2 !important; font-size: 0.9rem !important; font-weight: 600 !important; margin-bottom: 0.25rem !important; }
                    html:not(.dark) .top-row-card .fi-wi-stats-overview-stat-label { color: #374151 !important; }
                    .dark .top-row-card .fi-wi-stats-overview-stat-label { color: #d1d5db !important; }
                    
                    .top-row-card .fi-wi-stats-overview-stat-description { order: 3 !important; font-size: 0.7rem !important; margin-bottom: 0.75rem !important; }
                    html:not(.dark) .top-row-card .fi-wi-stats-overview-stat-description { color: #9ca3af !important; }
                    .dark .top-row-card .fi-wi-stats-overview-stat-description { color: #6b7280 !important; }
                    
                    .top-row-card .fi-wi-stats-overview-stat-value { order: 4 !important; font-size: 1.6rem !important; font-weight: 800 !important; }
                    html:not(.dark) .top-row-card .fi-wi-stats-overview-stat-value { color: #1f2937 !important; }
                    .dark .top-row-card .fi-wi-stats-overview-stat-value { color: #f3f4f6 !important; }
                    
                    /* First card has colored value text */
                    html:not(.dark) .value-text-red .fi-wi-stats-overview-stat-value { color: #ff6b6b !important; }
                    .dark .value-text-red .fi-wi-stats-overview-stat-value { color: #fca5a5 !important; }
                    
                    /* Text Alignment Fix */
                    .top-row-card .fi-wi-stats-overview-stat-label,
                    .top-row-card .fi-wi-stats-overview-stat-value,
                    .top-row-card .fi-wi-stats-overview-stat-description {
                        text-align: center !important;
                        display: flex !important;
                        justify-content: center !important;
                        width: 100% !important;
                    }
                    
                    /* Force 5 columns in 1 line for the top stats widget */
                    /* Filament 3 uses grid layout for stats overview */
                    div:has(> .top-row-card) {
                        display: grid !important;
                        grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
                        gap: 1rem !important;
                    }
                    
                    /* Ensure no wrapping */
                    @media (max-width: 1024px) {
                        div:has(> .top-row-card) {
                            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                        }
                    }
                    @media (max-width: 768px) {
                        div:has(> .top-row-card) {
                            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                        }
                    }
                    
                    /* Fix for description icons centering */
                    .centered-stat .fi-wi-stats-overview-stat-description {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    }
                    
                    /* Custom coloring for User Analytics text */
                    .text-success * { color: #22c55e !important; }
                    .text-primary * { color: var(--primary-red) !important; }
                    .text-warning * { color: #eab308 !important; }
                    
                </style>
            '),
        );
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn (): string => Blade::render('
                <script>
                (function () {
                    /**
                     * After editing a product, the redirect URL contains #table-row-{id}.
                     * This script waits for Livewire to finish rendering, then scrolls
                     * the matching table row into view and briefly highlights it.
                     */
                    function scrollToEditedRow() {
                        var hash = window.location.hash; // e.g. #table-row-42
                        if (!hash || !hash.startsWith("#table-row-")) return;

                        var recordId = hash.replace("#table-row-", "");
                        if (!recordId) return;

                        // Filament edit links contain /edit at the end with the record ID in the path
                        // e.g. href="/alpha-portal/products/42/edit"
                        var editLinks = document.querySelectorAll("a[href*=\"/" + recordId + "/edit\"]");
                        if (!editLinks.length) return;

                        // Walk up to the nearest table row container
                        var row = editLinks[0].closest("tr, [class*=fi-ta-record]");
                        if (!row) {
                            // fallback: scroll the link itself into view
                            editLinks[0].scrollIntoView({ behavior: "smooth", block: "center" });
                            return;
                        }

                        // Scroll into view
                        row.scrollIntoView({ behavior: "smooth", block: "center" });

                        // Brief highlight flash so user knows which row was edited
                        row.style.transition = "background-color 0.4s ease";
                        row.style.backgroundColor = "rgba(128, 0, 32, 0.08)";
                        setTimeout(function () {
                            row.style.backgroundColor = "";
                        }, 2000);

                        // Clean fragment from URL so back/forward nav is clean
                        history.replaceState(null, "", window.location.pathname + window.location.search);
                    }

                    // Wait for Livewire to finish its first render
                    document.addEventListener("livewire:navigated", function () {
                        setTimeout(scrollToEditedRow, 300);
                    });

                    // Also handle plain page loads (non-SPA navigation)
                    if (document.readyState === "complete") {
                        setTimeout(scrollToEditedRow, 500);
                    } else {
                        window.addEventListener("load", function () {
                            setTimeout(scrollToEditedRow, 500);
                        });
                    }
                })();
                </script>
            '),
        );
    }
}