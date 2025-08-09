<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TodaysFollowUps;
use App\Filament\Widgets\GlobalSchedulesWidget;
use App\Filament\Widgets\SchedulesStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\CenterInfo::class,
            TodaysFollowUps::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            SchedulesStatsWidget::class,
            GlobalSchedulesWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
