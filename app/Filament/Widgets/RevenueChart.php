<?php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Trend';
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    public ?array $reportData = [];
    public ?string $reportType = null;

    protected function getType(): string
    {
        return 'line';
    }


    public function updateChartData(): void {}

    #[On('report-generated')]
    public function handleReportData(array $payload): void
    {
        $this->reportData = $payload['reportData'];
        $this->reportType = $payload['reportType'];
        $this->dispatch('updateChart'); // This triggers the chart refresh
    }

    protected function getData(): array
    {


        if (empty($this->reportData) || $this->reportType !== 'monthly') {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => __('common.Revenue'),
                    'data' => array_column($this->reportData, 'revenue'),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => __('common.Paid'),
                    'data' => array_column($this->reportData, 'paid'),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
            ],
            'labels' => array_column($this->reportData, 'month'),
        ];
    }
}
