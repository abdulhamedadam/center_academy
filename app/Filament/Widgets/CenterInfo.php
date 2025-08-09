<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class CenterInfo extends Widget
{
    use InteractsWithPageFilters;

    protected static string $view = 'widgets.center-info';
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'systemName' => 'UpgradeX Center',
            'systemVersion' => '1.0.0',
            'customerServicePhone' => '01021783851',
            'customerServiceEmail' => 'support@upgradex.com',
            'lastUpdate' => Carbon::now()->format('Y-m-d'),
        ];
    }
}