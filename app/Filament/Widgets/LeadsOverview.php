<?php

namespace App\Filament\Widgets;

use App\Models\CrmLeads;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class LeadsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $today = now()->format('Y-m-d');

        return [

            Card::make(__('common.total_leads'), CrmLeads::count())
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->description(__('common.all_leads_count')),

            Card::make(__('common.new_leads'), CrmLeads::where('status', CrmLeads::NEW)->count())
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->description(__('common.recently_added')),

            Card::make(__('common.converted_leads'), CrmLeads::where('status', CrmLeads::CONVERTED)->count())
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->description(__('common.successful_conversions')),

            Card::make(__('common.lost_leads'), CrmLeads::where('status', CrmLeads::LOST)->count())
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->description(__('common.unsuccessful_leads')),

            // New cards
            Card::make(__('common.today_leads'), CrmLeads::whereDate('created_at', $today)->count())
                ->icon('heroicon-o-calendar')
                ->color('warning')
                ->description(__('common.new_leads_today')),

            Card::make(__('common.today_converted'), CrmLeads::where('status', CrmLeads::CONVERTED)
                ->whereDate('updated_at', $today)
                ->count())
                ->icon('heroicon-o-academic-cap')
                ->color('success')
                ->description(__('common.converted_today')),

            Card::make(__('common.top_course'), $this->getMostPopularCourse())
                ->icon('heroicon-o-book-open')
                ->color('primary')
                ->description(__('common.most_interested_course')),

            Card::make(__('common.top_sales'), $this->getTopSalesPerson())
                ->icon('heroicon-o-star')
                ->color('info')
                ->description(__('common.best_conversion_rate')),

            Card::make(__('common.conversion_rate'), $this->getConversionRate().'%')
                ->icon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description(__('common.leads_to_students_rate')),
        ];
    }

    protected function getMostPopularCourse(): string
    {
        $course = CrmLeads::with('course')
        ->select('course_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('course_id')
            ->orderByDesc('count')
            ->first();

        return $course && $course->course
            ? $course->course->name
            : __('common.no_data');
    }

    protected function getTopSalesPerson(): string
    {
        $topSales = CrmLeads::with('assignee')
        ->select('assigned_to')
            ->selectRaw('COUNT(*) as total_leads')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as converted', [CrmLeads::CONVERTED])
            ->groupBy('assigned_to')
            ->havingRaw('converted > 0')
            ->orderByDesc('converted')
            ->first();

        if (!$topSales || !$topSales->assignee) {
            return __('common.no_data');
        }

        return $topSales->assignee->name.' ('.round(($topSales->converted/$topSales->total_leads)*100).'%)';
    }

    protected function getConversionRate(): float
    {
        $totalLeads = CrmLeads::count();
        $converted = CrmLeads::where('status', CrmLeads::CONVERTED)->count();

        return $totalLeads > 0 ? round(($converted/$totalLeads)*100, 2) : 0;
    }
}
