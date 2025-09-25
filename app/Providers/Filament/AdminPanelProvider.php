<?php

namespace App\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Navigation\MenuSection;
use Filament\Panels\Panel;
use Filament\Providers\FilamentPanelProvider;

class AdminPanelProvider extends FilamentPanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('WPS Payroll Compliance')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->colors([
                'primary' => '#0ea5e9',
            ])
            ->navigationItems([
                MenuSection::make('Operations')
                    ->items([
                        MenuItem::make('Dashboard')->url('/admin')->icon('heroicon-o-home'),
                    ]),
            ]);
    }
}
