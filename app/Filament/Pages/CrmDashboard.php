<?php

// app/Filament/Pages/CrmDashboard.php
namespace App\Filament\Pages;

use App\Filament\Widgets\TodaysFollowUps;
use App\Filament\Widgets\LeadsOverview;
use App\Filament\Widgets\RecentConversations;
use Filament\Pages\Page;

class CrmDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'CRM Dashboard';
    protected static ?string $title = 'CRM Analytics';
    protected static ?int $navigationSort = 12;
    public static function getNavigationGroup(): ?string
    {
        return __('common.crm');
    }

    protected static string $view = 'filament.pages.crm-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            TodaysFollowUps::class,
            LeadsOverview::class,
            RecentConversations::class,
        ];
    }

    protected function getColumns(): int|string|array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.CRM Dashboard');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.CRM Dashboard');
    }

    public static function getLabel(): string
    {
        return __('common.CRM Dashboard');
    }

    public static function getModelLabel(): string
    {
        return __('common.CRM Dashboard');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.CRM Dashboard');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.CRM Dashboard');
    }
}
