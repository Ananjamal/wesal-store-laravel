<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\RecentOrdersWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\WelcomeWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
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
            ->brandName('✦ وِصال')
            ->brandLogo(fn() => view('filament.brand'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::hex('#467389'),
            ])
            ->font('Noto Kufi Arabic', provider: \Filament\FontProviders\GoogleFontProvider::class)
            ->maxContentWidth(MaxWidth::Full)
            ->databaseNotifications()
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('الكتالوج'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('المحتوى'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('التسويق'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('العمليات'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('المستخدمين'),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('الإعدادات'),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('زيارة المتجر')
                    ->icon('heroicon-o-globe-alt')
                    ->url('/'),
                MenuItem::make()
                    ->label('اللغة: العربية')
                    ->icon('heroicon-o-language')
                    ->url('/lang/ar'),
                MenuItem::make()
                    ->label('Language: English')
                    ->icon('heroicon-o-language')
                    ->url('/lang/en'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                WelcomeWidget::class,
                StatsOverview::class,
                RecentOrdersWidget::class,
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
}
